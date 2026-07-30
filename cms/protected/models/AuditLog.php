<?php
/**
 * Nhật ký thay đổi nội dung — bảng chỉ ghi thêm (append-only).
 *
 * Model cố tình không cho sửa/xoá: nhật ký sửa được thì không còn là nhật ký.
 */
class AuditLog extends CActiveRecord
{
    public static function actionLabels()
    {
        return array(
            'create'  => 'Thêm mới',
            'update'  => 'Cập nhật',
            'delete'  => 'Xoá',
            'login'   => 'Đăng nhập',
            'logout'  => 'Đăng xuất',
            'publish' => 'Xuất bản',
        );
    }

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'pvn_audit_logs';
    }

    public function relations()
    {
        return array(
            'user' => array(self::BELONGS_TO, 'User', 'user_id'),
        );
    }

    public function attributeLabels()
    {
        return array(
            'user_id'     => 'Người thực hiện',
            'action'      => 'Thao tác',
            'entity_type' => 'Đối tượng',
            'entity_id'   => 'ID bản ghi',
            'old_values'  => 'Giá trị cũ',
            'new_values'  => 'Giá trị mới',
            'ip_address'  => 'Địa chỉ IP',
            'created_at'  => 'Thời điểm',
        );
    }

    protected function beforeSave()
    {
        // Chỉ cho phép tạo mới, không cho sửa bản ghi log đã có.
        return $this->getIsNewRecord() && parent::beforeSave();
    }

    protected function beforeDelete()
    {
        return false;
    }

    public function getActionLabel()
    {
        $labels = self::actionLabels();
        return isset($labels[$this->action]) ? $labels[$this->action] : $this->action;
    }

    /**
     * Danh sách các cột đã thay đổi, dạng dễ đọc.
     */
    public function getChangeSummary()
    {
        $new = json_decode((string) $this->new_values, true);
        if (!is_array($new) || $new === array()) {
            return '—';
        }
        $keys = array_slice(array_keys($new), 0, 5);
        $summary = implode(', ', $keys);

        return count($new) > 5 ? $summary . '… (+' . (count($new) - 5) . ')' : $summary;
    }
}
