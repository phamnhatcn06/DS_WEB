<?php
/**
 * Xoá mềm: đánh dấu `deleted_at` thay vì DELETE thật.
 *
 * Lưu ý thiết kế: KHÔNG dùng defaultScope() để lọc bản ghi đã xoá.
 * Trong Yii1, defaultScope() áp cả vào relation và hành xử khó lường khi
 * resetScope() — dễ sinh bug thầm lặng. Thay vào đó model khai báo named scope
 * `notDeleted()` và code gọi tường minh.
 */
class SoftDeleteBehavior extends CActiveRecordBehavior
{
    /** @var string cột đánh dấu thời điểm xoá */
    public $deletedAttribute = 'deleted_at';

    /**
     * Đánh dấu bản ghi đã xoá. Trả về true nếu lưu thành công.
     */
    public function softDelete()
    {
        $owner = $this->getOwner();
        $owner->{$this->deletedAttribute} = date('Y-m-d H:i:s');
        return $owner->saveAttributes(array(
            $this->deletedAttribute => $owner->{$this->deletedAttribute},
        ));
    }

    /**
     * Khôi phục bản ghi đã xoá mềm.
     */
    public function restore()
    {
        $owner = $this->getOwner();
        $owner->{$this->deletedAttribute} = null;
        return $owner->saveAttributes(array($this->deletedAttribute => null));
    }

    public function getIsDeleted()
    {
        return $this->getOwner()->{$this->deletedAttribute} !== null;
    }
}
