<?php
/**
 * Ghi nhật ký thay đổi vào bảng `audit_logs` và xoá cache nội dung liên quan.
 *
 * Chỉ ghi các cột thực sự đổi (so `oldAttributes` với `attributes`) để log
 * không phình vô ích.
 */
class AuditBehavior extends CActiveRecordBehavior
{
    /** @var array cột không bao giờ được ghi vào log (dữ liệu nhạy cảm) */
    public $excludeAttributes = array(
        'password_hash', 'two_factor_secret', 'token_hash', 'refresh_token_hash',
    );

    /**
     * @var array giá trị lúc bản ghi được nạp từ DB.
     *
     * Yii1 (khác Yii2) KHÔNG theo dõi thuộc tính cũ — không có getOldAttributes().
     * Nên phải tự chụp lại ngay khi bản ghi được nạp.
     */
    private $_loadedAttributes = array();

    /** @var array giá trị trước khi lưu */
    private $_oldValues = array();

    public function afterFind($event)
    {
        $this->_loadedAttributes = $this->getOwner()->getAttributes();
    }

    public function beforeSave($event)
    {
        $owner = $this->getOwner();
        $this->_oldValues = $owner->getIsNewRecord() ? array() : $this->_loadedAttributes;
    }

    public function afterSave($event)
    {
        $owner = $this->getOwner();
        $isNew = $owner->getIsNewRecord();

        $new = $this->filter($owner->getAttributes());
        $old = $this->filter($this->_oldValues);

        if ($isNew) {
            $this->write('create', $owner, array(), $new);
        } else {
            $changed = array();
            $before = array();
            foreach ($new as $key => $value) {
                $oldValue = isset($old[$key]) ? $old[$key] : null;
                // So sánh lỏng bằng chuỗi: Yii1 trả số từ MySQL dưới dạng string.
                if ((string) $oldValue !== (string) $value) {
                    $changed[$key] = $value;
                    $before[$key] = $oldValue;
                }
            }
            if ($changed === array()) {
                return;
            }
            $this->write('update', $owner, $before, $changed);
        }

        $this->flushContentCache();
    }

    public function afterDelete($event)
    {
        $owner = $this->getOwner();
        $this->write('delete', $owner, $this->filter($owner->getAttributes()), array());
        $this->flushContentCache();
    }

    private function filter($attributes)
    {
        foreach ($this->excludeAttributes as $key) {
            unset($attributes[$key]);
        }
        return $attributes;
    }

    private function write($action, $owner, $oldValues, $newValues)
    {
        $app = Yii::app();

        // Console command (migrate, seed) không có user/request → bỏ qua.
        if (!($app instanceof CWebApplication)) {
            return;
        }

        try {
            $app->db->createCommand()->insert('audit_logs', array(
                'user_id'     => $app->user->getIsGuest() ? null : $app->user->id,
                'action'      => $action,
                'entity_type' => get_class($owner),
                'entity_id'   => $owner->getPrimaryKey(),
                'old_values'  => $oldValues ? json_encode($oldValues, JSON_UNESCAPED_UNICODE) : null,
                'new_values'  => $newValues ? json_encode($newValues, JSON_UNESCAPED_UNICODE) : null,
                'ip_address'  => $app->request->getUserHostAddress(),
                'user_agent'  => substr((string) $app->request->getUserAgent(), 0, 500),
                'created_at'  => date('Y-m-d H:i:s'),
            ));
        } catch (Exception $e) {
            // Không để lỗi ghi log làm hỏng thao tác lưu nội dung, nhưng cũng
            // không nuốt im lặng — vẫn ghi ra file log của ứng dụng.
            Yii::log('Không ghi được audit_logs: ' . $e->getMessage(), CLogger::LEVEL_ERROR, 'audit');
        }
    }

    /**
     * Xoá cache nội dung frontend khi có thay đổi.
     */
    private function flushContentCache()
    {
        if (Yii::app()->hasComponent('cache')) {
            Yii::app()->cache->delete(BaseActiveRecord::CACHE_KEY_HOMEPAGE);
        }
    }
}
