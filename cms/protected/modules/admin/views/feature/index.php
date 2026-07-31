<?php
/* @var $this FeatureController */
/* @var $features array */

$user = Yii::app()->user;
$canCreate = $user->checkAccess('features.create');
$canUpdate = $user->checkAccess('features.update');
$canDelete = $user->checkAccess('features.delete');
$actionLabels = FeatureForm::$standardActions;
?>

<div class="card">
  <div class="card-header d-flex flex-wrap gap-2 justify-content-between align-items-center">
    <span>Danh mục chức năng
      <span class="badge bg-secondary"><?php echo count($features); ?></span>
    </span>
    <?php if ($canCreate): ?>
      <a class="btn btn-sm btn-dsh" href="<?php echo $this->createUrl('create'); ?>">
        <i class="fa fa-plus"></i> Thêm chức năng
      </a>
    <?php endif; ?>
  </div>

  <div class="table-responsive">
    <table class="table table-hover mb-0 align-middle">
      <thead>
        <tr>
          <th>Chức năng</th>
          <th style="width:160px">Mã</th>
          <th>Hành động</th>
          <th style="width:110px" class="text-center">Nhóm quyền</th>
          <th style="width:110px" class="text-end">Thao tác</th>
        </tr>
      </thead>
      <tbody>
      <?php foreach ($features as $feature): ?>
        <tr>
          <td>
            <span class="fw-semibold"><?php echo CHtml::encode($feature['label']); ?></span>
            <?php if ($feature['reserved']): ?>
              <span class="badge bg-warning-subtle text-warning-emphasis ms-1">Hệ thống</span>
            <?php endif; ?>
          </td>
          <td><code><?php echo CHtml::encode($feature['code']); ?></code></td>
          <td>
            <?php foreach ($feature['actions'] as $action => $opName): ?>
              <span class="badge bg-light text-dark border me-1 mb-1">
                <?php echo CHtml::encode(isset($actionLabels[$action]) ? $actionLabels[$action] : $action); ?>
              </span>
            <?php endforeach; ?>
          </td>
          <td class="text-center"><?php echo (int) $feature['roleCount']; ?></td>
          <td class="text-end">
            <div class="table-actions">
              <?php if ($canUpdate): ?>
                <a class="btn btn-sm btn-action btn-action--edit" title="Sửa"
                   href="<?php echo $this->createUrl('update', array('id' => $feature['code'])); ?>">
                  <i class="fa fa-pencil"></i>
                </a>
              <?php endif; ?>
              <?php if ($canDelete && !$feature['reserved']): ?>
                <?php $formId = 'del-feature-' . CHtml::encode($feature['code']); ?>
                <?php echo CHtml::beginForm($this->createUrl('delete', array('id' => $feature['code'])), 'post',
                    array('id' => $formId)); ?>
                  <button type="button" class="btn btn-sm btn-action btn-action--delete" title="Xoá"
                          onclick="confirmDelete('<?php echo $formId; ?>')"><i class="fa fa-trash"></i></button>
                <?php echo CHtml::endForm(); ?>
              <?php elseif ($feature['reserved']): ?>
                <span class="text-muted small">—</span>
              <?php endif; ?>
            </div>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
