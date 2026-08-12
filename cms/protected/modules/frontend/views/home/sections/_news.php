<?php
/**
 * Section 9 — Tin tức nổi bật (tab lọc danh mục).
 *
 * Tabs: newsCategories (slug = data-filter). Lưới 3 ô theo thiết kế:
 * 1 card lớn (wide/lg) · 1 card cao (mid/tall) · các card nhỏ (narrow/sm).
 * Bài được phân vào ô theo THỨ TỰ hiển thị (mới nhất trước), không theo
 * card_size; rỗng → dữ liệu demo tĩnh. Khi ít hơn 3 bài, bố cục chuyển sang
 * biến thể --one/--two để không tràn/chồng lên tiêu đề.
 * JS lọc client-side khớp data-filter với data-category HOẶC một slug trong
 * data-tags của card (main.js) — nên lọc được cả theo danh mục lẫn theo thẻ.
 *
 * @var Controller $this
 * @var NewsCategory[] $newsCategories
 * @var NewsPost[] $newsPosts
 * @var Tag[] $newsTags
 */
$root = $this->assetsBase();

// --- Tabs danh mục ---
$categories = array();
if (!empty($newsCategories)) {
    foreach ($newsCategories as $cat) {
        $categories[] = array('slug' => $cat->slug, 'name' => $cat->name);
    }
} else {
    $categories = array(
        array('slug' => 'du-an', 'name' => 'Dự án'),
        array('slug' => 'thi-cong', 'name' => 'Thi công'),
        array('slug' => 'dau-tu', 'name' => 'Đầu tư'),
        array('slug' => 'co-dong', 'name' => 'Cổ đông'),
    );
}

// --- Thẻ (tag) làm bộ lọc phụ; chỉ có khi đọc dữ liệu thật ---
$tags = array();
if (!empty($newsTags)) {
    foreach ($newsTags as $tag) {
        $tags[] = array('slug' => $tag->slug, 'name' => $tag->name);
    }
}

// --- Bài viết, chuẩn hoá + phân bổ vào 3 ô ---
$posts = array();
if (!empty($newsPosts)) {
    foreach ($newsPosts as $post) {
        $tagNames = array();
        $tagSlugs = array();
        if (is_array($post->tags)) {
            foreach ($post->tags as $tag) {
                if (!is_object($tag)) {
                    continue;
                }
                $tagNames[] = $tag->name;
                $tagSlugs[] = $tag->slug;
            }
        }
        // Đa danh mục: gom slug tất cả danh mục để lọc khớp "bất kỳ".
        $catSlugs = array();
        if (is_array($post->categories)) {
            foreach ($post->categories as $category) {
                if (is_object($category)) {
                    $catSlugs[] = $category->slug;
                }
            }
        }
        if ($catSlugs === array() && $post->category) {
            $catSlugs[] = $post->category->slug; // fallback khi chưa migrate
        }
        $posts[] = array(
            'img'      => $post->thumbnail,
            'cat'      => implode(' ', $catSlugs),
            'chip'     => $post->category ? $post->category->name : '',
            'date'     => $post->getFormattedDate(),
            'title'    => $post->title,
            'excerpt'  => $post->excerpt,
            'tags'     => $tagNames,
            'tagSlugs' => $tagSlugs,
            'url'      => Yii::app()->createUrl('frontend/news/view', array('slug' => $post->slug)),
        );
    }
} else {
    $posts = array(
        array('img' => $root . '/assets/images/news-01.webp', 'cat' => 'du-an', 'chip' => 'Dự án', 'date' => '09/03/2026',
            'title' => 'Đông Sơn Holdings đầu tư dự án nhà ở xã hội Bãi Viên – Nam Định',
            'excerpt' => 'CTCP Đông Sơn Holdings chính thức công bố đầu tư Khu nhà ở xã hội Bãi Viên tại TP. Nam Định, tổng mức đầu tư hơn 909 tỷ đồng với 1.100 căn hộ, khởi công tháng 5/2025.',
            'url' => '#tin-tuc'),
        array('img' => $root . '/assets/images/news-02.webp', 'cat' => 'dau-tu', 'chip' => 'Đầu tư', 'date' => '11/2025',
            'title' => 'Tăng vốn điều lệ lên 350 tỷ đồng, mở rộng danh mục đầu tư',
            'excerpt' => '', 'url' => '#tin-tuc'),
        array('img' => $root . '/assets/images/duan-04-thicong.webp', 'cat' => 'thi-cong', 'chip' => 'Thi công', 'date' => '05/2025',
            'title' => 'Khởi công gói thầu xây lắp trọng điểm, bảo đảm tiến độ toàn tuyến',
            'excerpt' => '', 'url' => '#tin-tuc'),
        array('img' => $root . '/assets/images/duan-01-bot.webp', 'cat' => 'co-dong', 'chip' => 'Cổ đông', 'date' => '22/04/2025',
            'title' => 'Cổ phiếu DSH chính thức giao dịch trên UPCOM',
            'excerpt' => '', 'url' => '#tin-tuc'),
    );
}

// Phân bổ vào ô theo THỨ TỰ hiển thị: bài đầu → ô lớn, bài thứ hai → ô cao,
// còn lại → cột card nhỏ. Không phụ thuộc card_size của từng bài.
$wide   = isset($posts[0]) ? $posts[0] : null;
$mid    = isset($posts[1]) ? $posts[1] : null;
$narrow = array_slice($posts, 2);

// Biến thể bố cục khi thiếu ô: tránh tiêu đề chồng lên card (mosaic 3 ô cần đủ
// cả 3 zone mới cân). 1 bài → --one (card lớn full-width), 2 bài → --two.
$filledZones = ($wide !== null ? 1 : 0) + ($mid !== null ? 1 : 0) + count($narrow);
$layoutClass = 'row g-3 g-lg-4 tintuc-layout';
if ($filledZones === 1) {
    $layoutClass .= ' tintuc-layout--one';
} elseif ($filledZones === 2) {
    $layoutClass .= ' tintuc-layout--two';
}

// Closure render một news card theo ô (sizeClass) — không lặp markup 3 lần.
$renderCard = function ($card, $sizeClass, $showExcerpt, $hidden = false) use ($root) {
    $tagSlugs = isset($card['tagSlugs']) ? implode(' ', $card['tagSlugs']) : '';
    // Ẩn sẵn (is-hidden) các tin dư ở lượt "Tất cả" khi tải trang — chỉ 4 tin
    // đầu hiển thị, phần còn lại vẫn nằm trong DOM để bộ lọc danh mục dùng.
    $itemClass = $hidden ? 'news-item is-hidden' : 'news-item';
    $out  = '<article class="' . $itemClass . '" data-category="' . CHtml::encode($card['cat']) . '"'
        . ' data-tags="' . CHtml::encode($tagSlugs) . '">' . "\n";
    $out .= '  <a href="' . CHtml::encode($card['url']) . '" class="news-card news-card--' . $sizeClass . '">' . "\n";
    $out .= '    ' . MediaHelper::imgOr($card['img'], $root . '/assets/images/news-01.webp',
        $card['title'], array('class' => 'news-card-img')) . "\n";
    if ($card['chip'] !== '') {
        $out .= '    <span class="news-chip">' . CHtml::encode($card['chip']) . '</span>' . "\n";
    }
    $out .= '    <div class="news-body">' . "\n";
    if ($card['date'] !== '') {
        $out .= '      <p class="news-date"><img src="' . $root . '/assets/images/icon-calendar.svg" alt="" aria-hidden="true" />'
            . CHtml::encode($card['date']) . '</p>' . "\n";
    }
    $out .= '      <h3 class="news-heading">' . CHtml::encode($card['title']) . '</h3>' . "\n";
    if ($showExcerpt && $card['excerpt'] !== null && $card['excerpt'] !== '') {
        $out .= '      <p class="news-excerpt">' . CHtml::encode($card['excerpt']) . '</p>' . "\n";
    }
    if (!empty($card['tags'])) {
        $out .= '      <div class="news-tags">';
        foreach ($card['tags'] as $tagName) {
            $out .= '<span class="news-tag">' . CHtml::encode($tagName) . '</span>';
        }
        $out .= '</div>' . "\n";
    }
    $out .= '      <span class="news-more">Đọc tiếp <img src="' . $root . '/assets/images/arrow-right.svg" alt="" aria-hidden="true" /></span>' . "\n";
    $out .= '    </div>' . "\n  </a>\n</article>";
    return $out;
};
?>
    <!-- ===== Section 9: Tin tức nổi bật (tab lọc danh mục) ===== -->
    <section id="tin-tuc" class="tintuc fade-section">
      <div class="container">

        <!-- Lưới tin: tiêu đề+tab và card lớn cùng nằm ở lane cột 1,
             card cao giữa · 2 card nhỏ phải bám mép trên = tâm dòng tiêu đề -->
        <div class="<?php echo $layoutClass; ?>">

        <!-- Đầu section: tiêu đề + tab lọc danh mục -->
        <div class="col-12 tintuc-head">
          <h2 class="tintuc-title" data-reveal="left">Tin tức nổi bật</h2>
          <ul class="nav filter-pills" role="tablist" data-news-filter>
            <li class="nav-item" role="presentation">
              <button type="button" class="nav-link active" role="tab" aria-selected="true" data-filter="all">Tất cả</button>
            </li>
<?php foreach ($categories as $cat): ?>
            <li class="nav-item" role="presentation">
              <button type="button" class="nav-link" role="tab" aria-selected="false" data-filter="<?php echo CHtml::encode($cat['slug']); ?>"><?php echo CHtml::encode($cat['name']); ?></button>
            </li>
<?php endforeach; ?>
<?php if (!empty($tags)): ?>
            <li class="nav-item filter-sep" role="presentation" aria-hidden="true"></li>
<?php foreach ($tags as $tag): ?>
            <li class="nav-item" role="presentation">
              <button type="button" class="nav-link nav-link--tag" role="tab" aria-selected="false" data-filter="<?php echo CHtml::encode($tag['slug']); ?>"># <?php echo CHtml::encode($tag['name']); ?></button>
            </li>
<?php endforeach; ?>
<?php endif; ?>
          </ul>
        </div>

<?php if ($wide !== null): ?>
          <div class="col-12 tintuc-col--wide">
            <?php echo $renderCard($wide, 'lg', true); ?>
          </div>
<?php endif; ?>

<?php if ($mid !== null): ?>
          <div class="col-12 tintuc-col--mid">
            <?php echo $renderCard($mid, 'tall', false); ?>
          </div>
<?php endif; ?>

<?php if (!empty($narrow)): ?>
          <div class="col-12 tintuc-col--narrow">
            <div class="row g-3 g-lg-4">
<?php foreach ($narrow as $card): ?>
              <div class="col-12">
                <?php echo $renderCard($card, 'sm', false); ?>
              </div>
<?php endforeach; ?>
            </div>
          </div>
<?php endif; ?>

<?php if ($wide === null && $mid === null && empty($narrow)): ?>
          <div class="col-12">
            <p class="text-center py-5 m-0">Chưa có bài viết.</p>
          </div>
<?php endif; ?>

        </div>
      </div>
    </section>
