<?php
/**
 * Nạp sẵn thư viện media từ các ảnh đã export của website tĩnh.
 *
 * Ảnh nằm ở `assets/images/` cấp gốc dự án (dùng chung cho cả trang tĩnh và
 * CMS), nên `file_path` trỏ ra ngoài webroot của cms/ bằng đường dẫn tương đối.
 * Đúng ràng buộc "mọi asset là local" trong CLAUDE.md — không hotlink.
 */
class m260724_070000_seed_media_library extends CDbMigration
{
    /** Đường dẫn tương đối từ webroot của cms/ tới thư mục ảnh dùng chung. */
    const SHARED_IMAGE_PATH = '../assets/images';

    /** Alt text mô tả cho các ảnh chính — phần còn lại suy từ tên file. */
    private $altTexts = array(
        'hero-bg.webp'               => 'Công trình hạ tầng của Đông Sơn Holdings',
        'logo.webp'                  => 'Đông Sơn Holdings',
        'logo-red.webp'              => 'Biểu tượng Đông Sơn Holdings',
        'bot-interchange.webp'       => 'Nút giao thông hạ tầng BOT của Đông Sơn Holdings',
        'cta-bridge.webp'            => 'Cầu vượt do Đông Sơn Holdings thi công',
        'about-construction.webp'    => 'Công trình xây dựng của Đông Sơn Holdings',
        'about-energy.webp'          => 'Dự án năng lượng tái tạo của Đông Sơn Holdings',
        'linhvuc-crane.webp'         => 'Cần cẩu trên công trường Đông Sơn Holdings',
        'giatri-bg.webp'             => 'Công trình tiêu biểu của Đông Sơn Holdings',
        'doitac-bridge-night.webp'   => 'Cầu về đêm — dự án hạ tầng Đông Sơn Holdings',
        'duan-01-bot.webp'           => 'BOT Hà Nội – Bắc Giang, Quốc lộ 1',
        'duan-02-dothi.webp'         => 'Khu đô thị hiện đại do Đông Sơn Holdings phát triển',
        'duan-03-nhao.webp'          => 'Tổ hợp căn hộ đã bàn giao',
        'duan-04-thicong.webp'       => 'Công trình đang thi công phần thân',
        'news-01.webp'               => 'Khu nhà ở xã hội Bãi Viên – Nam Định',
        'news-02.webp'               => 'Khu đô thị do Đông Sơn Holdings đầu tư',
        'partner-1.webp'             => 'Tổng công ty 319 — Bộ Quốc phòng',
        'partner-2.webp'             => 'OGC Group',
        'partner-3.webp'             => 'Vinaconex',
        'partner-4.webp'             => 'Văn Phú – Invest',
        'partner-5.webp'             => 'Tư Lập',
        'partner-6.webp'             => 'Trung tâm Lưu ký & Bù trừ Chứng khoán Việt Nam (VSDC)',
        'partner-7.webp'             => 'Sở Giao dịch Chứng khoán Hà Nội (HNX)',
    );

    public function up()
    {
        $sourceDir = realpath(dirname(__FILE__) . '/../../../assets/images');

        if ($sourceDir === false || !is_dir($sourceDir)) {
            echo "    Bỏ qua: không tìm thấy thư mục assets/images.\n";
            return;
        }

        $this->insert('media_folders', array(
            'name'       => 'Ảnh website',
            'slug'       => 'anh-website',
            'sort_order' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ));
        $folderId = Yii::app()->db->getLastInsertID();

        $files = scandir($sourceDir);
        $now = date('Y-m-d H:i:s');
        $imported = 0;

        foreach ($files as $fileName) {
            $fullPath = $sourceDir . DIRECTORY_SEPARATOR . $fileName;
            if ($fileName === '.' || $fileName === '..' || !is_file($fullPath)) {
                continue;
            }

            $mime = $this->detectMime($fullPath);
            if ($mime === null) {
                continue;
            }

            list($width, $height) = $this->readDimensions($fullPath, $mime);

            $this->insert('media_files', array(
                'folder_id'  => $folderId,
                'file_name'  => $fileName,
                'file_path'  => self::SHARED_IMAGE_PATH . '/' . $fileName,
                'mime_type'  => $mime,
                'file_size'  => filesize($fullPath),
                'width'      => $width,
                'height'     => $height,
                'alt_text'   => isset($this->altTexts[$fileName])
                    ? $this->altTexts[$fileName]
                    : $this->guessAlt($fileName),
                'checksum'   => hash_file('sha256', $fullPath),
                'created_at' => $now,
                'updated_at' => $now,
            ));
            $imported++;
        }

        echo "    Đã nạp {$imported} file vào thư viện media.\n";
    }

    public function down()
    {
        $this->delete('media_files', 'file_path LIKE :p',
            array(':p' => self::SHARED_IMAGE_PATH . '/%'));
        $this->delete('media_folders', 'slug = :s', array(':s' => 'anh-website'));
    }

    private function detectMime($path)
    {
        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        $map = array(
            'webp' => 'image/webp',
            'jpg'  => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png'  => 'image/png',
            'svg'  => 'image/svg+xml',
            'gif'  => 'image/gif',
            'pdf'  => 'application/pdf',
        );
        return isset($map[$extension]) ? $map[$extension] : null;
    }

    private function readDimensions($path, $mime)
    {
        if ($mime === 'image/svg+xml' || $mime === 'application/pdf') {
            return array(null, null);
        }
        $info = @getimagesize($path);
        return $info === false ? array(null, null) : array($info[0], $info[1]);
    }

    /**
     * Suy alt tạm từ tên file cho các icon/asset phụ. Biên tập viên sửa lại sau.
     */
    private function guessAlt($fileName)
    {
        $base = pathinfo($fileName, PATHINFO_FILENAME);
        return ucfirst(str_replace('-', ' ', $base));
    }
}
