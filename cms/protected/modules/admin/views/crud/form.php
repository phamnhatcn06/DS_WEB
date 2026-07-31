<?php
/* @var $this AdminCrudController */
/* @var $model CActiveRecord */
/* @var $fields array */

$isNew = $model->getIsNewRecord();
?>

<?php $form = $this->beginWidget('CActiveForm', array(
    'id' => 'crud-form',
    'enableClientValidation' => false,
    'htmlOptions' => array('data-lock-submit' => '1'),
)); ?>

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
              // Kèm data-url vào từng option để JS xem trước được ảnh.
              $mediaFiles = MediaFile::model()->notDeleted()->findAll(array('order' => 'file_name ASC'));
              $options = array('' => '— Không chọn —');
              $optionAttributes = array();
              foreach ($mediaFiles as $file) {
                  $options[$file->id] = $file->file_name;
                  $optionAttributes[$file->id] = array('data-url' => $file->getPublicUrl());
              }
              $current = $model->$name ? MediaFile::model()->findByPk($model->$name) : null;
              ?>
              <?php echo $form->dropDownList($model, $name, $options, array(
                  'class'            => 'form-select',
                  'options'          => $optionAttributes,
                  'data-media-select' => $name,
              )); ?>
              <img class="field-preview" alt="" data-media-preview="<?php echo $name; ?>"
                   src="<?php echo $current ? $current->getPublicUrl() : ''; ?>"
                   <?php echo $current ? '' : 'hidden'; ?> />
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
      <a class="btn btn-link text-muted ms-auto" href="<?php echo $this->createUrl('index'); ?>">
        Huỷ
      </a>
    </div>
  </div>
</div>

<?php $this->endWidget(); ?>
