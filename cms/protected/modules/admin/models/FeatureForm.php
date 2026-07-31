<?php
/**
 * Form "Chức năng" — một tài nguyên RBAC (resource) cùng tập hành động của nó.
 *
 * Một "chức năng" gom nhóm các operation `resource.action` trong pvn_auth_items.
 * Ví dụ chức năng `projects` (Dự án) gồm các operation `projects.view`,
 * `projects.create`, ... Form này tạo/sửa/xoá cả nhóm operation đó cùng lúc.
 */
class FeatureForm extends CFormModel
{
    /** Hành động chuẩn: khoá máy => nhãn tiếng Việt. */
    public static $standardActions = array(
        'view'    => 'Xem',
        'create'  => 'Thêm',
        'update'  => 'Sửa',
        'delete'  => 'Xoá',
        'reorder' => 'Sắp xếp',
    );

    /**
     * Chức năng hệ thống trọng yếu: cho sửa nhãn nhưng KHÔNG cho xoá,
     * tránh khoá mất lối vào quản trị.
     */
    public static $reserved = array('features', 'roles', 'menus', 'users', 'audit', 'settings');

    /** @var string mã tài nguyên (slug), ví dụ `projects` */
    public $code;

    /** @var string tên hiển thị tiếng Việt, ví dụ `Dự án` */
    public $label;

    /** @var array khoá hành động được chọn, ví dụ ['view', 'create'] */
    public $actions = array();

    /** @var string mã gốc khi sửa (code không đổi được) */
    public $originalCode;

    public function rules()
    {
        return array(
            array('code, label', 'required', 'message' => '{attribute} không được để trống.'),
            array('code', 'match', 'pattern' => '/^[a-z][a-z0-9_]*$/',
                'message' => 'Mã chức năng chỉ gồm chữ thường, số và dấu gạch dưới, bắt đầu bằng chữ.'),
            array('code', 'length', 'max' => 60),
            array('label', 'length', 'max' => 200),
            array('code', 'validateUniqueCode', 'on' => 'insert'),
            array('actions', 'validateActions'),
        );
    }

    public function attributeLabels()
    {
        return array(
            'code'    => 'Mã chức năng',
            'label'   => 'Tên chức năng',
            'actions' => 'Hành động',
        );
    }

    public function validateUniqueCode($attribute)
    {
        if ($this->hasErrors($attribute)) {
            return;
        }
        if (self::actionsOf($this->code)) {
            $this->addError($attribute, 'Mã chức năng này đã tồn tại.');
        }
    }

    public function validateActions($attribute)
    {
        $selected = array_intersect((array) $this->actions, array_keys(self::$standardActions));
        if (empty($selected)) {
            $this->addError($attribute, 'Chọn ít nhất một hành động.');
        }
        $this->actions = array_values($selected);
    }

    // ---------------------------------------------------------------- helpers

    /**
     * Mọi chức năng (tài nguyên) trong hệ thống, gom từ pvn_auth_items.
     *
     * @return array [ code => ['label'=>string, 'actions'=>[action=>opName], 'reserved'=>bool] ]
     */
    public static function resources()
    {
        $rows = Yii::app()->db->createCommand()
            ->select('name, description')
            ->from('pvn_auth_items')
            ->where('type = 0') // 0 = operation
            ->order('name ASC')
            ->queryAll();

        $resources = array();
        foreach ($rows as $row) {
            $name = $row['name'];
            $dot = strpos($name, '.');
            if ($dot === false) {
                continue;
            }
            $code = substr($name, 0, $dot);
            $action = substr($name, $dot + 1);
            list(, $resourceLabel) = self::splitDescription($row['description'], $code);

            if (!isset($resources[$code])) {
                $resources[$code] = array(
                    'label'    => $resourceLabel,
                    'actions'  => array(),
                    'reserved' => in_array($code, self::$reserved, true),
                );
            }
            $resources[$code]['actions'][$action] = $name;
        }

        return $resources;
    }

    /**
     * Các hành động hiện có của một chức năng.
     *
     * @return array [action => opName]; rỗng nếu chức năng không tồn tại.
     */
    public static function actionsOf($code)
    {
        $resources = self::resources();
        return isset($resources[$code]) ? $resources[$code]['actions'] : array();
    }

    /** Số nhóm quyền đang tham chiếu tới bất kỳ operation của một chức năng. */
    public static function roleUsage($code)
    {
        return (int) Yii::app()->db->createCommand()
            ->select('COUNT(DISTINCT parent)')
            ->from('pvn_auth_item_children')
            ->where('child LIKE :prefix', array(':prefix' => $code . '.%'))
            ->queryScalar();
    }

    /** Nhãn operation: ghép "ActionLabel — ResourceLabel". */
    public static function describe($action, $resourceLabel)
    {
        $actionLabel = isset(self::$standardActions[$action])
            ? self::$standardActions[$action] : $action;
        return $actionLabel . ' — ' . $resourceLabel;
    }

    /** Tách "Xem — Dự án" thành ['Xem', 'Dự án']. */
    private static function splitDescription($description, $fallback)
    {
        $parts = preg_split('/\s+—\s+/u', (string) $description, 2);
        $actionLabel = isset($parts[0]) && $parts[0] !== '' ? $parts[0] : '';
        $resourceLabel = isset($parts[1]) && $parts[1] !== '' ? $parts[1] : $fallback;
        return array($actionLabel, $resourceLabel);
    }
}
