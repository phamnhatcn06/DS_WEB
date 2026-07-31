# Kế Hoạch: Tính Năng QUẢN LÝ MENU ĐỘNG (Dynamic Menu Manager) — DSH CMS

> Nguồn: phân tích bởi Project Manager, đã chốt quyết định với người dùng (2026-07-31).
> Phạm vi **Giai đoạn 1**: chỉ động hóa menu **sidebar admin** (`admin_sidebar`). Menu public giữ HTML tĩnh, tính sau.

---

## 1. Bối cảnh & Vấn đề

Hiện menu sidebar admin được **hardcode** trong mảng PHP `$menu` tại
`cms/themes/hope-ui/views/layouts/main.php` (dòng 14–31). Mỗi mục gồm `label / route / icon / perm`,
cộng các mục `divider`. Mọi thay đổi (đổi tên, thêm mục, đổi thứ tự) đều phải sửa code + deploy.

**Mục tiêu:** xây hệ thống quản lý menu động trong admin, cho phép:
1. Cấu hình **tên menu** + chọn **vị trí (location)** hiển thị.
2. **Kéo thả** (drag & drop) sắp xếp thứ tự.
3. Menu **phân cấp nhiều tầng** (cha–con, dropdown).

**Ràng buộc kế thừa:** Yii 1.x, giix CRUD, Hope UI (Bootstrap 5), JS vanilla/jQuery,
**KHÔNG CDN — thư viện tải local**, bảng tiền tố `pvn_`, RBAC `CDbAuthManager`.

---

## 2. Quyết định đã chốt

| # | Quyết định | Lựa chọn |
|---|---|---|
| 1 | Kiến trúc menu public | **Chỉ làm `admin_sidebar` trước**, menu public tính sau |
| 2 | Thư viện kéo thả | **Nestable2 (jQuery)** — serialize cây JSON sẵn, dùng jQuery có sẵn của Hope UI |
| 3 | Bảo vệ mục hệ thống | **Có** — thêm cột `is_protected` (không cho xóa/ẩn) |

---

## 3. Khái niệm "Vị trí menu" (Menu Location)

Tách **nội dung menu** khỏi **nơi hiển thị** (mô hình kiểu WordPress). Một menu được gán vào đúng một
location; mỗi location render một cây menu.

Giai đoạn 1 chỉ seed 1 location; giữ bảng location để mở rộng public sau này.

| Code (slug) | Tên hiển thị | Nơi dùng | Phân cấp |
|---|---|---|---|
| `admin_sidebar` | Sidebar quản trị | `main.php` (thay `$menu`) | Có (max 2 cấp) |
| *(sau này)* `public_header` | Menu Header website | Header nav public | Có |
| *(sau này)* `public_footer_col2/3/4` | 3 cột Footer | Footer | Phẳng |

---

## 4. Thiết kế Database (tiền tố `pvn_`)

### 4.1 Bảng `pvn_menu_locations`

| Cột | Kiểu | Ghi chú |
|---|---|---|
| `id` | INT PK AI | |
| `code` | VARCHAR(50) UNIQUE | slug bất biến dùng trong code (`admin_sidebar`) |
| `name` | VARCHAR(150) | tên hiển thị tiếng Việt |
| `description` | VARCHAR(255) NULL | mô tả nơi dùng |
| `supports_nesting` | TINYINT(1) default 1 | cho phép cấp con |
| `max_depth` | TINYINT default 2 | giới hạn số cấp (sidebar Hope UI = 2) |
| `is_active` | TINYINT(1) default 1 | |
| `created_at` / `updated_at` | DATETIME | |

### 4.2 Bảng `pvn_menu_items`

| Cột | Kiểu | Ghi chú |
|---|---|---|
| `id` | INT PK AI | |
| `location_id` | INT FK → `pvn_menu_locations.id` | mục thuộc location nào |
| `parent_id` | INT NULL FK → `pvn_menu_items.id` | NULL = gốc; self-reference, `ON DELETE CASCADE` |
| `title` | VARCHAR(200) | tên mục hiển thị |
| `item_type` | ENUM(`route`,`url`,`divider`) default `route` | route Yii / link ngoài / nhãn nhóm |
| `route` | VARCHAR(200) NULL | vd `/admin/project/index` (khi type=route) |
| `url` | VARCHAR(500) NULL | link (khi type=url) |
| `target` | ENUM(`_self`,`_blank`) default `_self` | mở tab mới |
| `icon` | VARCHAR(60) NULL | class Bootstrap Icons `bi-*` |
| `perm` | VARCHAR(80) NULL | khóa RBAC; NULL = ai cũng thấy |
| `sort_order` | INT default 0 | thứ tự trong cùng `parent_id` |
| `depth` | TINYINT default 0 | cache độ sâu (0 = gốc) |
| `is_protected` | TINYINT(1) default 0 | **1 = không cho xóa/ẩn** (mục hệ thống) |
| `css_class` | VARCHAR(120) NULL | class phụ (badge, highlight) |
| `is_active` | TINYINT(1) default 1 | ẩn/hiện không cần xóa |
| `created_at` / `updated_at` | DATETIME | |

**Chỉ mục:** `idx_menu_items_location`(`location_id`), `idx_menu_items_parent`(`parent_id`),
`idx_menu_items_sort`(`location_id`,`parent_id`,`sort_order`).

### 4.3 Model Yii (giix `GxActiveRecord`)

- `MenuLocation` (`pvn_menu_locations`) — `HAS_MANY items`.
- `MenuItem` (`pvn_menu_items`) — `BELONGS_TO location`, `BELONGS_TO parent`,
  `HAS_MANY children` (`self::HAS_MANY, 'MenuItem', 'parent_id'`, `order => 'sort_order ASC'`).

**Migration** `m26XXXX_000000_create_menu_tables.php`: tạo 2 bảng + seed location `admin_sidebar` +
import dữ liệu từ mảng `$menu` hardcode (giữ nguyên nhãn/route/icon/perm/divider + thứ tự).

### 4.4 Ánh xạ 3 yêu cầu

- **Tên + vị trí** → `title` + `location_id`
- **Kéo thả** → `sort_order` (+ `parent_id` khi kéo đổi cấp)
- **Phân cấp level** → `parent_id` self-reference + `depth`

---

## 5. Màn hình Admin

Route mới `/admin/menu/*` (controller `MenuController`, gen giix rồi tùy biến).
Perm RBAC: `menus.view`, `menus.create`, `menus.update`, `menus.delete`, `menus.reorder`.

1. **Danh sách Location** (`/admin/menu/index`) — bảng location (tên, code, số mục, trạng thái),
   nút "Quản lý menu".
2. **Trình quản lý cây menu** (`/admin/menu/manage?location=admin_sidebar`) — màn hình chính:
   - Cây menu **kéo thả nested** (Nestable2). Mỗi node: title + icon + badge type + trạng thái.
   - Nút mỗi node: Sửa (modal), Ẩn/Hiện, Xóa. **Ẩn nút Xóa/Ẩn khi `is_protected=1`.**
   - Nút "Thêm mục", lưu thứ tự (auto-save sau mỗi lần thả).
3. **Form thêm/sửa mục** (modal) — trường: title, item_type, route (autocomplete)/url, target,
   icon (picker `bi-*`), perm (dropdown RBAC), parent, css_class, is_active.
   Tuân `modal-submit.md`: disable nút + spinner khi submit, đóng modal khi thành công, khôi phục khi lỗi.

---

## 6. Kéo thả — Nestable2 (tải local)

- Tải về `cms/themes/hope-ui/assets/vendor/nestable/` (jquery.nestable.js + css). **Không CDN.**
- Cấu trúc `<ol class="dd-list"><li class="dd-item">…</li></ol>`; `.nestable('serialize')` xuất JSON cây.
- Endpoint `POST /admin/menu/reorder` nhận:
  ```json
  { "location_id": 1,
    "tree": [ { "id": 3, "children": [ { "id": 7 }, { "id": 8 } ] }, { "id": 4 } ] }
  ```
- Server duyệt đệ quy, cập nhật `parent_id`, `sort_order`, `depth` **trong transaction**.
  Validate `max_depth` + chống vòng lặp (node không là con của hậu duệ mình). CSRF token Yii.
- Trả JSON chuẩn `{ success, message }`, toast kết quả.

---

## 7. Render động thay `$menu` hardcode

Tạo `MenuHelper::getTree('admin_sidebar')` (đặt `protected/components/`):
- Đọc cây `MenuItem` theo `location_id`, `is_active=1`, sắp `sort_order`.
- **Lọc RBAC:** bỏ mục có `perm` mà `Yii::app()->user->checkAccess($perm)` = false (giữ logic dòng 111).
- **Cache** theo key `menu:{location}:{roleId}`, **xóa cache** trong `afterSave/afterDelete` của model.

Trong `main.php`: thay khối `$menu = array(...)` (dòng 14–31) + vòng `foreach` (dòng 103–121) bằng
render đệ quy — `divider` → static-item; mục thường → `nav-link`; mục có `children` → dropdown Hope UI
(`iq-submenu`). **Giữ nguyên class Hope UI.** `active` xác định bằng so khớp `route` với `$currentRoute`
(logic dòng 33–34 giữ nguyên).

---

## 8. Lộ trình (~7 ngày)

### Sprint 1 — DB & Model (2d) ✅ HOÀN THÀNH
- [x] Migration `m260731_000000_create_menu_tables.php`: tạo `pvn_menu_locations` + `pvn_menu_items` (+ `is_protected`) + index + FK
- [x] Seed location `admin_sidebar` + import mảng `$menu` (17 mục; `Tổng quan` + `Quản lý menu` `is_protected=1`)
- [x] Model `MenuLocation`, `MenuItem` (extends `BaseActiveRecord`) + quan hệ parent/children + validate
- [x] Thêm 5 perm RBAC `menus.*` (view/create/update/delete/reorder) + gán admin/super_admin
- **AC:** ✅ migrate chạy sạch; query trả cây đúng thứ tự; 17 mục đủ trong DB; RBAC gán đúng.

### Sprint 2 — CRUD & màn hình quản lý (2–3d) ✅ HOÀN THÀNH
- [x] `MenuController extends AdminController` + list location (`index`) + trang `manage`
- [x] Form thêm/sửa mục (`form.php`, trang riêng): validate type→route/url, datalist route, select perm/parent, JS ẩn/hiện trường theo loại
- [x] Toggle active + xóa mềm (cascade con, `confirmDelete`); **chặn khi `is_protected=1`** ở cả UI lẫn server
- [x] Render cây menu HTML nested (markup `.dd`/`.dd-item` sẵn cho Nestable2 ở Sprint 3)
- **AC:** ✅ thêm/sửa/xóa/ẩn hoạt động; validate depth/parent/cycle server-side; phân quyền `menus.*` chặn đúng; mục protected không xóa/ẩn được.

> Ghi chú: form dùng **trang riêng** (không modal) để chắc chắn & khớp CRUD hiện có — modal là polish tuỳ chọn sau. Action `reorder` (đích của form kéo thả) làm ở Sprint 3.

### Sprint 3 — Kéo thả & lưu cấu trúc (2d) ✅ HOÀN THÀNH
- [x] Vendor Nestable2 (JS+CSS) local: `cms/themes/hope-ui/assets/vendor/nestable/` (jQuery 3.6.1 có sẵn trong `libs.min.js`)
- [x] Gắn Nestable2 vào `#menu-tree` + custom CSS grip/content; nạp plugin động sau `window load` (jQuery ở cuối body)
- [x] `actionReorder` + `buildReorderUpdates()` (tách để test): cập nhật parent/sort/depth trong transaction; validate id lạ, trùng, vượt max_depth, con dưới divider; CSRF `_dsh_csrf`
- [x] Auto-save khi `change` + toast SweetAlert2; lỗi → reload để revert
- **AC:** ✅ kéo đổi thứ tự/cấp lưu qua AJAX; test: nesting hợp lệ OK, vượt cấp/divider/id lạ đều bị chặn.

### Sprint 4 — Render động & bỏ hardcode (1d) ✅ HOÀN THÀNH
- [x] `MenuHelper` (`protected/components/`): `getItems` cache theo location + `filteredTree` lọc RBAC + `renderSidebar` render đệ quy (divider / leaf / submenu collapse Hope UI)
- [x] Thay mảng `$menu` + vòng `foreach` trong `main.php` bằng `MenuHelper::renderSidebar('admin_sidebar', $this, $currentRoute)`
- [x] Xóa cache tự động qua `MenuItem::afterSave` (create/update/soft-delete/reorder)
- **AC:** ✅ sidebar lấy từ DB, giữ nguyên class Hope UI; submenu tự mở khi mục con active; lọc RBAC đúng; cache tự làm mới.

---

## 9. Definition of Done

- [x] Admin cấu hình tên + thứ tự + phân cấp menu sidebar không cần sửa code.
- [x] Kéo thả lưu bền vững qua AJAX trong transaction.
- [x] `main.php` không còn mảng `$menu` hardcode; render từ DB, giữ nguyên UI Hope UI.
- [x] RBAC lọc mục đúng theo `perm`.
- [x] Mục `is_protected` không thể xóa/ẩn.
- [x] Không có request ra CDN/host ngoài (Nestable2 tải local).
- [x] Migration reversible; `php -l` sạch; smoke test qua CLI đạt. *(QA thủ công trên trình duyệt: nên chạy trước khi coi là xong hẳn.)*

> **TẤT CẢ 4 SPRINT ĐÃ HOÀN THÀNH.** Còn lại: kiểm thử thủ công trên trình duyệt (đăng nhập admin, mở `/admin/menu`, kéo thả, đổi tên, kiểm tra sidebar cập nhật).

---

## 10. Rủi ro & Giảm thiểu

| Rủi ro | Mức | Giảm thiểu |
|---|---|---|
| Nested drag-drop lỗi cấu trúc (vòng lặp, sai depth) | TB | Validate server đệ quy + transaction; giới hạn `max_depth`; test kỹ Sprint 3 |
| Vỡ giao diện sidebar Hope UI khi render đệ quy (`iq-submenu`) | TB | Đối chiếu markup gốc; giữ nguyên class; so sánh trực quan từng bước |
| Cache menu không xóa kịp → sửa mà không thấy đổi | Thấp | Xóa cache theo location trong `afterSave/afterDelete` |
| Đổi/xóa menu sai làm mất lối vào admin | TB | Cờ `is_protected` cho mục trọng yếu (Dashboard, Quản lý menu) + fallback Dashboard |

---

## 11. File tham chiếu

- `cms/themes/hope-ui/views/layouts/main.php` — mảng `$menu` (dòng 14–31), vòng render (103–121),
  `$currentRoute` (33–34), lọc RBAC (111).
- `.claude/CLAUDE.md` — mô tả CMS Yii 1.x, Hope UI, giix, RBAC.
- `.claude/rules/modal-submit.md` — chuẩn submit form trong modal.
- `.claude/rules/database.md` — transaction, naming.
