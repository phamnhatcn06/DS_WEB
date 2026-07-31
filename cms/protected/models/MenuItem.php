<?php
/**
 * Mục menu (Menu Item).
 *
 * Tự tham chiếu qua parent_id để phân cấp cha–con. Ba loại mục:
 *   - route   : liên kết tới một route Yii nội bộ.
 *   - url     : liên kết ngoài / tuyệt đối.
 *   - divider : nhãn nhóm (không phải liên kết).
 */
class MenuItem extends BaseActiveRecord
{
    const TYPE_ROUTE   = 'route';
    const TYPE_URL     = 'url';
    const TYPE_DIVIDER = 'divider';

    public static function typeOptions()
    {
        return array(
            self::TYPE_ROUTE   => 'Route nội bộ',
            self::TYPE_URL     => 'Liên kết ngoài',
            self::TYPE_DIVIDER => 'Nhãn nhóm (divider)',
        );
    }

    public static function targetOptions()
    {
        return array(
            '_self'  => 'Cùng cửa sổ',
            '_blank' => 'Cửa sổ mới',
        );
    }

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'pvn_menu_items';
    }

    public function rules()
    {
        return array(
            array('location_id, title', 'required',
                'message' => '{attribute} không được để trống.'),
            array('title', 'length', 'max' => 200),
            array('item_type', 'in', 'range' => array_keys(self::typeOptions()),
                'message' => 'Loại mục không hợp lệ.'),
            array('target', 'in', 'range' => array_keys(self::targetOptions())),
            array('route', 'requiredIfType', 'type' => self::TYPE_ROUTE),
            array('url', 'requiredIfType', 'type' => self::TYPE_URL),
            array('route, css_class', 'length', 'max' => 200),
            array('url', 'length', 'max' => 500),
            array('icon', 'length', 'max' => 60),
            array('perm', 'length', 'max' => 80),
            array('location_id, parent_id, sort_order, depth', 'numerical', 'integerOnly' => true),
            array('is_protected, is_active', 'boolean'),
            array('parent_id', 'validateParentNotSelf'),
        );
    }

    /** route bắt buộc khi type=route, url bắt buộc khi type=url. */
    public function requiredIfType($attribute, $params)
    {
        if ($this->item_type === $params['type'] && ($this->$attribute === null || $this->$attribute === '')) {
            $label = $this->getAttributeLabel($attribute);
            $this->addError($attribute, $label . ' không được để trống với loại mục đã chọn.');
        }
    }

    /** Một mục không thể là cha của chính nó. */
    public function validateParentNotSelf($attribute)
    {
        if (!$this->isNewRecord && $this->parent_id !== null && (int) $this->parent_id === (int) $this->id) {
            $this->addError($attribute, 'Mục không thể là cha của chính nó.');
        }
    }

    public function relations()
    {
        return array(
            'location' => array(self::BELONGS_TO, 'MenuLocation', 'location_id'),
            'parent'   => array(self::BELONGS_TO, 'MenuItem', 'parent_id'),
            'children' => array(self::HAS_MANY, 'MenuItem', 'parent_id',
                'order' => 'children.sort_order ASC'),
        );
    }

    public function attributeLabels()
    {
        return array(
            'location_id'  => 'Vị trí',
            'parent_id'    => 'Mục cha',
            'title'        => 'Tên mục',
            'item_type'    => 'Loại mục',
            'route'        => 'Route',
            'url'          => 'Liên kết',
            'target'       => 'Mở tại',
            'icon'         => 'Icon',
            'perm'         => 'Quyền RBAC',
            'sort_order'   => 'Thứ tự',
            'depth'        => 'Cấp',
            'is_protected' => 'Được bảo vệ',
            'css_class'    => 'Class CSS',
            'is_active'    => 'Hiển thị',
        );
    }

    public function isDivider()
    {
        return $this->item_type === self::TYPE_DIVIDER;
    }

    public function getDisplayName()
    {
        return $this->title;
    }
}
