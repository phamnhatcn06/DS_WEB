<?php
/**
 * Đơn vị trong "Hệ thống phân cấp" (sơ đồ tổ chức dạng cây).
 *
 * Cây tự tham chiếu qua `parent_id`; `level` quyết định kiểu hiển thị:
 *   1 = Hội đồng quản trị (box đỏ), 2 = Ban Tổng giám đốc, 3 = các khối chức năng.
 */
class OrgUnit extends BaseActiveRecord
{
    const LEVEL_BOARD = 1;
    const LEVEL_EXEC  = 2;
    const LEVEL_DEPT  = 3;

    public static function levelOptions()
    {
        return array(
            self::LEVEL_BOARD => 'Cấp 1 — Hội đồng quản trị',
            self::LEVEL_EXEC  => 'Cấp 2 — Ban Tổng giám đốc',
            self::LEVEL_DEPT  => 'Cấp 3 — Khối chức năng',
        );
    }

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'pvn_org_units';
    }

    public function rules()
    {
        return array(
            array('name', 'required', 'message' => '{attribute} không được để trống.'),
            array('name', 'length', 'max' => 150),
            array('level', 'in', 'range' => array_keys(self::levelOptions())),
            array('parent_id, sort_order', 'numerical', 'integerOnly' => true),
            array('is_active', 'boolean'),
        );
    }

    public function relations()
    {
        return array(
            'parent'   => array(self::BELONGS_TO, 'OrgUnit', 'parent_id'),
            'children' => array(self::HAS_MANY, 'OrgUnit', 'parent_id',
                'condition' => 'children.deleted_at IS NULL',
                'order'     => 'children.sort_order ASC'),
        );
    }

    public function attributeLabels()
    {
        return array(
            'name'       => 'Tên đơn vị',
            'parent_id'  => 'Trực thuộc',
            'level'      => 'Cấp',
            'sort_order' => 'Thứ tự',
            'is_active'  => 'Hiển thị',
        );
    }

    /**
     * Danh sách đơn vị dùng cho dropdown "Trực thuộc" (loại chính bản ghi hiện tại).
     *
     * @return array id => "name (cấp)"
     */
    public static function parentOptions($excludeId = null)
    {
        $criteria = new CDbCriteria();
        $criteria->condition = 't.deleted_at IS NULL';
        if ($excludeId !== null) {
            $criteria->condition .= ' AND t.id <> :id';
            $criteria->params[':id'] = (int) $excludeId;
        }
        $criteria->order = 't.level ASC, t.sort_order ASC';

        $options = array();
        foreach (self::model()->findAll($criteria) as $unit) {
            $options[$unit->id] = $unit->name;
        }
        return $options;
    }

    public function getDisplayName()
    {
        return $this->name;
    }
}
