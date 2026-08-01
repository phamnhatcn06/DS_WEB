# Kế Hoạch Triển Khai Frontend Ghép Dữ Liệu — Website Đông Sơn Holdings

> Mục tiêu: chuyển `index.html` (tĩnh) thành trang chủ **động** đọc dữ liệu từ CMS (Yii 1.x),
> gom toàn bộ logic public vào **module `frontend`**, và cấu hình **homepage mặc định** để truy cập
> `/` (gốc website) vào thẳng trang home.
>
> Nguồn: phối hợp **Backend Developer** + **Frontend Developer** agent (2026-07-31).
> Ràng buộc kế thừa: Yii 1.x, bảng tiền tố `pvn_`, Bootstrap 5.3 **tải local (KHÔNG CDN)**,
> asset export từ Figma về `assets/`, palette brand đỏ `#9a1220` / vàng `#c9a84c` / navy `#080f1d`.

---

## 0. Hiện trạng đã xác minh

| Thành phần | Trạng thái |
|---|---|
| `SiteController::actionIndex()` | `renderPartial('home')` — **chưa truyền dữ liệu** |
| `SiteController::actionDataSource()` | Có `loadHomepagePayload()` (cache 1h, `with()` chống N+1) trả 8 nhóm dữ liệu |
| `views/site/home.php` (~52KB) | Đã port từ `index.html`: nav + 4 cột footer **động** qua MenuHelper, 93 asset prefix `$root`. **Thân 10 section vẫn hardcode** |
| Models | Đầy đủ: HeroSlide, BusinessSector, Project, CoreValue, TimelineMilestone, Partner, NewsCategory, NewsPost, MediaFile, MenuItem/MenuLocation, SiteSetting |
| Admin module | Đầy đủ controllers cho mọi bảng |
| Helpers | BaseActiveRecord, MenuHelper, MediaHelper, TextHelper |

**Hai việc còn thiếu:** (A) tổ chức lại thành module `frontend` + set homepage mặc định; (B) ghép dữ liệu payload vào 10 section.

---

# PHẦN A — BACKEND: Module `frontend` + Homepage mặc định

## A1. Quyết định kiến trúc — TÁCH thành module `frontend`

Đối xứng với module `admin`: gom mọi logic public một chỗ, layout/asset/cache riêng, dễ mở rộng đa trang
(About, Dự án, Tin tức) mà không phình `SiteController`. `SiteController` co lại thành shim mỏng (hoặc redirect 301).

> Yii 1.x: URL module mặc định là `frontend/<controller>/<action>`. Để `/` và trang public **không lộ prefix `frontend/`**,
> dùng `urlManager` rules (A3) — module chỉ tổ chức code, không bắt buộc lộ URL.

## A2. Cây file module

```
cms/protected/modules/frontend/
├── FrontendModule.php               # init(): layout + import path (KHÔNG setTheme hope-ui)
├── components/
│   └── FrontendController.php        # base: $layout = 'frontend.views.layouts.main'
├── controllers/
│   ├── HomeController.php            # actionIndex() -> render('home/index', $payload)
│   │                                 # actionDataSource() -> JSON payload (giữ endpoint cũ)
│   ├── AboutController.php           # (giai đoạn sau)
│   ├── ProjectController.php         # actionIndex / actionView($slug)  (sau)
│   └── NewsController.php            # actionIndex / actionView($slug)  (sau)
├── services/
│   └── HomepageDataService.php       # load(): cache + with() — chuyển từ SiteController
└── views/
    ├── layouts/
    │   └── main.php                  # <head> + Header + <main> + Footer (nav/footer động)
    ├── home/
    │   ├── index.php                 # gọi 10 partial section
    │   └── sections/                 # _hero _bot _about _pillars _projects
    │                                 # _stats _project_detail _partners _news
    ├── about/  ├── project/  └── news/
```

`FrontendModule::init()`:

```php
public function init() {
    $this->setImport([
        'frontend.components.*',
        'frontend.services.*',
    ]);
    $this->layout = 'frontend.views.layouts.main';
    // KHÔNG setTheme('hope-ui') — theme đó chỉ dành cho admin
}
public $defaultController = 'home'; // /frontend không kèm controller vẫn vào Home
```

## A3. Cơ chế đặt Homepage mặc định cho `/`

Ba lớp phối hợp — **khuyến nghị dùng (A) + (B)**; (C) chỉ khi cần đổi landing runtime qua DB.

### (A) urlManager rule ánh xạ `''` → module (tĩnh, chắc chắn)

`cms/protected/config/main.php` — thêm vào `modules` và `urlManager.rules`:

```php
'modules' => [
    'admin'    => ['class' => 'application.modules.admin.AdminModule'],   // giữ nguyên
    'frontend' => ['class' => 'application.modules.frontend.FrontendModule'],
],

'urlManager' => [
    'urlFormat' => 'path',
    'showScriptName' => false,
    'rules' => [
        // ... rule admin giữ nguyên phía trên (khớp theo thứ tự) ...
        ''                  => 'frontend/home/index',        // ROOT -> homepage
        'du-lieu-trang-chu' => 'frontend/home/dataSource',
        'gioi-thieu'        => 'frontend/about/index',       // (sau)
        'du-an'             => 'frontend/project/index',     // (sau)
        'du-an/<slug>'      => 'frontend/project/view',      // (sau)
        'tin-tuc'           => 'frontend/news/index',        // (sau)
        'tin-tuc/<slug>'    => 'frontend/news/view',         // (sau)
    ],
],
```

> Rule `'' => 'site/index'` hiện tại được thay bằng `'' => 'frontend/home/index'`.

### (B) defaultController (dự phòng)

`FrontendModule::$defaultController = 'home'` — truy cập `frontend/` không kèm controller vẫn vào Home.

### (C) Cấu hình động qua SiteSetting (tùy chọn)

Đọc `SiteSetting::get('homepage_route', 'frontend/home/index')` trong `onBeginRequest` khi `pathInfo` rỗng để
cho phép đổi trang gốc mà không sửa code. **Chỉ bật nếu nghiệp vụ cần** (tránh chạy controller 2 lần).

## A4. Luồng dữ liệu Controller → View

```
GET /  → urlManager('') → frontend/home/index
  HomeController::actionIndex()
      $payload = HomepageDataService::load();   // cache 1h + with() chống N+1
      $this->render('home/index', $payload);    // layout main.php bọc ngoài
  home/index.php
      renderPartial('sections/_hero',     ['heroSlides'  => $heroSlides]);
      renderPartial('sections/_pillars',  ['sectors'     => $sectors]);
      ... 10 section, mỗi cái nhận đúng lát dữ liệu ...
```

`HomepageDataService::load()` — bê nguyên `loadHomepagePayload()` từ `SiteController` (giữ `with()` + cache key
`BaseActiveRecord::CACHE_KEY_HOMEPAGE`). `actionDataSource()` → `echo CJSON::encode(HomepageDataService::load())`.

## A5. Ánh xạ Section ↔ Payload ↔ Model

| # | Section | Biến payload | Model | Ghi chú |
|---|---|---|---|---|
| 1 | Hero slider | `heroSlides` | `HeroSlide` | ảnh nền qua MediaHelper |
| 2 | Điểm nhấn BOT | `sectors` (lọc BOT) | `BusinessSector` | card nổi + banner |
| 3 | Giới thiệu / Tầm nhìn–Sứ mệnh | `coreValues` + SiteSetting | `CoreValue`,`SiteSetting` | quote lấy từ SiteSetting `general` |
| 4 | 3 trụ cột kinh doanh | `sectors` | `BusinessSector` | `row-cols-lg-3` |
| 5 | Dự án tiêu biểu | `projects` | `Project` (+media) | grid/carousel |
| 6 | Số liệu công ty | SiteSetting (`stats`) | `SiteSetting` | 4 con số |
| 7 | Chi tiết dự án / Timeline | `projects` featured + `milestones` | `Project`,`TimelineMilestone` | hàng xen kẽ |
| 8 | Đối tác & cổ đông | `partners` | `Partner` | grid logo grayscale |
| 9 | Tin tức + tab lọc | `newsPosts`, `newsCategories` | `NewsPost`,`NewsCategory` | filter `data-category` |
| H/F | Header + Footer | MenuHelper + SiteSetting (`contact`,`social`) | — | đã động, giữ nguyên |

> **Section 3 & 6 chưa có nguồn payload** → bổ sung key SiteSetting (A6). Các section còn lại đã có model/payload sẵn.

## A6. Migration / Seed `SiteSetting` cần thêm

Migration `mYYMMDD_000000_seed_frontend_settings` (dùng `SiteSetting::set()`), groups sẵn có `general/contact/social/seo`:

| key | group | type | ví dụ |
|---|---|---|---|
| `homepage_route` | general | string | `frontend/home/index` (cho cơ chế C) |
| `home_vision` / `home_mission` | general | string | Câu tầm nhìn / sứ mệnh |
| `stat_years` / `stat_projects` / `stat_capital` / `stat_staff` | general | string | `15` / `120` / `12.000 tỷ` / `800` |
| `seo_home_title` / `seo_home_description` | seo | string | Tiêu đề / mô tả SEO trang chủ |

Không tạo bảng mới — chỉ seed rows. Cast số ở view khi cần.

## A7. Sprint Backend

1. **Khung module (0.5d):** `FrontendModule` + `FrontendController`; đăng ký module; thêm rule `'' => 'frontend/home/index'` + `defaultController='home'`; smoke test `/`.
2. **Data layer (0.5d):** `HomepageDataService::load()` (copy từ SiteController); `HomeController` gọi service; `actionDataSource()` → JSON. Verify cache + không N+1.
3. **Di chuyển view + layout (1d):** `views/site/home.php` → `modules/frontend/views/home/index.php`; tách `<head>/header/footer` sang `layouts/main.php`; rà 93 asset prefix `$root` (đặt 1 lần trong layout); MenuHelper hoạt động dưới layout mới.
4. **Wire 10 section (1.5d):** tách `sections/_*.php`; thay hardcode bằng vòng lặp; section 3 & 6 đọc SiteSetting; seed migration + `migrate up`.
5. **Dọn dẹp & mở rộng (0.5d):** rút gọn/redirect 301 `SiteController`; cập nhật mọi `createUrl`; thêm rule + controller khung About/Project/News; invalidate `CACHE_KEY_HOMEPAGE` trong `afterSave/afterDelete` các model.

**Kiểm thử:** `/` vào thẳng home, không lộ `frontend/`; payload render đúng; sửa 1 slide/tin trong admin → cache làm mới → thấy cập nhật; JSON `dataSource` đúng cấu trúc cũ.

---

# PHẦN B — FRONTEND: Ghép dữ liệu vào view

> Giữ **100% markup/class Bootstrap 5.3, hiệu ứng và asset** như `index.html`. Chỉ thay phần thân tĩnh bằng
> vòng lặp `foreach` qua payload. Không thêm CDN, không đổi icon/asset.

## B0. Nền tảng chung (làm trước mọi section)

- **`MediaHelper::img($mediaFile, $alt, $htmlOptions = [])`** — helper render ảnh thống nhất:
  - Nhận `MediaFile` (qua relation `thumbnail/background/logo/image/icon`) hoặc `null`.
  - Xuất `<img src="{uploads}/..." alt="..." class="img-fluid..." loading="lazy">`.
  - **Fallback rỗng:** placeholder local (`assets/images/placeholder-16x9.webp`, `placeholder-logo.svg`) — không bao giờ `src` trống, không hotlink.
- **Escape:** mọi text từ DB qua `CHtml::encode()`; link qua `CHtml::normalizeUrl()`.
- **Guard payload:** đầu mỗi section kiểm tra `!empty($x)`; rỗng → render fallback tĩnh (1 slide/1 card mẫu) để layout không vỡ.

## B1. Tách partial view (làm trước để dễ bảo trì)

Cắt thân `home/index.php` thành partial trong `sections/`, render bằng `renderPartial` truyền đúng slice:

| Partial | Dữ liệu | Section |
|---|---|---|
| `_hero.php` | `heroSlides` | 1 |
| `_bot.php` | `sectors` (BOT) | 2 |
| `_about.php` | tĩnh + `coreValues` + SiteSetting | 3 |
| `_pillars.php` | `sectors` | 4 |
| `_projects.php` | `projects` | 5 |
| `_stats.php` | SiteSetting stats | 6 |
| `_project_detail.php` | `projects` featured + `milestones` | 7 |
| `_partners.php` | `partners` | 8 |
| `_news.php` | `newsCategories`, `newsPosts` | 9 |

Partial nhận data qua tham số thứ 2 của `renderPartial(..., ['items'=>$x])`; không đọc `$this` global.

## B2. Section 1 — Hero slider (`_hero.php`)

- `foreach ($heroSlides as $i => $slide)` sinh `.carousel-item` (item đầu `active`) + indicators (`02/04`, progress bar, prev/next giữ nguyên).
- Map: `background`→`MediaHelper::img()` nền; `title`→ **chỉ slide đầu là `<h1>`**, còn lại `<h2>`/`div` (giữ 1 `h1`/trang); CTA→`.btn` đỏ + `.btn` viền.
- Fallback: rỗng → 1 slide tĩnh (brand + slogan); ẩn prev/next nếu ≤1 slide.
- **JS giữ:** Bootstrap `Carousel` (`data-bs-ride`), đồng bộ chỉ số + progress bar theo event `slid.bs.carousel` (logic `main.js`).

## B3. Section 4 — Trụ cột lĩnh vực (`_pillars.php`)

- `foreach ($sectors as $sector)` → `.col` trong `row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4`, mỗi cái `.card`.
- Map: `number`→eyebrow (01/02/03); `icon`→`MediaHelper::img()` (giữ tỉ lệ gốc); `title`→`h3`; `description`→`p`; `tags[]`→lặp chip/badge.
- Fallback: rỗng → giữ 3 card tĩnh gốc.

## B4. Section 5 + 7 — Dự án (`_projects.php`, `_project_detail.php`)

- **Section 5 (grid):** `foreach ($projects as $p)` → `.col-md-6 .col-lg-4 > .card`; `thumbnail`→`.ratio ratio-16x9`; caption (tên + địa điểm) **luôn hiện** dưới ảnh (đúng mobile spec); nút "Xem tất cả dự án" trỏ trang danh sách.
- **Section 7 (chi tiết + timeline):** lọc `isFeatured`, các hàng ảnh/chữ xen kẽ (`flex-lg-row-reverse` theo `$index % 2`); `milestones[]` (`year`,`title`,`desc`) render trục dọc — mobile dồn về trái `left:8px`.
- Fallback: `projects` rỗng → ẩn section; `milestones` rỗng → ẩn timeline.

## B5. Section 3 + 6 — Giới thiệu/giá trị + Số liệu (`_about.php`, `_stats.php`)

- **Section 3:** `foreach ($coreValues)` → `.col-md-6/col-lg-4`; icon export đúng SVG Figma; khối tầm nhìn/sứ mệnh + quote từ SiteSetting (`home_vision`/`home_mission`).
- **Section 6:** 4 con số từ SiteSetting (`stat_years/projects/capital/staff`) → `.col-6 .col-md-3`, số màu vàng `--dsh-gold`. Fallback → 4 stat tĩnh gốc.

## B6. Section 8 — Đối tác/cổ đông (`_partners.php`)

- `foreach ($partners as $partner)` → `.col-6 .col-md-3/col-md-2`, logo grayscale, `alt = name`.
- Fallback: rỗng → ẩn section; logo qua `MediaHelper::img()` với `placeholder-logo.svg`.

## B7. Section 9 — Tin tức + tab lọc (`_news.php`)

- **Tabs:** `foreach ($newsCategories)` sinh `.nav-pills` (mục đầu "Tất cả" active) với `data-category="{slug}"`; mobile một hàng cuộn ngang.
- **Grid:** `foreach ($newsPosts as $post)` → `.col-md-6 .col-lg-4 > .card` mang `data-category="{categorySlug}"`; `thumbnail`, `publishedAt` (format `d/m/Y`), `title`→`h3`, `excerpt`, link.
- Fallback: `newsPosts` rỗng → "Chưa có bài viết"; `newsCategories` rỗng → ẩn tab.
- **JS giữ:** lọc client-side theo `data-category` (ẩn/hiện card, không reload) — handler `nav-pills` trong `main.js`.

## B8. SEO + hoàn thiện

- `<title>` + `<meta name="description">` từ SiteSetting (`seo_home_*`), fallback mặc định.
- **Heading:** đúng 1 `<h1>` (hero slide đầu); section title `<h2>`, card `<h3>`.
- **alt:** mọi ảnh động lấy từ `title/name` bản ghi; không để rỗng.
- `loading="lazy"` mọi ảnh dưới màn đầu; hero background eager.
- **Fade-in:** giữ `IntersectionObserver` thêm `.is-visible` cho `.fade-section` (class nằm trên `<section>`).
- **Kiểm thử:** rà 1920/1440/1024/768/375px; xác nhận không request host ngoài (offline hoàn toàn).

## B9. Thứ tự thực thi frontend

B0 (helper) → B1 (tách partial) → B2 Hero → B3 Pillars → B4 Projects → B5 About/Stats → B6 Partners → B7 News → B8 SEO/QA.
Mỗi sprint test render với payload **rỗng (fallback)** và **có dữ liệu** trước khi qua bước kế.

---

# PHẦN C — Lộ trình phối hợp (thứ tự chạy chung)

| Bước | Ai | Nội dung | Kết quả |
|---|---|---|---|
| 1 | BE | A7.1 Khung module + rule `''` + defaultController | `/` route tới HomeController |
| 2 | BE | A7.2 HomepageDataService + HomeController render payload | View nhận đủ 8 nhóm dữ liệu |
| 3 | BE | A7.3 Di chuyển home.php → module + layout | Trang render như cũ dưới module |
| 4 | FE | B0 + B1 MediaHelper + tách partial | Khung partial sẵn nhận payload |
| 5 | FE+BE | B2→B7 wire từng section (song song A7.4 seed SiteSetting) | 10 section động |
| 6 | FE | B8 SEO + QA responsive | Trang hoàn thiện |
| 7 | BE | A7.5 dọn SiteController + invalidate cache | Sạch, cache tự làm mới |

**Definition of Done:**
- Truy cập `/` vào thẳng trang home, URL không lộ `frontend/`.
- 10 section render từ DB; sửa dữ liệu trong admin → cache làm mới → frontend cập nhật.
- Mọi ảnh qua MediaHelper (có fallback), không hotlink/CDN, chạy offline.
- Responsive đạt ở 1920/1440/1024/768/375px; SEO (title/meta/alt/heading) chuẩn.

---

## File tham chiếu

- `cms/protected/config/main.php` — thêm module `frontend` + urlManager rule `''`.
- `cms/protected/controllers/SiteController.php` — nguồn `loadHomepagePayload()` để di chuyển.
- `cms/protected/views/site/home.php` — view nguồn để tách sang module.
- `cms/protected/components/MediaHelper.php` / `MenuHelper.php` — helper render ảnh / menu.
- `cms/protected/models/SiteSetting.php` — key-value config cho homepage/stats/SEO.
