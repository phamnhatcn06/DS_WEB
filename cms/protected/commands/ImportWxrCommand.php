<?php
/**
 * Nhập tin tức từ file WordPress WXR export (post.xml) vào CMS.
 *
 * Chạy:
 *   php protected/yiic.php importWxr --file=post.xml
 *   php protected/yiic.php importWxr --file=post.xml --dryRun=1   (chỉ thống kê, không ghi)
 *   php protected/yiic.php importWxr --file=post.xml --limit=5    (thử 5 bài đầu)
 *
 * Quy tắc đã chốt với chủ đầu tư:
 *  - Chỉ lấy bài post_type=post, status=publish.
 *  - Map danh mục WP -> 4 danh mục CMS; tin-tuc/khong-phan-loai -> danh mục mới "Tin tức".
 *  - Tải ảnh đại diện + ảnh trong nội dung từ htds.vn về uploads/news và viết lại link.
 *  - Idempotent theo source_url: chạy lại sẽ bỏ qua bài đã nhập.
 */
class ImportWxrCommand extends CConsoleCommand
{
    /**
     * Giữ nguyên toàn bộ danh mục WP: mỗi danh mục WP -> một danh mục CMS
     * (slug = nicename, tên = cat_name), tạo theo nhu cầu khi có bài đi kèm.
     * Chỉ các slug dưới đây được đánh dấu hiện trên thanh tab trang chủ.
     */
    private $filterSlugs = array('du-an', 'thi-cong', 'dau-tu', 'co-dong', 'tin-tuc');

    /** Nicename WP là spam/rác -> không tạo danh mục, không gán. */
    private $skipCategories = array(
        'bez-rubriki', 'semaglutide-online-2', '25', 'public',
    );

    /** slug -> tên hiển thị cho danh mục sẽ tạo (thu thập khi duyệt). */
    private $categoryNameBySlug = array();

    private $dryRun = false;

    /**
     * Các host WordPress nguồn xuất hiện trong nội dung (link ảnh/tệp).
     * Nội dung bài cũ còn trỏ htds.vn (đã 404), thư viện ảnh nay ở pvnsoft.cloud.
     */
    private $sourceHosts = array('pvnsoft.cloud', 'htds.vn');

    /** Host thực sự đang phục vụ tệp — mọi ảnh/tệp đều tải từ đây. */
    private $downloadHost = 'pvnsoft.cloud';

    /** slug danh mục CMS -> id (nạp 1 lần). */
    private $categoryIdBySlug = array();

    /** id thư mục media dành cho ảnh tin nhập. */
    private $newsFolderId;

    public function getHelp()
    {
        return "Nhập tin tức từ WordPress WXR.\n"
            . "  yiic importWxr --file=post.xml [--dryRun=1] [--limit=N] [--imgBase=/]\n";
    }

    /**
     * @param string $file    đường dẫn file WXR (tương đối basePath hoặc tuyệt đối).
     * @param int    $dryRun  1 = chỉ phân tích, không ghi DB / không tải ảnh.
     * @param int    $limit   giới hạn số bài xử lý (0 = tất cả).
     * @param string $imgBase tiền tố URL cho ảnh viết lại trong nội dung.
     */
    public function actionIndex($file, $dryRun = 0, $limit = 0, $imgBase = '/', $skipForeign = 1, $update = 0)
    {
        $dryRun = (int) $dryRun;
        $limit = (int) $limit;
        $skipForeign = (int) $skipForeign;
        $update = (int) $update;
        $this->dryRun = (bool) $dryRun;
        $this->imgBase = rtrim($imgBase, '/') . '/';

        $path = $this->resolvePath($file);
        if ($path === null) {
            $this->fail("Không tìm thấy file: {$file}");
        }
        echo "Đọc WXR: {$path}\n";
        echo $dryRun ? "*** DRY RUN — không ghi DB, không tải ảnh ***\n" : "";

        $xml = simplexml_load_file($path);
        if ($xml === false) {
            $this->fail('File XML không hợp lệ.');
        }
        $ns = $xml->getNamespaces(true);

        if (!$dryRun) {
            // Bảo đảm các danh mục hiện trên thanh tab trang chủ luôn tồn tại.
            $this->ensureFilterCategories();
            $this->newsFolderId = $this->findOrCreateFolder('Ảnh tin tức', 'anh-tin-tuc');
        }

        // Bước 1: map post_id attachment -> URL file (để tra ảnh đại diện).
        $attachmentUrl = $this->buildAttachmentMap($xml, $ns);
        echo 'Số attachment: ' . count($attachmentUrl) . "\n";

        // Map bài viết -> URL file PDF đính kèm (attachment PDF có post_parent là
        // bài đó) — dùng làm phương án dự phòng khi bài không có ACF file_dinh_kem.
        $pdfByParent = $this->buildPdfParentMap($xml, $ns);
        echo 'Số PDF gắn theo bài: ' . count($pdfByParent) . "\n";

        // Bước 2: duyệt bài viết.
        $stat = array('imported' => 0, 'updatedCats' => 0, 'skippedExisting' => 0,
            'fallbackCat' => 0, 'skippedNotPublish' => 0, 'skippedForeign' => 0,
            'images' => 0, 'reportPdfs' => 0, 'errors' => 0);
        $processed = 0;

        foreach ($xml->channel->item as $item) {
            $wp = $item->children($ns['wp']);
            if ((string) $wp->post_type !== 'post') {
                continue;
            }
            if ((string) $wp->status !== 'publish') {
                $stat['skippedNotPublish']++;
                continue;
            }
            if ($limit > 0 && $processed >= $limit) {
                break;
            }
            $processed++;

            $title = trim((string) $item->title);
            $link = trim((string) $item->link);

            // Lọc spam nước ngoài: bài thật đều là tiếng Việt có dấu.
            if ($skipForeign && !$this->isVietnamese($title)) {
                $stat['skippedForeign']++;
                continue;
            }

            // Danh mục CMS cho bài này. Giữ TOÀN BỘ bài: bài không map được
            // danh mục (hoặc chỉ dính danh mục spam) -> gán mặc định "Tin tức".
            $catIds = $this->mapPostCategories($item);
            if ($catIds === array()) {
                $catIds = array($this->ensureCategory('tin-tuc', 'Tin tức'));
                $stat['fallbackCat']++;
            }

            if ($dryRun) {
                echo "  [DRY] {$title}  ->  cat=" . implode(',', $catIds) . "\n";
                $stat['imported']++;
                continue;
            }

            // Idempotent theo source_url.
            if ($link !== '') {
                $existing = $this->findExistingPost($link);
                if ($existing !== null) {
                    if ($update) {
                        // Cập nhật lại danh mục cho đúng bản giữ-nguyên (không đụng
                        // nội dung, không tải lại ảnh nội dung).
                        $existing->categoryIds = $catIds;
                        $existing->syncCategories();
                        $stat['updatedCats']++;

                        // Cập nhật file PDF báo cáo từ <wp:attachment_url> (tải về
                        // local nếu chưa có) — đây là trường dữ liệu cần bổ sung.
                        $pdfMediaId = $this->resolveReportPdfMediaId($item, $wp, $attachmentUrl, $pdfByParent);
                        if ($pdfMediaId !== null
                            && (int) $existing->report_pdf_media_id !== $pdfMediaId) {
                            $existing->saveAttributes(array('report_pdf_media_id' => $pdfMediaId));
                            $stat['reportPdfs']++;
                            echo "  [+PDF] {$title}\n";
                        }
                        echo "  [CẬP NHẬT DM] {$title}  ->  " . implode(',', $catIds) . "\n";
                    } else {
                        $stat['skippedExisting']++;
                    }
                    continue;
                }
            }

            try {
                $result = $this->importPost($item, $wp, $ns, $catIds, $attachmentUrl, $pdfByParent);
                $stat['imported']++;
                $stat['images'] += $result['images'];
                $stat['reportPdfs'] += $result['reportPdf'] ? 1 : 0;
                echo "  [OK] {$title}" . ($result['reportPdf'] ? ' [+PDF]' : '') . "\n";
            } catch (Exception $e) {
                $stat['errors']++;
                echo "  [LỖI] {$title} — " . $e->getMessage() . "\n";
            }
        }

        echo "\n===== KẾT QUẢ =====\n";
        echo "Đã nhập:            {$stat['imported']}\n";
        echo "Cập nhật danh mục:  {$stat['updatedCats']}\n";
        echo "Bỏ (đã tồn tại):    {$stat['skippedExisting']}\n";
        echo "Gán 'Tin tức' (fallback): {$stat['fallbackCat']}\n";
        echo "Bỏ (spam nước ngoài):{$stat['skippedForeign']}\n";
        echo "Bỏ (không publish): {$stat['skippedNotPublish']}\n";
        echo "Ảnh đã tải:         {$stat['images']}\n";
        echo "File PDF báo cáo:    {$stat['reportPdfs']}\n";
        echo "Lỗi:                {$stat['errors']}\n";

        if ($this->dryRun && $this->categoryNameBySlug !== array()) {
            echo "\n--- Danh mục sẽ dùng (" . count($this->categoryNameBySlug) . ") ---\n";
            foreach ($this->categoryNameBySlug as $slug => $name) {
                $onBar = in_array($slug, $this->filterSlugs, true) ? '  [tab trang chủ]' : '';
                echo "  - {$name} ({$slug}){$onBar}\n";
            }
        }
    }

    /** Tiền tố URL cho ảnh trong nội dung. */
    private $imgBase = '/';

    // ---------------------------------------------------------------------
    // Phân tích WXR
    // ---------------------------------------------------------------------

    /** post_id (attachment) => URL file gốc. */
    private function buildAttachmentMap($xml, $ns)
    {
        $map = array();
        foreach ($xml->channel->item as $item) {
            $wp = $item->children($ns['wp']);
            if ((string) $wp->post_type === 'attachment') {
                $url = trim((string) $wp->attachment_url);
                if ($url !== '') {
                    $map[(string) $wp->post_id] = $url;
                }
            }
        }
        return $map;
    }

    /**
     * Trả về mảng id danh mục CMS cho một item bài viết.
     * Giữ nguyên toàn bộ danh mục WP: mỗi nicename -> một danh mục CMS
     * (tạo theo nhu cầu), bỏ các danh mục spam trong skipCategories.
     */
    private function mapPostCategories($item)
    {
        $ids = array();
        foreach ($item->category as $cat) {
            if ((string) $cat['domain'] !== 'category') {
                continue;
            }
            $nicename = (string) $cat['nicename'];
            if ($nicename === '' || in_array($nicename, $this->skipCategories, true)) {
                continue;
            }
            $name = trim((string) $cat);
            $id = $this->ensureCategory($nicename, $name !== '' ? $name : $nicename);
            $ids[$id] = true;
        }
        return array_keys($ids);
    }

    // ---------------------------------------------------------------------
    // Ghi dữ liệu
    // ---------------------------------------------------------------------

    /**
     * Nhập một bài.
     * @return array{images:int, reportPdf:bool} số ảnh đã tải và có gắn PDF báo cáo không.
     */
    private function importPost($item, $wp, $ns, $catIds, $attachmentUrl, $pdfByParent)
    {
        $content = $item->children($ns['content']);
        $excerpt = $item->children($ns['excerpt']);

        $contentHtml = (string) $content->encoded;
        $excerptText = trim(strip_tags((string) $excerpt->encoded));

        $imgCount = 0;

        // Ảnh đại diện: postmeta _thumbnail_id -> attachment post_id -> URL.
        $thumbMediaId = null;
        $thumbId = $this->findThumbnailId($wp);
        if ($thumbId !== null && isset($attachmentUrl[$thumbId])) {
            $media = $this->downloadToMedia($attachmentUrl[$thumbId], trim((string) $item->title));
            if ($media !== null) {
                $thumbMediaId = $media->id;
                $imgCount++;
            }
        }

        // Ảnh trong nội dung: tải về + viết lại src.
        $rewrite = $this->rewriteContentImages($contentHtml);
        $contentHtml = $rewrite['html'];
        $imgCount += $rewrite['count'];

        // Slug = post_name (hoặc slugify tiêu đề nếu trống) + hậu tố -{post_id}
        // để mỗi bài WP là một slug duy nhất, không đụng slug bài khác.
        $baseSlug = trim((string) $wp->post_name);
        if ($baseSlug === '') {
            $baseSlug = TextHelper::slugify(trim((string) $item->title));
        }
        $slug = TextHelper::truncate($baseSlug, 210, '') . '-' . (string) $wp->post_id;

        $post = new NewsPost();
        $post->title = TextHelper::truncate(trim((string) $item->title), 300, '');
        $post->slug = $slug;
        $post->excerpt = $excerptText !== '' ? $excerptText : null;
        $post->content = $contentHtml;
        $post->thumbnail_media_id = $thumbMediaId;
        $post->published_at = date('Y-m-d H:i:s', strtotime((string) $wp->post_date));
        $post->date_display_format = 'd/m/Y';
        $post->card_size = 'sm';
        $post->status = BaseActiveRecord::STATUS_PUBLISHED;
        $post->is_active = 1;
        $post->is_featured = 0;
        $post->source_url = TextHelper::truncate(trim((string) $item->link), 500, '');
        $post->categoryIds = $catIds;

        if (!$post->save()) {
            throw new Exception('Lưu bài thất bại: '
                . implode('; ', $this->flattenErrors($post->getErrors())));
        }
        $post->syncCategories();

        return $imgCount;
    }

    /** postmeta _thumbnail_id -> id attachment (chuỗi) hoặc null. */
    private function findThumbnailId($wp)
    {
        foreach ($wp->postmeta as $meta) {
            if ((string) $meta->meta_key === '_thumbnail_id') {
                $v = trim((string) $meta->meta_value);
                return $v !== '' ? $v : null;
            }
        }
        return null;
    }

    /** Tải mọi ảnh htds.vn trong nội dung, viết lại src -> local. */
    private function rewriteContentImages($html)
    {
        $count = 0;
        $hosts = array();
        foreach ($this->sourceHosts as $h) {
            $hosts[] = preg_quote($h, '#');
        }
        $pattern = '#https?://(?:' . implode('|', $hosts)
            . ')/wp-content/uploads/[^\s"\'\\\\)]+#i';

        $html = preg_replace_callback($pattern, function ($m) use (&$count) {
            $url = $m[0];
            $local = $this->downloadImageFile($url);
            if ($local === null) {
                return $url; // giữ nguyên nếu tải lỗi
            }
            $count++;
            return $this->imgBase . ltrim($local['relPath'], '/');
        }, $html);

        return array('html' => $html, 'count' => $count);
    }

    // ---------------------------------------------------------------------
    // Tải ảnh + media
    // ---------------------------------------------------------------------

    /**
     * Tải 1 URL ảnh về uploads/news/... (giữ cây thư mục theo năm/tháng của WP).
     * Trả về array(relPath, absPath, mime, size, width, height) hoặc null.
     */
    private function downloadImageFile($url)
    {
        $relFromUploads = $this->uploadsRelative($url);
        if ($relFromUploads === null) {
            return null;
        }
        $relPath = 'uploads/news/' . $relFromUploads;
        $absPath = Yii::app()->basePath . '/../' . $relPath;
        $absPath = str_replace('\\', '/', $absPath);

        if (!file_exists($absPath)) {
            // Luôn tải từ host đang phục vụ tệp (pvnsoft.cloud), kể cả khi URL
            // gốc trong nội dung còn trỏ htds.vn (đã 404).
            $fetchUrl = 'https://' . $this->downloadHost
                . '/wp-content/uploads/' . $relFromUploads;
            $data = $this->httpGet($fetchUrl);
            if ($data === null || strlen($data) < 100) {
                return null;
            }
            @mkdir(dirname($absPath), 0775, true);
            if (file_put_contents($absPath, $data) === false) {
                return null;
            }
        }

        $size = @getimagesize($absPath);
        return array(
            'relPath' => $relPath,
            'absPath' => $absPath,
            'mime'    => $size ? $size['mime'] : 'application/octet-stream',
            'size'    => filesize($absPath),
            'width'   => $size ? $size[0] : null,
            'height'  => $size ? $size[1] : null,
        );
    }

    /** Tải ảnh và tạo bản ghi MediaFile (dùng cho ảnh đại diện). */
    private function downloadToMedia($url, $altText)
    {
        $file = $this->downloadImageFile($url);
        if ($file === null) {
            return null;
        }

        $checksum = @hash_file('sha256', $file['absPath']);

        // Tránh trùng: nếu đã có media cùng checksum thì tái sử dụng.
        if ($checksum) {
            $existing = MediaFile::model()->find('checksum = :c', array(':c' => $checksum));
            if ($existing !== null) {
                return $existing;
            }
        }

        $media = new MediaFile();
        $media->folder_id = $this->newsFolderId;
        $media->file_name = basename($file['relPath']);
        $media->file_path = $file['relPath'];
        $media->mime_type = $file['mime'];
        $media->file_size = $file['size'];
        $media->width = $file['width'];
        $media->height = $file['height'];
        $media->alt_text = TextHelper::truncate($altText, 300, '');
        $media->checksum = $checksum ?: null;

        if (!$media->save()) {
            throw new Exception('Lưu media thất bại: '
                . implode('; ', $this->flattenErrors($media->getErrors())));
        }
        return $media;
    }

    /** Lấy phần đường dẫn sau /wp-content/uploads/ của URL. */
    private function uploadsRelative($url)
    {
        $marker = '/wp-content/uploads/';
        $pos = strpos($url, $marker);
        if ($pos === false) {
            return null;
        }
        $rel = substr($url, $pos + strlen($marker));
        $rel = preg_replace('~[?#].*$~', '', $rel); // bỏ query string
        // Chặn path traversal.
        $rel = str_replace(array('..', '\\'), '', $rel);
        return ltrim($rel, '/');
    }

    /** GET nội dung URL, ưu tiên cURL. */
    private function httpGet($url)
    {
        if (function_exists('curl_init')) {
            $ch = curl_init($url);
            curl_setopt_array($ch, array(
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_TIMEOUT        => 30,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_USERAGENT      => 'DSH-CMS-Importer/1.0',
            ));
            $data = curl_exec($ch);
            $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            return ($data !== false && $code >= 200 && $code < 400) ? $data : null;
        }
        $ctx = stream_context_create(array('http' => array('timeout' => 30)));
        $data = @file_get_contents($url, false, $ctx);
        return $data !== false ? $data : null;
    }

    // ---------------------------------------------------------------------
    // Chuẩn bị danh mục / thư mục
    // ---------------------------------------------------------------------

    /** Bảo đảm các danh mục hiện trên thanh tab trang chủ luôn tồn tại. */
    private function ensureFilterCategories()
    {
        $names = array(
            'du-an'   => 'Dự án',
            'thi-cong'=> 'Thi công',
            'dau-tu'  => 'Đầu tư',
            'co-dong' => 'Cổ đông',
            'tin-tuc' => 'Tin tức',
        );
        foreach ($names as $slug => $name) {
            $this->ensureCategory($slug, $name);
        }
    }

    /**
     * Tìm hoặc tạo danh mục CMS theo slug. Trả về id (dry-run: trả về slug).
     * show_in_filter = 1 nếu slug nằm trong danh sách tab trang chủ, ngược lại 0.
     */
    private function ensureCategory($slug, $name)
    {
        if (isset($this->categoryIdBySlug[$slug])) {
            return $this->categoryIdBySlug[$slug];
        }
        if ($this->dryRun) {
            $this->categoryNameBySlug[$slug] = $name;
            $this->categoryIdBySlug[$slug] = $slug; // id giả để thống kê
            return $slug;
        }

        $cat = NewsCategory::model()->find('slug = :s', array(':s' => $slug));
        if ($cat === null) {
            $cat = new NewsCategory();
            $cat->name = $name;
            $cat->slug = $slug;
            $cat->show_in_filter = in_array($slug, $this->filterSlugs, true) ? 1 : 0;
            $cat->is_active = 1;
            $cat->sort_order = count($this->categoryIdBySlug) + 1;
            if (!$cat->save()) {
                $this->fail('Không tạo được danh mục ' . $slug . ': '
                    . implode('; ', $this->flattenErrors($cat->getErrors())));
            }
            echo "  + Tạo danh mục: {$name} ({$slug})\n";
        }
        $this->categoryIdBySlug[$slug] = (int) $cat->id;
        return (int) $cat->id;
    }

    private function findOrCreateFolder($name, $slug)
    {
        $id = Yii::app()->db->createCommand()
            ->select('id')->from('pvn_media_folders')
            ->where('slug = :s', array(':s' => $slug))
            ->queryScalar();
        if ($id) {
            return (int) $id;
        }
        Yii::app()->db->createCommand()->insert('pvn_media_folders', array(
            'parent_id'  => null,
            'name'       => $name,
            'slug'       => $slug,
            'sort_order' => 99,
            'created_at' => new CDbExpression('NOW()'),
            'updated_at' => new CDbExpression('NOW()'),
        ));
        return (int) Yii::app()->db->getLastInsertID();
    }

    // ---------------------------------------------------------------------
    // Tiện ích
    // ---------------------------------------------------------------------

    /** Tiêu đề có chứa ký tự tiếng Việt có dấu không (dấu hiệu bài thật). */
    private function isVietnamese($text)
    {
        return (bool) preg_match(
            '/[ăâđêôơưàáạảãằắặẳẵầấậẩẫèéẹẻẽềếệểễìíịỉĩòóọỏõồốộổỗờớợởỡ'
            . 'ùúụủũừứựửữỳýỵỷỹ]/ui',
            $text
        );
    }

    private function postExists($sourceUrl)
    {
        return $this->findExistingPost($sourceUrl) !== null;
    }

    /** Bài đã nhập trước đó (khớp source_url, chưa xoá mềm) hoặc null. */
    private function findExistingPost($sourceUrl)
    {
        return NewsPost::model()->notDeleted()->find(
            'source_url = :u', array(':u' => $sourceUrl)
        );
    }

    private function resolvePath($file)
    {
        if (is_file($file)) {
            return $file;
        }
        $candidate = Yii::app()->basePath . '/../' . $file;
        return is_file($candidate) ? $candidate : null;
    }

    private function flattenErrors($errors)
    {
        $out = array();
        foreach ($errors as $attr => $msgs) {
            foreach ((array) $msgs as $m) {
                $out[] = "{$attr}: {$m}";
            }
        }
        return $out;
    }

    private function fail($message)
    {
        echo 'LỖI: ' . $message . "\n";
        Yii::app()->end(1);
    }
}
