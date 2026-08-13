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

    <div class="d-flex gap-2 align-items-center">
      <form method="get" class="d-flex gap-2 align-items-center">
        <?php foreach ($filters as $filter): ?>
          <?php $selected = isset($activeFilters[$filter['name']]) ? $activeFilters[$filter['name']] : ''; ?>
          <select name="<?php echo CHtml::encode($filter['name']); ?>"
                  class="form-select form-select-sm w-auto" style="min-width:160px"
                  aria-label="<?php echo CHtml::encode($filter['label']); ?>"
                  onchange="this.form.submit()">
            <option value=""><?php echo CHtml::encode(
                isset($filter['all']) ? $filter['all'] : 'Tất cả'); ?></option>
            <?php foreach ($filter['options'] as $value => $label): ?>
              <option value="<?php echo CHtml::encode($value); ?>"
                <?php echo ((string) $selected === (string) $value) ? 'selected' : ''; ?>>
                <?php echo CHtml::encode($label); ?>
              </option>
            <?php endforeach; ?>
          </select>
        <?php endforeach; ?>

        <input type="search" name="q" class="form-control form-control-sm"
               placeholder="Tìm theo tiêu đề…" value="<?php echo CHtml::encode($search); ?>"
               style="min-width:180px" />
        <button class="btn btn-sm btn-outline-secondary" type="submit">
          <i class="fa fa-search"></i>
        </button>
        <?php if ($search !== '' || $hasFilter): ?>
          <a class="btn btn-sm btn-outline-secondary" href="<?php echo $this->createUrl('index'); ?>"
             title="Xoá bộ lọc">
            <i class="fa fa-times"></i>
          </a>
        <?php endif; ?>
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
        <?php if ($search !== ''): ?>
          Không tìm thấy kết quả nào cho “<?php echo CHtml::encode($search); ?>”.
        <?php elseif ($hasFilter): ?>
          Không có bản ghi nào khớp bộ lọc đã chọn.
        <?php else: ?>
          Chưa có dữ liệu. Bấm “Thêm mới” để bắt đầu.
        <?php endif; ?>
      </p>
    </div>
  <?php else: ?>
    <div class="table-responsive">
      <table class="table table-hover mb-0 align-middle">
        <thead>
          <tr>
            <?php $sort = $dataProvider->getSort(); ?>
            <?php foreach ($columns as $column): ?>
              <th<?php echo isset($column['width']) ? ' style="width:' . $column['width'] . '"' : ''; ?>>
                <?php if (!empty($column['sortable']) && $sort !== false): ?>
                  <?php echo $sort->link($column['name'], CHtml::encode($column['label'])); ?>
                <?php else: ?>
                  <?php echo CHtml::encode($column['label']); ?>
                <?php endif; ?>
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
