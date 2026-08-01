# Phân Tích Thiết Kế Website Đông Sơn Holdings (DSH) từ Figma & Kế Hoạch Triển Khai

---

## 1. Danh Sách Các Trang (Pages)

Trong file thiết kế Figma bao gồm 3 trang chính:

1. **Trang UI (`1134:23`) - BẢN THIẾT KẾ GIAO DIỆN CHÍNH (UI Final)**:
   - **`Update ver 04` / `OP1 3` (`1134:25`)**: Trang chủ hoàn chỉnh quy mô lớn (Kích thước: `1920px` x `11331px`), tập hợp đầy đủ 10 khối nội dung chuẩn nhận diện thương hiệu Đông Sơn Holdings.
2. **Trang WIREFRAME (`286:84`)**: Cấu trúc phác thảo wireframe các phiên bản thử nghiệm (OPTION 01, OLD, HOME FIX VER 01, UPDATE variants).
3. **Trang MOODBOARD (`0:1`)**: Đề xuất định hướng giao diện (OPTION 01, OPTION 2, OPTION 3, OPTION 4) & phân tích đề xuất.

---

## 2. Danh Sách Các Section (Trang chủ UI)

Trang chủ chính (`OP1 3`) bao gồm **10 Section** liên hoàn:

1. **Section 1: HeroSlider (`1134:26`)** `[1920x853px]`
   - Background Slider hình ảnh dự án quy mô lớn.
   - Header / Navbar trong suốt nổi trên slider.
   - Thương hiệu chính `ĐÔNG SƠN HOLDING`, Slogan/Tầm nhìn, cặp nút CTA hành động.
   - Thanh điều khiển carousel slider (`02 / 04`, progress bar, nút Prev/Next).
2. **Section 2: Lĩnh Vực Nổi Bật - Hạ Tầng BOT (`1134:90`)** `[1920x773px]`
   - Tagline lĩnh vực: *"Đầu tư BOT & Hạ tầng"*.
   - Tiêu đề chính: *"Kết nối hành lang kinh tế"*.
   - Khối thẻ thông tin nổi bật (Tags: *BOT, Cao tốc, Cầu đường, Vành đai*).
   - Banner hình ảnh minh họa quy mô dự án.
3. **Section 3: AboutServices - Về Chúng Tôi & Tầm Nhìn Sứ Mệnh (`1134:471`)** `[1920x1734px]`
   - Background minh họa đô thị (Cityscape Illustration).
   - Khối Tầm nhìn & Sứ mệnh: *"Kiến tạo giá trị bền vững cho khách hàng, đối tác và cộng đồng."*
   - Biểu tượng Quote nổi bật (`“”`).
4. **Section 4: BusinessAreas - Các Trụ Cột Lĩnh Vực Kinh Doanh (`1134:1206`)** `[1920x868px]`
   - Tiêu đề: *"Lĩnh vực kinh doanh"*.
   - Giới thiệu 3 trụ cột cốt lõi: *01. Thi công & Xây lắp*, *02. Đầu tư BOT & Hạ tầng*, *03. Năng lượng & Bất động sản*.
5. **Section 5: FeaturedProjects - Dự Án Tiêu Biểu (`1134:1265`)** `[1920x787px]`
   - Tiêu đề: *"Dự án tiêu biểu - Các công trình trọng điểm quốc gia..."*.
   - Nút hành động *"Xem tất cả dự án"*.
   - Slider / Grid các thẻ dự án tiêu biểu.
6. **Section 6: CompanyStats - Thống Kê & Năng Lực Công Ty (`1134:1588`)** `[1920x961px]`
   - Khối số liệu ấn tượng (Năm kinh nghiệm, Số lượng dự án, Tổng vốn đầu tư, Quy mô nhân sự).
7. **Section 7: FeaturedProjects Detail / Hoạt Động Chuyên Sâu (`1134:1628`)** `[1920x2611px]`
   - Trình bày chi tiết các dự án trọng điểm và thông tin năng lực thi công.
8. **Section 8: Đối Tác & Cổ Đông Chiến Lược (`1134:2742`)** `[1920x956px]`
   - Tiêu đề: *"Đối tác & Cổ đông chiến lược"*.
   - Slider logo các đối tác ngân hàng, tập đoàn kinh tế và cổ đông lớn.
9. **Section 9: Tin Tức Nổi Bật & Bộ Lọc Tin Tức (`1134:2767`)** `[1920x956px]`
   - Bộ lọc danh mục (Tabs: *Tất cả, Dự án, Thi công, Đầu tư, Cổ đông*).
   - Grid bài viết tin tức mới nhất (ảnh thumbnail, ngày đăng, tiêu đề, mô tả ngắn).
10. **Section 10: Footer - Chân Trang (`1134:2908`)** `[1920x833px]`
    - Khối Call-to-Action kết nối top footer.
    - Thông tin liên hệ tập đoàn & 4 cột menu điều hướng.

---

## 3. Header

- **Vị trí & Quy cách**: Cố định (Sticky/Fixed top), chiều cao `80px`, Full-width `1920px`, container nội dung `1680px` với padding hai bên `120px`.
- **Thành phần**:
  - **Logo (Bên trái)**:
    - Biểu tượng Vector ĐS đặt trong khung tròn (`38x38px`).
    - Chữ tên thương hiệu: **ĐÔNG SƠN** (Size `14px`, Font Bold, Letter-spacing `3px`, Color `#ffd5d5`).
    - Subtitle: **HOLDINGS · DSH** (Size `9px`, Font Regular, Letter-spacing `2.5px`, Color `#c9a84c` - Vàng kim).
  - **Navigation (Ở giữa)**: Thanh menu 6 mục chính.
  - **Button CTA (Bên phải)**: Nút *"Liên hệ ngay"* (Background đỏ `#9a1220`, Padding `10px 24px`, Text `13px` SemiBold, Color `#ffffff`).

---

## 4. Menu

- **Danh sách mục Menu**:
  1. `Về chúng tôi` *(Có icon mũi tên dropdown)*
  2. `Lĩnh vực` *(Có icon mũi tên dropdown)*
  3. `Dự án`
  4. `Quan hệ cổ đông`
  5. `Tin tức`
- **Thông số Typography Menu**:
  - Font family: `Inter` (Medium)
  - Size: `18px`
  - Line-height: `19.5px`
  - Letter-spacing: `0.325px`
  - Màu sắc: `#ffffff`
  - Khoảng cách giữa các mục (Gap): `28px`
  - Dropdown indicator: Icon chevron down (`12x12px`).

---

## 5. Footer

- **Màu nền**: Khung nền tối sang trọng `#080f1d` (Deep Navy) & Sub-card `#101c36` (Dark Slate Blue).
- **Banner Top Footer**:
  - Tiêu đề chữ hoa quy mô lớn: *"KHÁM PHÁ TIỀM NĂNG. BẮT ĐẦU KẾT NỐI."* (Size `60px`, ExtraBold, Line-height `69px`).
  - Nút bấm chính: *"Liên lạc ngay"* (`#9a1220`, Padding `16px 36px`, Border-radius `8px`).
- **Cấu trúc 4 Cột Main Footer**:
  - **Cột 1 (Thông tin Tập đoàn & Liên hệ)**:
    - Logo Đông Sơn Holdings.
    - Điện thoại: `024 3933 5708`
    - Email: `hatangdongson@htds.vn`
    - Mạng xã hội: Icon đường liên kết (Facebook, LinkedIn, YouTube).
  - **Cột 2 (Về Đông Sơn)**: *Giới thiệu, Tầm nhìn & Sứ mệnh, Ban lãnh đạo, Giá trị cốt lõi, Trách nhiệm xã hội*.
  - **Cột 3 (Lĩnh vực)**: *Thi công & Xây lắp, Đầu tư BOT, Nhà ở & Đô thị, Năng lượng & KCN*.
  - **Cột 4 (Dự án)**: *BOT Hà Nội – Bắc Giang, Nhà ở XH Bãi Viên, Cao tốc TQ–HG, Mỹ Đình – Bái Đính*.

---

## 6. Component Dùng Chung (Reusable Components)

1. **Primary Button (Nút Đỏ Thương Hiệu)**:
   - Background: `#9a1220` (Đỏ rượu / Crimson Red).
   - Border Radius: `8px`.
   - Padding: `16px 36px` - `17px 41px`.
   - Typography: `Inter` SemiBold `14px` - `16px`, Color `#ffffff`.
   - Mũi tên icon đi kèm (`14x14px`).
2. **Secondary Button (Nút Đen / Viền Mờ)**:
   - Background: `#000000` hoặc mờ viền `0.65px` solid.
   - Border Radius: `8px`.
   - Typography: `Aeonik` / `Inter` Medium `14px`, Color `#ffffff`.
3. **Card Component (Khối Lĩnh Vực / Dự Án)**:
   - Nền tối mờ / Glassmorphism, bo góc `8px` - `12px`.
   - Padding trong: `32px`.
   - Tiêu đề card (`24px` Bold), Đoạn văn mô tả (`14px` Regular), Tag chip/badge bên dưới (BOT, Cao tốc, Cầu đường, Vành đai).
4. **Category Filter Tabs (Bộ Lọc Tin Tức / Dự Án)**:
   - Dạng nút filter ngang: *Tất cả, Dự án, Thi công, Đầu tư, Cổ đông*.
   - Trạng thái Active: Highlight màu thương hiệu `#9a1220`.
5. **Carousel Control & Progress Bar**:
   - Chỉ số trang: `02 / 04` (`11px` Medium).
   - Thanh tiến trình: Cao `2px`, active bar màu `#9a1220`.
   - Cặp nút mũi tên chuyển slide: Kích thước `44x44px`, border `rgba(0,0,0,0.18)`.

---

## 7. Font (Typography)

- **Font chữ chủ đạo**: **`Inter`** (Sans-serif hiện đại, độ phân giải cao).
- **Font chữ phụ / Accent**: **`Aeonik`**.
- **Thang kích thước chuẩn (Typography Hierarchy)**:
  - **Hero Title (`H1 Super`)**: `96px` (Bold, Line-height `101.76px`, Tracking `-1px`, Color `#ffffff`).
  - **Section Title (`H1 / Banner`)**: `60px` - `64px` (ExtraBold, Line-height `69px`).
  - **Section Headline (`H2`)**: `36px` - `40px` (Bold).
  - **Card Title (`H3`)**: `24px` (Bold / SemiBold, Line-height `33px`).
  - **Navigation Menu Item**: `18px` (Medium, Line-height `19.5px`, Tracking `0.325px`).
  - **Footer Heading (`H4`)**: `16px` (SemiBold).
  - **Button Text**: `14px` - `16px` (SemiBold).
  - **Logo Text**: `14px` (Bold, Tracking `3px`) / Subtitle `9px` (Tracking `2.5px`, `#c9a84c`).
  - **Body / List Text**: `14px` (Regular, Line-height `18px`).
  - **Meta / Carousel Indicator**: `11px` - `12px` (Medium).

---

## 8. Màu Sắc (Color Palette & Design Tokens)

- **Màu thương hiệu chính (Primary Red)**: `#9a1220` (Đỏ rượu vang / Crimson Red đại diện cho nhiệt huyết, năng lượng và uy tín).
- **Màu nhấn thương hiệu (Brand Gold Accent)**: `#c9a84c` (Màu vàng kim sang trọng dùng cho logo subtitle và các điểm nhấn nhận diện).
- **Màu nền tối (Dark Themes)**:
  - Base Dark Background: `#080f1d` (Deep Navy)
  - Container / Card Dark Background: `#101c36` (Dark Blue Slate)
  - Card Mask Overlay: `rgba(0, 0, 0, 0.7)` / `#000000`
- **Màu chữ (Text Colors)**:
  - Chữ chính (Primary Text): `#ffffff` (Trắng tinh)
  - Chữ đệm Logo (Light Pink Gold): `#ffd5d5`
  - Chữ phụ (Muted Text): `rgba(255, 255, 255, 0.78)`
  - Indicator Muted: `rgba(0, 0, 0, 0.3)` / `rgba(255, 255, 255, 0.45)`
- **Màu đường viền (Border & Divider Colors)**:
  - Viền mờ tối: `rgba(0, 0, 0, 0.18)`
  - Viền mờ sáng: `rgba(255, 255, 255, 0.08)` / `rgba(255, 255, 255, 0.45)`

---

## 9. Khoảng Cách (Spacing & Layout Grid)

- **Chiều rộng Canvas thiết kế**: `1920px`.
- **Khung chứa nội dung chính (Max Container Width)**: `1680px` (Màn hình lớn) - `1760px` (Footer).
- **Padding lề hai bên (Horizontal Container Padding)**: `120px` (Desktop Large), co giãn tự động trên màn hình nhỏ hơn.
- **Khoảng cách dọc giữa các Section (Vertical Section Margin/Padding)**: `80px` đến `140px`.
- **Khoảng cách giữa các thành phần (Component Gaps)**:
  - Header Nav items gap: `28px`
  - Cặp nút CTA Hero gap: `12px`
  - Grid danh sách Card gap: `24px` - `32px`
  - Cột Footer gap: `48px` - `64px`

---

## 10. Responsive (Quy Tắc Co Giãn Phản Hồi)

- **Các điểm ngắt (Breakpoints)**:
  - `Desktop Full Wide`: `1920px` (Khung thiết kế chuẩn Figma).
  - `Desktop Standard / Laptop`: `1440px` (Padding hai bên giảm từ 120px xuống 60px, container `1280px`).
  - `Tablet Landscape / Laptop nhỏ`: `1024px` (Chuyển Navbar sang Mobile Menu Hamburger, Grid 3-4 cột chuyển thành 2 cột).
  - `Tablet Portrait`: `768px` (Title Hero `96px` co giãn còn `48px`, các khối button xếp dọc theo cột).
  - `Mobile Smart Phone`: `375px` - `480px` (Padding hai bên `16px`, full-width card, ẩn bớt chi tiết trang trí không cần thiết).
- **Kỹ thuật co giãn mượt mà**:
  - Sử dụng hàm `clamp()` cho Typography chính (Ví dụ Hero Title: `font-size: clamp(36px, 6vw, 96px)`).
  - Khởi tạo CSS Variable cho Container Padding: `--container-pad: clamp(16px, 5vw, 120px)`.

---

## 11. Hiệu Ứng (Effects, Animations & Micro-interactions)

1. **Tương tác Nút & Card (Hover Effects)**:
   - **Nút CTA đỏ `#9a1220`**: Tăng độ sáng nền nhẹ (`#b51627`), hiệu ứng phóng to nhẹ `transform: scale(1.02)` kèm bóng đổ viền `box-shadow` mượt thời gian `300ms ease`.
   - **Thanh Menu Header**: Đổi màu chữ sang màu `#c9a84c` kết hợp underline trượt từ trái sang phải.
   - **Khối Card Lĩnh vực / Dự án**: Hiệu ứng nổi lên nhẹ `transform: translateY(-6px)` kèm viền phát sáng mờ `border-color: rgba(201, 168, 76, 0.4)`.
2. **Hiệu ứng Slider & Chuyển trang (Carousel Animations)**:
   - Chuyển slide mượt (Fade opacity / Slide transform X `600ms cubic-bezier`).
   - Progress bar tự động fill màu `#9a1220` từ `0%` đến `100%` theo thời gian chuyển slide.
3. **Hiệu ứng Cuộn trang (Scroll & Parallax Effects)**:
   - Xuất hiện từng phần (Fade-in up transition) cho các Section khi cuộn màn hình bằng `IntersectionObserver`.
   - Parallax nhẹ cho hình nền minh họa đô thị (Cityscape Illustration).

---

## 12. Lập Kế Hoạch Triển Khai (Implementation Plan)

### Giai đoạn 1: Khởi tạo hệ thống Design Tokens & CSS Base (Ngày 1)
- Xây dựng file `variables.css` chứa toàn bộ Design Tokens:
  - Bảng màu (`--color-primary: #9a1220`, `--color-gold: #c9a84c`, `--color-bg-dark: #080f1d`,...).
  - Font chữ (`--font-main: 'Inter', sans-serif`, `--font-accent: 'Aeonik', sans-serif`).
  - Font Size Scale & Line-height Scale.
  - Spacing variables (`--container-padding`, `--section-gap`).
- Cấu hình Reset CSS chuẩn và thiết lập Layout Grid / Flexbox utilities.

### Giai đoạn 2: Triển khai các Component dùng chung (Reusable UI Components) (Ngày 2)
- Triển khai **Button System** (Primary Button đỏ, Secondary Button viền đen).
- Triển khai **Typography System** (Khung Headings H1, H2, H3, Body text, Subtitle).
- Triển khai **Card Components** (Card Lĩnh vực, Card Dự án tiêu biểu, Card Tin tức).
- Triển khai **Tabs Filter Component** & **Carousel Controller Component**.

### Giai đoạn 3: Dựng Layout Header & Footer (Ngày 3)
- Triển khai **Header / Navbar**:
  - Logo vector + text thương hiệu Đông Sơn Holdings.
  - Menu điều hướng với hiệu ứng hover & dropdown.
  - Xử lý hiệu ứng Sticky Header khi cuộn trang (Đổi background mờ Glassmorphism).
- Triển khai **Footer**:
  - Khối Top CTA Banner *"Khám phá tiềm năng. Bắt đầu kết nối."*.
  - 4 cột thông tin liên hệ và danh mục liên kết.

### Giai đoạn 4: Triển khai toàn bộ 10 Section Trang chủ (Ngày 4 - 5)
1. Dựng Section 1 (`HeroSlider`) với background slider và khối thông tin trung tâm.
2. Dựng Section 2 (`Lĩnh vực Hạ tầng BOT`) với thẻ card nổi và minh họa công trình.
3. Dựng Section 3 (`AboutServices - Tầm nhìn Sứ mệnh`).
4. Dựng Section 4 (`BusinessAreas - 3 Trụ cột kinh doanh`).
5. Dựng Section 5 & 7 (`FeaturedProjects - Danh sách & Chi tiết dự án tiêu biểu`).
6. Dựng Section 6 (`CompanyStats - Thống kê con số ấn tượng`).
7. Dựng Section 8 (`Đối tác & Cổ đông chiến lược`).
8. Dựng Section 9 (`Tin tức nổi bật & Tab filter`).

### Giai đoạn 5: Tích hợp JavaScript tương tác & Micro-animations (Ngày 6)
- Viết JavaScript cho Carousel Slider (Tự động chuyển slide, điều khiển nút Prev/Next, đồng bộ thanh progress bar và chỉ số `02 / 04`).
- Viết JavaScript cho bộ lọc tin tức (Filter tabs đổi danh mục bài viết mượt mà).
- Thêm hiệu ứng cuộn trang Fade-in Up cho các section.

### Giai đoạn 6: Tối ưu Responsive, SEO & Kiểm thử (Ngày 7)
- Kiểm tra hiển thị chuẩn xác trên tất cả kích thước màn hình (Desktop 1920px, 1440px, Tablet 1024px, 768px, Mobile 375px).
- Tối ưu hóa SEO: Thẻ `<title>`, `<meta description>`, cấu trúc `<h1>` -> `<h6>` chuẩn ngữ nghĩa, thuộc tính `alt` cho tất cả ảnh.
- Kiểm tra hiệu năng tải trang và tinh chỉnh mượt mà mọi trải nghiệm người dùng.
