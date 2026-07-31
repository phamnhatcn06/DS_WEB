<?php
/**
 * Vị trí menu (Menu Location).
 *
 * Mỗi location là một điểm neo giao diện nơi một cây menu được render
 * (vd `admin_sidebar`). Nội dung menu tách khỏi nơi hiển thị.
 */
class MenuLocation extends BaseActiveRecord
{
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'pvn_menu_locations';
    }

    public function rules()
    {
        return array(
            array('code, name', 'required',
                'message' => '{attribute} không được để trống.'),
            array('code', 'length', 'max' => 50),
            array('code', 'match', 'pattern' => '/^[a-z0-9_]+$/',
                'message' => 'Mã chỉ gồm chữ thường, số và dấu gạch dưới.'),
            array('code', 'unique', 'message' => 'Mã vị trí đã tồn tại.'),
            array('name', 'length', 'max' => 150),
            array('description', 'length', 'max' => 255),
            array('max_depth', 'numerical', 'integerOnly' => true, 'min' => 1),
            array('supports_nesting, is_active', 'boolean'),
        );
    }

    public function relations()
    {
        return array(
            'items' => array(self::HAS_MANY, 'MenuItem', 'location_id',
                'order' => 'items.sort_order ASC'),
            'itemCount' => array(self::STAT, 'MenuItem', 'location_id',
                'condition' => 'deleted_at IS NULL'),
        );
    }

    public function attributeLabels()
    {
        return array(
            'code'             => 'Mã vị trí',
            'name'             => 'Tên vị trí',
            'description'      => 'Mô tả',
            'supports_nesting' => 'Cho phép phân cấp',
            'max_depth'        => 'Số cấp tối đa',
            'is_active'        => 'Kích hoạt',
        );
    }

    /** Tìm location theo mã slug (chưa xoá mềm). */
    public static function findByCode($code)
    {
        return self::model()->notDeleted()->find('code = :code', array(':code' => $code));
    }

    public function getDisplayName()
    {
        return $this->name;
    }
}
