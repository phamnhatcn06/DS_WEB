<?php
/* @var $this AdminCrudController */
/* @var $dataProvider CActiveDataProvider */
/* @var $columns array */
/* @var $search string */
/* @var $filters array */
/* @var $activeFilters array */

$filters = isset($filters) ? $filters : array();
$activeFilters = isset($activeFilters) ? $activeFilters : array();
$hasFilter = false;
foreach ($activeFilters as $activeValue) {
    if ($activeValue !== '') { $hasFilter = true; break; }
}

$user = Yii::app()->user;
$resource = $this->getPermissionResource();
$canUpdate = $user->checkAccess($resource . '.update');
$canDelete = $user->checkAccess($resource . '.delete');
$items = $dataProvider->getData();
?>

<div class="card">
  <div class="card-header d-flex flex-wrap gap-2 justify-content-between align-items-center">
    <span><?php echo CHtml::encode($this->getTitlePlural()); ?>
      <span class="badge bg-secondary"><?php echo $dataProvider->getTotalItemCount(); ?></span>
    </span>

    <div class="d-flex gap-2">
      <form method="get" class="d-flex gap-2">
        <input type="search" name="q" class="form-control form-control-sm"
               placeholder="Tìm kiếm…" value="<?php echo CHtml::encode($search); ?>"
               style="min-width:200px" />
        <button class="btn btn-sm btn-outline-secondary" type="submit">
          <i class="fa fa-search"></i>
        </button>
      </form>

      <?php if ($user->checkAccess($resource . '.create')): ?>
        <a class="btn btn-sm btn-dsh" href="<?php echo $this->createUrl('create'); ?>">
          <i class="fa fa-plus"></i> Thêm mới
        </a>
      <?php endif; ?>
    </div>
  </div>

  <?php if ($items === array()): ?>
    <div class="card-body text-center text-muted py-5">
      <i class="fa fa-inbox" style="font-size:2rem"></i>
      <p class="mt-2 mb-0">
        <?php echo $search !== ''
            ? 'Không tìm thấy kết quả nào cho “' . CHtml::encode($search) . '”.'
            : 'Chưa có dữ liệu. Bấm “Thêm mới” để bắt đầu.'; ?>
      </p>
    </div>
  <?php else: ?>
    <div class="table-responsive">
      <table class="table table-hover mb-0 align-middle">
        <thead>
          <tr>
            <?php foreach ($columns as $column): ?>
              <th<?php echo isset($column['width']) ? ' style="width:' . $column['width'] . '"' : ''; ?>>
                <?php echo CHtml::encode($column['label']); ?>
              </th>
            <?php endforeach; ?>
            <th style="width:150px" class="text-end">Thao tác</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach ($items as $item): ?>
          <tr>
            <?php foreach ($columns as $column): ?>
              <td><?php echo $this->renderPartial('admin.views.crud._cell', array(
                  'item'   => $item,
                  'column' => $column,
              ), true); ?></td>
            <?php endforeach; ?>

            <td class="text-end">
              <div class="table-actions">
                <?php if ($this->getSortable() && $canUpdate): ?>
                  <?php echo CHtml::beginForm($this->createUrl('move',
                      array('id' => $item->id, 'dir' => 'up')), 'post'); ?>
                    <button class="btn btn-sm btn-action" title="Lên trên"><i class="fa fa-arrow-up"></i></button>
                  <?php echo CHtml::endForm(); ?>
                  <?php echo CHtml::beginForm($this->createUrl('move',
                      array('id' => $item->id, 'dir' => 'down')), 'post'); ?>
                    <button class="btn btn-sm btn-action" title="Xuống dưới"><i class="fa fa-arrow-down"></i></button>
                  <?php echo CHtml::endForm(); ?>
                <?php endif; ?>

                <?php if ($canUpdate): ?>
                  <a class="btn btn-sm btn-action btn-action--edit" title="Sửa"
                     href="<?php echo $this->createUrl('update', array('id' => $item->id)); ?>">
                    <i class="fa fa-pencil"></i>
                  </a>
                <?php endif; ?>

                <?php if ($canDelete): ?>
                  <?php echo CHtml::beginForm($this->createUrl('delete', array('id' => $item->id)), 'post',
                      array(
                          'data-confirm' => 'Xoá “' . $item->getDisplayName() . '”? '
                              . 'Bản ghi sẽ được đánh dấu đã xoá và không còn hiển thị trên website.',
                      )); ?>
                    <button class="btn btn-sm btn-action btn-action--delete" title="Xoá"><i class="fa fa-trash"></i></button>
                  <?php echo CHtml::endForm(); ?>
                <?php endif; ?>
              </div>
            </td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <?php if ($dataProvider->getPagination()->getPageCount() > 1): ?>
      <div class="card-footer d-flex justify-content-center">
        <?php $this->widget('CLinkPager', array(
            'pages'              => $dataProvider->getPagination(),
            'header'             => '',
            'prevPageLabel'      => '&laquo;',
            'nextPageLabel'      => '&raquo;',
            'firstPageLabel'     => 'Đầu',
            'lastPageLabel'      => 'Cuối',
            'htmlOptions'        => array('class' => 'pagination pagination-sm mb-0'),
            'selectedPageCssClass' => 'active',
            'hiddenPageCssClass' => 'disabled',
        )); ?>
      </div>
    <?php endif; ?>
  <?php endif; ?>
</div>
