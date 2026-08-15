<?php
/* @var $this AdminCrudController */
/* @var $model CActiveRecord */
/* @var $fields array */

$isNew = $model->getIsNewRecord();

// URL trở về danh sách đã lọc — nhận từ query (lúc mở form) hoặc POST (khi
// submit lỗi validate và render lại). Rỗng nếu vào form không qua danh sách.
$returnUrl = trim((string) Yii::app()->request->getParam('return', ''));
?>

<?php $form = $this->beginWidget('CActiveForm', array(
    'id' => 'crud-form',
    'enableClientValidation' => false,
    'htmlOptions' => array('data-lock-submit' => '1'),
)); ?>

<?php if ($returnUrl !== ''): ?>
  <input type="hidden" name="return" value="<?php echo CHtml::encode($returnUrl); ?>" />
<?php endif; ?>

<div class="card">
  <div class="card-header">
    <?php echo $isNew ? 'Thêm mới' : 'Chỉnh sửa'; ?>
    <?php echo CHtml::encode(mb_strtolower($this->getTitleSingular(), 'UTF-8')); ?>
  </div>

  <div class="card-body">

    <?php if ($model->hasErrors()): ?>
      <div class="alert alert-danger">
        <strong>Chưa lưu được. Vui lòng kiểm tra lại:</strong>
        <?php echo $form->errorSummary($model, '', '', array('class' => 'mb-0 mt-2')); ?>
      </div>
    <?php endif; ?>

    <div class="row g-3">
      <?php foreach ($fields as $field): ?>
        <?php
        $name  = $field['name'];
        $type  = isset($field['type']) ? $field['type'] : 'text';
        $width = isset($field['width']) ? $field['width'] : 12;
        ?>
        <div class="col-12 col-lg-<?php echo $width; ?>">

          <?php if ($type !== 'checkbox'): ?>
            <?php echo $form->labelEx($model, $name, array('class' => 'form-label')); ?>
          <?php endif; ?>

          <?php switch ($type):
            case 'textarea': ?>
              <?php echo $form->textArea($model, $name, array(
                  'class' => 'form-control',
                  'rows'  => isset($field['rows']) ? $field['rows'] : 3,
              )); ?>
              <?php break; ?>

            <?php case 'number': ?>
              <?php echo $form->numberField($model, $name, array(
                  'class' => 'form-control',
                  'step'  => isset($field['step']) ? $field['step'] : '1',
              )); ?>
              <?php break; ?>

            <?php case 'select': ?>
              <?php echo $form->dropDownList($model, $name, $field['options'], array(
                  'class' => 'form-select',
                  'empty' => isset($field['empty']) ? $field['empty'] : null,
              )); ?>
              <?php break; ?>

            <?php case 'media': ?>
              <?php
              $current = $model->$name ? MediaFile::model()->findByPk($model->$name) : null;
              $currentUrl = $current ? $current->getPublicUrl() : '';
              $currentName = $current ? $current->file_name : '';
              ?>
              <div class="media-field" data-media-field>
                <?php echo $form->hiddenField($model, $name, array('data-media-input' => '1')); ?>
                <div class="media-field-preview<?php echo $currentUrl ? '' : ' is-empty'; ?>" data-media-box>
                  <img src="<?php echo CHtml::encode($currentUrl); ?>" alt="" data-media-img
                       <?php echo $currentUrl ? '' : 'hidden'; ?> />
                  <span class="media-field-empty" data-media-empty <?php echo $currentUrl ? 'hidden' : ''; ?>>
                    <i class="fa fa-image"></i> Chưa chọn ảnh
                  </span>
                </div>
                <div class="media-field-actions">
                  <button type="button" class="btn btn-sm btn-dsh" data-media-open>
                    <i class="fa fa-photo-video me-1"></i> Chọn / Tải ảnh
                  </button>
                  <button type="button" class="btn btn-sm btn-outline-secondary" data-media-clear
                          <?php echo $currentUrl ? '' : 'hidden'; ?>>Bỏ chọn</button>
                  <span class="media-field-name" data-media-name><?php echo CHtml::encode($currentName); ?></span>
                </div>
              </div>
              <?php break; ?>

            <?php case 'checkbox': ?>
              <div class="form-check mt-lg-4">
                <?php echo $form->checkBox($model, $name, array('class' => 'form-check-input')); ?>
                <?php echo $form->label($model, $name, array('class' => 'form-check-label')); ?>
              </div>
              <?php break; ?>

            <?php case 'date': ?>
              <?php echo $form->textField($model, $name, array(
                  'class' => 'form-control',
                  'type'  => 'date',
              )); ?>
              <?php break; ?>

            <?php case 'datetime': ?>
              <?php
              // Input datetime-local cần định dạng Y-m-d\TH:i
              $raw = $model->$name;
              $value = $raw ? date('Y-m-d\TH:i', strtotime($raw)) : '';
              ?>
              <?php echo CHtml::activeTextField($model, $name, array(
                  'class' => 'form-control',
                  'type'  => 'datetime-local',
                  'value' => $value,
              )); ?>
              <?php break; ?>

            <?php case 'password': ?>
              <?php echo $form->passwordField($model, $name, array(
                  'class'        => 'form-control',
                  'autocomplete' => 'new-password',
              )); ?>
              <?php break; ?>

            <?php case 'checkboxlist': ?>
              <?php
              $modelName = get_class($model);
              $options = isset($field['options']) ? $field['options'] : array();
              $selectedValues = array_map('strval', (array) $model->$name);
              ?>
              <?php if ($options === array()): ?>
                <p class="form-hint mb-0"><em>Chưa có mục nào để chọn.</em></p>
              <?php else: ?>
                <div class="d-flex flex-wrap gap-3 pt-1">
                  <?php foreach ($options as $value => $label): ?>
                    <div class="form-check">
                      <?php $inputId = $name . '_' . $value; ?>
                      <input type="checkbox" class="form-check-input" id="<?php echo $inputId; ?>"
                             name="<?php echo $modelName . '[' . $name . '][]'; ?>"
                             value="<?php echo CHtml::encode($value); ?>"
                             <?php echo in_array((string) $value, $selectedValues, true) ? 'checked' : ''; ?> />
                      <label class="form-check-label" for="<?php echo $inputId; ?>">
                        <?php echo CHtml::encode($label); ?>
                      </label>
                    </div>
                  <?php endforeach; ?>
                </div>
              <?php endif; ?>
              <?php // Luôn gửi kèm giá trị rỗng để khi bỏ chọn hết vẫn ghi nhận được. ?>
              <input type="hidden" name="<?php echo $modelName . '[' . $name . '][]'; ?>" value="" />
              <?php break; ?>

            <?php default: ?>
              <?php echo $form->textField($model, $name, array(
                  'class'       => 'form-control',
                  'maxlength'   => isset($field['maxlength']) ? $field['maxlength'] : 255,
                  'placeholder' => isset($field['placeholder']) ? $field['placeholder'] : '',
              )); ?>
          <?php endswitch; ?>

          <?php if (isset($field['hint'])): ?>
            <p class="form-hint mt-1 mb-0"><?php echo $field['hint']; ?></p>
          <?php endif; ?>

          <?php echo $form->error($model, $name, array('class' => 'text-danger small mt-1')); ?>
        </div>
      <?php endforeach; ?>
    </div>

    <div class="form-actions">
      <button type="submit" class="btn btn-dsh">
        <i class="fa fa-check me-1"></i> Lưu
      </button>
      <button type="submit" name="save_and_continue" value="1" class="btn btn-outline-secondary">
        Lưu và tiếp tục sửa
      </button>
      <a class="btn btn-link text-muted ms-auto"
         href="<?php echo $returnUrl !== '' ? CHtml::encode($returnUrl) : $this->createUrl('index'); ?>">
        Huỷ
      </a>
    </div>
  </div>
</div>

<?php $this->endWidget(); ?>

<?php
// Bộ chọn ảnh dùng chung: chỉ nhúng khi form có ít nhất một trường media.
$hasMediaField = false;
foreach ($fields as $field) {
    if (isset($field['type']) && $field['type'] === 'media') {
        $hasMediaField = true;
        break;
    }
}
?>
<?php if ($hasMediaField): ?>
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
  <script src="<?php echo Yii::app()->baseUrl; ?>/admin-assets/admin.js" defer></script>
<?php endif; ?>
