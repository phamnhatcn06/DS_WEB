<?php
/**
 * Trang lưu trữ theo thẻ — liệt kê lĩnh vực + tin tức gắn thẻ.
 *
 * @var TagController $this
 * @var Tag $tag
 * @var NewsPost[] $posts
 * @var BusinessSector[] $sectors
 */
$root = $this->assetsBase();
$home = Yii::app()->homeUrl;

Yii::app()->clientScript->registerCssFile($root . '/assets/css/tag-page.css');

$totalPosts   = count($posts);
$totalSectors = count($sectors);
$total        = $totalPosts + $totalSectors;
?>
    <!-- ===== Trang thẻ (tag archive) ===== -->
    <section class="tagp">
      <div class="container">

        <nav class="tagp-crumbs" aria-label="breadcrumb">
          <a href="<?php echo CHtml::encode($home); ?>">Trang chủ</a>
          <span aria-hidden="true">/</span>
          <span>Thẻ</span>
        </nav>

        <header class="tagp-head">
          <span class="tagp-eyebrow">Lưu trữ theo thẻ</span>
          <h1 class="tagp-title"># <?php echo CHtml::encode($tag->name); ?></h1>
          <p class="tagp-sub">
<?php if ($total > 0): ?>
            <?php echo $total; ?> kết quả gắn thẻ này<?php if ($tag->description): ?> — <?php echo CHtml::encode($tag->description); ?><?php endif; ?>
<?php else: ?>
            Chưa có nội dung nào gắn thẻ này.
<?php endif; ?>
          </p>
        </header>

<?php if ($totalSectors > 0): ?>
        <!-- Lĩnh vực gắn thẻ -->
        <h2 class="tagp-section-title">Lĩnh vực kinh doanh</h2>
        <div class="row g-4 tagp-grid">
<?php foreach ($sectors as $sector): ?>
          <div class="col-12 col-md-6 col-lg-4">
            <a href="<?php echo CHtml::encode($home); ?>#linh-vuc" class="tagp-card">
              <div class="tagp-card-media">
                <?php echo MediaHelper::imgOr($sector->image, $root . '/assets/images/linhvuc-crane.webp',
                  'Lĩnh vực ' . $sector->name, array('class' => 'tagp-card-img')); ?>
              </div>
              <div class="tagp-card-body">
                <span class="tagp-card-kicker">Lĩnh vực</span>
                <h3 class="tagp-card-title"><?php echo CHtml::encode($sector->name); ?></h3>
<?php if ($sector->description !== null && $sector->description !== ''): ?>
                <p class="tagp-card-desc"><?php echo CHtml::encode(TextHelper::truncate($sector->description, 120)); ?></p>
<?php endif; ?>
              </div>
            </a>
          </div>
<?php endforeach; ?>
        </div>
<?php endif; ?>

<?php if ($totalPosts > 0): ?>
        <!-- Tin tức gắn thẻ -->
        <h2 class="tagp-section-title">Tin tức</h2>
        <div class="row g-4 tagp-grid">
<?php foreach ($posts as $post): ?>
<?php $url = ($post->source_url !== null && $post->source_url !== '') ? $post->source_url : $home . '#tin-tuc'; ?>
          <div class="col-12 col-md-6 col-lg-4">
            <a href="<?php echo CHtml::encode($url); ?>" class="tagp-card">
              <div class="tagp-card-media">
                <?php echo MediaHelper::imgOr($post->thumbnail, $root . '/assets/images/news-01.webp',
                  $post->title, array('class' => 'tagp-card-img')); ?>
<?php if ($post->category !== null): ?>
                <span class="tagp-card-chip"><?php echo CHtml::encode($post->category->name); ?></span>
<?php endif; ?>
              </div>
              <div class="tagp-card-body">
                <p class="tagp-card-date"><?php echo CHtml::encode($post->getFormattedDate()); ?></p>
                <h3 class="tagp-card-title"><?php echo CHtml::encode($post->title); ?></h3>
<?php if ($post->excerpt !== null && $post->excerpt !== ''): ?>
                <p class="tagp-card-desc"><?php echo CHtml::encode(TextHelper::truncate($post->excerpt, 120)); ?></p>
<?php endif; ?>
              </div>
            </a>
          </div>
<?php endforeach; ?>
        </div>
<?php endif; ?>

<?php if ($total === 0): ?>
        <div class="tagp-empty">
          <p>Chưa có tin tức hay lĩnh vực nào được gắn thẻ <strong><?php echo CHtml::encode($tag->name); ?></strong>.</p>
          <a href="<?php echo CHtml::encode($home); ?>" class="btn btn-dsh">Về trang chủ</a>
        </div>
<?php endif; ?>

      </div>
    </section>
