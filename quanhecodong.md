# Phân Tích Thiết Kế Trang "Quan Hệ Cổ Đông → Báo Cáo Tài Chính" (DSH) từ Figma & Kế Hoạch Triển Khai

> Nguồn Figma: file `ĐÔNG SƠN WEBSITE` — Frame trang **`BÁO CÁO TÀI CHÍNH` (`1480:884`)** `[1920 × 2682px]` (bản Desktop).
> Node được chọn ban đầu: **`Article - Report Card 1` (`1480:1796`)** — thẻ báo cáo tài chính trong danh sách.
> Bản Mobile tương ứng: **`BÁO CÁO TÀI CHÍNH` (`1484:2784`)** `[1920 × 3748px]` (khung dựng mobile 375px).
> Đây là trang con thuộc mục **Quan hệ cổ đông** — hiển thị danh sách **Báo cáo tài chính** dạng bài viết (Light Mode).

---

## 1. Tổng Quan Trang

Trang là bố cục **danh sách bài viết (article listing)** trên nền sáng (Light Mode), gồm **6 khối liên hoàn** từ trên xuống:

| # | Khối (Section) | Node ID | Kích thước |
|---|----------------|---------|------------|
| 1 | **HeroBanner** — Banner đầu trang + Navbar trong suốt | `1480:885` | `1920 × ~440px` |
| 2 | **Breadcrumb** — Đường dẫn điều hướng | `1480:2013` | `1680px` |
| 3 | **Year Filter** — Bộ lọc theo năm | `1611:56` | `1680px` |
| 4 | **Main Content** — 2 cột: Danh sách báo cáo + Sidebar tin mới | `1480:1793` | `1680px` |
| 5 | **CTA Banner** — Dải "Khám phá tiềm năng. Bắt đầu kết nối." | *(chung Footer top)* | `1920px` |
| 6 | **Footer** — Chân trang 4 cột | `1480:1318` | `1920 × 833px` |

> **Ghi chú tái sử dụng:** Header/Navbar, CTA Banner và Footer **giống hệt** trang chủ (xem `CLAUDE.md` mục 3, 5). Trang này chỉ khác ở phần **thân giữa** (Breadcrumb + Year Filter + Danh sách + Sidebar).

---

## 2. Section 1 — HeroBanner (`1480:885`)

- **Nền**: Ảnh dự án (`Image` `1480:888`) phủ lớp mask **đỏ rượu thương hiệu** `#9a1220` (gradient tối dần), tạo dải hero màu đỏ đặc trưng.
- **Navbar (`1480:890`)**: Trong suốt, nổi trên hero — Logo trái, Navigation giữa (6 mục), nút CTA "Liên hệ ngay" phải. Mục **`Quan hệ cổ đông` (`1480:2026`)** là mục active của trang này.
- **Breadcrumb nhỏ (trên tiêu đề)**: `Trang chủ / Giới thiệu` (chữ trắng mờ, `~13px`).
- **Eyebrow**: `ĐÔNG SƠN HOLDINGS` (chữ hoa, letter-spacing rộng, `#ffd5d5` / trắng mờ).
- **Tiêu đề chính (`H1`)**: **`BÁO CÁO TÀI CHÍNH`** — căn giữa, chữ hoa, trắng `#ffffff`, cỡ lớn (`~60px` ExtraBold, xem thang H1 Banner trong `CLAUDE.md`).

---

## 3. Section 2 — Breadcrumb (`1480:2013`)

- Container `1680px`, căn trái, cách hero một khoảng.
- Chuỗi: **`TRANG CHỦ  ›  QUAN HỆ CỔ ĐÔNG  ›  BÁO CÁO TÀI CHÍNH`**.
  - Các mục cha (`Trang chủ`, `Quan hệ cổ đông`): màu muted (`#5f5e5e`), có link.
  - Mục hiện tại (`Báo cáo tài chính`): màu **đỏ nhấn `#730011`**, in đậm/hoa.
  - Dấu phân cách: icon **ChevronRight** (`10px`).
- Typography: chữ hoa, cỡ nhỏ (`~12–13px`), letter-spacing nhẹ.

---

## 4. Section 3 — Year Filter (`1611:56`)

Bộ lọc danh sách báo cáo **theo năm**, dạng hàng nút ngang:

- Nhãn dẫn: **`Năm:`** (chữ thường, muted).
- Các chip năm: **`Tất cả`** · **`2026`** · **`2025`** · **`2024`** · **`2023`** · **`2022`**.
- **Trạng thái Active** (vd `2025`): nền **đỏ thương hiệu `#9a1220`**, chữ trắng `#ffffff`, bo tròn (pill).
- **Trạng thái mặc định**: chữ tối `#191c1d`, nền trong suốt, hover đổi màu đỏ.
- Khoảng cách giữa các chip: đều nhau (`~12–16px`).

---

## 5. Section 4 — Main Content (`1480:1793`) — Bố cục 2 cột

Container `1680px`, chia **2 cột**: **cột trái** (danh sách báo cáo, rộng ~`1083px`) + **cột phải** (Sidebar "Tin tức mới nhất", rộng ~`573px`).

### 5.1. Cột trái — Danh sách Báo cáo (`Main Grid` `1480:1794`)

- Danh sách dọc gồm **4 thẻ báo cáo** (`Article - Report Card 1..4`: `1480:1796`, `1480:1812`, `1480:1828`, `1480:1844`), xếp chồng, cách nhau đều.
- Dưới danh sách là **Pagination (`1480:1908`)**: các nút số `1` `2` và nút mũi tên `›`.
  - Trang active (`1`): nền đỏ `#9a1220`, chữ trắng.
  - Trang thường: viền mảnh, chữ tối.

#### ⭐ Component chính: **Report Card** (`1480:1796`) — thông số chuẩn 100%

> Đây là node được yêu cầu triển khai. Thông số lấy trực tiếp từ Figma dev mode.

- **Khung thẻ**:
  - Nền: `#ffffff` (trắng).
  - Viền: `1px solid #e7e8e9`.
  - Bo góc: `2px`.
  - Padding trong: `25px`.
  - Bố cục dọc (flex column), các khối cách nhau bằng margin/padding riêng (xem dưới).
- **Hàng 1 — Ngày + Icon** (`Container` `1480:1798`, `justify-between`):
  - **Chip ngày** (`1480:1799`): nền `#f3f4f5`, viền `1px solid #e1bebc`, bo góc `12px`, padding `5px 13px`.
    - Text: `30 THÁNG 7, 2026` — Font **Inter Medium**, `12px`, màu `#191c1d`, letter-spacing `1.2px`, **UPPERCASE**, line-height `12px`.
  - **Icon phải** (`1480:1801`): SVG `16 × 16px` (biểu tượng lịch/tài liệu) — export đúng SVG từ Figma.
  - Padding-bottom hàng: `12px`.
- **Hàng 2 — Tiêu đề (`Heading 2` `1480:1804`)**:
  - Text (2 dòng): `BÁO CÁO TÀI CHÍNH HỢP NHẤT` / `QUÝ 2 NĂM 2025`.
  - Font **Inter SemiBold**, `20px`, màu `#191c1d`, line-height `28px`.
  - Padding-bottom: `8px`.
- **Hàng 3 — Mô tả ngắn (`1480:1806`)**:
  - Text: `Công ty Cổ phần Đông Sơn Holdings trân trọng công bố Báo cáo tài chính hợp nhất Quý 2 năm…` (cắt 2 dòng).
  - Font **Inter Regular**, `16px`, màu `#5f5e5e`, line-height `25.6px`.
- **Hàng 4 — Link tải (`Link` `1480:1808`)**:
  - Text: **`Tải về PDF`** — Font **Inter SemiBold**, `14px`, màu **`#730011`** (đỏ đậm), letter-spacing `0.7px`.
  - Icon kèm (`1480:1810`): SVG `13.33 × 13.33px` (biểu tượng tải/mở tài liệu), gap `8px`.
  - Padding-top: `12px`.

> **Nội dung 4 thẻ mẫu** (từ thiết kế): *Báo cáo tài chính hợp nhất Quý 2 năm 2025*, *Báo cáo tài chính riêng Quý 2 năm 2026*, *Báo cáo tài chính hợp nhất Quý 1 năm 2026*, *Báo cáo tài chính riêng Quý 1 năm 2026* — tất cả ngày `30 THÁNG 7, 2026`.

### 5.2. Cột phải — Sidebar "Tin tức mới nhất" (`Latest Posts` `1480:1989`)

- **Tiêu đề khối (`Heading 3` `1480:1990`)**: **`Tin tức mới nhất`** (Inter Bold/SemiBold, ~`20–24px`, màu tối).
- **Danh sách item** (mỗi item là 1 `Link`, vd `1480:1993`, `1503:64`, `1503:73`, `1480:2001`): bố cục ngang gồm:
  - **Badge ngày** bên trái: khối nhỏ hiển thị số ngày lớn + tháng (vd `30 / TH7`), nền nhạt.
  - **Tiêu đề tin (`Heading 4`)**: 2 dòng, cỡ nhỏ (`~14–16px`), màu tối, hover đổi đỏ.
- Các item cách nhau bằng đường kẻ mảnh / khoảng trắng đều.
- Nội dung mẫu: *"Báo cáo 2 hình quản trị công ty 6 tháng…"*, *"Thông báo về việc lấy ý kiến cổ đông bằng văn…"* (×2), *"Báo cáo 2 hình quản trị công ty 6 tháng…"*.

---

## 6. Section 5 & 6 — CTA Banner + Footer (tái sử dụng)

- **CTA Banner**: Dải ảnh **cầu dây văng** (bridge) phủ tối, tiêu đề trắng **`KHÁM PHÁ TIỀM NĂNG. BẮT ĐẦU KẾT NỐI.`** + nút đỏ **`Liên lạc ngay`** (`#9a1220`, bo góc `8px`).
- **Footer (`1480:1318`)**: Nền navy `#080f1d`, 4 cột (`Về Đông Sơn`, `Lĩnh vực`, `Dự án`, `Nhà đầu tư`) + cột logo & liên hệ. Dòng bản quyền + link `Chính sách bảo mật / Điều khoản sử dụng`.

> Chi tiết đầy đủ Header, CTA và Footer: xem `CLAUDE.md` mục 3 (Header), 5 (Footer).

---

## 7. Design Tokens Mới Của Trang (bổ sung cho `variables.css`)

Trang danh sách này dùng **hệ màu Light Mode trung tính** — bổ sung các token sau (KHÔNG có ở trang chủ tối):

```css
:root {
  /* Neutral text (light mode) */
  --dsh-ink:        #191c1d;   /* chữ chính trên nền sáng (tiêu đề, chip ngày) */
  --dsh-muted-2:    #5f5e5e;   /* chữ mô tả, breadcrumb cha */

  /* Accent phụ (link tải) — đỏ đậm hơn brand red */
  --dsh-red-deep:   #730011;   /* "Tải về PDF", breadcrumb active */

  /* Bề mặt & viền thẻ (light) */
  --dsh-card-bg:    #ffffff;   /* nền thẻ báo cáo */
  --dsh-card-line:  #e7e8e9;   /* viền thẻ */
  --dsh-chip-bg:    #f3f4f5;   /* nền chip ngày */
  --dsh-chip-line:  #e1bebc;   /* viền chip ngày (ánh hồng) */

  /* Radius */
  --dsh-radius-card: 2px;      /* thẻ báo cáo (góc gần vuông) */
  --dsh-radius-pill: 12px;     /* chip ngày / chip năm */
}
```

- Giữ nguyên token brand: `--dsh-red: #9a1220` (hero mask, chip năm active, pagination, CTA), `--dsh-navy: #080f1d` (footer).
- **Lưu ý**: link tải dùng `#730011` **khác** với `#9a1220` — không gộp làm một.

---

## 8. Typography (áp dụng cho trang)

| Vai trò | Font | Size | Weight | Line-height | Màu | Ghi chú |
|---------|------|------|--------|-------------|-----|---------|
| Hero Title (H1) | Inter | ~60px | ExtraBold | ~69px | `#ffffff` | Chữ hoa, căn giữa |
| Card Title (H2) | Inter | 20px | SemiBold | 28px | `#191c1d` | 2 dòng |
| Card Description | Inter | 16px | Regular | 25.6px | `#5f5e5e` | Cắt ~2 dòng |
| Chip ngày | Inter | 12px | Medium | 12px | `#191c1d` | Uppercase, tracking `1.2px` |
| Link "Tải về PDF" | Inter | 14px | SemiBold | 14px | `#730011` | Tracking `0.7px` |
| Sidebar Heading (H3) | Inter | ~20–24px | SemiBold | — | `#191c1d` | "Tin tức mới nhất" |
| Sidebar item (H4) | Inter | ~14–16px | Medium | — | `#191c1d` | Hover → đỏ |
| Breadcrumb / Year label | Inter | ~12–13px | Medium | — | `#5f5e5e` | Uppercase |

Font chủ đạo toàn trang: **Inter** (tải local, không CDN — theo `frontend-bootstrap.md`).

---

## 9. Responsive (Desktop `1480:884` → Mobile `1484:2784`)

| Thiết bị | Breakpoint | Quy tắc |
|----------|-----------|---------|
| Desktop Wide | `1920px` | 2 cột: danh sách `1083px` + sidebar `573px`, container `1680px` |
| Desktop / Laptop | `1440px` | Giữ 2 cột, container co còn `~1280px`, padding lề giảm |
| Tablet | `1024px` | Sidebar xuống dưới danh sách hoặc thu hẹp; Year Filter cho phép wrap |
| Tablet Portrait | `768px` | 1 cột — sidebar xếp dưới; Navbar → Hamburger |
| Mobile | `375px` | Thẻ full-width (`343px`, xem `Report-Card` `1481:2303`), Hero title co nhỏ, Year Filter cuộn ngang / xuống dòng |

- Bản mobile Figma: cột báo cáo `Main-Reports-Column` (`1481:2302`, `375px`) với các `Report-Card` `343 × 192px`, bo góc và padding thu gọn.
- Kỹ thuật: dùng **Bootstrap Grid** (`col-lg-8` danh sách + `col-lg-4` sidebar), `clamp()` cho tiêu đề Hero, chip năm dùng flex-wrap.

---

## 10. Hiệu Ứng & Tương Tác

1. **Report Card hover**: nổi nhẹ `translateY(-4px)` + đổi viền sang ánh vàng/đỏ mờ (`border-color: rgba(201,168,76,.4)` hoặc `#e1bebc`), transition `300ms ease`.
2. **Link "Tải về PDF" hover**: đậm màu / gạch chân trượt, icon dịch nhẹ.
3. **Year Filter chip**: hover đổi chữ sang đỏ; active nền `#9a1220`.
4. **Pagination**: hover nút số đổi nền nhạt; active nền đỏ.
5. **Sidebar item hover**: tiêu đề đổi màu đỏ `#9a1220`.
6. **Fade-in up** cho danh sách thẻ khi cuộn (dùng `IntersectionObserver`, không poll scroll — theo `frontend-bootstrap.md`).

---

## 11. Kế Hoạch Triển Khai (Bootstrap 5.3 tĩnh, local)

Trang: `quan-he-co-dong-bao-cao-tai-chinh.html` (hoặc tích hợp vào layout CMS hiện có).

1. **Tái sử dụng** Header/Navbar, CTA Banner, Footer từ trang chủ (đặt active mục "Quan hệ cổ đông").
2. **Hero**: `<section>` full-bleed, ảnh nền + overlay đỏ `#9a1220`, tiêu đề `<h1>` căn giữa.
3. **Breadcrumb**: dùng `nav.breadcrumb` của Bootstrap, tùy biến màu active `#730011`.
4. **Year Filter**: `Nav pills` — mỗi năm 1 pill, active `#9a1220`; lọc thẻ theo `data-year` bằng JS (ẩn/hiện, không reload).
5. **Main Content**: `row` → `col-lg-8` (danh sách `.report-card`) + `col-lg-4` (sidebar).
   - **Report Card**: dựng đúng token mục 7 (border `#e7e8e9`, radius `2px`, padding `25px`, chip ngày `#f3f4f5`/`#e1bebc`, link `#730011`). Export đúng 2 icon SVG (`16px` lịch, `13.33px` tải) từ Figma về `assets/images/` — **không** thay bằng icon "gần giống" (theo `figma-icons.md`).
   - **Pagination**: component `Pagination` của Bootstrap, active `#9a1220`.
   - **Sidebar**: danh sách item với badge ngày + tiêu đề.
6. **Responsive & kiểm thử** ở các mốc `1920 / 1440 / 1024 / 768 / 375px`.
7. **SEO**: `<title>Báo cáo tài chính | Quan hệ cổ đông — Đông Sơn Holdings</title>`, `<h1>` duy nhất, `alt` cho ảnh, link "Tải về PDF" trỏ file thật với `download`.

---

## 12. Checklist Bám Sát Figma

- [ ] Report Card: nền trắng, viền `#e7e8e9`, radius `2px`, padding `25px`.
- [ ] Chip ngày: nền `#f3f4f5`, viền `#e1bebc`, radius `12px`, text `12px`/`#191c1d`/tracking `1.2px`/UPPERCASE.
- [ ] Tiêu đề thẻ `20px` SemiBold `#191c1d`, line-height `28px`.
- [ ] Mô tả `16px` Regular `#5f5e5e`, line-height `25.6px`.
- [ ] Link "Tải về PDF" `14px` SemiBold `#730011`, tracking `0.7px` + icon `13.33px`.
- [ ] Icon lịch `16px` & icon tải `13.33px` export đúng từ Figma (không thay thế).
- [ ] Year Filter: chip active `2025` nền `#9a1220`.
- [ ] Breadcrumb active màu `#730011`.
- [ ] Hero mask đỏ `#9a1220`, tiêu đề trắng căn giữa.
- [ ] Header / CTA / Footer đồng bộ trang chủ.
