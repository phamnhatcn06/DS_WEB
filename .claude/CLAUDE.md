# CLAUDE.md

File này cung cấp hướng dẫn cho Claude Code (claude.ai/code) khi làm việc với mã nguồn trong repo này.

## Tổng quan dự án

Website marketing tĩnh bằng HTML/CSS/JS cho **Đông Sơn Holdings (DSH)**, dựng từ thiết kế Figma (canvas rộng `1920px`). Không dùng framework frontend, không bundler, không package manager — đây là HTML/CSS/JS thuần, phục vụ dưới dạng file tĩnh.

Hiện trạng: repo mới ở dạng khung (scaffold). `index.html` và các thư mục `assets/css/`, `assets/js/`, `assets/images/` đã tồn tại nhưng còn trống — chưa section/trang nào bên dưới được triển khai.

## Lệnh (Commands)

Repo không có `package.json`, không build tool, không linter, không test runner. Quy trình phát triển:

- **Xem trước (Preview)**: mở trực tiếp `index.html` trên trình duyệt, hoặc chạy một static file server bất kỳ trong thư mục gốc (vd `python -m http.server`) để các đường dẫn asset tương đối phân giải đúng.
- **Pipeline asset**: `download_and_convert_assets.py` tải các ảnh được tham chiếu bởi một bản export Figma/Antigravity bên ngoài (các URL `http://localhost:3845/assets/...` tìm thấy trong thư mục "steps" của Antigravity IDE cục bộ), chuyển ảnh raster sang `.webp`, và sao chép nguyên các file `.svg` vào `assets/images/`. Chỉ hoạt động khi công cụ export cục bộ đó đang chạy và sinh ra file step; đây không phải trình tải file đa dụng. Chạy bằng `python download_and_convert_assets.py`.

## Kiến trúc

Website là một trang chủ cuộn dài duy nhất (`OPTION 01` trong Figma), gồm 10 section xếp chồng, cộng thêm Header và Footer dùng chung. Khi triển khai, dựng từng section/component một, tách thành các file CSS partial (hoặc các khối được phân định rõ) thay vì một stylesheet khổng lồ, vì trang rất cao (~11.300px theo thiết kế) và nhiều nội dung.

**Cấu trúc file dự kiến** (theo kế hoạch triển khai bên dưới):
- `assets/css/variables.css` — design token (màu, font, spacing) dưới dạng CSS custom properties
- `assets/css/` — stylesheet cho component và section
- `assets/js/` — điều khiển carousel/slider, tab lọc tin tức, hiệu ứng fade-in khi cuộn (IntersectionObserver)
- `assets/images/` — ảnh đã export/convert

**Thứ tự các section trên trang chủ** (mỗi cái là một khối hình riêng, đại khái theo trình tự): Hero slider → Điểm nhấn hạ tầng BOT → Giới thiệu/tầm nhìn–sứ mệnh → 3 trụ cột lĩnh vực kinh doanh → Dự án tiêu biểu → Số liệu công ty → Chi tiết dự án tiêu biểu → Đối tác/cổ đông → Tin tức có tab lọc theo danh mục → Footer kèm banner CTA.

Nguồn Figma là trang **MOODBOARD**. Trang chủ được chọn là **OPTION 01** (node section Figma `12:11`, nằm trong section "ĐỀ XUẤT GIAO DIỆN" `24:22`). Có 3 option khác (`17:759`, `23:3`, `24:17`) — bỏ qua. Một section riêng "PHÂN TÍCH VÀ ĐỀ XUẤT" (`13:46`) chứa phần lý giải.

## Phân tích thiết kế (OPTION 01 — trang chủ `12:11`)

> **Trạng thái trích xuất Figma (2026-07-23):** Việc trích token trực tiếp theo từng node qua Figma desktop MCP hiện đang **bị chặn**: mỗi OPTION là một node *section* của Figma và MCP trả về phản hồi "sparse" cho section (không liệt kê ID các frame con, nên không gọi được `get_design_context` trên các frame bên trong); ảnh chụp node render ra **màu đen tuyền** vì phần image fill của thiết kế tham chiếu tới asset server `localhost:3845` đang offline; và `get_variable_defs` trả về `{}` (file **không có biến Figma nào được bind** — màu/kích thước là fill hardcode). Để lấy giá trị hex/px chính xác, **mở Figma desktop và chọn một frame con** (không phải section OPTION 01), rồi chạy lại `get_design_context` / `get_variable_defs` trên frame đó. Trước khi làm được điều đó: màu brand = **đã xác nhận**; palette phụ, thang typography và spacing = **giá trị mặc định đề xuất**, cần đối chiếu lại với Figma khi có thể chọn được frame.

### Design token

**Màu sắc — palette brand đã xác nhận:**
| Token | Hex | Dùng cho |
|-------|-----|----------|
| `--dsh-red` | `#9a1220` | Đỏ brand chính — nút, điểm nhấn, gạch chân tab active, banner CTA |
| `--dsh-gold` | `#c9a84c` | Vàng nhấn — đường kẻ mảnh, số liệu thống kê, divider trang trí, trạng thái hover |
| `--dsh-navy` | `#080f1d` | Navy tối — nền section chính, footer |

**Màu sắc — palette phụ đề xuất (đối chiếu với Figma):**
| Token | Hex (gợi ý) | Dùng cho |
|-------|-----|----------|
| `--dsh-navy-2` | `#0f1a2e` | Card/panel nổi trên nền navy |
| `--dsh-white` | `#ffffff` | Chữ/tiêu đề trên nền navy |
| `--dsh-muted` | `#c7ccd6` | Chữ body/phụ trên nền navy |
| `--dsh-line` | `rgba(201,168,76,.25)` | Đường kẻ mảnh màu vàng |

**Typography — đã xác nhận (Figma node `1134:25`): dùng `Inter` cho TOÀN BỘ** (heading + body), tải về local `assets/fonts/` (weight 400/500/600/700/800), không CDN. Thang chữ fluid bằng `clamp()`:
| Vai trò | `clamp()` (min → max) |
|---------|-----------------------|
| Hero H1 | `clamp(2.5rem, 5vw, 4.5rem)` |
| Section H2 | `clamp(1.75rem, 3.5vw, 3rem)` |
| Card/H3 | `clamp(1.125rem, 2vw, 1.5rem)` |
| Body | `clamp(0.95rem, 1.1vw, 1.125rem)` |
| Eyebrow/nhãn | `0.8125rem`, letter-spacing `.12em`, viết hoa |
| Số thống kê | `clamp(2.5rem, 5vw, 4rem)` |

**Thang spacing (đề xuất, gốc 8px):** `4 / 8 / 16 / 24 / 32 / 48 / 64 / 96 / 128 px`. Padding dọc mỗi section: `clamp(64px, 8vw, 128px)`. Container nội dung max-width ≈ `1320px` (Bootstrap `.container`), canvas thiết kế 1920px.

### Bóc tách từng section (→ ánh xạ Bootstrap)

| # | Section | Mục đích | Bố cục | Ánh xạ Bootstrap |
|---|---------|----------|--------|------------------|
| — | **Header** | Nav sticky, glassmorphism khi cuộn | Logo trái, nav giữa/phải, CTA + đổi ngôn ngữ | `.navbar .navbar-expand-lg .fixed-top`, `.container`, toggler collapse ở `< lg` |
| 1 | **Hero slider** | Tuyên ngôn brand + các slide xoay vòng | Full-bleed navy, tiêu đề + CTA trên ảnh, dot/mũi tên | `#carousel` (Bootstrap Carousel), overlay `.container`, `.btn` |
| 2 | **Điểm nhấn hạ tầng BOT** | Câu chuyện hạ tầng chủ lực | 2 cột: ảnh + chữ | `.row`, `.col-lg-6` (xếp chồng ở `< lg`) |
| 3 | **Giới thiệu / tầm nhìn–sứ mệnh** | Giới thiệu công ty, tầm nhìn & sứ mệnh | Tiêu đề + 2–3 khối giá trị | `.row .g-4`, `.col-md-6 / col-lg-4` |
| 4 | **Trụ cột lĩnh vực kinh doanh** | 3 mảng kinh doanh cốt lõi | 3 card đều nhau: icon/tiêu đề/mô tả | `.row .row-cols-1 .row-cols-md-3`, `.card` |
| 5 | **Dự án tiêu biểu** | Trưng bày các dự án chính | Grid hoặc carousel card dự án | `.row .g-4`, `.col-md-6 .col-lg-4`, `.card` |
| 6 | **Số liệu công ty** | Con số chính (số năm, dự án, vốn…) | Dải ngang 3–4 bộ đếm | `.row .text-center`, `.col-6 .col-md-3`, số màu vàng |
| 7 | **Chi tiết dự án tiêu biểu** | Đi sâu một dự án chủ lực | Các hàng ảnh/chữ xen kẽ | lặp `.row` với `.flex-lg-row-reverse` |
| 8 | **Đối tác / cổ đông** | Logo đối tác & cổ đông | Grid logo responsive | `.row .row-cols-2 .row-cols-md-4 / md-6`, logo grayscale |
| 9 | **Tin tức + tab lọc danh mục** | Tin mới nhất kèm lọc danh mục | Thanh tab + grid card | `.nav .nav-pills` (lọc), `.row .g-4`, `.card`; JS lọc |
| — | **Footer** | Banner CTA + 4 cột link + copyright | Dải CTA full-width đỏ/navy trên footer tối | `.container`, `.row`, `.col-lg-3` × 4 |

### Đặc tả Header & Footer

- **Header:** trong suốt khi ở đỉnh hero; khi cuộn xuống thêm nền navy trong mờ + blur kiểu "glassmorphism" (bật/tắt một class qua scroll listener nhỏ hoặc sentinel IntersectionObserver). Gồm logo, các link nav chính, một nút CTA màu `--dsh-red`, và công tắc đổi ngôn ngữ VI/EN. Thu về hamburger (`.navbar-toggler`) dưới `lg`.
- **Footer:** một banner CTA nổi bật (nền đỏ brand, tiêu đề + nút) nằm ngay trên footer 4 cột nền navy tối (thông tin/logo công ty, link nhanh, lĩnh vực kinh doanh, liên hệ). Thanh dưới cùng: copyright + icon mạng xã hội. Đường kẻ mảnh vàng (`--dsh-line`) ngăn banner với các cột.

### Hành vi responsive

> **Thiết kế mobile đã xác nhận:** Figma node **`1241:23`** ("op1-3-mobile", khung 375px) là bản responsive chính thức — đối chiếu khi chỉnh mobile.

- Desktop (`≥ lg` / 992px+): grid nhiều cột đầy đủ như thiết kế (3–4 cột).
- Tablet (`md` / 768–991px): grid 2 cột; các section ảnh+chữ giữ cạnh nhau hoặc bắt đầu xếp chồng; nav thu về hamburger.
- Mobile (`< md` / < 768px): mọi thứ xếp về 1 cột (`col-12`); chữ hero co lại nhờ `clamp()`; số liệu wrap 2/hàng (`col-6`); logo đối tác 2/hàng.
- Ưu tiên typography/spacing fluid bằng `clamp()` thay vì đè breakpoint cứng; dùng các class cột responsive của Bootstrap (`col-`, `col-md-`, `col-lg-`) cho thay đổi bố cục.

**Hành vi mobile riêng theo section (khớp `op1-3-mobile`):**
- **Section 2 (BOT):** cột nội dung canh giữa (eyebrow/tiêu đề/lead/tags/nút); card nổi rơi xuống dưới ảnh, chữ card vẫn canh trái.
- **Section 5 (Dự án tiêu biểu):** bỏ slider ngang → xếp **dọc** từng thẻ, caption (tên + địa điểm) **luôn hiện** dưới ảnh (không dựa vào hover vì thiết bị cảm ứng).
- **Section 7 (Timeline):** dồn về **một phía**, trục dọc nằm bên trái (`left: 8px`), card full-width.
- **Section 9 (Tin tức):** tab lọc nằm **một hàng, cuộn ngang** khi tràn.

## CMS quản trị (thư mục `cms/` — Yii 1.x)

Ngoài site tĩnh, repo có một CMS chạy bằng **Yii Framework 1.x** trong `cms/` (webroot = `cms/`, entry `cms/index.php`). Nội dung động (hero slide, dự án, tin tức, đối tác, cấu hình…) quản trị qua module admin, model dưới `cms/protected/models/`, module dưới `cms/protected/modules/admin/`.

- **Theme giao diện admin — `hope-ui`:** module admin dùng theme **Hope UI** đặt tại `cms/themes/hope-ui/` (Bootstrap 5 admin dashboard). Asset (CSS/JS/ảnh) nằm trong `cms/themes/hope-ui/assets/`, các trang mẫu HTML tham chiếu trong `cms/themes/hope-ui/dashboard/` (vd `auth/sign-in.html`, `index.html`). Theme được kích hoạt cho module admin qua `AdminModule::init()` (`Yii::app()->setTheme('hope-ui')`), truy cập asset bằng `Yii::app()->theme->baseUrl`. Layout admin dùng theme này; **không tự chế layout/CSS khác** — bám markup + class của Hope UI.
- **CRUD sinh bằng giix:** các controller/view CRUD của admin được **generate bằng giix component** đã đặt sẵn trong `cms/protected/extensions/` (`giix-core/` — generator `giixModel` + `giixCrud`; `giix-components/` — base class `GxActiveRecord`, `GxController`, `GxActiveForm`, `GxHtml`). Khi thêm CRUD mới cho một bảng, ưu tiên gen bằng giix rồi tùy biến, không viết tay từ đầu; giữ nguyên các base class giix.
- **Xác thực:** đăng nhập admin qua `admin/auth/login` (route thân thiện `admin/dang-nhap`). Thành phần: `LoginForm` (`modules/admin/models/`), `UserIdentity` (`protected/components/`) xác thực bằng `email` + `password_hash` bcrypt, ghi audit vào `pvn_audit_logs`. Model tài khoản: `User` (bảng `pvn_users`). RBAC dùng `CDbAuthManager` (bảng `pvn_auth_*`).
- **Khoá tài khoản:** đăng nhập sai ≥ 5 lần liên tiếp (`UserIdentity::MAX_FAILED_ATTEMPTS`) → **khoá vĩnh viễn** (`User::registerFailedLogin` đặt `locked_until = User::LOCK_FOREVER '9999-12-31'`). Chỉ mở lại được bằng đặt lại mật khẩu qua email (tự gọi `User::unlock()`) hoặc admin mở khoá thủ công.
- **Quên/đặt lại mật khẩu qua email:** `admin/auth/requestPasswordReset` (route `admin/quen-mat-khau`) → nhập email → sinh token (`User::generatePasswordResetToken`, chỉ lưu **SHA-256 hash** + hạn `params['resetTokenTtl']` mặc định 1h vào cột `reset_token_hash` / `reset_token_expires_at`, migration `m260730_000000`) → gửi link `admin/auth/resetPassword?token=...` (route `admin/dat-lai-mat-khau`). Form: `PasswordResetRequestForm`, `SetPasswordForm`. Email gửi qua `Mailer::send()` (`protected/components/Mailer.php`, dùng `mail()` PHP; **luôn ghi log category `mailer`** để lấy link khi dev offline chưa cấu hình SMTP). Chống dò email: luôn hiện thông báo chung dù email có tồn tại hay không.

## Các trang khác (ngoài trang chủ)

- **Trang Giới thiệu (About):** xem `About.md` (thư mục gốc) — bóc tách 4 section thân (HeroBanner, Lịch sử hình thành, Cột mốc phát triển, Tầm nhìn & Chiến lược) + Header/Footer dùng chung, kèm node ID Figma (`GIỚI THIỆU` `1251:11840`, trong section `ANOTHER PAGES` `1251:11839`).

## Kế hoạch triển khai

> **Bóc tách section chi tiết để dựng lần lượt:** xem `.claude/SECTIONS.md` — nguồn thiết kế thật hiện tại là Figma node **`1134:25`** (10 section thân + header/footer, kèm node ID từng section, trạng thái ☐/☑). Bảng "Bóc tách từng section" phía trên (dựa trên design cũ `12:11`) đã lỗi thời ở vài điểm — ưu tiên `SECTIONS.md`.

Thứ tự dựng dự kiến (xem tài liệu thiết kế để biết chi tiết từng phase):

1. Design token & CSS reset (`variables.css`, layout/grid utilities cơ bản)
2. Component tái sử dụng (button, typography, card, tab lọc, điều khiển carousel)
3. Header (sticky, glassmorphism khi cuộn) và Footer (banner CTA + 4 cột link)
4. Toàn bộ 10 section trang chủ, làm lần lượt từng section
5. Tương tác JavaScript: carousel autoplay/điều khiển, tab lọc tin tức, fade-in khi cuộn
6. Rà responsive (1920/1440/1024/768/375px) + SEO (meta tag, phân cấp heading ngữ nghĩa, alt text) + tối ưu hiệu năng

## Ràng buộc chính cần giữ khi triển khai

- **Cách tiếp cận triển khai là Bootstrap 5.3** — xem `.claude/rules/frontend-bootstrap.md` để biết quy tắc code bắt buộc (ưu tiên grid/utilities/component, tối thiểu custom CSS, script cuối body, HTML ngữ nghĩa, Bootstrap Icons).
- **Icon & asset phải giống Figma 100% — KHÔNG tự chọn icon thay thế** — xem `.claude/rules/figma-icons.md`. Export đúng SVG/ảnh từ Figma (`localhost:3845`) về `assets/images/`, giữ đúng tỉ lệ; chỉ dùng placeholder khi asset server offline.
- **Không dùng CDN — mọi thư viện bên thứ ba phải tải về local** trong `assets/vendor/` (Bootstrap CSS/JS, Bootstrap Icons + font) và `assets/fonts/` (Google Fonts + file `.woff2`), tham chiếu qua đường dẫn tương đối. Trang phải chạy hoàn toàn offline, không request ra `cdn.jsdelivr.net`, `fonts.googleapis.com`, `fonts.gstatic.com` hay host ngoài nào. Cùng nguyên tắc với ảnh (export về local).
- Màu, typography và spacing phải lấy từ design token, không dùng giá trị tùy tiện — đỏ brand `#9a1220`, vàng nhấn `#c9a84c`, nền navy tối `#080f1d` là palette cốt lõi đã xác nhận. Các giá trị palette phụ/type/spacing trong phần Phân tích thiết kế ở trên là mặc định đề xuất; đối chiếu lại với Figma khi có thể chọn được frame con (xem ghi chú trạng thái trích xuất).
- Scaling responsive dùng `clamp()` cho typography/spacing fluid thay vì đè breakpoint cố định khi có thể, đặt trên nền các class cột responsive của Bootstrap.
- Fade-in section khi cuộn dùng `IntersectionObserver`, không poll sự kiện scroll.
- **Toàn bộ ảnh từ Figma phải export về local** trong `assets/images/` và tham chiếu qua đường dẫn tương đối — **không** hotlink tới `localhost:3845`, CDN ngoài, hay URL Figma. Ảnh raster → `.webp`, vector/logo → `.svg`. Trong khi asset thật chưa export được (server Figma offline), dùng placeholder local (SVG/`.webp` tạm) trong `assets/images/` và thay bằng ảnh thật sau; markup không được trỏ ra ngoài.
