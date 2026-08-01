<?php
/* @var $this SettingController */
/* @var $grouped array */
/* @var $labels array */

$canUpdate = Yii::app()->user->checkAccess('settings.update');
$groupKeys = array_keys($grouped);
$firstGroup = $groupKeys === array() ? null : $groupKeys[0];
?>

<?php echo CHtml::beginForm('', 'post', array('data-lock-submit' => '1')); ?>

<div class="card">
  <div class="card-header p-0">
    <ul class="nav nav-tabs card-header-tabs m-0 px-2 pt-2" role="tablist">
      <?php foreach ($groupKeys as $group): ?>
        <li class="nav-item" role="presentation">
          <button class="nav-link<?php echo $group === $firstGroup ? ' active' : ''; ?>"
                  data-bs-toggle="tab" data-bs-target="#tab-<?php echo $group; ?>"
                  type="button" role="tab">
            <?php echo CHtml::encode(isset($labels[$group]) ? $labels[$group] : $group); ?>
          </button>
        </li>
      <?php endforeach; ?>
    </ul>
  </div>

  <div class="card-body">
    <div class="tab-content">
      <?php foreach ($grouped as $group => $settings): ?>
        <div class="tab-pane fade<?php echo $group === $firstGroup ? ' show active' : ''; ?>"
             id="tab-<?php echo $group; ?>" role="tabpanel">
          <div class="row g-3">
            <?php foreach ($settings as $setting): ?>
              <?php
              $inputId = 'setting-' . $setting->setting_key;
              // Nhóm script luôn là textarea (mặc định rỗng nên không tự nhận diện được).
              $isLong = $setting->group_name === 'scripts'
                  || mb_strlen((string) $setting->setting_value, 'UTF-8') > 90
                  || strpos((string) $setting->setting_value, "\n") !== false;
              ?>
              <div class="col-12 col-lg-<?php echo $isLong ? 12 : 6; ?>">
                <label class="form-label" for="<?php echo $inputId; ?>">
                  <?php echo CHtml::encode($setting->label ?: $setting->setting_key); ?>
                </label>

                <?php if ($setting->value_type === 'boolean'): ?>
                  <select class="form-select" id="<?php echo $inputId; ?>"
                          name="Setting[<?php echo $setting->setting_key; ?>]"
                          <?php echo $canUpdate ? '' : 'disabled'; ?>>
                    <option value="1"<?php echo $setting->setting_value ? ' selected' : ''; ?>>Bật</option>
                    <option value="0"<?php echo $setting->setting_value ? '' : ' selected'; ?>>Tắt</option>
                  </select>

                <?php elseif ($setting->value_type === 'media'): ?>
                  <?php echo CHtml::dropDownList(
                      'Setting[' . $setting->setting_key . ']',
                      $setting->setting_value,
                      MediaFile::optionsForSelect(),
                      array(
                          'class'    => 'form-select',
                          'id'       => $inputId,
                          'disabled' => !$canUpdate,
                      )
                  ); ?>

                <?php elseif ($isLong): ?>
                  <textarea class="form-control" id="<?php echo $inputId; ?>" rows="3"
                            name="Setting[<?php echo $setting->setting_key; ?>]"
                            <?php echo $canUpdate ? '' : 'readonly'; ?>><?php
                      echo CHtml::encode($setting->setting_value); ?></textarea>

                <?php else: ?>
                  <input type="text" class="form-control" id="<?php echo $inputId; ?>"
                         name="Setting[<?php echo $setting->setting_key; ?>]"
                         value="<?php echo CHtml::encode($setting->setting_value); ?>"
                         <?php echo $canUpdate ? '' : 'readonly'; ?> />
                <?php endif; ?>

                <p class="form-hint mt-1 mb-0">
                  <code><?php echo CHtml::encode($setting->setting_key); ?></code>
                  <?php if ($setting->hint): ?>
                    — <?php echo CHtml::encode($setting->hint); ?>
                  <?php endif; ?>
                </p>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>

  <?php if ($canUpdate): ?>
    <div class="card-footer">
      <button type="submit" class="btn btn-dsh">
        <i class="fa fa-check me-1"></i> Lưu cấu hình
      </button>
      <span class="form-hint ms-2">
        Các thay đổi được lưu trong một transaction — hoặc tất cả thành công, hoặc không đổi gì.
      </span>
    </div>
  <?php endif; ?>
</div>

<?php echo CHtml::endForm(); ?>
