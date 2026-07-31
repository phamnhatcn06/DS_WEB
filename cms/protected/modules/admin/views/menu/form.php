<?php
/* @var $this MenuController */
/* @var $model MenuItem */
/* @var $location MenuLocation */

$isNew = $model->getIsNewRecord();
$perms = $this->permOptions();
$parents = $this->parentOptions($model, $location);
$routes = $this->routeSuggestions($location->id);
?>

<div class="d-flex align-items-center mb-3">
  <a class="btn btn-sm btn-link text-muted ps-0" href="<?php echo $this->createUrl('manage', array('id' => $location->id)); ?>">
    <i class="fa fa-arrow-left me-1"></i> Về cây menu
  </a>
</div>

<?php $form = $this->beginWidget('CActiveForm', array(
    'id' => 'menu-item-form',
    'enableClientValidation' => false,
)); ?>

<div class="card">
  <div class="card-header">
    <?php echo $isNew ? 'Thêm mục menu' : 'Chỉnh sửa mục menu'; ?>
    — <span class="text-muted"><?php echo CHtml::encode($location->name); ?></span>
  </div>

  <div class="card-body">
    <?php if ($model->hasErrors()): ?>
      <div class="alert alert-danger">
        <strong>Chưa lưu được. Vui lòng kiểm tra lại:</strong>
        <?php echo $form->errorSummary($model, '', '', array('class' => 'mb-0 mt-2')); ?>
      </div>
    <?php endif; ?>

    <div class="row g-3">
      <div class="col-12 col-lg-8">
        <?php echo $form->labelEx($model, 'title', array('class' => 'form-label')); ?>
        <?php echo $form->textField($model, 'title', array('class' => 'form-control', 'maxlength' => 200)); ?>
        <?php echo $form->error($model, 'title', array('class' => 'text-danger small mt-1')); ?>
      </div>

      <div class="col-12 col-lg-4">
        <?php echo $form->labelEx($model, 'item_type', array('class' => 'form-label')); ?>
        <?php echo $form->dropDownList($model, 'item_type', MenuItem::typeOptions(),
            array('class' => 'form-select', 'data-menu-type' => '1')); ?>
      </div>

      <!-- Route (khi item_type = route) -->
      <div class="col-12 col-lg-8" data-when-type="route">
        <?php echo $form->labelEx($model, 'route', array('class' => 'form-label')); ?>
        <?php echo $form->textField($model, 'route', array(
            'class' => 'form-control', 'maxlength' => 200, 'list' => 'route-suggestions',
            'placeholder' => '/admin/project/index')); ?>
        <datalist id="route-suggestions">
          <?php foreach ($routes as $r): ?><option value="<?php echo CHtml::encode($r); ?>"></option><?php endforeach; ?>
        </datalist>
        <?php echo $form->error($model, 'route', array('class' => 'text-danger small mt-1')); ?>
      </div>

      <!-- URL (khi item_type = url) -->
      <div class="col-12 col-lg-8" data-when-type="url">
        <?php echo $form->labelEx($model, 'url', array('class' => 'form-label')); ?>
        <?php echo $form->textField($model, 'url', array(
            'class' => 'form-control', 'maxlength' => 500, 'placeholder' => 'https://...')); ?>
        <?php echo $form->error($model, 'url', array('class' => 'text-danger small mt-1')); ?>
      </div>

      <div class="col-6 col-lg-4" data-when-type="url">
        <?php echo $form->labelEx($model, 'target', array('class' => 'form-label')); ?>
        <?php echo $form->dropDownList($model, 'target', MenuItem::targetOptions(), array('class' => 'form-select')); ?>
      </div>

      <div class="col-6 col-lg-4" data-when-type="route url">
        <?php echo $form->labelEx($model, 'icon', array('class' => 'form-label')); ?>
        <?php echo $form->textField($model, 'icon', array(
            'class' => 'form-control', 'maxlength' => 60, 'placeholder' => 'fa-building')); ?>
        <p class="form-hint mt-1 mb-0">Class Bootstrap Icons, ví dụ <code>fa-building</code>.</p>
      </div>

      <div class="col-12 col-lg-6" data-when-type="route url">
        <?php echo $form->labelEx($model, 'perm', array('class' => 'form-label')); ?>
        <?php echo $form->dropDownList($model, 'perm', $perms, array('class' => 'form-select')); ?>
        <p class="form-hint mt-1 mb-0">Mục chỉ hiện với người có quyền này.</p>
      </div>

      <div class="col-12 col-lg-6">
        <?php echo $form->labelEx($model, 'parent_id', array('class' => 'form-label')); ?>
        <?php echo $form->dropDownList($model, 'parent_id', $parents, array('class' => 'form-select')); ?>
        <?php echo $form->error($model, 'parent_id', array('class' => 'text-danger small mt-1')); ?>
      </div>

      <div class="col-12 col-lg-6" data-when-type="route url">
        <?php echo $form->labelEx($model, 'css_class', array('class' => 'form-label')); ?>
        <?php echo $form->textField($model, 'css_class', array('class' => 'form-control', 'maxlength' => 120)); ?>
      </div>

      <div class="col-12">
        <div class="form-check">
          <?php echo $form->checkBox($model, 'is_active', array('class' => 'form-check-input')); ?>
          <?php echo $form->label($model, 'is_active', array('class' => 'form-check-label')); ?>
        </div>
      </div>
    </div>
  </div>

  <div class="card-body border-top">
    <div class="form-actions d-flex gap-2">
      <button type="submit" class="btn btn-dsh">
        <i class="fa fa-check me-1"></i> Lưu
      </button>
      <a class="btn btn-link text-muted ms-auto"
         href="<?php echo $this->createUrl('manage', array('id' => $location->id)); ?>">Huỷ</a>
    </div>
  </div>
</div>

<?php $this->endWidget(); ?>

<script>
  // Ẩn/hiện các trường theo loại mục đang chọn (route / url / divider).
  (function () {
    var select = document.querySelector('[data-menu-type]');
    if (!select) return;
    function apply() {
      var type = select.value;
      document.querySelectorAll('[data-when-type]').forEach(function (el) {
        var types = el.getAttribute('data-when-type').split(' ');
        el.style.display = types.indexOf(type) !== -1 ? '' : 'none';
      });
    }
    select.addEventListener('change', apply);
    apply();
  })();
</script>
