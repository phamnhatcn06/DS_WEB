<?php
/**
 * Tiện ích xử lý file media: upload an toàn, sinh thẻ <img> có width/height.
 */
class MediaHelper
{
    /**
     * Sinh thẻ <img> từ bản ghi MediaFile.
     *
     * Luôn kèm width/height để tránh layout shift (CLS) — đúng yêu cầu
     * hiệu năng trong CLAUDE.md.
     *
     * @param MediaFile|null $media
     * @param array $htmlOptions class, loading, …
     * @param string $fallbackAlt alt dùng khi media không có alt_text
     */
    public static function img($media, $htmlOptions = array(), $fallbackAlt = '')
    {
        if (!($media instanceof MediaFile)) {
            return '';
        }

        $options = array_merge(array(
            'alt'     => $media->alt_text !== null && $media->alt_text !== ''
                ? $media->alt_text
                : $fallbackAlt,
            'loading' => 'lazy',
        ), $htmlOptions);

        if ($media->width && $media->height) {
            $options['width']  = $media->width;
            $options['height'] = $media->height;
        }

        return CHtml::image($media->getPublicUrl(), $options['alt'], $options);
    }

    /**
     * Sinh thẻ <img> cho frontend với fallback local — KHÔNG BAO GIỜ src rỗng,
     * không hotlink ra ngoài.
     *
     * Nhận vào một trong ba:
     *   - MediaFile  → render ảnh từ CMS (kèm width/height, alt của bản ghi);
     *   - string     → dùng làm src trực tiếp (asset tĩnh local);
     *   - null/rỗng  → dùng $fallbackSrc (placeholder local).
     *
     * @param MediaFile|string|null $mediaOrSrc
     * @param string $fallbackSrc  đường dẫn asset local khi không có ảnh
     * @param string $alt
     * @param array  $htmlOptions  class, loading, aria-hidden, …
     */
    public static function imgOr($mediaOrSrc, $fallbackSrc, $alt = '', $htmlOptions = array())
    {
        if ($mediaOrSrc instanceof MediaFile) {
            return self::img($mediaOrSrc, $htmlOptions, $alt);
        }

        $src = (is_string($mediaOrSrc) && $mediaOrSrc !== '') ? $mediaOrSrc : $fallbackSrc;
        $options = array_merge(array('loading' => 'lazy'), $htmlOptions);

        return CHtml::image($src, $alt, $options);
    }

    /**
     * Kiểm tra file upload có an toàn không.
     *
     * Không tin `$_FILES['type']` (client gửi lên, giả được) — đọc mime thật
     * bằng finfo và đối chiếu cả phần mở rộng.
     *
     * @return string|null thông báo lỗi, hoặc null nếu hợp lệ
     */
    public static function validateUpload(CUploadedFile $file)
    {
        $params = Yii::app()->params;

        if ($file->getSize() > $params['uploadMaxSize']) {
            return 'File vượt quá dung lượng cho phép ('
                . round($params['uploadMaxSize'] / 1024 / 1024) . ' MB).';
        }

        $extension = strtolower($file->getExtensionName());
        if (!in_array($extension, $params['allowedExtensions'], true)) {
            return 'Định dạng ".' . $extension . '" không được phép.';
        }

        $mime = self::detectMimeType($file->getTempName());
        if ($mime === null || !in_array($mime, $params['allowedMimeTypes'], true)) {
            return 'Nội dung file không khớp định dạng cho phép (phát hiện: '
                . ($mime === null ? 'không xác định' : $mime) . ').';
        }

        // SVG là XML — có thể nhúng <script>. Chặn thẳng thay vì cố lọc.
        if ($mime === 'image/svg+xml' && self::svgHasScript($file->getTempName())) {
            return 'File SVG chứa mã script, không được phép tải lên.';
        }

        return null;
    }

    public static function detectMimeType($path)
    {
        if (!function_exists('finfo_open')) {
            return null;
        }
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        if ($finfo === false) {
            return null;
        }
        $mime = finfo_file($finfo, $path);
        finfo_close($finfo);

        return $mime === false ? null : $mime;
    }

    private static function svgHasScript($path)
    {
        $content = (string) file_get_contents($path);
        return (bool) preg_match('/<script|javascript:|on\w+\s*=/i', $content);
    }

    /**
     * Đọc kích thước ảnh. Trả về array(width, height) hoặc array(null, null).
     */
    public static function readDimensions($path, $mime)
    {
        if ($mime === 'image/svg+xml' || $mime === 'application/pdf') {
            return array(null, null);
        }
        $info = @getimagesize($path);
        if ($info === false) {
            return array(null, null);
        }
        return array($info[0], $info[1]);
    }

    /**
     * Sinh tên file an toàn, không trùng, giữ phần mở rộng gốc.
     */
    public static function buildFileName($originalName, $extension)
    {
        $base = pathinfo($originalName, PATHINFO_FILENAME);
        $slug = TextHelper::slugify($base);
        if ($slug === '') {
            $slug = 'file';
        }
        return $slug . '-' . date('YmdHis') . '-' . substr(md5(uniqid('', true)), 0, 6)
            . '.' . strtolower($extension);
    }

    /**
     * Đường dẫn tuyệt đối tới thư mục upload, tự tạo nếu chưa có.
     */
    public static function uploadDir()
    {
        $dir = Yii::getPathOfAlias('webroot') . DIRECTORY_SEPARATOR
            . Yii::app()->params['uploadPath'] . DIRECTORY_SEPARATOR . date('Y') . date('m');

        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        return $dir;
    }

    public static function formatFileSize($bytes)
    {
        $bytes = (float) $bytes;
        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 1) . ' MB';
        }
        if ($bytes >= 1024) {
            return number_format($bytes / 1024, 0) . ' KB';
        }
        return $bytes . ' B';
    }
}
