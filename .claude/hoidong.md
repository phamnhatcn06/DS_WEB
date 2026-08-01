# Phân Tích Trang Sơ Đồ - Tổ Chức (Đông Sơn Holdings)

> **Nguồn Figma:** node `1263:17786` (thuộc nhóm "ANOTHER PAGES").
> Tên layer trong Figma là "SỨ MỆNH - TẦM NHÌN" nhưng **nội dung thực tế là trang SƠ ĐỒ - TỔ CHỨC**
> (breadcrumb `Trang chủ › Sơ đồ - Tổ chức`, tiêu đề `SƠ ĐỒ - TỔ CHỨC`).
> **Kích thước:** `1920 × 3138px`. Trích xuất qua Figma desktop MCP (2026-07-29).

---

## Tổng quan cấu trúc

Trang gồm **4 khối** xếp dọc (Header/Footer dùng chung với các trang khác):

1. **HeroBanner** (`1263:17787`) — `1920×480px`
2. **Board of Directors — Hội đồng quản trị** (`1263:17708`) — `1920×1227px`
3. **Core Values Section — Hệ thống phân cấp / Sơ đồ tổ chức** (`1263:17878`) — `1920×598px` ⭐ khối chính
4. **Footer** (`1263:17918`) — `1920×833px` (dùng chung)

---

## 1. HeroBanner (`1263:17787`) — `1920×480px`

- Ảnh nền (`Image:transform` `1263:17789`) + overlay đỏ; **Navbar trong suốt** (`1263:17792`) nổi phía trên — giống các trang khác.
- **Breadcrumb** (`1263:17823`): `Trang chủ › Sơ đồ - Tổ chức` (có `ChevronRight` phân cách).
- **Eyebrow** (`1263:17832`): `ĐỊNH HƯỚNG CHIẾN LƯỢC` — hai bên có đường kẻ ngang `48×1px`.
- **Tiêu đề** (`1263:17835`): `SƠ ĐỒ - TỔ CHỨC` — căn giữa, chữ trắng, `~60px`.

→ Tái dùng component hero của trang About; chỉ đổi breadcrumb + tiêu đề + eyebrow.

---

## 2. Board of Directors — Hội đồng quản trị (`1263:17708`)

- **Header khối** (`1263:17709`): H2 `HỘI ĐỒNG QUẢN TRỊ` + sub `Board of Directors`, viền dọc trái.
- **Chairman Card (bento style)** (`1263:17714`) — `1680×428px`:
  - Ảnh trái `271.5×362px` (bo góc).
  - Nội dung phải:
    - Tên (H3): **Nguyễn Thị Minh Huệ**
    - Chức vụ: `CHỦ TỊCH HỘI ĐỒNG QUẢN TRỊ`
    - Đoạn 1: *"Bà Nguyễn Thị Minh Huệ có hơn 20 năm kinh nghiệm trong lĩnh vực quản trị doanh nghiệp và đầu tư chiến lược."*
    - Đoạn 2: *"Với 15 năm gắn bó cùng Đông Sơn Holdings, bà đã dẫn dắt công ty vượt qua nhiều giai đoạn chuyển mình quan trọng, khẳng định vị thế của tập đoàn trong các lĩnh vực trọng yếu."*
    - Thống kê (có đường kẻ ngang): **20+** (năm kinh nghiệm) · **15** (năm tại tập đoàn).
- **Grid 4 thành viên** (`1263:17736`) — mỗi card `402×542px` (ảnh `400×335px` + info block `400×205px`, padding `24px`):

  | # | Tên | Chức vụ |
  |---|-----|---------|
  | 2 | Nguyễn Thành Trung | PHÓ CHỦ TỊCH HĐQT |
  | 3 | Nguyễn Tiến Hưng | THÀNH VIÊN HĐQT / TỔNG GIÁM ĐỐC |
  | 4 | Lại Thành Nam | THÀNH VIÊN HĐQT |
  | 5 | Nguyễn Giang Nam | THÀNH VIÊN HĐQT |

  → Ánh xạ Bootstrap: `.row .row-cols-1 .row-cols-md-2 .row-cols-lg-4 .g-4`, mỗi cột `.card` (ảnh trên, tên + chức vụ + mô tả ngắn dưới).

---

## 3. Hệ thống phân cấp — Sơ đồ tổ chức (`1263:17878`) ⭐

Organization chart dạng cây, **nền sáng `#f8f9fa`**, padding `47px 120px 48px`.

- **Tiêu đề** (`1263:18035`): `HỆ THỐNG PHÂN CẤP`
  - Font **Montserrat SemiBold**, `~35.85px`, line-height `~46.6px`, màu `#191c1d`, uppercase, căn giữa.
  - ⚠️ Khác chuẩn `Inter` toàn site — cân nhắc chuẩn hóa về Inter khi dựng.

### Cây phân cấp (3 cấp)

```
          ┌─────────────────────────┐
          │   HỘI ĐỒNG QUẢN TRỊ      │   ← Cấp 1
          └───────────┬─────────────┘
                      │ (đường nối #730011, dọc 71.7px)
          ┌───────────┴─────────────┐
          │   BAN TỔNG GIÁM ĐỐC      │   ← Cấp 2
          └───────────┬─────────────┘
                      │ (branching lines #8d706e)
   ┌──────────┬───────┴───────┬──────────┐
┌──┴───┐  ┌───┴────┐    ┌─────┴───┐  ┌────┴─────┐
│ ĐẦU  │  │  TÀI   │    │   KỸ    │  │  HÀNH    │   ← Cấp 3 (4 khối)
│ TƯ   │  │ CHÍNH  │    │ THUẬT   │  │  CHÍNH   │
└──────┘  └────────┘    └─────────┘  └──────────┘
```

- **Cấp 1 — `HỘI ĐỒNG QUẢN TRỊ`** (`1263:18037`): box `382×92px`, nền đỏ đậm `#730011`, viền `#9a1220` `2.99px`, chữ **Inter Bold** `~23.9px` màu trắng, có shadow nhẹ.
- **Đường nối dọc** (`1263:18040`): `1.5×71.7px`, màu `#730011`.
- **Cấp 2 — `BAN TỔNG GIÁM ĐỐC`** (`1263:18041`): box `382×92px`, nền trắng, viền `#730011` `2.99px`, chữ **Inter Bold** `~23.9px` màu `#730011`, drop-shadow nhẹ.
- **Branching lines** (`1263:18060`): nhánh dọc + đường ngang màu `#8d706e` (`1.5px`) tỏa xuống 4 khối.
- **Cấp 3 — 4 khối phòng ban** (`1263:18043`), mỗi khối có divider dọc `#8d706e` phía trên + box:
  box nền `#f8f9fa`, viền hồng nhạt `#e1bebc` `1.49px`, padding `~19px`, chữ **Inter Medium** `~17.9px` màu `#191c1d`, uppercase, căn giữa:
  1. `KHỐI ĐẦU TƯ`
  2. `KHỐI TÀI CHÍNH`
  3. `KHỐI KỸ THUẬT`
  4. `KHỐI HÀNH CHÍNH`

→ Dựng bằng flex/grid + đường nối bằng div divider tuyệt đối (hoặc `::before/::after`).
Cấp 3 responsive: `.col-6 .col-lg-3`; mobile xếp dọc theo trục trái (pattern giống timeline Section 7).

---

## 4. Footer (`1263:17918`) — dùng chung

Banner CTA `Khám phá tiềm năng. Bắt đầu kết nối.` + nút `Liên lạc ngay`, trên footer 4 cột nền navy tối:
- **Cột 1:** Logo + Điện thoại `024 3933 5708` + Email `hatangdongson@htds.vn` + social (FB/LinkedIn/YouTube).
- **Cột 2 — Về Đông Sơn:** Giới thiệu · Tầm nhìn & Sứ mệnh · Ban lãnh đạo · Giá trị cốt lõi · Trách nhiệm XH.
- **Cột 3 — Lĩnh vực:** Thi công & Xây lắp · Đầu tư BOT · Nhà ở & Đô thị · Năng lượng & KCN.
- **Cột 4 — Dự án:** BOT Hà Nội – Bắc Giang · Nhà ở XH Bãi Viên · Cao tốc TQ–HG · Mỹ Đình – Bái Đính.
- **Cột 5 — Nhà đầu tư:** Báo cáo tài chính · Công bố thông tin · Báo cáo thường niên · ĐHĐCĐ 2026.
- Thanh dưới: `© 2026 Công ty Cổ phần Đông Sơn Holdings (DSH). Bảo lưu mọi quyền.` + Chính sách bảo mật · Điều khoản sử dụng.

---

## Token màu mới (khối sơ đồ) — cần bổ sung vào `variables.css`

CLAUDE.md hiện chỉ xác nhận `--dsh-red #9a1220`, `--dsh-gold #c9a84c`, `--dsh-navy #080f1d`.
Khối org chart dùng thêm các màu **chưa có trong token**:

| Token đề xuất | Hex | Dùng cho |
|---------------|-----|----------|
| `--dsh-red-dark` | `#730011` | Nền box cấp 1, viền/chữ box cấp 2, đường nối dọc |
| `--dsh-surface` | `#f8f9fa` | Nền khối sơ đồ + box phòng ban cấp 3 |
| `--dsh-line-pink` | `#e1bebc` | Viền box phòng ban cấp 3 |
| `--dsh-line-branch` | `#8d706e` | Divider/nhánh cây phân cấp |
| `--dsh-ink` | `#191c1d` | Tiêu đề section, chữ box phòng ban |

**Font:** tiêu đề `HỆ THỐNG PHÂN CẤP` dùng **Montserrat** (khác chuẩn Inter toàn site) — nên chuẩn hóa về Inter hoặc xác nhận lại với thiết kế.

---

## Ghi chú asset

- Ảnh chân dung 5 lãnh đạo + ảnh nền hero hiện tham chiếu asset server `localhost:3845` (đang offline khi trích xuất).
- Khi dựng: dùng **placeholder local** trong `assets/images/`, thay bằng ảnh thật khi export được (theo `.claude/rules/figma-icons.md`). Markup **không** hotlink `localhost:3845`.

---

## Node ID tham chiếu nhanh

| Khối | Node ID |
|------|---------|
| Trang (root) | `1263:17786` |
| HeroBanner | `1263:17787` |
| Tiêu đề hero | `1263:17835` |
| Board of Directors | `1263:17708` |
| Chairman Card | `1263:17714` |
| Grid 4 thành viên | `1263:17736` |
| Hệ thống phân cấp (org chart) | `1263:17878` |
| — Box HĐQT (cấp 1) | `1263:18037` |
| — Box Ban TGĐ (cấp 2) | `1263:18041` |
| — 4 khối phòng ban (cấp 3) | `1263:18043` |
| Footer | `1263:17918` |
