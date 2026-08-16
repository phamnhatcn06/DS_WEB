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
    // 30MB: file PDF báo cáo thường niên có thể 13–20MB. Lưu ý phải nới cả
    // upload_max_filesize / post_max_size trong php.ini cho khớp.
    'uploadMaxSize'   => 30 * 1024 * 1024, // 30 MB

    // Chỉ nhận đúng các định dạng dùng trong thiết kế: raster → webp, vector → svg.
    'allowedMimeTypes' => array(
        'image/webp', 'image/jpeg', 'image/png', 'image/svg+xml',
        'image/gif', 'application/pdf',
    ),
    'allowedExtensions' => array('webp', 'jpg', 'jpeg', 'png', 'svg', 'gif', 'pdf'),

    'bcryptCost'      => 12,
    'pageSize'        => 20,

    // Đặt lại mật khẩu qua email.
    'resetTokenTtl'   => 3600,              // Hạn dùng link reset (giây) — 1 giờ.
    'mailFromEmail'   => 'no-reply@htds.vn', // Địa chỉ gửi email hệ thống.
    'mailFromName'    => 'Đông Sơn Holdings CMS',
);
