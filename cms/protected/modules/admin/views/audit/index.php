<?php
/* @var $this AuditController */
/* @var $dataProvider CActiveDataProvider */
/* @var $action string */
/* @var $entity string */
/* @var $entityTypes array */

$logs = $dataProvider->getData();
?>

<div class="card">
  <div class="card-header d-flex flex-wrap gap-2 justify-content-between align-items-center">
    <span>Nhật ký
      <span class="badge bg-secondary"><?php echo $dataProvider->getTotalItemCount(); ?></span>
    </span>

    <form method="get" class="d-flex flex-wrap gap-2">
      <?php echo CHtml::dropDownList('action', $action, AuditLog::actionLabels(), array(
          'class' => 'form-select form-select-sm',
          'empty' => 'Mọi thao tác',
          'style' => 'min-width:150px',
      )); ?>
      <?php echo CHtml::dropDownList('entity', $entity, $entityTypes, array(
          'class' => 'form-select form-select-sm',
          'empty' => 'Mọi đối tượng',
          'style' => 'min-width:150px',
      )); ?>
      <button class="btn btn-sm btn-outline-secondary" type="submit">Lọc</button>
      <a class="btn btn-sm btn-link" href="<?php echo $this->createUrl('index'); ?>">Bỏ lọc</a>
    </form>
  </div>

  <?php if ($logs === array()): ?>
    <div class="card-body text-center text-muted py-5">
      <i class="fa fa-book" style="font-size:2rem"></i>
      <p class="mt-2 mb-0">Chưa có bản ghi nào khớp điều kiện.</p>
    </div>
  <?php else: ?>
    <div class="table-responsive">
      <table class="table table-hover mb-0 align-middle">
        <thead>
          <tr>
            <th style="width:150px">Thời điểm</th>
            <th style="width:160px">Người thực hiện</th>
            <th style="width:120px">Thao tác</th>
            <th style="width:160px">Đối tượng</th>
            <th>Trường thay đổi</th>
            <th style="width:130px">IP</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($logs as $log): ?>
            <tr>
              <td class="small"><?php echo date('d/m/Y H:i:s', strtotime($log->created_at)); ?></td>
              <td class="small">
                <?php echo CHtml::encode($log->user !== null ? $log->user->full_name : 'Hệ thống'); ?>
              </td>
              <td><span class="badge bg-light text-dark border">
                <?php echo CHtml::encode($log->getActionLabel()); ?>
              </span></td>
              <td class="small">
                <?php echo CHtml::encode($log->entity_type); ?>
                <?php if ($log->entity_id): ?>
                  <span class="text-muted">#<?php echo (int) $log->entity_id; ?></span>
                <?php endif; ?>
              </td>
              <td class="small text-muted"><?php echo CHtml::encode($log->getChangeSummary()); ?></td>
              <td class="small text-muted"><?php echo CHtml::encode($log->ip_address); ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <?php if ($dataProvider->getPagination()->getPageCount() > 1): ?>
      <div class="card-footer d-flex justify-content-center">
        <?php $this->widget('CLinkPager', array(
            'pages'         => $dataProvider->getPagination(),
            'header'        => '',
            'prevPageLabel' => '&laquo;',
            'nextPageLabel' => '&raquo;',
            'htmlOptions'   => array('class' => 'pagination pagination-sm mb-0'),
            'selectedPageCssClass' => 'active',
        )); ?>
      </div>
    <?php endif; ?>
  <?php endif; ?>
</div>
