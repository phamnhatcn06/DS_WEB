<?php
/* @var $this MediaController */
/* @var $dataProvider CActiveDataProvider */
/* @var $search string */
/* @var $missingAlt bool */

$files = $dataProvider->getData();
$user = Yii::app()->user;
?>

<?php if ($user->checkAccess('media.create')): ?>
  <div class="card mb-3">
    <div class="card-header">Tải file lên</div>
    <div class="card-body">
      <?php echo CHtml::beginForm($this->createUrl('upload'), 'post', array(
          'enctype'          => 'multipart/form-data',
          'class'            => 'row g-2 align-items-end',
          'data-lock-submit' => '1',
      )); ?>
        <div class="col-12 col-md-8">
          <label class="form-label" for="mediaFiles">Chọn file</label>
          <input type="file" name="mediaFiles[]" id="mediaFiles" class="form-control"
                 multiple accept=".webp,.jpg,.jpeg,.png,.svg,.gif,.pdf" required />
          <p class="form-hint mt-1 mb-0">
            Ảnh raster nên dùng <code>.webp</code>, vector/logo dùng <code>.svg</code>.
            Tối đa <?php echo round(Yii::app()->params['uploadMaxSize'] / 1024 / 1024); ?> MB mỗi file.
          </p>
        </div>
        <div class="col-12 col-md-4">
          <button type="submit" class="btn btn-dsh w-100">
            <i class="bi bi-upload me-1"></i> Tải lên
          </button>
        </div>
      <?php echo CHtml::endForm(); ?>
    </div>
  </div>
<?php endif; ?>

<div class="card">
  <div class="card-header d-flex flex-wrap gap-2 justify-content-between align-items-center">
    <span>
      Thư viện
      <span class="badge bg-secondary"><?php echo $dataProvider->getTotalItemCount(); ?></span>
      <?php if ($missingAlt): ?>
        <span class="badge bg-warning text-dark ms-1">Đang lọc: thiếu alt</span>
        <a class="small ms-2" href="<?php echo $this->createUrl('index'); ?>">Bỏ lọc</a>
      <?php endif; ?>
    </span>

    <form method="get" class="d-flex gap-2">
      <?php if ($missingAlt): ?>
        <input type="hidden" name="missingAlt" value="1" />
      <?php endif; ?>
      <input type="search" name="q" class="form-control form-control-sm"
             placeholder="Tìm theo tên file hoặc alt…" value="<?php echo CHtml::encode($search); ?>"
             style="min-width:240px" />
      <button class="btn btn-sm btn-outline-secondary" type="submit"><i class="bi bi-search"></i></button>
    </form>
  </div>

  <div class="card-body">
    <?php if ($files === array()): ?>
      <p class="text-muted text-center py-4 mb-0">Không có file nào khớp điều kiện.</p>
    <?php else: ?>
      <div class="media-grid">
        <?php foreach ($files as $file): ?>
          <div class="media-tile">
            <figure>
              <?php if ($file->getIsImage()): ?>
                <img src="<?php echo $file->getPublicUrl(); ?>"
                     alt="<?php echo CHtml::encode($file->alt_text); ?>" loading="lazy" />
              <?php else: ?>
                <i class="bi bi-file-earmark-pdf" style="font-size:2.4rem;color:#9a1220"></i>
              <?php endif; ?>
            </figure>

            <figcaption>
              <span class="file-name"><?php echo CHtml::encode($file->file_name); ?></span>

              <?php if ($file->getIsImage() && ($file->alt_text === null || $file->alt_text === '')): ?>
                <span class="media-warning"><i class="bi bi-exclamation-triangle"></i> Thiếu alt</span>
              <?php else: ?>
                <span class="file-meta"><?php echo CHtml::encode(
                    TextHelper::truncate($file->alt_text, 48)); ?></span>
              <?php endif; ?>

              <span class="file-meta d-block mt-1">
                <?php echo $file->getFormattedSize(); ?>
                <?php if ($file->width): ?>
                  · <?php echo $file->width; ?>×<?php echo $file->height; ?>
                <?php endif; ?>
              </span>

              <div class="d-flex gap-1 mt-2">
                <?php if ($user->checkAccess('media.update')): ?>
                  <a class="btn btn-sm btn-outline-primary py-0 px-1"
                     href="<?php echo $this->createUrl('update', array('id' => $file->id)); ?>"
                     title="Sửa">
                    <i class="bi bi-pencil"></i>
                  </a>
                <?php endif; ?>

                <a class="btn btn-sm btn-outline-secondary py-0 px-1" target="_blank" rel="noopener"
                   href="<?php echo $file->getPublicUrl(); ?>" title="Mở file">
                  <i class="bi bi-box-arrow-up-right"></i>
                </a>

                <?php if ($user->checkAccess('media.delete')): ?>
                  <?php echo CHtml::beginForm($this->createUrl('delete', array('id' => $file->id)),
                      'post', array(
                          'class'        => 'd-inline',
                          'data-confirm' => 'Ẩn file “' . $file->file_name . '” khỏi thư viện? '
                              . 'File vật lý không bị xoá và có thể khôi phục.',
                      )); ?>
                    <button class="btn btn-sm btn-outline-danger py-0 px-1" title="Xoá">
                      <i class="bi bi-trash"></i>
                    </button>
                  <?php echo CHtml::endForm(); ?>
                <?php endif; ?>
              </div>
            </figcaption>
          </div>
        <?php endforeach; ?>
      </div>

      <?php if ($dataProvider->getPagination()->getPageCount() > 1): ?>
        <div class="d-flex justify-content-center mt-3">
          <?php $this->widget('CLinkPager', array(
              'pages'         => $dataProvider->getPagination(),
              'header'        => '',
              'prevPageLabel' => '&laquo;',
              'nextPageLabel' => '&raquo;',
              'htmlOptions'   => array('class' => 'pagination pagination-sm mb-0'),
              'selectedPageCssClass' => 'active',
          )); ?>
        </div>
      <?php endif; ?>
    <?php endif; ?>
  </div>
</div>
