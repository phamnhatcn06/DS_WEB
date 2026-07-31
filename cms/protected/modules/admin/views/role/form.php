<?php
/* @var $this RoleController */
/* @var $form RoleForm */
/* @var $matrix array */
/* @var $isNew bool */

$selected = array_flip((array) $form->permissions);

// Cột hành động = hợp của mọi hành động, theo thứ tự ưu tiên.
$order = array('view' => 'Xem', 'create' => 'Thêm', 'update' => 'Sửa',
    'delete' => 'Xoá', 'reorder' => 'Sắp xếp');
$columns = array();
foreach ($matrix as $res) {
    foreach ($res['actions'] as $opName => $opLabel) {
        $dot = strpos($opName, '.');
        $action = $dot === false ? $opName : substr($opName, $dot + 1);
        if (!isset($columns[$action])) {
            $columns[$action] = isset($order[$action]) ? $order[$action] : $opLabel;
        }
    }
}
// Sắp cột theo thứ tự ưu tiên.
uksort($columns, function ($a, $b) use ($order) {
    $ia = array_search($a, array_keys($order));
    $ib = array_search($b, array_keys($order));
    $ia = $ia === false ? 99 : $ia;
    $ib = $ib === false ? 99 : $ib;
    return $ia - $ib;
});

$aform = $this->beginWidget('CActiveForm', array(
    'id' => 'role-form',
    'enableClientValidation' => false,
));
?>

<div class="card">
  <div class="card-header"><?php echo $isNew ? 'Thêm nhóm quyền' : 'Chỉnh sửa nhóm quyền'; ?></div>

  <div class="card-body">
    <?php if ($form->hasErrors()): ?>
      <div class="alert alert-danger">
        <strong>Chưa lưu được. Vui lòng kiểm tra lại:</strong>
        <?php echo $aform->errorSummary($form, '', '', array('class' => 'mb-0 mt-2')); ?>
      </div>
    <?php endif; ?>

    <div class="row g-3 mb-4">
      <div class="col-12 col-lg-4">
        <?php echo $aform->labelEx($form, 'name', array('class' => 'form-label')); ?>
        <?php if ($isNew): ?>
          <?php echo $aform->textField($form, 'name', array(
              'class' => 'form-control', 'placeholder' => 'vd: content_editor')); ?>
          <div class="form-text">Chỉ chữ thường, số, gạch dưới. Không đổi được sau khi tạo.</div>
        <?php else: ?>
          <input type="text" class="form-control" value="<?php echo CHtml::encode($form->name); ?>" readonly>
          <?php echo $aform->hiddenField($form, 'name'); ?>
        <?php endif; ?>
      </div>
      <div class="col-12 col-lg-8">
        <?php echo $aform->labelEx($form, 'description', array('class' => 'form-label')); ?>
        <?php echo $aform->textField($form, 'description', array(
            'class' => 'form-control', 'placeholder' => 'vd: Biên tập nội dung')); ?>
      </div>
    </div>

    <label class="form-label d-block">Danh sách chức năng</label>
    <div class="table-responsive">
      <table class="table table-bordered table-sm align-middle mb-0" id="perm-matrix">
        <thead>
          <tr>
            <th style="min-width:220px">Tài nguyên</th>
            <?php foreach ($columns as $action => $label): ?>
              <th class="text-center" style="width:90px">
                <?php echo CHtml::encode($label); ?><br>
                <input type="checkbox" class="form-check-input col-toggle mt-1"
                       data-action="<?php echo CHtml::encode($action); ?>" title="Chọn cả cột">
              </th>
            <?php endforeach; ?>
            <th class="text-center" style="width:70px">Tất cả</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach ($matrix as $resource => $data): ?>
          <?php
          // Map action → tên operation cho tài nguyên này.
          $opByAction = array();
          foreach ($data['actions'] as $opName => $opLabel) {
              $dot = strpos($opName, '.');
              $opByAction[$dot === false ? $opName : substr($opName, $dot + 1)] = $opName;
          }
          ?>
          <tr>
            <td><?php echo CHtml::encode($data['label']); ?>
              <span class="text-muted small">(<?php echo CHtml::encode($resource); ?>)</span></td>
            <?php foreach ($columns as $action => $label): ?>
              <td class="text-center">
                <?php if (isset($opByAction[$action])): $op = $opByAction[$action]; ?>
                  <input type="checkbox" class="form-check-input perm-check"
                         name="RoleForm[permissions][]"
                         value="<?php echo CHtml::encode($op); ?>"
                         data-action="<?php echo CHtml::encode($action); ?>"
                         <?php echo isset($selected[$op]) ? 'checked' : ''; ?>>
                <?php else: ?>
                  <span class="text-muted">—</span>
                <?php endif; ?>
              </td>
            <?php endforeach; ?>
            <td class="text-center">
              <input type="checkbox" class="form-check-input row-toggle" title="Chọn cả hàng">
            </td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

  <div class="card-footer d-flex gap-2">
    <button type="submit" class="btn btn-dsh"><i class="fa fa-save me-1"></i>Lưu</button>
    <a class="btn btn-outline-secondary" href="<?php echo $this->createUrl('index'); ?>">Huỷ</a>
  </div>
</div>

<?php $this->endWidget(); ?>

<script>
(function () {
  var table = document.getElementById('perm-matrix');
  if (!table) return;

  // Chọn cả hàng.
  table.querySelectorAll('.row-toggle').forEach(function (toggle) {
    var row = toggle.closest('tr');
    var checks = row.querySelectorAll('.perm-check');
    syncRowToggle(row);
    toggle.addEventListener('change', function () {
      checks.forEach(function (c) { c.checked = toggle.checked; });
    });
  });

  // Chọn cả cột.
  table.querySelectorAll('.col-toggle').forEach(function (toggle) {
    var action = toggle.getAttribute('data-action');
    var checks = table.querySelectorAll('.perm-check[data-action="' + action + '"]');
    toggle.addEventListener('change', function () {
      checks.forEach(function (c) { c.checked = toggle.checked; });
    });
  });

  // Cập nhật lại toggle hàng khi tick lẻ.
  table.querySelectorAll('.perm-check').forEach(function (check) {
    check.addEventListener('change', function () {
      syncRowToggle(check.closest('tr'));
    });
  });

  function syncRowToggle(row) {
    var checks = row.querySelectorAll('.perm-check');
    var toggle = row.querySelector('.row-toggle');
    if (!toggle || checks.length === 0) return;
    var allChecked = Array.prototype.every.call(checks, function (c) { return c.checked; });
    toggle.checked = allChecked;
  }
})();
</script>
