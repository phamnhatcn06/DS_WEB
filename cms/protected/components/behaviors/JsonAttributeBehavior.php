<?php
/**
 * Tự encode/decode các cột kiểu JSON.
 *
 * Yii1 ActiveRecord không cast JSON tự động: đọc lên là string, ghi xuống cũng
 * phải là string. Behavior này giữ cho model luôn làm việc với mảng PHP.
 */
class JsonAttributeBehavior extends CActiveRecordBehavior
{
    /** @var array danh sách tên cột kiểu JSON */
    public $attributes = array();

    public function afterFind($event)
    {
        $owner = $this->getOwner();
        foreach ($this->attributes as $attribute) {
            $value = $owner->$attribute;
            if (is_string($value) && $value !== '') {
                $decoded = json_decode($value, true);
                $owner->$attribute = (json_last_error() === JSON_ERROR_NONE) ? $decoded : array();
            } elseif ($value === null || $value === '') {
                $owner->$attribute = array();
            }
        }
    }

    public function beforeSave($event)
    {
        $owner = $this->getOwner();
        foreach ($this->attributes as $attribute) {
            $value = $owner->$attribute;
            if (is_array($value)) {
                $owner->$attribute = $value === array()
                    ? null
                    : json_encode($value, JSON_UNESCAPED_UNICODE);
            }
        }
    }

    /**
     * Sau khi lưu, trả model về dạng mảng để code phía sau dùng tiếp không bị lỗi.
     */
    public function afterSave($event)
    {
        $this->afterFind($event);
    }
}
