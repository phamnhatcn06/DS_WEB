<?php
/* @var $this FeatureController */
/* @var $form FeatureForm */
/* @var $isNew bool */

$selected = array_flip((array) $form->actions);
$reserved = in_array($form->code, FeatureForm::$reserved, true);

$aform = $this->beginWidget('CActiveForm', array(
    'id' => 'feature-form',
    'enableClientValidation' => false,
));
?>

<div class="card">
  <div class="card-header"><?php echo $isNew ? 'Thêm chức năng' : 'Chỉnh sửa chức năng'; ?></div>

  <div class="card-body">
    <?php if ($form->hasErrors()): ?>
      <div class="alert alert-danger">
        <strong>Chưa lưu được. Vui lòng kiểm tra lại:</strong>
        <?php echo $aform->errorSummary($form, '', '', array('class' => 'mb-0 mt-2')); ?>
      </div>
    <?php endif; ?>

    <div class="row g-3 mb-4">
      <div class="col-12 col-lg-4">
        <?php echo $aform->labelEx($form, 'code', array('class' => 'form-label')); ?>
        <?php if ($isNew): ?>
          <?php echo $aform->textField($form, 'code', array(
              'class' => 'form-control', 'placeholder' => 'vd: projects')); ?>
          <div class="form-text">Chỉ chữ thường, số, gạch dưới. Không đổi được sau khi tạo.</div>
        <?php else: ?>
          <input type="text" class="form-control" value="<?php echo CHtml::encode($form->code); ?>" readonly>
          <?php echo $aform->hiddenField($form, 'code'); ?>
        <?php endif; ?>
      </div>
      <div class="col-12 col-lg-8">
        <?php echo $aform->labelEx($form, 'label', array('class' => 'form-label')); ?>
        <?php echo $aform->textField($form, 'label', array(
            'class' => 'form-control', 'placeholder' => 'vd: Dự án')); ?>
      </div>
    </div>

    <label class="form-label d-block">Hành động</label>
    <?php if ($reserved): ?>
      <div class="alert alert-warning py-2 small">
        Đây là chức năng hệ thống. Bạn có thể đổi nhãn hoặc bổ sung hành động,
        nhưng nên giữ các hành động sẵn có để không mất quyền truy cập quản trị.
      </div>
    <?php endif; ?>
    <div class="row row-cols-2 row-cols-md-3 row-cols-lg-5 g-2">
      <?php foreach (FeatureForm::$standardActions as $action => $label): ?>
        <div class="col">
          <div class="form-check">
            <input type="checkbox" class="form-check-input"
                   id="action-<?php echo $action; ?>"
                   name="FeatureForm[actions][]"
                   value="<?php echo $action; ?>"
                   <?php echo isset($selected[$action]) ? 'checked' : ''; ?>>
            <label class="form-check-label" for="action-<?php echo $action; ?>">
              <?php echo CHtml::encode($label); ?>
              <span class="text-muted small d-block"><?php echo $action; ?></span>
            </label>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
    <div class="form-text mt-2">
      Mỗi hành động tạo một quyền dạng <code>mã.hành_động</code> (vd <code>projects.view</code>),
      dùng lại trong ma trận nhóm quyền và dropdown phân quyền của menu.
    </div>
  </div>

  <div class="card-footer d-flex gap-2">
    <button type="submit" class="btn btn-dsh"><i class="fa fa-save me-1"></i>Lưu</button>
    <a class="btn btn-outline-secondary" href="<?php echo $this->createUrl('index'); ?>">Huỷ</a>
  </div>
</div>

<?php $this->endWidget(); ?>
