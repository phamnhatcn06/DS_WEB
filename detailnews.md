# Phân Tích Trang "Chi Tiết Sự Kiện / Tin Tức" (News Detail) — Đông Sơn Holdings (DSH)

> **Nguồn Figma:** file `deMQyvVaKpesA0qLrKSTBZ` (ĐÔNG SƠN WEBSITE)
> **Frame chính:** `1353:894` — *"CHI TIẾT SỰ KIỆN"* `[1920 × 3874px]` (Desktop)
> **Bản Mobile:** `1355:1660` — *"Main-Article"* `[375 × 2160px]`
>
> Lưu ý: node trong link ban đầu (`1353:930`) chỉ là một *Container* con (khối tiêu đề nổi trên hero banner). Trang đầy đủ nằm ở frame cha `1353:894`.

---

## 1. Bố cục tổng thể (Page Structure)

Trang dạng **long-scroll**, gồm 4 khối dọc liên hoàn:

| # | Khối | Node | Kích thước |
|---|------|------|-----------|
| 1 | **Header / Navbar** (nền đỏ, giống trang chủ) | — | `1920×80` |
| 2 | **HeroBanner** (tiêu đề danh mục "TIN TỨC VÀ SỰ KIỆN") | `1353:895` | `1920×480` |
| 3 | **Main → Article** (nội dung 2 cột: bài viết + sidebar) | `1353:1442` | `1920×2561` |
| 4 | **Footer CTA + Footer 4 cột** | `1353:1328` | `1920×833` |

Vùng nội dung chính (`Main → Article` `1353:1442`) chia **2 cột**:
- **Cột trái — `Main Editorial Content` (`1353:1443`)**: rộng `1104px`.
- **Cột phải — `Aside - Sidebar` (`1353:1587`)**: rộng `528px`.
- Gap giữa 2 cột ≈ `48px`; tổng khớp container `1680px` (padding lề `120px`).

---

## 2. Section HeroBanner (`1353:895` — 1920×480)

- **Background:** ảnh (`Image:transform` `1353:897`) phủ overlay đỏ rượu thương hiệu `#9a1220`.
- **Nội dung căn giữa (`Container` `1353:930`, 1071×260):**
  - Tagline nhỏ: *"ĐÔNG SƠN HOLDINGS"* — letter-spacing rộng, màu vàng kim `#c9a84c`.
  - **Tiêu đề trang (H1)**: *"TIN TỨC VÀ SỰ KIỆN"* — chữ hoa, ExtraBold, trắng `#ffffff` (`Paragraph:margin` `1353:944`, ~656px).
  - Mô tả phụ 1 dòng (muted white).
- Đây là **banner tiêu đề danh mục**, KHÔNG phải tiêu đề bài viết (tiêu đề bài viết nằm trong content body bên dưới).

---

## 3. Cột trái — Main Editorial Content (`1353:1443`, rộng 1104px)

### 3.1. Breadcrumbs (`1353:1444`)
- `Trang chủ / Tin tức / [Tên bài]` — 3 `Link` cách nhau bởi dấu phân cách (`Container` 5×15). Font ~13px, muted.

### 3.2. Article Header (`1353:1455`)
- **Badge danh mục** (`Background` `1353:1456`, 158×25): chip đỏ `#9a1220` — vd *"THÔNG BÁO DOANH NGHIỆP"*.
- **Heading 1** (`1353:1458`, 1104×188): tiêu đề bài viết lớn, Bold — vd *"THÔNG BÁO THAY ĐỔI NỘI DUNG ĐĂNG KÝ DOANH NGHIỆP"*.
- **Meta row** (`Border` `1353:1460`, có đường kẻ dưới): ngày đăng (icon lịch `1353:1462`) + tác giả/nguồn *"Admin Đông Sơn"* (icon `1353:1466`).

### 3.3. Featured Image (`1353:1469`, 1104×458)
- Ảnh đại diện lớn (`Corporate Announcement` `1353:1470`), bo góc.

### 3.4. Content Body (`1353:1471`, 1104×1536)
Nội dung bài viết có cấu trúc phong phú:
- **Lead / Sapo** (`VerticalBorder` `1353:1472`): đoạn trích dẫn có **viền trái nổi bật** (blockquote style).
- **Section 1 — Thay đổi công ty** (`1353:1474`): `Heading 2` + 2 card so sánh cạnh nhau (`Background+Border` 540px/card) — kiểu "trước / sau".
- **Section 2 — Bổ sung ngành, nghề công ty** (`1353:1495`): **bảng dữ liệu (`Table` `1353:1501`)** — header 3 cột (STT / Tên ngành / Mã ngành), 6+ hàng dữ liệu, cột mã ngành in đỏ.
- **Section 3 & 4 Callouts** (`1353:1556`): 2 khối callout cạnh nhau — bên trái text lý do thay đổi, bên phải **card đỏ nổi bật** hiển thị **ngày hiệu lực lớn "14.05.2026"**.
- **Full Disclosure Link** (`1353:1572`): khối tải tài liệu — icon file + tiêu đề + **Button** *"Tải văn bản"* (nút viền, có icon download `1353:1584`).

---

## 4. Cột phải — Aside Sidebar (`1353:1587`, rộng 528px)

3 widget xếp dọc:

1. **Related News / Tin liên quan** (`1353:1589`): `Heading 4` + 3 item (`Link` 528×80) — mỗi item = thumbnail 80×80 + tiêu đề (`Heading 5`) + ngày.
2. **Quick Links / Chuyên mục** (`1353:1614`): `Heading 4` + list 4 mục, mỗi mục có **badge đếm số** bên phải (`Background+Border` ~34×19).
3. **Contact Card / Hỗ trợ cổ đông** (`1353:1634`): **card nền đỏ `#9a1220`**, heading trắng + email liên hệ (icon `1353:1640`) — CTA hỗ trợ.

---

## 5. Footer (`1353:1328`)

- **CTA banner** *"KHÁM PHÁ TIỀM NĂNG. BẮT ĐẦU KẾT NỐI."* (nền ảnh cầu dây văng + overlay) + nút đỏ *"Liên hệ ngay"*.
- **4 cột footer** nền navy `#080f1d`: thông tin tập đoàn + social, Về Đông Sơn, Lĩnh vực, Dự án + thanh copyright.

---

## 6. Design Tokens áp dụng

Trang **tái sử dụng 100%** hệ token trong `CLAUDE.md`, không phát sinh màu mới (variable defs chỉ trả về `Neutral White #ffffff`):

- **Đỏ thương hiệu** `#9a1220`: badge danh mục, card ngày hiệu lực, contact card, mã ngành trong bảng, nút CTA.
- **Vàng kim** `#c9a84c`: tagline hero, điểm nhấn.
- **Navy** `#080f1d` / `#101c36`: footer.
- **Nền content**: trắng `#ffffff` (khác trang chủ tối) → trang tin tức dùng nền sáng cho khả năng đọc.
- **Font**: `Inter` toàn bộ.

---

## 7. Responsive

- Bản Mobile `1355:1660` (375px): 1 cột — content xếp trên, sidebar dồn xuống dưới (`Related-News-Mobile` `1355:1754`, `Latest-News-Box` `1481:2393`).
- Quy tắc: Desktop 2 cột (1104 + 528) → Tablet giữ 2 cột co hẹp → Mobile 1 cột (`col-12`).
- Bảng dữ liệu Section 2 dùng `.table-responsive` (scroll ngang) trên mobile.

---

## 8. Component tái sử dụng cần dựng

| Component | Vị trí | Bootstrap mapping |
|-----------|--------|-------------------|
| Category badge chip | Article header, sidebar | `.badge` custom đỏ |
| Blockquote (viền trái) | Lead / sapo | custom `border-left` |
| Comparison card (before/after) | Section 1 | `row` 2× `col-lg-6` card |
| Data table | Section 2 | `.table` custom header đỏ |
| Highlight date card | Section 3-4 | card nền đỏ |
| Download / document button | Full disclosure | `.btn` outline + icon |
| Related news item (thumb + title) | Sidebar | media object / `list-group` |
| Quick-links list có counter badge | Sidebar | `list-group` + `.badge` |
| Support / contact card đỏ | Sidebar | card nền brand |

---

## 9. Node ID tham chiếu nhanh (Figma)

| Khối | Node ID |
|------|---------|
| Frame trang (Desktop) | `1353:894` |
| Hero banner | `1353:895` |
| Main → Article (2 cột) | `1353:1442` |
| Main Editorial Content (trái) | `1353:1443` |
| Breadcrumbs | `1353:1444` |
| Article header | `1353:1455` |
| Featured image | `1353:1469` |
| Content body | `1353:1471` |
| Bảng ngành nghề | `1353:1501` |
| Callout ngày hiệu lực | `1353:1556` |
| Nút tải văn bản | `1353:1572` |
| Aside Sidebar (phải) | `1353:1587` |
| Related news | `1353:1589` |
| Quick links | `1353:1614` |
| Contact card | `1353:1634` |
| Footer | `1353:1328` |
| Frame Mobile | `1355:1660` |
