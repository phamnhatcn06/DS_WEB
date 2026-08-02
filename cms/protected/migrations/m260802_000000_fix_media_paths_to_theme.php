<?php
/**
 * Sửa đường dẫn thư viện media để trỏ đúng nơi ảnh thật sự nằm.
 *
 * Bối cảnh: seed m260724_070000 quét thư mục dùng chung `../assets/images`
 * (cấp gốc dự án). Ảnh sau đó được gom hết vào theme frontend
 * `cms/themes/dongson/assets/images/`, còn thư mục gốc thì rỗng. Hệ quả:
 *
 *   MediaFile::getPublicUrl() = baseUrl . '/' . file_path
 *                             = baseUrl . '/../assets/images/hero-bg.webp'
 *                             → /assets/images/hero-bg.webp  (404 — không tồn tại)
 *
 * Trong khi fallback tĩnh của view lại dùng đúng chỗ:
 *
 *   theme->baseUrl . '/assets/images/hero-bg.webp'
 *   (= baseUrl . '/themes/dongson/assets/images/hero-bg.webp')
 *
 * Vì các bản ghi nội dung ĐÃ liên kết media (image_media_id ... khác NULL),
 * MediaHelper::imgOr dùng URL từ DB (hỏng) thay vì fallback → ảnh biến mất.
 *
 * Cách sửa bất biến theo baseUrl: đặt file_path = 'themes/dongson/assets/images/<tên>'.
 * Khi đó getPublicUrl luôn khớp đúng fallback đang chạy tốt, bất kể docroot MAMP
 * map baseUrl thành '' hay '/cms'.
 *
 * Migration này còn:
 *  - Nạp bổ sung ảnh trong theme chưa có trong thư viện (giúp cài mới, khi seed
 *    cũ quét thư mục rỗng nên không nạp được gì);
 *  - Điền lại các liên kết media_id còn NULL theo tên file (chỉ đụng NULL nên an
 *    toàn với DB đã liên kết sẵn) — đồng bộ với m260724_080000.
 */
class m260802_000000_fix_media_paths_to_theme extends CDbMigration
{
    /** Đường dẫn cũ (hỏng) và mới (đúng theme), tương đối với baseUrl. */
    const OLD_PATH_PREFIX = '../assets/images/';
    const NEW_PATH_PREFIX = 'themes/dongson/assets/images/';

    private $now;

    public function up()
    {
        $this->now = date('Y-m-d H:i:s');

        $this->importMissingImages();
        $this->repointExistingPaths();
        $this->backfillContentLinks();
        $this->clearHomepageCache();
    }

    public function down()
    {
        // Trả file_path về vị trí gốc cũ. Không gỡ ảnh đã nạp bổ sung hay liên kết
        // đã điền — việc đó thuộc migration seed, không phải migration sửa đường dẫn.
        $this->execute(
            'UPDATE pvn_media_files SET file_path = REPLACE(file_path, :new, :old)'
                . ' WHERE file_path LIKE :like',
            array(
                ':new'  => self::NEW_PATH_PREFIX,
                ':old'  => self::OLD_PATH_PREFIX,
                ':like' => self::NEW_PATH_PREFIX . '%',
            )
        );
    }

    // ------------------------------------------------------------------ import

    /**
     * Nạp các ảnh trong theme chưa có bản ghi (so theo file_name), để cài mới
     * có thư viện đầy đủ dù seed cũ quét nhầm thư mục rỗng.
     */
    private function importMissingImages()
    {
        $sourceDir = realpath(dirname(__FILE__) . '/../../themes/dongson/assets/images');
        if ($sourceDir === false || !is_dir($sourceDir)) {
            echo "    Bỏ qua nạp bổ sung: không thấy thư mục ảnh theme.\n";
            return;
        }

        $folderId = $this->ensureWebsiteFolder();
        $imported = 0;

        foreach (scandir($sourceDir) as $fileName) {
            $fullPath = $sourceDir . DIRECTORY_SEPARATOR . $fileName;
            if ($fileName === '.' || $fileName === '..' || !is_file($fullPath)) {
                continue;
            }

            $mime = $this->detectMime($fullPath);
            if ($mime === null) {
                continue;
            }

            // Bỏ qua nếu đã có bản ghi trùng tên HOẶC trùng nội dung (checksum).
            // Theme chứa cả bản Figma đặt tên băm lẫn bản đặt tên ngữ nghĩa với
            // nội dung y hệt — cột checksum là UNIQUE nên phải lọc trước khi chèn.
            $checksum = hash_file('sha256', $fullPath);
            $exists = Yii::app()->db->createCommand()
                ->select('COUNT(*)')->from('pvn_media_files')
                ->where('file_name = :n OR checksum = :c',
                    array(':n' => $fileName, ':c' => $checksum))
                ->queryScalar();
            if ($exists) {
                continue;
            }

            list($width, $height) = $this->readDimensions($fullPath, $mime);

            $this->insert('pvn_media_files', array(
                'folder_id'  => $folderId,
                'file_name'  => $fileName,
                'file_path'  => self::NEW_PATH_PREFIX . $fileName,
                'mime_type'  => $mime,
                'file_size'  => filesize($fullPath),
                'width'      => $width,
                'height'     => $height,
                'alt_text'   => ucfirst(str_replace('-', ' ',
                    pathinfo($fileName, PATHINFO_FILENAME))),
                'checksum'   => $checksum,
                'created_at' => $this->now,
                'updated_at' => $this->now,
            ));
            $imported++;
        }

        echo "    Đã nạp bổ sung {$imported} ảnh từ theme vào thư viện.\n";
    }

    private function ensureWebsiteFolder()
    {
        $folderId = Yii::app()->db->createCommand()
            ->select('id')->from('pvn_media_folders')
            ->where('slug = :s', array(':s' => 'anh-website'))
            ->queryScalar();
        if ($folderId) {
            return $folderId;
        }

        $this->insert('pvn_media_folders', array(
            'name'       => 'Ảnh website',
            'slug'       => 'anh-website',
            'sort_order' => 1,
            'created_at' => $this->now,
            'updated_at' => $this->now,
        ));
        return Yii::app()->db->getLastInsertID();
    }

    // ------------------------------------------------------------- repoint

    /**
     * Trỏ file_path của mọi bản ghi đang ở thư mục gốc cũ sang thư mục theme.
     */
    private function repointExistingPaths()
    {
        $affected = $this->getDbConnection()->createCommand(
            'UPDATE pvn_media_files SET file_path = REPLACE(file_path, :old, :new),'
                . ' updated_at = :now'
                . ' WHERE file_path LIKE :like'
        )->execute(array(
            ':old'  => self::OLD_PATH_PREFIX,
            ':new'  => self::NEW_PATH_PREFIX,
            ':now'  => $this->now,
            ':like' => self::OLD_PATH_PREFIX . '%',
        ));

        echo "    Đã trỏ lại {$affected} đường dẫn media sang theme dongson.\n";
    }

    // ------------------------------------------------------------- backfill

    /**
     * Điền lại liên kết media còn NULL theo tên file (đồng bộ m260724_080000).
     * Chỉ cập nhật khi đang NULL — không đè lựa chọn biên tập viên đã có.
     */
    private function backfillContentLinks()
    {
        // hero_slides: nền + logo cho mọi slide.
        $this->linkAll('pvn_hero_slides', 'background_media_id', 'hero-bg.webp');
        $this->linkAll('pvn_hero_slides', 'logo_media_id', 'logo.webp');

        // business_sectors theo slug.
        $this->linkBy('pvn_business_sectors', 'image_media_id', 'slug', array(
            'thi-cong-xay-lap'   => 'hero-bg.webp',
            'dau-tu-bot-ha-tang' => 'bot-interchange.webp',
            'nha-o-do-thi'       => 'cta-bridge.webp',
            'nang-luong-kcn'     => 'hero-bg.webp',
        ));

        // core_values theo icon_variant + thứ tự (dùng title là an toàn nhất).
        $this->linkBy('pvn_core_values', 'icon_media_id', 'title', array(
            'Trách nhiệm'  => 'giatri-icon-shield.svg',
            'Chuyên nghiệp' => 'giatri-icon-award.svg',
            'Đổi mới'      => 'giatri-icon-innovation.svg',
            'Tin cậy'      => 'giatri-icon-person.svg',
        ));

        // partners theo tên.
        $this->linkBy('pvn_partners', 'logo_media_id', 'name', array(
            'Tổng công ty 319 — Bộ Quốc phòng' => 'partner-1.webp',
            'OGC Group'                        => 'partner-2.webp',
            'Vinaconex'                        => 'partner-3.webp',
            'Văn Phú – Invest'                 => 'partner-4.webp',
            'Tư Lập'                           => 'partner-5.webp',
            'Trung tâm Lưu ký & Bù trừ Chứng khoán Việt Nam (VSDC)' => 'partner-6.webp',
            'Sở Giao dịch Chứng khoán Hà Nội (HNX)'                 => 'partner-7.webp',
        ));

        // projects theo slug.
        $this->linkBy('pvn_projects', 'thumbnail_media_id', 'slug', array(
            'bot-ha-noi-bac-giang'       => 'duan-01-bot.webp',
            'khu-do-thi-dong-son'        => 'duan-02-dothi.webp',
            'nha-o-xa-hoi-bai-vien'      => 'duan-01-bot.webp',
            'to-hop-can-ho-song-dao'     => 'duan-03-nhao.webp',
            'du-an-dang-thi-cong-ha-noi' => 'duan-04-thicong.webp',
        ));

        // news_posts theo slug.
        $this->linkBy('pvn_news_posts', 'thumbnail_media_id', 'slug', array(
            'dau-tu-du-an-nha-o-xa-hoi-bai-vien-nam-dinh' => 'news-01.webp',
            'tang-von-dieu-le-len-350-ty-dong'            => 'news-02.webp',
            'khoi-cong-goi-thau-xay-lap-trong-diem'       => 'duan-04-thicong.webp',
            'co-phieu-dsh-giao-dich-tren-upcom'           => 'duan-01-bot.webp',
        ));
    }

    /** Gán cùng một ảnh cho mọi dòng của bảng khi cột còn NULL. */
    private function linkAll($table, $column, $fileName)
    {
        $mediaId = $this->media($fileName);
        if ($mediaId === null) {
            return;
        }
        $this->getDbConnection()->createCommand(
            "UPDATE {$table} SET {$column} = :id WHERE {$column} IS NULL"
        )->execute(array(':id' => $mediaId));
    }

    /** Gán ảnh theo một cột khoá (slug/name/title), chỉ khi cột media còn NULL. */
    private function linkBy($table, $column, $keyColumn, array $map)
    {
        foreach ($map as $keyValue => $fileName) {
            $mediaId = $this->media($fileName);
            if ($mediaId === null) {
                continue;
            }
            $this->getDbConnection()->createCommand(
                "UPDATE {$table} SET {$column} = :id"
                    . " WHERE {$keyColumn} = :key AND {$column} IS NULL"
            )->execute(array(':id' => $mediaId, ':key' => $keyValue));
        }
    }

    private function media($fileName)
    {
        return Yii::app()->db->createCommand()
            ->select('id')->from('pvn_media_files')
            ->where('file_name = :n', array(':n' => $fileName))
            ->queryScalar() ?: null;
    }

    private function clearHomepageCache()
    {
        if (Yii::app()->hasComponent('cache') && Yii::app()->cache) {
            Yii::app()->cache->delete(BaseActiveRecord::CACHE_KEY_HOMEPAGE);
        }
    }

    // ---------------------------------------------------------------- helpers

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
}
