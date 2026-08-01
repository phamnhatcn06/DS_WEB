# Phân Tích Trang "Sứ Mệnh - Tầm Nhìn" — Đông Sơn Holdings (DSH)

> Nguồn Figma: file **ĐÔNG SƠN WEBSITE**
> - Desktop: node `1260:16821` — `SỨ MỆNH - TẦM NHÌN` (khung `1920 × 2811px`)
> - Mobile: node `1266:18366` — `su-menh-tam-nhin-mobile` (khung `375 × 2871px`)
>
> Trạng thái trích xuất (2026-07-29): trích được **cấu trúc + text + màu hardcode + spacing** đầy đủ qua Figma MCP `get_design_context`/`get_metadata`. Ảnh fill tham chiếu `localhost:3845` (asset server) → khi triển khai phải export ảnh về local `assets/images/` (xem ràng buộc trong `.claude/CLAUDE.md`).

---

## 0. Ghi chú quan trọng về Font (khác design token cũ)

Design context trả về font **thực tế đang dùng trong file**:
- **Heading** (`SỨ MỆNH`, `TẦM NHÌN`, `GIÁ TRỊ CỐT LÕI`, tiêu đề card giá trị): **`Montserrat`** (Bold / SemiBold), viết hoa.
- **Body / mô tả / tag / caption**: **`Inter`** (Regular / Medium / Italic).

> ⚠️ `.claude/CLAUDE.md` ghi "dùng Inter cho toàn bộ". Trang này thực tế dùng **Montserrat cho heading**. Khi triển khai cần thống nhất: hoặc tải thêm `Montserrat` về `assets/fonts/`, hoặc quy về Inter theo chuẩn dự án. **Đề xuất: tải Montserrat local để bám sát thiết kế**, vẫn tuân thủ quy tắc "không CDN".

---

## 1. Cấu trúc tổng thể (Desktop)

Trang gồm **Header (dùng chung)** + **4 section thân** + **Footer (dùng chung)**:

| # | Section | Node ID | Kích thước (desktop) | Nền |
|---|---------|---------|----------------------|-----|
| — | **HeroBanner** (breadcrumb + tiêu đề trang) | `1260:16822` | `1920 × 480` | Ảnh nền + overlay đỏ→navy |
| 1 | **Vision Section** (Tầm nhìn) | `1260:17109` | `1920 × 318` | `#f3f4f5` (xám nhạt) |
| 2 | **Mission Section** (Sứ mệnh) | `1260:17119` | `1920 × 604` | `#f3f4f5` |
| 3 | **Core Values Section** (Giá trị cốt lõi) | `1260:17155` | `1920 × 576` | `#f8f9fa` |
| — | **Footer** (CTA banner + 4 cột) | `1260:16995` | `1920 × 833` | Đỏ → navy `#080f1d` |

Container nội dung: max-width `1680px`, padding hai bên `120px` (đúng chuẩn dự án).

---

## 2. Section HeroBanner (`1260:16822`)

Banner đầu trang cao `480px`, ảnh nền công trình phủ overlay tối/đỏ, chứa Navbar trong suốt nổi lên trên.

- **Breadcrumb** (`1260:16858`) — góc trên trái vùng nội dung: `Trang chủ  ›  Sứ mệnh - Tầm nhìn` (`ChevronRight` phân tách).
- **Eyebrow** (`1260:16865`): dòng nhỏ căn giữa `— ĐỊNH HƯỚNG CHIẾN LƯỢC —` (hai gạch ngang `48×1px` hai bên, chữ viết hoa, letter-spacing rộng).
- **Tiêu đề trang** (`1260:16870`): **`SỨ MỆNH - TẦM NHÌN`** — cỡ lớn (~`60px`), Bold, viết hoa, trắng, căn giữa.
- Navbar giống các trang khác (logo trái, menu giữa, nút `Liên hệ ngay` đỏ bên phải).

Bố cục: eyebrow + tiêu đề **căn giữa** theo chiều ngang, đặt ở nửa dưới banner (y ≈ 220–325).

---

## 3. Section 1 — Vision / Tầm Nhìn (`1260:17109`)

Khối tuyên ngôn tầm nhìn, đơn giản, căn giữa.

- **Heading** `1260:17113`: **`TẦM NHÌN`** — Montserrat Bold `32px` (line-height `38.4px`), màu `#191c1d`, viết hoa, **căn giữa**, phía trên có **divider ngang vàng/đỏ `48×2px`** (`1260:17114`).
- **Blockquote** `1260:17115`: câu tuyên ngôn in nghiêng/đậm, căn giữa, padding trái `32px`:
  > *"TRỞ THÀNH DOANH NGHIỆP UY TÍN TRONG LĨNH VỰC NĂNG LƯỢNG, BẤT ĐỘNG SẢN VÀ XÂY LẮP. KIẾN TẠO CÁC GIÁ TRỊ BỀN VỮNG VÀ ĐỒNG HÀNH CÙNG SỰ PHÁT TRIỂN CỦA XÃ HỘI"*
- Padding section: `py-48px`, container `1680px`.

---

## 4. Section 2 — Mission / Sứ Mệnh (`1260:17119`)

Bố cục **2 cột**: thẻ chữ trái + ảnh phải, chiều cao `508px`, gap `48px`.

**Cột trái — Card trắng** (`1260:17121`, `599px` rộng):
- Nền trắng, border `rgba(225,190,188,0.3)`, bo góc `12px`, shadow nhẹ, padding `48–49px`.
- **Badge nổi góc trên trái** (`1260:17148`): ô vuông `48×48px` màu đỏ brand `#9a1220`, lệch ra ngoài card (`top:-24px; left:-24px`), chứa icon trắng (target/mũi tên).
- **Heading** `1260:17123`: **`SỨ MỆNH`** — Montserrat Bold `32px`, `#191c1d`, viết hoa.
- **Đoạn mô tả** `1260:17124`: Inter Regular `18px` (line-height `29.25px`), màu **`#59413f`** (nâu đỏ trầm):
  > *"Đông Sơn định hướng phát triển trên ba lĩnh vực trọng tâm gồm đầu tư, bất động sản và xây lắp; tập trung mở rộng hoạt động đầu tư vào các dự án khu công nghiệp, năng lượng, hạ tầng và phát triển đô thị, đồng thời không ngừng nâng cao năng lực quản trị, tài chính và triển khai dự án nhằm tạo ra giá trị bền vững cho khách hàng, đối tác và cộng đồng."*
- **3 tag/chip** (`1260:17132`): nền `#f8f9fa`, border `rgba(225,190,188,0.4)`, bo `2px`, icon + chữ Inter Medium `12px` viết hoa:
  - `ĐẦU TƯ TẬP TRUNG` · `NĂNG LỰC QUẢN TRỊ` · `GIÁ TRỊ BỀN VỮNG`
  - Xếp: 2 chip hàng trên, 1 chip hàng dưới.

**Cột phải — Ảnh** (`1260:17153`): ảnh công trình/cẩu tháp, chiếm phần còn lại (`flex:1`), bo góc `12px`, `object-cover`.

---

## 5. Section 3 — Core Values / Giá Trị Cốt Lõi (`1260:17155`)

Nền `#f8f9fa`, padding `py-48px`, gap dọc `64px`, nội dung căn giữa.

**Cụm tiêu đề** (`1260:17156`, max-width `1280px`):
- **Heading** `1260:17158`: **`GIÁ TRỊ CỐT LÕI`** — Montserrat Bold `32px`, **màu đỏ brand `#9a1220`**, căn giữa.
- **Phụ đề** `1260:17160`: Inter *Italic* `16px`, màu `#59413f`, căn giữa:
  > *"Nền tảng vững chắc cho sự phát triển trường tồn của Đông Sơn Holdings"*
- **Gạch chân trang trí** `1260:17161`: thanh đỏ `#9a1220`, `96×4px`.

**Lưới 4 card giá trị** (`1260:17162`, gap `24px`, mỗi card `flex:1`):
Mỗi card: nền `#f3f4f5`, bo góc `12px`, **viền dưới `4px`** (accent khi hover), padding `32px`, gồm icon-box trắng `64×64px` (bo `12px`, shadow) + tiêu đề + mô tả.

| Card | Icon | Tiêu đề (Montserrat SemiBold `20px`) | Mô tả (Inter `16px`, `#59413f`) |
|------|------|--------------------------------------|--------------------------------|
| 1 | handshake / bắt tay | **TRÁCH NHIỆM** | Cam kết cao nhất với lời hứa, đảm bảo quyền lợi tốt nhất cho đối tác và cộng đồng. |
| 2 | award / huy chương | **CHUYÊN NGHIỆP** | Quy trình chuẩn mực, nhân sự tinh nhuệ, triển khai hiệu quả và chuẩn xác. |
| 3 | lightbulb / bóng đèn | **ĐỔI MỚI** | Không ngừng sáng tạo, ứng dụng công nghệ hiện đại trong mọi hoạt động đầu tư. |
| 4 | shield / khiên | **TIN CẬY** | Xây dựng uy tín dựa trên sự minh bạch, trung thực và hiệu quả tài chính bền vững. |

---

## 6. Footer (`1260:16995`) — dùng chung

Giống footer trang chủ:
- **Banner CTA** đỏ→navy: tiêu đề lớn *"Khám phá tiềm năng. Bắt đầu kết nối."* + nút `Liên lạc ngay` (đỏ brand, có icon mũi tên).
- **4 cột link** trên nền navy `#080f1d`:
  1. **Thông tin tập đoàn** — logo, `024 3933 5708`, `hatangdongson@htds.vn`, 3 icon mạng xã hội.
  2. **Về Đông Sơn** — Giới thiệu, Tầm nhìn & Sứ mệnh, Ban lãnh đạo, Giá trị cốt lõi, Trách nhiệm XH.
  3. **Lĩnh vực** — Thi công & Xây lắp, Đầu tư BOT, Nhà ở & Đô thị, Năng lượng & KCN.
  4. **Dự án** — BOT Hà Nội–Bắc Giang, Nhà ở XH Bãi Viên, Cao tốc TQ–HG, Mỹ Đình–Bái Đính.
  - *(Desktop có thêm cột 5 "Nhà đầu tư": Báo cáo tài chính, Công bố thông tin, Báo cáo thường niên, ĐHĐCĐ 2026.)*
- **Thanh dưới**: `© 2026 Công ty Cổ phần Đông Sơn Holdings (DSH). Bảo lưu mọi quyền.` + `Chính sách bảo mật` · `Điều khoản sử dụng`.

---

## 7. Bảng màu dùng trong trang

| Vai trò | Hex | Ghi chú |
|---------|-----|---------|
| Đỏ brand | `#9a1220` | Badge Sứ mệnh, tiêu đề "GIÁ TRỊ CỐT LÕI", divider, nút CTA |
| Nền section sáng | `#f3f4f5` / `#f8f9fa` | Vision/Mission = `#f3f4f5`; Core Values = `#f8f9fa`; card giá trị = `#f3f4f5` |
| Chữ heading tối | `#191c1d` | Tiêu đề đen than |
| Chữ body | `#59413f` | **Nâu đỏ trầm** — mô tả, phụ đề (khác `--dsh-muted` trong token cũ) |
| Trắng | `#ffffff` | Card sứ mệnh, icon-box, chữ trên hero/footer |
| Viền hồng nhạt | `rgba(225,190,188,0.3–0.4)` | Border card & chip |
| Navy footer | `#080f1d` | Nền footer |

> Lưu ý bổ sung so với token dự án: xuất hiện **`#59413f` (chữ body nâu)** và **nền xám sáng `#f3f4f5`/`#f8f9fa`** — nên bổ sung vào `variables.css` khi dựng.

---

## 8. Bản Mobile (`1266:18366`, khung 375px)

Khung cao `2871px`. Padding hai bên `20px`. Thứ tự & khác biệt so với desktop:

### 8.1 HeroBanner mobile (`1266:18367`, `375 × 340`)
- Ảnh nền + overlay, cao `340px`.
- **Mobile_Navbar** (`1266:18505`): logo trái, bên phải là nút `Liên hệ` nhỏ + **icon hamburger** (`Menu_Trigger` `36×36px`) → nav thu về menu bật/tắt.
- Breadcrumb (`1266:18381`) căn giữa, eyebrow `ĐỊNH HƯỚNG CHIẾN LƯỢC` căn giữa.
- Tiêu đề **`SỨ MỆNH TẦM NHÌN`** (`1266:18389`) xuống dòng (khối `335×76px`), căn giữa.

### 8.2 Vision mobile (`1266:18390`, cao `279`)
- `TẦM NHÌN` căn giữa + divider `48×2px`.
- Blockquote full-width (`335px`), padding trái `16px`.

### 8.3 Mission mobile (`1266:18396`, cao `687`) — **xếp dọc**
- Card nội dung **trước**, badge nổi đỏ `40×40px` góc trên (`top:-20; left:20`).
- `SỨ MỆNH` → đoạn mô tả (full-width `295px`) → 3 chip xếp **dọc từng dòng** (mỗi chip 1 hàng).
- **Ảnh công trình rơi xuống dưới** (`1266:18416`, `335×200px`, bo góc) — ngược thứ tự desktop (desktop ảnh bên phải, mobile ảnh dưới).

### 8.4 Core Values mobile (`1266:18417`, cao `654`)
- Tiêu đề `GIÁ TRỊ CỐT LÕI` + divider + phụ đề, căn giữa.
- **Values Grid** `1266:18422`: **lưới 2×2** — 4 card, mỗi card `157px` rộng, `~215px` cao (2 cột, 2 hàng). Icon-box `48×48px` (nhỏ hơn desktop `64px`).

### 8.5 CTA_Section mobile (`1266:18514`, cao `260`)
- Banner CTA **tách riêng** thành section độc lập (desktop nó nằm trong footer): nền đỏ/ảnh + overlay, tiêu đề *"Khám phá tiềm năng. Bắt đầu kết nối."* (`327px`) + nút `Liên lạc ngay` (`167×45px`) có icon.

### 8.6 Footer mobile (`1266:18524`, cao `651`)
- Brand block: logo → SĐT → email (xếp dọc) → 3 icon MXH.
- **3 cột link rút gọn xếp dọc** (mỗi mục còn 3 dòng): Về Đông Sơn / Lĩnh vực / Dự án (bỏ cột "Nhà đầu tư").
- Copyright + `Chính sách bảo mật` · `Điều khoản sử dụng`.

### 8.7 Tóm tắt quy tắc responsive rút ra
| Section | Desktop | Mobile |
|---------|---------|--------|
| Hero | Navbar full menu | Hamburger + nút Liên hệ |
| Mission | 2 cột (chữ trái · ảnh phải) | 1 cột dọc (chữ trên · **ảnh dưới**) |
| Mission chip | 2 + 1 (hàng) | 3 dòng dọc |
| Core Values | 4 cột 1 hàng | **lưới 2×2** |
| CTA | nằm trong footer | **section riêng** trên footer |
| Footer | 5 cột | 3 cột dọc (rút gọn) |
| Icon-box giá trị | `64px` | `48px` |

---

## 9. Ánh xạ triển khai (Bootstrap 5.3 — theo chuẩn dự án)

| Section | Cách dựng |
|---------|-----------|
| HeroBanner | `section` full-width, `.position-relative`, ảnh nền + overlay gradient; `.container` chứa breadcrumb (`.breadcrumb`) + eyebrow + `h1` căn giữa |
| Vision | `.container`, block căn giữa (`text-center`), `h2` + divider (`<span>` custom) + `blockquote` |
| Mission | `.row .align-items-center .g-4` → `.col-lg-6` (card) + `.col-lg-6` (ảnh `.ratio`); mobile dùng `order-*`/thứ tự DOM để ảnh xuống dưới; badge = phần tử `.position-absolute` |
| Core Values | Tiêu đề `text-center`; lưới `.row .row-cols-2 .row-cols-lg-4 .g-4` → 4 `.card`; icon Bootstrap Icons |
| CTA + Footer | Banner CTA full-bleed (`.container-fluid`) + `footer` 4–5 cột `.col-lg-3`; đường kẻ vàng `--dsh-line` |

**Cần khi dựng:** export ảnh hero + ảnh mission về `assets/images/` (`.webp`); bổ sung token màu `#59413f`, `#f3f4f5`, `#f8f9fa`; quyết định font heading (Montserrat local vs Inter); fade-in bằng `IntersectionObserver`; chip/badge và icon-box là custom CSS gọn.
