<?php
/* @var $this RoleController */
/* @var $roles array */

$user = Yii::app()->user;
$canCreate = $user->checkAccess('roles.create');
$canUpdate = $user->checkAccess('roles.update');
$canDelete = $user->checkAccess('roles.delete');
?>

<div class="card">
  <div class="card-header d-flex flex-wrap gap-2 justify-content-between align-items-center">
    <span>Nhóm quyền
      <span class="badge bg-secondary"><?php echo count($roles); ?></span>
    </span>
    <?php if ($canCreate): ?>
      <a class="btn btn-sm btn-dsh" href="<?php echo $this->createUrl('create'); ?>">
        <i class="fa fa-plus"></i> Thêm nhóm quyền
      </a>
    <?php endif; ?>
  </div>

  <div class="table-responsive">
    <table class="table table-hover mb-0 align-middle">
      <thead>
        <tr>
          <th>Nhóm quyền</th>
          <th style="width:160px">Mã</th>
          <th style="width:120px" class="text-center">Số chức năng</th>
          <th style="width:120px" class="text-center">Người dùng</th>
          <th style="width:130px" class="text-end">Thao tác</th>
        </tr>
      </thead>
      <tbody>
      <?php foreach ($roles as $role): ?>
        <tr>
          <td>
            <span class="fw-semibold"><?php echo CHtml::encode($role['description']); ?></span>
            <?php if ($role['reserved']): ?>
              <span class="badge bg-warning-subtle text-warning-emphasis ms-1">Hệ thống</span>
            <?php endif; ?>
          </td>
          <td><code><?php echo CHtml::encode($role['name']); ?></code></td>
          <td class="text-center"><?php echo (int) $role['permCount']; ?></td>
          <td class="text-center"><?php echo (int) $role['userCount']; ?></td>
          <td class="text-end">
            <div class="table-actions">
              <?php if ($canUpdate && !$role['reserved']): ?>
                <a class="btn btn-sm btn-action btn-action--edit" title="Sửa"
                   href="<?php echo $this->createUrl('update', array('id' => $role['name'])); ?>">
                  <i class="fa fa-pencil"></i>
                </a>
              <?php endif; ?>
              <?php if ($canDelete && !$role['reserved']): ?>
                <?php echo CHtml::beginForm($this->createUrl('delete', array('id' => $role['name'])), 'post',
                    array('data-confirm' => 'Xoá nhóm quyền “' . $role['description'] . '”?')); ?>
                  <button class="btn btn-sm btn-action btn-action--delete" title="Xoá"><i class="fa fa-trash"></i></button>
                <?php echo CHtml::endForm(); ?>
              <?php endif; ?>
              <?php if ($role['reserved']): ?>
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
