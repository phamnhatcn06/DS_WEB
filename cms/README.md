# Đông Sơn Holdings — CMS

Hệ quản trị nội dung cho website tĩnh DSH. Yii Framework 1.1.32 + PHP 8.1 + MySQL.
Toàn bộ nội dung đang hiển thị trên `index.html` đã được nạp sẵn vào CSDL.

---

## Yêu cầu môi trường

| Hạng mục | Giá trị đã kiểm chứng |
|---|---|
| PHP | 8.1 (MAMP: `C:\MAMP\bin\php\php8.1.0`) |
| Extension | `pdo_mysql`, `mbstring`, `fileinfo`, `gd`, `openssl` |
| MySQL | 5.7.24 (MAMP) — cần ≥ 5.7.8 vì dùng kiểu `JSON` |
| Charset | `utf8mb4` / `utf8mb4_unicode_ci` |

> **Lưu ý về PHP CLI của MAMP:** bản CLI không nạp `php.ini` nào cả, nên không có
> extension nào được bật mặc định. `yiic.bat` truyền các extension cần thiết bằng
> cờ `-d` để không phải sửa cấu hình MAMP.

---

## Cài đặt

```bash
# 1. Cấu hình kết nối CSDL
cp protected/config/db.example.php protected/config/db.php
#   rồi sửa host / user / password trong db.php  (file này đã được gitignore)

# 2. Tạo database
mysql -u root -p -e "CREATE DATABASE dsh_cms CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# 3. Chạy migration (tạo bảng + nạp dữ liệu)
yiic.bat migrate
```

Migration cuối cùng in ra **email và mật khẩu quản trị đầu tiên** — mật khẩu được
sinh ngẫu nhiên và **chỉ hiện một lần**. Lưu lại rồi đổi ngay sau khi đăng nhập.

### Chạy thử nhanh (không cần Apache)

```bash
php -S 127.0.0.1:8123 -t .
```

- Trang kiểm chứng dữ liệu: `http://127.0.0.1:8123/index.php`
- Khu quản trị: `http://127.0.0.1:8123/index.php/admin`

---

## Cấu trúc

```
cms/
├── index.php                    Điểm vào web
├── yiic.bat                     Điểm vào console (migrate…)
├── framework/                   Yii 1.1.32
├── admin-assets/                CSS/JS riêng của khu quản trị
├── uploads/                     File người dùng tải lên (gitignored)
└── protected/
    ├── config/                  main.php · console.php · db.php (gitignored)
    ├── components/              BaseActiveRecord, behaviors, helper
    │   └── behaviors/           SoftDelete · Slug · JsonAttribute · Audit
    ├── migrations/              8 migration: schema + seed
    ├── models/                  12 model ActiveRecord
    ├── controllers/             SiteController (frontend)
    ├── views/                   View frontend
    └── modules/admin/           Khu quản trị
        ├── components/          AdminController · AdminCrudController
        ├── controllers/         10 controller
        └── views/               layout · crud dùng chung · media · setting
```

---

## Các quyết định thiết kế đáng chú ý

**Khoá chính là `INT UNSIGNED AUTO_INCREMENT`, không dùng UUID.** Yii1 ActiveRecord
không hỗ trợ UUID sẵn; `CActiveDataProvider`, relation và `findByPk` đều mượt hơn
với INT, và UUID làm phình clustered index của InnoDB.

**RBAC dùng `CDbAuthManager` có sẵn của Yii1**, chỉ đổi tên bảng về snake_case
(`auth_items`, `auth_item_children`, `auth_assignments`). Nhờ vậy bỏ được 4 bảng
`roles`/`permissions`/`role_permissions`/`user_roles` tự viết và toàn bộ tầng kiểm
tra quyền. **Không dùng `bizRule`** — Yii1 `eval()` chuỗi đó, là rủi ro thực thi mã.

**Một bảng `business_sectors` phục vụ cả Section 2 và Section 4.** Slider và lưới
01–04 là cùng một tập dữ liệu, chỉ khác cách render; hai cờ `show_in_slider` /
`show_in_grid` quyết định nơi hiển thị. Tách hai bảng sẽ buộc biên tập viên nhập
trùng và dữ liệu sẽ lệch nhau theo thời gian.

**Xoá mềm dùng named scope `notDeleted()`, không dùng `defaultScope()`.** Trong Yii1,
`defaultScope()` áp cả vào relation và hành xử khó lường khi `resetScope()` — nguồn
bug thầm lặng.

**Audit tự chụp giá trị cũ ở `afterFind`.** Yii1 (khác Yii2) không theo dõi thuộc
tính cũ — **không có `getOldAttributes()`**. Ngoài ra Yii1 đặt `isNewRecord = false`
*trước* khi gọi `afterSave`, nên cờ này cũng phải chụp ở `beforeSave`.

**Chuỗi rỗng được đổi thành NULL ở `BaseActiveRecord::beforeSave()`.** Form HTML gửi
`''` cho dropdown không chọn gì; MySQL strict mode từ chối `''` cho cột `INT`/`DATE`.
Xử lý một lần ở lớp cha thay vì lặp trong từng model.

**Ảnh luôn tham chiếu `media_files` bằng khoá ngoại**, không lưu path chuỗi rời rạc.
Thư viện media được nạp sẵn 42 file từ `assets/images/` dùng chung với trang tĩnh —
đúng ràng buộc "mọi asset là local" trong `CLAUDE.md`.

---

## Bảo mật đã áp dụng

| Hạng mục | Cách làm |
|---|---|
| Mật khẩu | `password_hash()` bcrypt cost 12, tự rehash khi đổi cost |
| CSRF | Bật tường minh (`enableCsrfValidation` — Yii1 **mặc định tắt**) |
| Session fixation | `regenerateID(true)` sau khi đăng nhập |
| Brute force | Khoá tài khoản 15 phút sau 5 lần sai |
| User enumeration | Chạy hash giả khi email không tồn tại (chống dò qua thời gian phản hồi) |
| Thao tác phá huỷ | Xoá/đổi thứ tự chỉ nhận POST; GET trả 405 |
| Upload | Kiểm mime thật bằng `finfo` (không tin `$_FILES['type']`), whitelist đuôi, chặn SVG chứa `<script>`, chống trùng bằng SHA-256 |
| Phân quyền | Deny-by-default; mỗi action gọi `requirePermission()` |
| Credentials | `protected/config/db.php` nằm trong `.gitignore` |

---

## Kết quả kiểm thử

Đã chạy thực tế trên PHP 8.1 + MySQL 5.7:

- 18/18 màn hình quản trị trả HTTP 200, không lỗi trong nội dung.
- Đăng nhập, ghi dữ liệu, validate chặn giá trị sai, audit log, xoá mềm,
  slug tự sinh bỏ dấu tiếng Việt, tag JSON, lưu cấu hình theo transaction,
  đổi thứ tự, chặn xoá danh mục còn bài viết — tất cả PASS.
- Bảo mật: CSRF chặn POST lạ (400), xoá bằng GET bị chặn (405), khách bị đẩy về
  login, mật khẩu sai bị từ chối, file PHP đổi đuôi `.png` và SVG chứa script đều
  bị chặn (không ghi xuống đĩa, không vào DB).

---

## Việc tiếp theo

1. **Chuyển `index.html` sang view Yii** — tách thành partial theo section
   (`_hero.php`, `_bot.php`, …) và đổ dữ liệu từ DB. **Giữ nguyên 100% markup và
   class**; chỉ thay text/ảnh cứng bằng biến. Nhớ tắt jQuery tự động của
   `CClientScript` để không đẩy asset thừa vào trang:
   ```php
   Yii::app()->clientScript->scriptMap['jquery.js'] = false;
   ```
2. **Bảng phase 2** (xem `../system_design.md`): `section_settings`, `menus`/`menu_items`,
   `about_blocks`, `quotes`, `cta_banners`, `contact_submissions`, `project_images`.
3. **Production**: đặt `YII_DEBUG = false` trong `index.php` — khi đó
   `schemaCachingDuration` tự bật (3600s). Không bật, Yii1 chạy `SHOW COLUMNS` cho
   mọi bảng ở mọi request; đây là nút thắt hiệu năng lớn nhất của Yii1.
