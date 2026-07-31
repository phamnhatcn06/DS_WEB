<?php
/* @var $this MenuController */
/* @var $location MenuLocation */
/* @var $tree array danh sách lồng nhau: array('item'=>MenuItem, 'children'=>[...]) */

$user = Yii::app()->user;
$canCreate = $user->checkAccess('menus.create');
$canUpdate = $user->checkAccess('menus.update');
$canDelete = $user->checkAccess('menus.delete');

/** Render đệ quy một nhánh cây thành markup tương thích Nestable2. */
$renderBranch = function ($branch) use (&$renderBranch, $canUpdate, $canDelete, $that = $this) {
    if (empty($branch)) {
        return;
    }
    echo '<ol class="dd-list">';
    foreach ($branch as $node):
        /** @var MenuItem $item */
        $item = $node['item'];
        $isDivider = $item->isDivider();
        ?>
        <li class="dd-item" data-id="<?php echo (int) $item->id; ?>">
          <div class="dd-handle" title="Kéo để sắp xếp"><i class="bi bi-grip-vertical"></i></div>
          <div class="dd-content d-flex align-items-center gap-2<?php echo $item->is_active ? '' : ' opacity-50'; ?>">
            <span class="dd-title fw-semibold">
              <?php if (!$isDivider && $item->icon): ?><i class="bi <?php echo CHtml::encode($item->icon); ?> me-1"></i><?php endif; ?>
              <?php echo CHtml::encode($item->title); ?>
            </span>

            <?php if ($isDivider): ?>
              <span class="badge bg-info-subtle text-info">divider</span>
            <?php elseif ($item->item_type === MenuItem::TYPE_URL): ?>
              <span class="badge bg-secondary-subtle text-secondary" title="<?php echo CHtml::encode($item->url); ?>">link</span>
            <?php else: ?>
              <code class="small text-muted"><?php echo CHtml::encode($item->route); ?></code>
            <?php endif; ?>

            <?php if ($item->perm): ?>
              <span class="badge bg-warning-subtle text-warning" title="Quyền RBAC"><?php echo CHtml::encode($item->perm); ?></span>
            <?php endif; ?>
            <?php if ($item->is_protected): ?>
              <span class="badge bg-primary-subtle text-primary"><i class="bi bi-shield-lock me-1"></i>bảo vệ</span>
            <?php endif; ?>
            <?php if (!$item->is_active): ?>
              <span class="badge bg-secondary-subtle text-secondary">đã ẩn</span>
            <?php endif; ?>

            <span class="ms-auto btn-group btn-group-sm">
              <?php if ($canUpdate): ?>
                <a class="btn btn-outline-primary" title="Sửa"
                   href="<?php echo $that->createUrl('update', array('id' => $item->id)); ?>">
                  <i class="bi bi-pencil"></i>
                </a>
              <?php endif; ?>

              <?php if ($canUpdate && !$item->is_protected): ?>
                <?php echo CHtml::beginForm($that->createUrl('toggle', array('id' => $item->id)), 'post', array('class' => 'd-inline')); ?>
                  <button class="btn btn-outline-secondary" title="<?php echo $item->is_active ? 'Ẩn' : 'Hiện'; ?>">
                    <i class="bi bi-eye<?php echo $item->is_active ? '-slash' : ''; ?>"></i>
                  </button>
                <?php echo CHtml::endForm(); ?>
              <?php endif; ?>

              <?php if ($canDelete && !$item->is_protected): ?>
                <?php $formId = 'del-item-' . $item->id; ?>
                <?php echo CHtml::beginForm($that->createUrl('delete', array('id' => $item->id)), 'post',
                    array('class' => 'd-inline', 'id' => $formId)); ?>
                  <button type="button" class="btn btn-outline-danger" title="Xoá"
                          onclick="confirmDelete('<?php echo $formId; ?>')">
                    <i class="bi bi-trash"></i>
                  </button>
                <?php echo CHtml::endForm(); ?>
              <?php endif; ?>
            </span>
          </div>
          <?php $renderBranch($node['children']); ?>
        </li>
    <?php endforeach;
    echo '</ol>';
};
?>

<div class="d-flex align-items-center mb-3">
  <a class="btn btn-sm btn-link text-muted ps-0" href="<?php echo $this->createUrl('index'); ?>">
    <i class="bi bi-arrow-left me-1"></i> Vị trí menu
  </a>
  <?php if ($canCreate): ?>
    <a class="btn btn-sm btn-dsh ms-auto" href="<?php echo $this->createUrl('create', array('location' => $location->id)); ?>">
      <i class="bi bi-plus-lg me-1"></i> Thêm mục
    </a>
  <?php endif; ?>
</div>

<div class="card">
  <div class="card-header d-flex align-items-center">
    <span><i class="bi bi-diagram-3 me-1"></i> <?php echo CHtml::encode($location->name); ?></span>
    <span class="badge bg-light text-muted ms-2">tối đa <?php echo (int) $location->max_depth; ?> cấp</span>
  </div>
  <div class="card-body">
    <?php if (empty($tree)): ?>
      <p class="text-muted mb-0">Chưa có mục menu nào. Bấm “Thêm mục” để bắt đầu.</p>
    <?php else: ?>
      <div class="dd" id="menu-tree" data-reorder-url="<?php echo $this->createUrl('reorder', array('id' => $location->id)); ?>">
        <?php $renderBranch($tree); ?>
      </div>
      <p class="form-hint mt-3 mb-0">
        <i class="bi bi-info-circle me-1"></i>
        Kéo thả để sắp xếp và phân cấp sẽ được bật ở bước tiếp theo.
      </p>
    <?php endif; ?>
  </div>
</div>
