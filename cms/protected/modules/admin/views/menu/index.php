<?php
/* @var $this MenuController */
/* @var $locations MenuLocation[] */
?>
<div class="card">
  <div class="card-header d-flex align-items-center">
    <span><i class="bi bi-list-nested me-1"></i> Vị trí menu</span>
  </div>
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table align-middle mb-0">
        <thead>
          <tr>
            <th>Tên vị trí</th>
            <th>Mã</th>
            <th class="text-center" style="width:120px;">Số mục</th>
            <th class="text-center" style="width:120px;">Trạng thái</th>
            <th class="text-end" style="width:160px;">Thao tác</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($locations as $loc): ?>
            <tr>
              <td>
                <div class="fw-semibold"><?php echo CHtml::encode($loc->name); ?></div>
                <?php if ($loc->description): ?>
                  <div class="small text-muted"><?php echo CHtml::encode($loc->description); ?></div>
                <?php endif; ?>
              </td>
              <td><code><?php echo CHtml::encode($loc->code); ?></code></td>
              <td class="text-center"><?php echo (int) $loc->itemCount; ?></td>
              <td class="text-center">
                <?php if ($loc->is_active): ?>
                  <span class="badge bg-success-subtle text-success">Kích hoạt</span>
                <?php else: ?>
                  <span class="badge bg-secondary-subtle text-secondary">Tắt</span>
                <?php endif; ?>
              </td>
              <td class="text-end">
                <a class="btn btn-sm btn-dsh" href="<?php echo $this->createUrl('manage', array('id' => $loc->id)); ?>">
                  <i class="bi bi-diagram-3 me-1"></i> Quản lý menu
                </a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
