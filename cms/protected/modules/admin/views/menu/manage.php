<?php
/* @var $this MenuController */
/* @var $location MenuLocation */
/* @var $tree array danh sách lồng nhau: array('item'=>MenuItem, 'children'=>[...]) */

$user = Yii::app()->user;
$canCreate = $user->checkAccess('menus.create');
$canUpdate = $user->checkAccess('menus.update');
$canDelete = $user->checkAccess('menus.delete');
$canReorder = $user->checkAccess('menus.reorder');
$themeUrl = Yii::app()->theme->baseUrl;

/** Render đệ quy một nhánh cây thành markup tương thích Nestable2. */
$that = $this;
$renderBranch = function ($branch) use (&$renderBranch, $canUpdate, $canDelete, $that) {
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
          <div class="dd-handle" title="Kéo để sắp xếp"><i class="fa fa-ellipsis-v"></i></div>
          <div class="dd-content d-flex align-items-center gap-2<?php echo $item->is_active ? '' : ' opacity-50'; ?>">
            <span class="dd-title fw-semibold">
              <?php if (!$isDivider && $item->icon): ?><i class="fa <?php echo CHtml::encode($item->icon); ?> me-1"></i><?php endif; ?>
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
              <span class="badge bg-primary-subtle text-primary"><i class="fa fa-shield me-1"></i>bảo vệ</span>
            <?php endif; ?>
            <?php if (!$item->is_active): ?>
              <span class="badge bg-secondary-subtle text-secondary">đã ẩn</span>
            <?php endif; ?>

            <span class="ms-auto btn-group btn-group-sm">
              <?php if ($canUpdate): ?>
                <a class="btn btn-outline-primary" title="Sửa"
                   href="<?php echo $that->createUrl('update', array('id' => $item->id)); ?>">
                  <i class="fa fa-pencil"></i>
                </a>
              <?php endif; ?>

              <?php if ($canUpdate && !$item->is_protected): ?>
                <?php echo CHtml::beginForm($that->createUrl('toggle', array('id' => $item->id)), 'post', array('class' => 'd-inline')); ?>
                  <button class="btn btn-outline-secondary" title="<?php echo $item->is_active ? 'Ẩn' : 'Hiện'; ?>">
                    <i class="fa fa-eye<?php echo $item->is_active ? '-slash' : ''; ?>"></i>
                  </button>
                <?php echo CHtml::endForm(); ?>
              <?php endif; ?>

              <?php if ($canDelete && !$item->is_protected): ?>
                <?php $formId = 'del-item-' . $item->id; ?>
                <?php echo CHtml::beginForm($that->createUrl('delete', array('id' => $item->id)), 'post',
                    array('class' => 'd-inline', 'id' => $formId)); ?>
                  <button type="button" class="btn btn-outline-danger" title="Xoá"
                          onclick="confirmDelete('<?php echo $formId; ?>')">
                    <i class="fa fa-trash"></i>
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

<link rel="stylesheet" href="<?php echo $themeUrl; ?>/assets/vendor/nestable/jquery.nestable.min.css" />
<style>
  /* Ghi đè Nestable2: grip nhỏ bên trái, nội dung + nút bấm bên phải. */
  #menu-tree.dd { max-width: 100%; font-size: 14px; }
  #menu-tree .dd-item { margin: 6px 0; }
  #menu-tree .dd-handle {
    display: inline-flex; align-items: center; justify-content: center;
    width: 34px; height: auto; min-height: 42px; margin: 0; padding: 0;
    color: #9aa0a6; background: #f6f7f9; border: 1px solid var(--bs-border-color, #dee2e6);
    border-right: 0; border-radius: 6px 0 0 6px; cursor: grab; font-weight: 400;
  }
  #menu-tree .dd-handle:hover { color: var(--bs-primary, #9a1220); background: #eef0f2; }
  #menu-tree .dd-item > .dd-handle + .dd-content {
    display: flex; align-items: center; gap: .5rem; min-height: 42px;
    padding: .35rem .75rem; background: #fff;
    border: 1px solid var(--bs-border-color, #dee2e6); border-radius: 0 6px 6px 0;
  }
  /* Gộp handle + content thành một hàng ngang. */
  #menu-tree .dd-item > .dd-handle { float: left; }
  #menu-tree .dd-item > .dd-content { overflow: hidden; }
  #menu-tree .dd-placeholder, #menu-tree .dd-empty {
    min-height: 42px; margin: 6px 0; background: rgba(154,18,32,.06);
    border: 1px dashed var(--bs-primary, #9a1220); border-radius: 6px;
  }
  #menu-tree .dd-dragel > .dd-item .dd-content { box-shadow: 0 6px 16px rgba(0,0,0,.15); }
  #menu-tree.dd-dragging .dd-handle { cursor: grabbing; }
</style>

<div class="d-flex align-items-center mb-3">
  <a class="btn btn-sm btn-link text-muted ps-0" href="<?php echo $this->createUrl('index'); ?>">
    <i class="fa fa-arrow-left me-1"></i> Vị trí menu
  </a>
  <?php if ($canCreate): ?>
    <a class="btn btn-sm btn-dsh ms-auto" href="<?php echo $this->createUrl('create', array('location' => $location->id)); ?>">
      <i class="fa fa-plus me-1"></i> Thêm mục
    </a>
  <?php endif; ?>
</div>

<div class="card">
  <div class="card-header d-flex align-items-center">
    <span><i class="fa fa-sitemap me-1"></i> <?php echo CHtml::encode($location->name); ?></span>
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
        <i class="fa fa-info-circle me-1"></i>
        Kéo biểu tượng <i class="fa fa-ellipsis-v"></i> để sắp xếp và lồng cấp
        (tối đa <?php echo (int) $location->max_depth; ?> cấp). Thứ tự được lưu tự động.
      </p>
    <?php endif; ?>
  </div>
</div>

<?php if ($canReorder): ?>
<script>
  // Kéo thả cây menu bằng Nestable2. jQuery + libs nạp ở CUỐI body (layout),
  // nên đợi 'load' rồi mới nạp động plugin để chắc chắn $ đã sẵn sàng.
  window.addEventListener('load', function () {
    if (typeof window.jQuery === 'undefined') { return; }
    var $ = window.jQuery;

    var script = document.createElement('script');
    script.src = '<?php echo $themeUrl; ?>/assets/vendor/nestable/jquery.nestable.min.js';
    script.onload = function () { initMenuNestable($); };
    document.body.appendChild(script);
  });

  function initMenuNestable($) {
    var $tree = $('#menu-tree');
    if (!$tree.length || typeof $.fn.nestable === 'undefined') { return; }

    var reorderUrl = $tree.data('reorder-url');
    var csrfToken = '<?php echo Yii::app()->request->csrfToken; ?>';
    var csrfName = '<?php echo Yii::app()->request->csrfTokenName; ?>';
    var saving = false;

    $tree.nestable({ maxDepth: <?php echo (int) $location->max_depth; ?>, handleClass: 'dd-handle' });

    // Toast gọn dùng SweetAlert2 (đã nạp ở layout).
    function toast(icon, title) {
      if (typeof Swal === 'undefined') { return; }
      Swal.fire({ toast: true, position: 'top-end', icon: icon, title: title,
        showConfirmButton: false, timer: 2200, timerProgressBar: true });
    }

    $tree.on('change', function () {
      if (saving) { return; }
      saving = true;
      var data = { tree: JSON.stringify($tree.nestable('serialize')) };
      data[csrfName] = csrfToken;

      $.ajax({ url: reorderUrl, method: 'POST', dataType: 'json', data: data })
        .done(function (res) {
          if (res && res.success) { toast('success', res.message || 'Đã lưu thứ tự.'); }
          else { toast('error', (res && res.message) || 'Lưu thất bại.'); setTimeout(reload, 1200); }
        })
        .fail(function () { toast('error', 'Lỗi kết nối, đang tải lại…'); setTimeout(reload, 1200); })
        .always(function () { saving = false; });
    });

    function reload() { window.location.reload(); }
  }
</script>
<?php endif; ?>
