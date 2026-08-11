# Phân Tích Trang Lĩnh Vực (Chi Tiết) — "Thi Công & Xây Lắp"

> Nguồn Figma: file `deMQyvVaKpesA0qLrKSTBZ` — ĐÔNG SƠN WEBSITE
> Node link gửi: `1484:2789` (chỉ là **HeroBanner**) → Frame trang đầy đủ: **`1484:2784`**
> ⚠️ Frame bị đặt nhầm tên còn sót là *"BÁO CÁO TÀI CHÍNH"*, nhưng **nội dung thực tế là trang chi tiết lĩnh vực "Thi công và Xây lắp"**.

---

## 1. Thông Tin Trang

- **Frame trang:** `1484:2784`
- **Kích thước:** `1920 × 3880px` (layout desktop chuẩn)
- **Container nội dung:** `1680px` · **Padding hai bên:** `120px` · **Navbar:** `80px`
- **Đặc điểm:** Trang chi tiết theo hướng **sáng** (section nội dung nền trắng), khác các section tối của trang chủ.
- **Là template dùng chung** cho 4 lĩnh vực: *Thi công & Xây lắp, Đầu tư BOT, Nhà ở & Đô thị, Năng lượng & KCN* → nên dựng **1 template + data động**.

---

## 2. Cấu Trúc 6 Khối (Sections)

| # | Section | Node | Kích thước | Nội dung chính |
|---|---------|------|-----------|----------------|
| 1 | **HeroBanner** | `1484:2785` | `1920×619` | Nền gradient đỏ→đen, navbar trong suốt |
| 2 | **Intro / Legacy** | (trong page) | ~ `1920×430` | Ảnh "1979 – Kế Thừa Kỷ Luật" + Quote + 2 stats |
| 3 | **Heritage + Stats** | — | ~ `1920×430` | "Kế Thừa Di Sản, Kiến Tạo Tương Lai" + ảnh |
| 4 | **Năng Lực Cốt Lõi** | ~`1484:6190` | ~ `1920×500` | Grid card lĩnh vực (Giao thông / Công nghiệp / Dân dụng) |
| 5 | **CTA Banner** | — | `1920×~280` | "KHÁM PHÁ TIỀM NĂNG. BẮT ĐẦU KẾT NỐI." nền cầu dây văng |
| 6 | **Footer** | `1484:3013` | `1920×833` | 4 cột + top CTA (footer chuẩn) |

---

## 3. Chi Tiết Từng Section

### Section 1 — HeroBanner (`1484:2789` – node được gửi) `[1920×619]`
- **Nền:** gradient đỏ rượu `#9a1220` → đen (`#080f1d`), **không dùng ảnh** (khác Hero trang chủ).
- **Navbar trong suốt** nổi trên gradient — logo ĐS + 6 menu + nút *"Liên hệ ngay"* đỏ.
- **Breadcrumb:** `Trang chủ / Giới thiệu`.
- **Tagline:** `ĐÔNG SƠN HOLDINGS` (chữ nhỏ, letter-spacing rộng, màu vàng/muted).
- **H1:** **"Thi công và Xây lắp"** (canh giữa, ~`60px` ExtraBold, trắng).
- **Mô tả:** *"Kế thừa truyền thống kỷ luật và uy tín từ Lữ đoàn 319, chúng tôi tiên phong ứng dụng công nghệ hiện đại để xây dựng những công trình mang tầm vóc quốc gia, đảm bảo tiến độ, chất lượng và hiệu quả vượt trội."*
- **2 CTA:** *"Khám phá dự án"* (nút đỏ) + *"Xem thi công chúng tôi"* (nút viền mờ).

### Section 2 — Intro / Legacy `[nền trắng]`
- **Cột trái:** ảnh công trình + overlay số lớn **`1979`** + nhãn **"Kế Thừa Kỷ Luật"** (chữ trắng góc dưới).
- **Cột phải:** icon quote đỏ `❝`, câu **"Chúng tôi không chỉ xây dựng các công trình, mà còn kiến tạo những giá trị bền vững cho xã hội."**
- **2 stat đỏ:** `100+` Dự án · `5K+` Nhân sự.

### Section 3 — Kế Thừa Di Sản, Kiến Tạo Tương Lai
- **Trái:** H2 + 2 đoạn văn:
  - *"Mảng thi công xây lắp của Đông Sơn Holdings tự hào kế thừa truyền thống kỷ luật, chuyên nghiệp và uy tín từ Lữ đoàn 319. Chúng tôi không chỉ xây dựng các công trình, mà còn kiến tạo những giá trị bền vững cho xã hội."*
  - *"Với đội ngũ lãnh đạo dày dạn kinh nghiệm, lực lượng lao động tay nghề cao và sự đầu tư mạnh mẽ vào công nghệ quản lý dự án hiện đại, Đông Sơn cam kết mang đến những giải pháp thi công tối ưu, đáp ứng những tiêu chuẩn khắt khe nhất về an toàn, chất lượng và tiến độ."*
  - **2 stat:** `100+` Dự án hoàn thành · `5000+` Nhân sự chất lượng cao.
- **Phải:** ảnh công trình (bo góc).

### Section 4 — Năng Lực Cốt Lõi
- Tiêu đề + mô tả: *"Chúng tôi cung cấp các giải pháp thi công toàn diện, bao phủ nhiều lĩnh vực trọng điểm của nền kinh tế, đảm bảo năng lực thực thi vượt trội cho mọi quy mô dự án."*
- **Grid card ảnh** (kiểu bento/masonry):
  - **Giao thông** (card lớn bên trái, có mô tả overlay)
  - **Công nghiệp** — *"Nhà máy, khu công nghiệp, kho bãi logistics."*
  - **Dân dụng** — *"Khu đô thị cao cấp, trung tâm thương mại, tòa nhà văn phòng."*

### Section 5 — CTA Banner
- Nền ảnh **cầu dây văng** phủ overlay tối, H2 hoa lớn **"KHÁM PHÁ TIỀM NĂNG. BẮT ĐẦU KẾT NỐI."** + nút đỏ *"Liên lạc ngay"*.
- Tái sử dụng component top-footer CTA của `CLAUDE.md`.

### Section 6 — Footer
- Footer chuẩn: 4 cột (*Về Đông Sơn / Lĩnh vực / Dự án / Nhà đầu tư*) + liên hệ `024 3933 5708`, `hatangdongson@htds.vn`, mạng xã hội.

---

## 4. Design Tokens

- **Palette:** đúng bộ token chuẩn — đỏ `#9a1220`, navy `#080f1d`, trắng `#ffffff` (biến Figma chỉ định nghĩa `Neutral-White #ffffff`).
- **Nền section nội dung:** **trắng** → định hướng trang chi tiết sáng, chuyên nghiệp.
- **Layout:** container `1680px` / padding `120px` / navbar `80px` — nhất quán toàn site.
- **Component tái sử dụng 100%:** Navbar, Primary/Secondary Button, Stat block (số đỏ + nhãn), Quote block, Card ảnh overlay, CTA banner, Footer.

---

## 5. Điểm Cần Lưu Ý Khi Triển Khai

1. **Node được gửi (`1484:2789`) chỉ là Hero** — trang đầy đủ là frame cha `1484:2784`.
2. **Tên frame sai** ("BÁO CÁO TÀI CHÍNH") — nội dung thật là "Thi công & Xây lắp".
3. Đây là **template dùng chung cho 4 lĩnh vực** → dựng 1 template + data động.
4. Có sẵn **bản mobile 375px** trong file (các frame `*-mobile`) để đối chiếu responsive.
5. Tuân theo `frontend-bootstrap.md`: Bootstrap 5.3 local, grid + utilities, export icon/ảnh từ Figma về `assets/images/`, không CDN.
