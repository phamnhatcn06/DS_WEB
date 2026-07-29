# Phân Tích Trang Tin Tức (TIN TỨC & SỰ KIỆN) — Đông Sơn Holdings

> **Nguồn Figma:** file `ĐÔNG SƠN WEBSITE`, node **`1270:18856`** (tên layer trong Figma là "GIỚI THIỆU" nhưng nội dung thực tế là **trang Tin tức**).
> Khung thiết kế: `1920 × 3184px`. Trích xuất ngày 2026-07-29 qua Figma Desktop MCP.
> **Lưu ý asset:** ảnh tham chiếu `http://localhost:3845/assets/...` — phải export về `assets/images/` (`.webp`/`.svg`), KHÔNG hotlink (theo `figma-icons.md`).

---

## 1. Cấu trúc tổng thể (4 khối)

| # | Khối | Node ID | Kích thước | Nền |
|---|------|---------|-----------|-----|
| — | **Header / Navbar** | `1270:18862` | `1920×80` | Trong suốt trên hero |
| 1 | **HeroBanner** (tiêu đề trang + breadcrumb) | `1270:18857` | `1920×480` | Ảnh nền đỏ tối + overlay |
| 2 | **Nổi bật** — Trending Stories (Light Mode) | `1272:19688` | `1920×1045` | Trắng `#ffffff` |
| 3 | **Dự án trọng điểm** — Editor's Picks (Dark Mode Transition) | `1272:19775` | `1920×846` | Navy tối + CityScape |
| — | **CTA Banner + Footer** | `1270:19030` | `1920×833` | CTA đỏ → Footer navy `#080f1d` |

---

## 2. HeroBanner (`1270:18857`) — `1920×480`

- **Breadcrumb** (`1270:18893`): `Trang chủ` › `Giới thiệu` (cần đổi thành *Tin tức*).
- **Eyebrow** (`1270:18903`): `ĐÔNG SƠN HOLDINGS` — chữ hoa, letter-spacing rộng, màu vàng/hồng nhạt, canh giữa.
- **H1** (`1270:18905`): **"TIN TỨC VÀ SỰ KIỆN"** — Montserrat Bold, ~`60–72px`, trắng, canh giữa.
- **Subtitle** (`1271:19412`): *"Cập nhật những thông tin mới nhất về dự án, hoạt động kinh doanh và các sự kiện nổi bật của Đông Sơn Holdings trên hành trình kiến tạo giá trị bền vững."* — canh giữa, `~16px`, max-width `656px`.
- Nền: ảnh dự án phủ overlay đỏ tối (giống tông brand `#9a1220`/`#7e101b`).
- **Navbar** giống trang chủ: logo ĐS trái, menu giữa (Về chúng tôi ▾, Về chúng tôi ▾, Lĩnh vực ▾, Dự án, Quan hệ cổ đông, Tin tức), nút đỏ "Liên hệ ngay" phải.

---

## 3. Section "Nổi bật" (`1272:19688`) — Light Mode

Nền trắng, padding dọc `80px`, padding ngang `60px`, gap `40px`.

### 3.1. Header hàng tiêu đề (`1272:19690`)
- **H2** (`1272:19692`): **"Nổi bật"** — Montserrat Bold `36px`, line-height `40px`, màu `#191c1d`.
- **Link "Xem thêm"** (phải, `1272:19694`): Inter Regular `16px`, màu đỏ `#9a1220`, tracking `1px` + icon mũi tên `9.3px` (SVG).

### 3.2. Layout grid 12 cột (`1272:19698`)
Grid `12 cột`, 1 hàng cao `805px`, gap `40px`:
- **Cột trái (col 1→8):** khối nội dung chính.
- **Cột phải (col 9→12):** sidebar danh mục.

#### Cột trái — hàng 2 card trên (`1272:19700`, gap `32px`)
Mỗi card (2 card đều nhau):
- Ảnh thumbnail cao `256px`, nền `#edeeef`.
- **Tiêu đề** (H4): Montserrat Bold `20px`, line-height `28px`, `#191c1d`.
- **Mô tả**: Inter Regular `16px`, line-height `24px`, màu nâu `#59413f`.
- **Ngày**: Inter SemiBold `14px`, chữ hoa, tracking `0.7px`, màu `#5f5e5e`.

| Card | Tiêu đề | Mô tả | Ngày |
|------|---------|-------|------|
| 1 (`1272:19701`) | **NHÀ Ở XÃ HỘI BÃI VIÊN – NAM ĐỊNH: BƯỚC TIẾN MỚI** | "Khu nhà ở xã hội quy mô hơn 1.100 căn hộ cho người thu nhập thấp là bước đi chiến lược…" | 09 TH3, 2026 |
| 2 (`1272:19710`) | **ĐHĐCĐ THƯỜNG NIÊN 2026 THÀNH CÔNG RỰC RỠ** | "Đông Sơn Holdings đã tổ chức thành công ĐHĐCĐ với nhiều quyết sách hạ tầng quan…" | 23 TH4, 2026 |

#### Cột trái — Featured Full Card (`1272:19719`)
Thẻ lớn ngang, nền `#f3f4f5`, bo góc `2px`: **ảnh lớn bên trái** (cao `333px`) + **nội dung phải** (padding `32px`, rộng `722px`).
- **Badge** (`1272:19724`): nền đỏ `#9a1220`, chữ trắng `10px` chữ hoa: **"SỰ KIỆN"**.
- **H3** (`1272:19728`): Montserrat Bold `24px`, line-height `30px`: **"GIẢI PICKLEBALL ĐÔNG SƠN 2026: GẮN KẾT VÀ NĂNG LƯỢNG"**.
- **Mô tả**: Inter Regular `16px`, `#59413f`: "Ngày 14/3/2026, Công ty Cổ phần Đầu tư Hạ tầng Đông Sơn đã tổ chức giải thi đấu thường niên thu hút đông đảo đối tác…".
- **Meta** (`1272:19732`): `HTDS MEDIA` • `15 TH3, 2026` — Inter Bold `14px`, `#191c1d`, dấu `•` opacity 30%.

#### Cột phải — Sidebar "Danh mục tin tức" (`1272:19821`)
Card nền trắng, viền `#e1bebc`, padding `33px`, gap `24px`:
- **Heading 5** (`1272:19823`): "DANH MỤC TIN TỨC" — Inter SemiBold `14px`, chữ hoa, tracking `1.4px`, màu `#730011`, gạch dưới viền `#e1bebc`.
- **List danh mục** (gap `16px`):
  - **Tất cả tin tức (24)** — *active*: Inter SemiBold `16px`, màu `#730011`, viền trái `3px` `#730011`, padding-left `15px`, số đếm bên phải.
  - Dự án & Công trình (12) — Inter Regular, `#59413f`.
  - Hoạt động tập đoàn (8).
  - Quan hệ cổ đông (4).

---

## 4. Section "Dự án trọng điểm" (`1272:19775`) — Dark Mode Transition

Nền navy tối chuyển sắc + **CityScape** minh họa đô thị (`1272:20419`, `2339×616`, phủ mờ nền). Padding dọc `80px`.

- **Header hàng:** **H2 "Dự án trọng điểm"** (Montserrat Bold) + link **"Xem thêm"** (đỏ, mũi tên) bên phải.
- **Card lớn bên trái (featured):**
  - Badge **"DỰ ÁN TRỌNG ĐIỂM"** (nền đỏ).
  - **H3**: **"THI CÔNG CẦU HỒNG HÀ THUỘC DỰ ÁN VÀNH ĐAI 4 – VÙNG THỦ ĐÔ HÀ NỘI"**.
  - Mô tả: "Cầu Hồng Hà – một trong những công trình quan trọng nhất của dự án đường Vành đai 4, kết nối huyết mạch giao thông thủ đô…".
- **Danh sách phải (3 tin nhỏ, thumbnail + tiêu đề + ngày):**

| Tiêu đề | Ngày |
|---------|------|
| Chương trình Tổng kết năm 2025 – Đông Sơn nối vòng tay lớn | 07 TH2, 2026 |
| Thông báo thay đổi nội dung đăng ký doanh nghiệp | 15 TH5, 2026 |
| Dự án Cầu Ngọc Hồi và đường dẫn hai đầu cầu | 05 TH9, 2025 |

---

## 5. CTA Banner + Footer (`1270:19030`)

- **CTA Banner** (full-width, ảnh cầu dây văng nền tối): tiêu đề lớn **"KHÁM PHÁ TIỀM NĂNG. BẮT ĐẦU KẾT NỐI."** (Montserrat ExtraBold, canh giữa) + nút đỏ **"Liên hệ ngay"** (`#9a1220`).
- **Footer 4 cột** nền navy `#080f1d` (giống trang chủ):
  - Cột 1: Logo + Điện thoại + Email + social (Facebook, LinkedIn, YouTube).
  - Cột 2 *Về Đông Sơn*: Giới thiệu, Tầm nhìn & Sứ mệnh, Ban lãnh đạo, Giá trị cốt lõi, Trách nhiệm XH.
  - Cột 3 *Lĩnh vực*: Thi công & Xây lắp, Đầu tư BOT, Nhà ở & Đô thị, Năng lượng & KCN.
  - Cột 4 *Dự án*: BOT Hà Nội – Bắc Giang, Nhà ở XH Bãi Viên, Cao tốc TQ–HG, Mỹ Đình – Bái Đính.
  - Thanh dưới: © 2026 Đông Sơn Holdings + Chính sách bảo mật / Điều khoản.

---

## 6. Design Tokens (trích từ Figma)

### Màu sắc
| Token | Hex | Dùng cho |
|-------|-----|----------|
| Đỏ brand | `#9a1220` | Badge, link "Xem thêm", nút CTA, viền/nhấn |
| Đỏ đậm | `#730011` / `#7e101b` | Heading sidebar, mục active danh mục |
| Navy tối | `#080f1d` | Nền footer / section dark mode |
| Tiêu đề đậm | `#191c1d` | H2/H3/H4, meta |
| Chữ body nâu | `#59413f` | Mô tả tin |
| Chữ meta xám | `#5f5e5e` | Ngày đăng |
| Nền ảnh/skeleton | `#edeeef` / `#f3f4f5` | Thumbnail, card featured |
| Viền hồng nhạt | `#e1bebc` | Viền card sidebar |

### Typography
> ⚠️ **Khác với trang chủ:** trang này dùng **`Montserrat` Bold cho heading** (H2/H3/H4) và **`Inter` cho body/meta** (Regular/SemiBold/Bold). Trang chủ dùng Inter cho toàn bộ → cần thống nhất hoặc bổ sung font Montserrat local vào `assets/fonts/` (KHÔNG CDN).

| Vai trò | Font | Size / Line-height | Ghi chú |
|---------|------|--------------------|---------|
| Hero H1 | Montserrat Bold | ~`60–72px` | Chữ hoa, trắng |
| Section H2 ("Nổi bật", "Dự án trọng điểm") | Montserrat Bold | `36px` / `40px` | `#191c1d` |
| Card featured H3 | Montserrat Bold | `24px` / `30px` | |
| Card H4 | Montserrat Bold | `20px` / `28px` | |
| Body / mô tả | Inter Regular | `16px` / `24px` | `#59413f` |
| Meta / ngày | Inter SemiBold | `14px` / `20px` | chữ hoa, tracking `0.7px` |
| Badge | Inter Bold | `10px` / `15px` | chữ hoa, tracking `1px` |
| Danh mục heading | Inter SemiBold | `14px` | tracking `1.4px`, `#730011` |
| Link "Xem thêm" | Inter Regular | `16px` | tracking `1px`, `#9a1220` |

### Spacing / Layout
- Padding dọc section: `80px`; padding ngang: `60px`.
- Grid nội dung: **12 cột** — chính `8/12`, sidebar `4/12`; gap `40px`.
- Hàng 2 card: gap `32px`; card gap trong: `12px`.
- Container: `1920px` canvas; nội dung dùng `.container` (~`1320px`) + `.row/.col` khi dựng Bootstrap.

---

## 7. Ánh xạ Bootstrap 5.3 (đề xuất dựng)

| Khối | Bootstrap |
|------|-----------|
| HeroBanner | `<section>` full-bleth, ảnh nền + overlay, `.container` canh giữa, breadcrumb `.breadcrumb` |
| Nổi bật — layout | `.row` → `.col-lg-8` (chính) + `.col-lg-4` (sidebar) |
| 2 card trên | `.row .row-cols-1 .row-cols-md-2 .g-4` + `.card` |
| Featured card | `.card` ngang: `.row .g-0` → `.col-md-7` ảnh + `.col-md-5` nội dung; badge `.badge` |
| Sidebar danh mục | `.card` + `.list-group .list-group-flush`; mục active viền trái đỏ |
| Dự án trọng điểm | section nền navy; `.col-lg-8` card lớn + `.col-lg-4` list 3 tin (`.d-flex` thumbnail + text) |
| CTA + Footer | tái dùng component chung của trang chủ |

### Hành vi
- Danh mục tin tức: lọc bài theo `data-category` (JS, không reload) — active `#730011` viền trái.
- Fade-in section khi cuộn: `IntersectionObserver`.
- Responsive: `< lg` sidebar rơi xuống dưới; `< md` mọi card `col-12`, list dọc, tab danh mục cuộn ngang.

---

## 8. Asset cần export (từ `localhost:3845`)
- `imgNews` (Nhà ở XH Bãi Viên), `imgNews1` (ĐHĐCĐ 2026), `imgFeatured` (Giải Pickleball) — section Nổi bật.
- Ảnh cầu Hồng Hà + 3 thumbnail nhỏ + CityScape — section Dự án trọng điểm.
- Icon mũi tên "Xem thêm" (SVG), ảnh nền hero, ảnh nền CTA (cầu dây văng).
- → Lưu `assets/images/` dạng `.webp` (raster) / `.svg` (vector). Trong lúc server offline dùng placeholder local.
