# Quy tắc Frontend — Bootstrap 5.3 (Website tĩnh)

> Quy tắc **bắt buộc** khi code website tĩnh cho Đông Sơn Holdings (DSH).
> Trang chủ là một trang cuộn dài (single long-scrolling) dựng từ Figma OPTION 01 (`12:11`).
> Không dùng framework JS, không bundler, không package manager — chỉ HTML/CSS/JS tĩnh + Bootstrap **tải về local** (không CDN).

---

## 🎯 Nguyên tắc cốt lõi

| # | Rule |
|---|------|
| 1 | **Bootstrap 5.3 tải về local** (`assets/vendor/`, KHÔNG CDN) — ưu tiên Grid, utilities và component có sẵn; hạn chế tối đa custom CSS |
| 2 | **Responsive bằng Bootstrap Grid** — `col-`, `col-md-`, `col-lg-` cho mobile / tablet / desktop |
| 3 | **Semantic HTML** — dùng đúng thẻ ngữ nghĩa, không "div soup" |
| 4 | **Clean code** — thụt lề chuẩn, dễ đọc, comment rõ ràng cho từng section |
| 5 | **Script ở cuối `<body>`** — Popper + Bootstrap JS đặt trước `</body>` |

---

## 📦 Framework — Bootstrap 5.3 (tải về local, KHÔNG CDN)

> **Bắt buộc:** mọi thư viện bên thứ ba (Bootstrap CSS/JS, Bootstrap Icons + file font, Google Fonts + `.woff2`) phải tải về `assets/vendor/` và `assets/fonts/`, tham chiếu bằng đường dẫn tương đối. Trang chạy offline hoàn toàn — không request tới `cdn.jsdelivr.net`, `fonts.googleapis.com`, `fonts.gstatic.com` hay host ngoài. Cấu trúc: `assets/vendor/bootstrap/`, `assets/vendor/bootstrap-icons/` (+ `fonts/`), `assets/fonts/` (+ `files/`).


- **Luôn** ưu tiên component dựng sẵn của Bootstrap trước khi tự viết:
  - `Navbar` (header), `Carousel` (hero slider), `Card` (project/news/pillar), `Button`,
    `Nav / Nav pills` (news filter tabs), `Collapse`, `Ratio` (ảnh 16:9), `Modal` (nếu cần).
- **Layout dùng Grid + utilities**, không viết flexbox/float thủ công khi Bootstrap đã có:
  - Spacing: `p-*`, `px-*`, `py-*`, `m-*`, `mt-*`, `gap-*`, `g-*` (gutter của row).
  - Flex: `d-flex`, `align-items-center`, `justify-content-between`, `flex-column`, `flex-lg-row`.
  - Khác: `text-center`, `text-uppercase`, `fw-bold`, `ratio ratio-16x9`, `rounded`, `shadow`, `w-100`.
- **Custom CSS chỉ khi Bootstrap không đáp ứng** (brand colors, gradient/overlay, glassmorphism header,
  fluid `clamp()` typography, hiệu ứng fade-in). Gom vào `assets/css/` theo section, không rải inline.

```html
<!-- ✅ Đúng: dùng grid + utilities + component -->
<section class="py-5 bg-dark text-white">
  <div class="container">
    <div class="row g-4 align-items-center">
      <div class="col-12 col-lg-6"> ... </div>
      <div class="col-12 col-lg-6"> ... </div>
    </div>
  </div>
</section>

<!-- ❌ Sai: tự viết flex/spacing thủ công khi Bootstrap đã có -->
<div style="display:flex; padding:48px; gap:24px;"> ... </div>
```

---

## 📐 Responsive — Bootstrap Grid

- Mọi block nội dung nằm trong `.container` (hoặc `.container-fluid` cho dải full-bleth như hero/CTA).
- Cấu trúc cột theo breakpoint:

| Thiết bị | Breakpoint | Quy ước cột |
|----------|-----------|-------------|
| Mobile | `< 768px` (`col-`) | 1 cột — `col-12`; stats `col-6`; logo đối tác `col-6` |
| Tablet | `≥ 768px` (`col-md-`) | 2 cột — `col-md-6`; pillars/news `col-md-6` |
| Desktop | `≥ 992px` (`col-lg-`) | 3–4 cột — `col-lg-4`, `col-lg-3`; 2-col image+text `col-lg-6` |

```html
<!-- Business pillars: 1 → 2 → 3 cột -->
<div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4"> ... </div>

<!-- Stats: 2/hàng mobile, 4/hàng desktop -->
<div class="row text-center">
  <div class="col-6 col-md-3"> ... </div>
</div>
```

- Ưu tiên **fluid `clamp()`** cho font-size/spacing thay vì đè nhiều breakpoint thủ công.
- Kiểm tra ở các mốc: 1920 / 1440 / 1024 / 768 / 375px.

---

## 🖼️ Ảnh & Icon

- Ảnh: dùng `<img>` với `alt` mô tả, `loading="lazy"`, `class="img-fluid"` để co giãn.
  Khung tỉ lệ dùng `.ratio` (vd `ratio ratio-16x9`) để tránh layout shift (CLS).
- Đồ hoạ vector/logo: `<img src="*.svg">` hoặc `<svg>` inline.
- Icon: **Bootstrap Icons** (tải về `assets/vendor/bootstrap-icons/`, dùng nhất quán) — KHÔNG link CDN.

```html
<img src="assets/images/project-01.webp" alt="Dự án BOT cao tốc DSH"
     class="img-fluid rounded" loading="lazy" />

<!-- Bootstrap Icons -->
<i class="bi bi-buildings"></i>
<!-- hoặc FontAwesome -->
<i class="fa-solid fa-road"></i>
```

---

## 🧱 Cấu trúc file — markup hoàn chỉnh

Mỗi trang là HTML đầy đủ: `<head>` chứa link **local** Bootstrap + Fonts + Icon (`assets/vendor/`, `assets/fonts/`),
`<body>` chứa nội dung, script Popper/Bootstrap đặt cuối `<body>`.

```html
<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <!-- SEO -->
  <title>Đông Sơn Holdings</title>
  <meta name="description" content="..." />

  <!-- Fonts (local — tải về, KHÔNG dùng Google Fonts CDN) -->
  <link href="assets/fonts/fonts.css" rel="stylesheet" />

  <!-- Bootstrap 5.3 CSS (local) -->
  <link href="assets/vendor/bootstrap/bootstrap.min.css" rel="stylesheet" />
  <!-- Bootstrap Icons (local) -->
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.min.css" rel="stylesheet" />

  <!-- Custom CSS (design tokens + section styles) -->
  <link href="assets/css/variables.css" rel="stylesheet" />
  <link href="assets/css/main.css" rel="stylesheet" />
</head>
<body>

  <!-- ===== Header ===== -->
  <header> ... </header>

  <main>
    <!-- ===== Section 1: Hero slider ===== -->
    <section id="hero"> ... </section>

    <!-- ===== Section 2: BOT / Infrastructure ===== -->
    <section id="bot"> ... </section>

    <!-- ... các section còn lại ... -->
  </main>

  <!-- ===== Footer ===== -->
  <footer> ... </footer>

  <!-- Bootstrap JS bundle (đã kèm Popper) — local, CUỐI body -->
  <script src="assets/vendor/bootstrap/bootstrap.bundle.min.js"></script>
  <!-- Custom JS (carousel config, news filter, fade-in) -->
  <script src="assets/js/main.js"></script>
</body>
</html>
```

> **Lưu ý:** dùng `bootstrap.bundle.min.js` (đã gồm Popper) để không phải load Popper riêng.

---

## 🎨 Design tokens (custom CSS)

Brand colors **bắt buộc** đặt trong `assets/css/variables.css` dưới dạng CSS custom properties,
không hardcode rải rác:

```css
:root {
  /* Confirmed brand palette */
  --dsh-red:  #9a1220;
  --dsh-gold: #c9a84c;
  --dsh-navy: #080f1d;

  /* Supporting (recommended — đối chiếu Figma) */
  --dsh-navy-2: #0f1a2e;
  --dsh-muted:  #c7ccd6;
  --dsh-line:   rgba(201, 168, 76, .25);

  /* Fonts */
  --font-heading: "Playfair Display", serif;
  --font-body: "Be Vietnam Pro", system-ui, sans-serif;
}
```

- Có thể override biến của Bootstrap (`--bs-primary`, `--bs-body-font-family`) để component dùng luôn màu brand.
- Typography/spacing fluid bằng `clamp()` (xem bảng scale trong `CLAUDE.md`).

---

## ✨ JavaScript (vanilla, cuối body)

| Tính năng | Cách làm |
|-----------|----------|
| Hero slider | Bootstrap `Carousel` (data-attributes hoặc JS API), autoplay `data-bs-ride="carousel"` |
| News filter tabs | `Nav pills` + JS lọc card theo `data-category` (ẩn/hiện, không reload) |
| Header glassmorphism | Thêm/bỏ class khi scroll — dùng `IntersectionObserver` sentinel, **không** poll `scroll` |
| Fade-in on scroll | `IntersectionObserver`, thêm class `.is-visible`, **không** dùng scroll polling |

```js
// ✅ Fade-in bằng IntersectionObserver (không polling scroll)
const observer = new IntersectionObserver((entries) => {
  entries.forEach((entry) => {
    if (entry.isIntersecting) {
      entry.target.classList.add('is-visible');
      observer.unobserve(entry.target);
    }
  });
}, { threshold: 0.15 });

document.querySelectorAll('.fade-section').forEach((el) => observer.observe(el));
```

- Tuân theo `clean-code.md`: tên biến rõ nghĩa, hàm làm một việc, không magic number, không dead code.

---

## ✅ Clean code checklist

- [ ] HTML ngữ nghĩa: `header`, `nav`, `main`, `section`, `article`, `footer`, heading đúng cấp (1 × `h1`).
- [ ] Mỗi section có comment mở đầu (`<!-- ===== Section N: Tên ===== -->`).
- [ ] Thụt lề 2 space, nhất quán, không trailing whitespace.
- [ ] Ưu tiên class Bootstrap; custom CSS gom theo section, không inline style.
- [ ] Ảnh có `alt` + `loading="lazy"`; icon dùng một bộ nhất quán.
- [ ] Không có CSS/JS chết; không comment code cũ để lại.
- [ ] Kiểm tra responsive 1920 / 1440 / 1024 / 768 / 375px trước khi coi là xong.

---

## ❌ Tránh

- ❌ Tự viết grid/flex thủ công khi Bootstrap đã có class.
- ❌ Inline `style="..."` cho những gì utilities làm được.
- ❌ Nhồi toàn bộ CSS vào một file khổng lồ — chia theo section/component.
- ❌ Load Popper riêng khi đã dùng `bootstrap.bundle.min.js`.
- ❌ Poll sự kiện `scroll` cho fade-in/header (dùng `IntersectionObserver`).
- ❌ Chế màu brand tuỳ tiện — chỉ dùng token đã xác nhận.
- ❌ Link thư viện/font qua CDN hay host ngoài — mọi asset phải tải về local (`assets/vendor/`, `assets/fonts/`, `assets/images/`).
