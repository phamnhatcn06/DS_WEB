<?php
/* @var $this NewsPostController */
/* @var $model NewsPost */
/* @var $fields array (không dùng ở view này — bố cục kiểu WordPress tự dựng) */

$isNew     = $model->getIsNewRecord();
$modelName = 'NewsPost';
$root      = Yii::app()->baseUrl;

// Danh sách danh mục (kèm cờ phân loại) & thẻ để render checkbox nhiều lựa chọn.
$categoriesWithFlags = NewsCategory::optionsWithFlags();
$tagOptions      = Tag::optionsForSelect();
$selectedCats    = array_map('strval', $model->getCategoryIds());
$selectedTags    = array_map('strval', (array) $model->tagIds);

// Ảnh đại diện hiện tại.
$thumb    = $model->thumbnail_media_id ? MediaFile::model()->findByPk($model->thumbnail_media_id) : null;
$thumbUrl = $thumb ? $thumb->getPublicUrl() : '';
$thumbName = $thumb ? $thumb->file_name : '';

// File PDF báo cáo hiện tại (Quan hệ cổ đông — Báo cáo thường niên).
$reportPdf     = $model->report_pdf_media_id ? MediaFile::model()->findByPk($model->report_pdf_media_id) : null;
$reportPdfUrl  = $reportPdf ? $reportPdf->getPublicUrl() : '';
$reportPdfName = $reportPdf ? $reportPdf->getDisplayName() : '';

/** Render một danh sách checkbox (danh mục / thẻ) trong hộp cuộn kiểu WordPress. */
$renderCheckList = function ($attribute, $options, $selected) use ($modelName) {
    if ($options === array()) {
        echo '<p class="text-muted small mb-0"><em>Chưa có mục nào.</em></p>';
        // Vẫn gửi giá trị rỗng để lưu được khi bỏ chọn hết.
        echo '<input type="hidden" name="' . $modelName . '[' . $attribute . '][]" value="" />';
        return;
    }
    echo '<div class="wp-checklist">';
    foreach ($options as $value => $label) {
        $inputId = $attribute . '_' . $value;
        $checked = in_array((string) $value, $selected, true) ? 'checked' : '';
        echo '<div class="form-check">'
            . '<input type="checkbox" class="form-check-input" id="' . $inputId . '" '
            . 'name="' . $modelName . '[' . $attribute . '][]" '
            . 'value="' . CHtml::encode($value) . '" ' . $checked . ' />'
            . '<label class="form-check-label" for="' . $inputId . '">' . CHtml::encode($label) . '</label>'
            . '</div>';
    }
    echo '</div>';
    echo '<input type="hidden" name="' . $modelName . '[' . $attribute . '][]" value="" />';
};

// Giá trị datetime-local cần định dạng Y-m-d\TH:i.
$publishedRaw   = $model->published_at;
$publishedValue = $publishedRaw ? date('Y-m-d\TH:i', strtotime($publishedRaw)) : '';
?>

<?php $form = $this->beginWidget('CActiveForm', array(
    'id' => 'crud-form',
    'enableClientValidation' => false,
    'htmlOptions' => array('data-lock-submit' => '1', 'class' => 'wp-editor'),
)); ?>

<?php if ($model->hasErrors()): ?>
  <div class="alert alert-danger">
    <strong>Chưa lưu được. Vui lòng kiểm tra lại:</strong>
    <?php echo $form->errorSummary($model, '', '', array('class' => 'mb-0 mt-2')); ?>
  </div>
<?php endif; ?>

<div class="row g-3">

  <!-- ===== Cột chính: tiêu đề, slug, nội dung, trích dẫn ===== -->
  <div class="col-12 col-lg-8">

    <!-- Tiêu đề -->
    <div class="card mb-3">
      <div class="card-body">
        <?php echo $form->textField($model, 'title', array(
            'class'       => 'form-control form-control-lg wp-title',
            'maxlength'   => 300,
            'placeholder' => 'Nhập tiêu đề bài viết',
            'data-slug-source' => '1',
        )); ?>

        <!-- Permalink / slug kiểu WordPress -->
        <div class="wp-permalink mt-2">
          <span class="text-muted small">Đường dẫn (slug):</span>
          <?php echo $form->textField($model, 'slug', array(
              'class'       => 'wp-slug-input',
              'maxlength'   => 220,
              'placeholder' => 'tu-dong-sinh-tu-tieu-de',
              'data-slug-target' => '1',
              'data-slug-edited' => $isNew ? '0' : '1',
          )); ?>
        </div>
        <?php echo $form->error($model, 'title', array('class' => 'text-danger small mt-1')); ?>
        <?php echo $form->error($model, 'slug', array('class' => 'text-danger small mt-1')); ?>
      </div>
    </div>

    <!-- Nội dung: trình soạn thảo TinyMCE -->
    <div class="card mb-3">
      <div class="card-header py-2"><?php echo $form->labelEx($model, 'content', array('class' => 'form-label mb-0')); ?></div>
      <div class="card-body">
        <?php echo $form->textArea($model, 'content', array(
            'class' => 'form-control wp-content-editor',
            'rows'  => 18,
        )); ?>
        <?php echo $form->error($model, 'content', array('class' => 'text-danger small mt-1')); ?>
      </div>
    </div>

    <!-- Trích dẫn -->
    <div class="card mb-3">
      <div class="card-header py-2"><?php echo $form->labelEx($model, 'excerpt', array('class' => 'form-label mb-0')); ?></div>
      <div class="card-body">
        <?php echo $form->textArea($model, 'excerpt', array('class' => 'form-control', 'rows' => 3)); ?>
        <p class="form-hint mt-1 mb-0">Chỉ card lớn hiển thị trích dẫn — bắt buộc nhập nếu chọn “Card lớn”.</p>
        <?php echo $form->error($model, 'excerpt', array('class' => 'text-danger small mt-1')); ?>
      </div>
    </div>

    <!-- ===== Khối Dự án — hiện khi chọn danh mục là "danh mục dự án" ===== -->
    <div class="card mb-3 dsh-conditional" data-project-box hidden>
      <div class="card-header py-2"><i class="fa fa-building me-1"></i> Thông tin dự án</div>
      <div class="card-body">
        <div class="row g-3">
          <div class="col-12 col-md-6">
            <?php echo $form->label($model, 'project_name', array('class' => 'form-label')); ?>
            <?php echo $form->textField($model, 'project_name', array('class' => 'form-control', 'maxlength' => 255)); ?>
          </div>
          <div class="col-12 col-md-6">
            <?php echo $form->label($model, 'project_location', array('class' => 'form-label')); ?>
            <?php echo $form->textField($model, 'project_location', array('class' => 'form-control', 'maxlength' => 255)); ?>
          </div>
          <div class="col-12 col-md-6">
            <?php echo $form->label($model, 'project_investment_display', array('class' => 'form-label')); ?>
            <?php echo $form->textField($model, 'project_investment_display', array('class' => 'form-control', 'maxlength' => 100, 'placeholder' => 'VD: 4.213 tỷ đồng')); ?>
          </div>
          <div class="col-12 col-md-6">
            <?php echo $form->label($model, 'project_scale_display', array('class' => 'form-label')); ?>
            <?php echo $form->textField($model, 'project_scale_display', array('class' => 'form-control', 'maxlength' => 150, 'placeholder' => 'VD: 1.100 căn hộ')); ?>
          </div>
          <div class="col-12 col-md-6">
            <?php echo $form->label($model, 'project_status', array('class' => 'form-label')); ?>
            <?php echo $form->dropDownList($model, 'project_status', NewsPost::projectStatusOptions(),
                array('class' => 'form-select', 'empty' => '— Chọn tình trạng —')); ?>
            <?php echo $form->error($model, 'project_status', array('class' => 'text-danger small mt-1')); ?>
          </div>
        </div>
        <p class="form-hint mt-2 mb-0">Các ô này chỉ dùng cho bài thuộc danh mục dự án.</p>
      </div>
    </div>

    <!-- ===== Khối Quan hệ cổ đông — hiện khi chọn danh mục quan hệ cổ đông ===== -->
    <div class="card mb-3 dsh-conditional" data-investor-box hidden>
      <div class="card-header py-2"><i class="fa fa-language me-1"></i> Nội dung song ngữ &amp; tài liệu đính kèm</div>
      <div class="card-body">

        <!-- Nội dung tiếng Anh -->
        <div class="mb-3">
          <?php echo $form->label($model, 'content_en', array('class' => 'form-label')); ?>
          <?php echo $form->textArea($model, 'content_en', array(
              'class' => 'form-control wp-content-editor',
              'rows'  => 14,
          )); ?>
          <p class="form-hint mt-1 mb-0">Bản dịch tiếng Anh của nội dung. Ô “Nội dung” phía trên là bản tiếng Việt.</p>
        </div>

        <!-- File đính kèm — tách riêng bản tiếng Việt và bản tiếng Anh -->
        <div class="row g-3">
<?php
/** Render một khu vực đính kèm (một ngôn ngữ). */
$renderAttachmentGroup = function ($field, $label, $addLabel, $files) {
    echo '<div class="col-12 col-md-6">';
    echo '<div class="attachment-group" data-attachment-group data-field="' . $field . '">';
    echo '<label class="form-label mb-1">' . CHtml::encode($label) . '</label>';
    echo '<div class="attachment-list" data-attachment-list>';
    foreach ($files as $file) {
        echo '<div class="attachment-item d-flex align-items-center gap-2 mb-1" data-attachment-item>'
            . '<input type="hidden" name="NewsPost[' . $field . '][]" value="' . (int) $file->id . '" />'
            . '<i class="fa fa-file-pdf-o text-danger"></i>'
            . '<a href="' . CHtml::encode($file->getPublicUrl()) . '" target="_blank" rel="noopener" class="flex-grow-1 text-truncate">'
            . CHtml::encode($file->getDisplayName()) . '</a>'
            . '<button type="button" class="btn btn-sm btn-outline-danger" data-attachment-remove title="Bỏ file">'
            . '<i class="fa fa-times"></i></button>'
            . '</div>';
    }
    echo '</div>';
    // Luôn gửi giá trị rỗng để lưu được khi xoá hết file.
    echo '<input type="hidden" name="NewsPost[' . $field . '][]" value="" />';
    echo '<button type="button" class="btn btn-sm btn-dsh mt-2" data-attachment-add>'
        . '<i class="fa fa-paperclip me-1"></i> ' . CHtml::encode($addLabel) . '</button>';
    echo '</div>';
    echo '</div>';
};

$renderAttachmentGroup('attachmentIdsVi', 'File đính kèm (tiếng Việt)',
    'Thêm file tiếng Việt', $isNew ? array() : $model->getAttachmentFiles('vi'));
$renderAttachmentGroup('attachmentIdsEn', 'File đính kèm (tiếng Anh)',
    'Thêm file tiếng Anh', $isNew ? array() : $model->getAttachmentFiles('en'));
?>
        </div>
        <p class="form-hint mt-2 mb-0">Chọn từ thư viện hoặc tải file PDF mới lên (mỗi ngôn ngữ một khu vực).</p>

      </div>
    </div>
  </div>

  <!-- ===== Cột phải: hộp đăng bài, danh mục, thẻ, ảnh, tuỳ chọn ===== -->
  <div class="col-12 col-lg-4">

    <!-- Hộp Đăng bài -->
    <div class="card mb-3">
      <div class="card-header py-2"><i class="fa fa-paper-plane me-1"></i> Đăng bài</div>
      <div class="card-body">
        <div class="mb-3">
          <?php echo $form->labelEx($model, 'status', array('class' => 'form-label')); ?>
          <?php echo $form->dropDownList($model, 'status', NewsPost::statusOptions(), array('class' => 'form-select')); ?>
        </div>
        <div class="mb-3">
          <?php echo $form->labelEx($model, 'published_at', array('class' => 'form-label')); ?>
          <?php echo CHtml::activeTextField($model, 'published_at', array(
              'class' => 'form-control', 'type' => 'datetime-local', 'value' => $publishedValue,
          )); ?>
          <?php echo $form->error($model, 'published_at', array('class' => 'text-danger small mt-1')); ?>
        </div>
        <div class="form-check mb-2">
          <?php echo $form->checkBox($model, 'is_featured', array('class' => 'form-check-input')); ?>
          <?php echo $form->label($model, 'is_featured', array('class' => 'form-check-label')); ?>
        </div>
        <div class="form-check mb-2">
          <?php echo $form->checkBox($model, 'is_featured_project', array('class' => 'form-check-input')); ?>
          <?php echo $form->label($model, 'is_featured_project', array('class' => 'form-check-label')); ?>
        </div>
        <div class="form-check mb-3">
          <?php echo $form->checkBox($model, 'is_active', array('class' => 'form-check-input')); ?>
          <?php echo $form->label($model, 'is_active', array('class' => 'form-check-label')); ?>
        </div>
        <div class="d-grid gap-2">
          <button type="submit" class="btn btn-dsh"><i class="fa fa-check me-1"></i> Lưu</button>
          <button type="submit" name="save_and_continue" value="1" class="btn btn-outline-secondary">
            Lưu và tiếp tục sửa
          </button>
          <a class="btn btn-link text-muted" href="<?php echo $this->createUrl('index'); ?>">Huỷ</a>
        </div>
      </div>
    </div>

    <!-- Danh mục (chọn nhiều) — data-attribute cờ phân loại để JS bật/tắt khối trường -->
    <div class="card mb-3">
      <div class="card-header py-2"><i class="fa fa-folder-open me-1"></i> Danh mục</div>
      <div class="card-body">
<?php if ($categoriesWithFlags === array()): ?>
        <p class="text-muted small mb-0"><em>Chưa có danh mục nào.</em></p>
        <input type="hidden" name="NewsPost[categoryIds][]" value="" />
<?php else: ?>
        <div class="wp-checklist" data-category-checklist>
<?php foreach ($categoriesWithFlags as $catId => $info): ?>
          <div class="form-check">
            <input type="checkbox" class="form-check-input" id="categoryIds_<?php echo $catId; ?>"
                   name="NewsPost[categoryIds][]" value="<?php echo $catId; ?>"
                   data-project-cat="<?php echo $info['is_project']; ?>"
                   data-investor-cat="<?php echo $info['is_investor']; ?>"
                   <?php echo in_array((string) $catId, $selectedCats, true) ? 'checked' : ''; ?> />
            <label class="form-check-label" for="categoryIds_<?php echo $catId; ?>">
              <?php echo CHtml::encode($info['name']); ?>
            </label>
          </div>
<?php endforeach; ?>
        </div>
        <input type="hidden" name="NewsPost[categoryIds][]" value="" />
<?php endif; ?>
        <?php echo $form->error($model, 'categoryIds', array('class' => 'text-danger small mt-1')); ?>
        <p class="form-hint mt-2 mb-0">Bài sẽ hiển thị ở tất cả danh mục được chọn.</p>
      </div>
    </div>

    <!-- Thẻ (Tag) -->
    <div class="card mb-3">
      <div class="card-header py-2"><i class="fa fa-tags me-1"></i> Thẻ (Tag)</div>
      <div class="card-body">
        <?php $renderCheckList('tagIds', $tagOptions, $selectedTags); ?>
      </div>
    </div>

    <!-- Ảnh đại diện -->
    <div class="card mb-3">
      <div class="card-header py-2"><i class="fa fa-image me-1"></i> Ảnh đại diện</div>
      <div class="card-body">
        <div class="media-field" data-media-field>
          <?php echo $form->hiddenField($model, 'thumbnail_media_id', array('data-media-input' => '1')); ?>
          <div class="media-field-preview<?php echo $thumbUrl ? '' : ' is-empty'; ?>" data-media-box>
            <img src="<?php echo CHtml::encode($thumbUrl); ?>" alt="" data-media-img <?php echo $thumbUrl ? '' : 'hidden'; ?> />
            <span class="media-field-empty" data-media-empty <?php echo $thumbUrl ? 'hidden' : ''; ?>>
              <i class="fa fa-image"></i> Chưa chọn ảnh
            </span>
          </div>
          <div class="media-field-actions">
            <button type="button" class="btn btn-sm btn-dsh" data-media-open>
              <i class="fa fa-photo-video me-1"></i> Chọn / Tải ảnh
            </button>
            <button type="button" class="btn btn-sm btn-outline-secondary" data-media-clear <?php echo $thumbUrl ? '' : 'hidden'; ?>>Bỏ chọn</button>
            <span class="media-field-name" data-media-name><?php echo CHtml::encode($thumbName); ?></span>
          </div>
        </div>
      </div>
    </div>

    <!-- Tuỳ chọn hiển thị -->
    <div class="card mb-3">
      <div class="card-header py-2"><i class="fa fa-sliders-h me-1"></i> Tuỳ chọn hiển thị</div>
      <div class="card-body">
        <div class="mb-3">
          <?php echo $form->labelEx($model, 'card_size', array('class' => 'form-label')); ?>
          <?php echo $form->dropDownList($model, 'card_size', NewsPost::cardSizeOptions(), array('class' => 'form-select')); ?>
        </div>
        <div class="mb-3">
          <?php echo $form->labelEx($model, 'date_display_format', array('class' => 'form-label')); ?>
          <?php echo $form->dropDownList($model, 'date_display_format', NewsPost::dateFormatOptions(), array('class' => 'form-select')); ?>
        </div>
        <div class="mb-0">
          <?php echo $form->labelEx($model, 'source_url', array('class' => 'form-label')); ?>
          <?php echo $form->textField($model, 'source_url', array('class' => 'form-control', 'maxlength' => 500, 'placeholder' => 'https://…')); ?>
          <?php echo $form->error($model, 'source_url', array('class' => 'text-danger small mt-1')); ?>
        </div>
      </div>
    </div>

  </div>
</div>

<?php $this->endWidget(); ?>

<!-- Bộ chọn ảnh dùng chung (giống crud/form.php) -->
<div class="modal fade" id="mediaPickerModal" tabindex="-1" aria-hidden="true"
     data-list-url="<?php echo $this->createUrl('/admin/media/listJson'); ?>"
     data-upload-url="<?php echo $this->createUrl('/admin/media/ajaxUpload'); ?>"
     data-csrf-name="<?php echo Yii::app()->request->csrfTokenName; ?>"
     data-csrf-value="<?php echo Yii::app()->request->csrfToken; ?>">
  <div class="modal-dialog modal-xl modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="fa fa-photo-video me-2"></i>Thư viện ảnh</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
      </div>
      <div class="modal-body">
        <div class="media-picker-toolbar">
          <label class="btn btn-dsh btn-sm mb-0">
            <i class="fa fa-upload me-1"></i> Tải ảnh lên
            <input type="file" accept="image/*" multiple hidden data-media-file />
          </label>
          <input type="search" class="form-control form-control-sm media-picker-search"
                 placeholder="Tìm theo tên hoặc mô tả…" data-media-search />
          <span class="media-picker-status" data-media-status></span>
        </div>
        <div class="media-picker-grid" data-media-grid>
          <p class="text-muted m-0 p-3">Đang tải…</p>
        </div>
      </div>
    </div>
  </div>
</div>

<?php
// Cache-busting theo thời điểm sửa file — buộc trình duyệt tải bản mới nhất.
$assetDir = Yii::getPathOfAlias('webroot') . '/admin-assets';
$adminJsV = @filemtime($assetDir . '/admin.js');
$formJsV  = @filemtime($assetDir . '/news-post-form.js');
?>
<!-- TinyMCE (self-host, không CDN) -->
<script src="<?php echo $root; ?>/admin-assets/vendor/tinymce/tinymce.min.js"></script>
<script src="<?php echo $root; ?>/admin-assets/admin.js?v=<?php echo $adminJsV; ?>" defer></script>
<script src="<?php echo $root; ?>/admin-assets/news-post-form.js?v=<?php echo $formJsV; ?>" defer></script>
<script>
  window.DSH_TINYMCE_BASE = '<?php echo $root; ?>/admin-assets/vendor/tinymce';
</script>
