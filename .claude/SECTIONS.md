# SECTIONS — Bóc tách trang chủ theo Figma thật (node `1134:25` "OP1 3")

> Nguồn thiết kế **chính thức** hiện tại: Figma node **`1134:25`** (không phải `12:11` cũ trong CLAUDE.md).
> Trang cao ~11.332px, canvas 1920px. Dựng **lần lượt từng section**, mỗi section một khối, CSS partial riêng.
>
> **Quy trình mỗi section:**
> 1. `get_design_context` + `get_screenshot` trên node của section để lấy layout/màu/px chính xác.
> 2. Export mọi ảnh của section về `assets/images/` (raster→`.webp`, vector→`.svg`) — KHÔNG hotlink `localhost:3845`.
> 3. Dựng markup bằng Bootstrap grid/utilities + component; custom CSS tối thiểu gom vào `assets/css/section-<n>-<tên>.css`.
> 4. Dùng design token (màu brand, Inter, spacing `clamp()`); fade-in bằng `IntersectionObserver`.
> 5. Rà responsive 1920/1440/1024/768/375, cập nhật trạng thái ☐→☑ ở đây.

---

## Trạng thái tổng thể

| ✔ | # | Section | Node Figma | Cao (px) | File CSS dự kiến |
|---|---|---------|-----------|----------|------------------|
| ☑ | — | **Header / Navbar** | `1134:28` | 80 | `header-footer.css` |
| ☑ | 1 | **Hero slider** | `1134:26` | 853 | `section-1-hero.css` |
| ☑ | 2 | **Đầu tư BOT & Hạ tầng** | `1134:90` | 773 | `section-2-bot.css` |
| ☑ | 3 | **Giới thiệu — Sứ mệnh & Tầm nhìn** | `1134:471` | 1734 | `section-3-about.css` |
| ☑ | 4 | **Lĩnh vực kinh doanh** | `1134:1206` | 868 | `section-4-linhvuc.css` |
| ☑ | 5 | **Dự án tiêu biểu** | `1134:1265` | 787 | `section-5-duan.css` |
| ☑ | 6 | **Giá trị cốt lõi ("Xây dựng niềm tin")** | `1134:1588` | 961 | `section-6-giatri.css` |
| ☑ | 7 | **Hành trình phát triển (timeline)** | `1134:1628` | 2611 | `section-7-timeline.css` |
| ☑ | 8 | **Đối tác & Cổ đông chiến lược** | `1134:2742` | 956 | `section-8-doitac.css` |
| ☐ | 9 | **Tin tức nổi bật (tab lọc)** | `1134:2767` | 956 | `section-9-tintuc.css` |
| ☑ | — | **Footer (CTA + 4 cột)** | `1134:2908` | 833 | `header-footer.css` |

---

## Section 1 — Hero slider · `1134:26`
- **Nội dung:** slider full-bleed nền ảnh (cao tốc/rừng) tối, overlay tiêu đề lớn + CTA, điều khiển dot/mũi tên. Liên thông với slider "02/04" ở section 2 (cùng bộ slide 4 trang).
- **Bootstrap:** Bootstrap `Carousel` (`.carousel-dsh`), overlay `.container`, `.btn-dsh` + `.btn-dsh-outline`.
- **Assets:** ảnh nền slide (webp), mũi tên (đã có `arrow-right.svg`).
- **Lưu ý:** header trong suốt nằm đè lên hero → khi xong hero, chữ nav trắng mới hiển thị đúng.

## Section 2 — Đầu tư BOT & Hạ tầng · `1134:90`
- **Nội dung:** nền **đỏ brand**, eyebrow "ĐẦU TƯ BOT & HẠ TẦNG", tiêu đề "Kết nối hành lang kinh tế", body + tags (BOT · Cao tốc · Cầu đường · Vành đai), 2 nút CTA. Cột phải: ảnh dự án + card thông tin nổi + dot slider "02/04" + nút prev/next.
- **Bootstrap:** `.row .align-items-center`, `.col-lg-6`; card nổi `.card-dsh--dark`; tag `.tag-chip` (biến thể trên nền đỏ).

## Section 3 — Giới thiệu · Sứ mệnh & Tầm nhìn · `1134:471`
- **Nội dung:** nền sáng (hồng nhạt), block **quote** lớn (dấu ""), rồi 2 hàng card xen kẽ ảnh↔chữ: **Sứ mệnh** (logo + text | ảnh), **Tầm nhìn** (ảnh | logo + text).
- **Bootstrap:** `.row` + `.flex-lg-row-reverse` xen kẽ; `.col-lg-6`; ảnh `.ratio`.
- **Assets:** ảnh công trình/điện gió (webp), logo (đã có `logo.webp`).

## Section 4 — Lĩnh vực kinh doanh · `1134:1206`
- **Nội dung:** nền **đỏ brand** (có ảnh mờ cần cẩu), tiêu đề trái "Lĩnh vực kinh doanh", intro phải. Card trắng lớn chứa **4 cột đánh số 01–04**: Thi công và xây lắp / Đầu tư BOT & Hạ tầng / Nhà ở & Đô thị / Năng lượng & KCN — mỗi mục có mô tả + tags.
- **Bootstrap:** `.row .row-cols-1 .row-cols-md-2 .row-cols-lg-4`; số lớn màu đỏ; `.tag-chip`.
- ⚠️ **4 cột** (không phải 3 như bảng cũ trong CLAUDE.md).

## Section 5 — Dự án tiêu biểu · `1134:1265`
- **Nội dung:** nền sáng, tiêu đề "Dự án tiêu biểu" + intro + link "Xem tất cả dự án →". Dải **gallery ngang 5 ảnh** dự án (có thể carousel/scroll).
- **Bootstrap:** hàng ảnh `.row`/carousel; ảnh `.ratio`, bo góc; `.card-dsh` nếu có caption.

## Section 6 — Giá trị cốt lõi · `1134:1588`
- **Nội dung:** **ảnh full-bleed** (kỹ sư công trường), heading phải "Xây dựng niềm tin, vươn tới xuất sắc", **4 card kính (glassmorphism)** 2×2: Trách nhiệm / Chuyên nghiệp / Đổi mới / Tin cậy — mỗi card icon tròn + tiêu đề + mô tả.
- **Bootstrap:** section nền ảnh + overlay; `.row .row-cols-1 .row-cols-md-2`; card kính (custom `backdrop-filter`); `.icon-badge`.

## Section 7 — Hành trình phát triển (timeline) · `1134:1628`
- **Nội dung:** nền sáng, tiêu đề "Hành trình phát triển" + quote nhỏ. **Timeline dọc** có trục giữa, mốc năm xen kẽ trái/phải: 2009 · 2014 · 2017 · 2019 · 2024 · 2025 · 2026 — mỗi mốc card eyebrow (KHỞI ĐẦU/HẠ TẦNG/…) + năm lớn đỏ + tiêu đề + mô tả.
- **Bootstrap:** grid 2 cột quanh trục giữa; ở mobile dồn 1 phía. Đây là section cao nhất (2611px) — custom CSS timeline nhiều nhất.

## Section 8 — Đối tác & Cổ đông chiến lược · `1134:2742`
- **Nội dung:** **nền ảnh cầu ban đêm** tối, tiêu đề trắng trái "Đối tác & Cổ đông chiến lược", **grid logo 4×2** (8 ô trắng bo góc chứa logo đối tác).
- **Bootstrap:** `.row .row-cols-2 .row-cols-md-4 .g-4`; ô logo nền trắng; `.partner-logo`.
- **Assets:** 8 logo đối tác (svg/webp) + ảnh nền cầu đêm.

## Section 9 — Tin tức nổi bật (tab lọc) · `1134:2767`
- **Nội dung:** nền sáng, tiêu đề "Tin tức nổi bật" + **tab lọc** (Tất cả / Dự án / Thi công / Đầu tư / Cổ đông). Grid card tin: 1 card lớn trái + các card nhỏ; mỗi card ảnh + chip danh mục + ngày + tiêu đề + "Đọc tiếp →".
- **Bootstrap:** `.nav .nav-pills` (`.filter-pills`), `.row .g-4`, `.card-dsh`; JS lọc theo `data-category` (`.news-item.is-hidden`).

---

## Ghi chú đồng bộ
- Bảng section trong `CLAUDE.md` (dựa trên design cũ `12:11`) đã **lỗi thời** ở vài điểm (3 vs 4 trụ cột, có timeline & section giá trị mới, không có VI/EN switch). Tài liệu **này** là nguồn đúng cho việc dựng.
- Token màu/typography (Inter, brand palette) giữ nguyên như đã chốt trong `variables.css`.
