<?php
/**
 * Lớp cha cho mọi model nội dung.
 *
 * Gắn sẵn: tự điền created_at/updated_at, xoá mềm, ghi audit log.
 * Model con chỉ cần khai báo tableName(), rules(), relations().
 */
#[\AllowDynamicProperties]
abstract class BaseActiveRecord extends CActiveRecord
{
    /** Khoá cache payload trang chủ — xoá mỗi khi nội dung thay đổi. */
    const CACHE_KEY_HOMEPAGE = 'dsh.homepage.payload';

    const STATUS_DRAFT     = 'draft';
    const STATUS_PUBLISHED = 'published';
    const STATUS_ARCHIVED  = 'archived';

    public static function statusOptions()
    {
        return array(
            self::STATUS_DRAFT     => 'Bản nháp',
            self::STATUS_PUBLISHED => 'Đã xuất bản',
            self::STATUS_ARCHIVED  => 'Lưu trữ',
        );
    }

    public function behaviors()
    {
        return array(
            'timestamp' => array(
                'class'              => 'zii.behaviors.CTimestampBehavior',
                'createAttribute'    => 'created_at',
                'updateAttribute'    => 'updated_at',
                'setUpdateOnCreate'  => true,
                'timestampExpression' => new CDbExpression('NOW()'),
            ),
            'softDelete' => array(
                'class'            => 'SoftDeleteBehavior',
                'deletedAttribute' => 'deleted_at',
            ),
            'audit' => array(
                'class' => 'AuditBehavior',
            ),
        );
    }

    /**
     * Named scope thay cho defaultScope() — xem ghi chú trong SoftDeleteBehavior.
     */
    public function scopes()
    {
        return array(
            'notDeleted' => array(
                'condition' => $this->getTableAlias(false, false) . '.deleted_at IS NULL',
            ),
            'active' => array(
                'condition' => $this->getTableAlias(false, false) . '.deleted_at IS NULL'
                    . ' AND ' . $this->getTableAlias(false, false) . '.is_active = 1',
                'order'     => $this->getTableAlias(false, false) . '.sort_order ASC',
            ),
        );
    }

    /**
     * Đảo thứ tự sắp xếp — dùng cho nút lên/xuống trong admin.
     */
    public function swapSortOrder($otherId)
    {
        $other = self::model(get_class($this))->findByPk($otherId);
        if ($other === null) {
            return false;
        }

        $transaction = $this->getDbConnection()->beginTransaction();
        try {
            $mine = $this->sort_order;
            $this->saveAttributes(array('sort_order' => $other->sort_order));
            $other->saveAttributes(array('sort_order' => $mine));
            $transaction->commit();
            return true;
        } catch (Exception $e) {
            $transaction->rollback();
            Yii::log('Đổi thứ tự thất bại: ' . $e->getMessage(), CLogger::LEVEL_ERROR, 'app');
            return false;
        }
    }

    /**
     * Giá trị sort_order kế tiếp khi tạo bản ghi mới.
     */
    public function nextSortOrder()
    {
        $max = $this->getDbConnection()->createCommand()
            ->select('MAX(sort_order)')
            ->from($this->tableName())
            ->queryScalar();

        return ((int) $max) + 1;
    }

    /**
     * Nhãn hiển thị bản ghi trong danh sách/dropdown của admin.
     */
    public function getDisplayName()
    {
        foreach (array('name', 'title', 'label') as $attribute) {
            if ($this->hasAttribute($attribute)) {
                return (string) $this->$attribute;
            }
        }
        return '#' . $this->getPrimaryKey();
    }
}
