<?php
/**
 * Tham số ứng dụng.
 *
 * Lưu ý: file này KHÔNG chứa secret (API key, mật khẩu). Secret đặt trong
 * biến môi trường hoặc db.php — xem .claude/rules/security.md.
 */

return array(
    'adminEmail'      => 'hatangdongson@htds.vn',

    // Thư mục upload — đường dẫn tương đối từ webroot của cms/
    'uploadPath'      => 'uploads',
    'uploadMaxSize'   => 10 * 1024 * 1024, // 10 MB

    // Chỉ nhận đúng các định dạng dùng trong thiết kế: raster → webp, vector → svg.
    'allowedMimeTypes' => array(
        'image/webp', 'image/jpeg', 'image/png', 'image/svg+xml',
        'image/gif', 'application/pdf',
    ),
    'allowedExtensions' => array('webp', 'jpg', 'jpeg', 'png', 'svg', 'gif', 'pdf'),

    'bcryptCost'      => 12,
    'pageSize'        => 20,
);
