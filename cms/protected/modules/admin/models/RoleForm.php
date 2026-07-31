<?php
/**
 * Form nhóm quyền (Role group) — tạo/sửa một vai trò RBAC và danh sách chức năng.
 *
 * Mô hình PHẲNG: mỗi nhóm quyền chứa trực tiếp các operation `resource.action`
 * được tick trong ma trận, không dùng kế thừa vai trò. Khi nạp để sửa, quyền
 * kế thừa (nếu có từ seed cũ) được resolve đệ quy để tick sẵn; khi lưu thì ghi
 * phẳng lại toàn bộ.
 */
class RoleForm extends CFormModel
{
    /** Vai trò hệ thống không cho sửa/xoá qua UI. */
    public static $reserved = array('super_admin', 'guest');

    /** @var string mã máy của vai trò (khoá trong pvn_auth_items) */
    public $name;

    /** @var string tên hiển thị (description trong pvn_auth_items) */
    public $description;

    /** @var array danh sách operation được chọn, ví dụ ['projects.view', ...] */
    public $permissions = array();

    /** @var string mã gốc khi sửa (name không đổi được) */
    public $originalName;

    public function rules()
    {
        return array(
            array('name, description', 'required',
                'message' => '{attribute} không được để trống.'),
            array('name', 'match', 'pattern' => '/^[a-z][a-z0-9_]*$/',
                'message' => 'Mã nhóm quyền chỉ gồm chữ thường, số và dấu gạch dưới, bắt đầu bằng chữ.'),
            array('name', 'length', 'max' => 64),
            array('description', 'length', 'max' => 255),
            array('name', 'validateUniqueName', 'on' => 'insert'),
            array('permissions', 'safe'),
        );
    }

    public function attributeLabels()
    {
        return array(
            'name'        => 'Mã nhóm quyền',
            'description' => 'Tên nhóm quyền',
            'permissions' => 'Chức năng',
        );
    }

    public function validateUniqueName($attribute)
    {
        if ($this->hasErrors($attribute)) {
            return;
        }
        if (Yii::app()->authManager->getAuthItem($this->name) !== null) {
            $this->addError($attribute, 'Mã nhóm quyền này đã tồn tại.');
        }
    }

    // ---------------------------------------------------------------- helpers

    /**
     * Ma trận chức năng: mọi operation trong hệ thống gom theo tài nguyên.
     *
     * @return array [ resource => ['label'=>string, 'actions'=>[ name=>label ]] ]
     */
    public static function permissionMatrix()
    {
        $rows = Yii::app()->db->createCommand()
            ->select('name, description')
            ->from('pvn_auth_items')
            ->where('type = 0') // 0 = operation
            ->order('name ASC')
            ->queryAll();

        $matrix = array();
        foreach ($rows as $row) {
            $name = $row['name'];
            $dot = strpos($name, '.');
            if ($dot === false) {
                continue;
            }
            $resource = substr($name, 0, $dot);
            list($actionLabel, $resourceLabel) = self::splitDescription($row['description'], $resource);

            if (!isset($matrix[$resource])) {
                $matrix[$resource] = array('label' => $resourceLabel, 'actions' => array());
            }
            $matrix[$resource]['actions'][$name] = $actionLabel;
        }

        return $matrix;
    }

    /** Tách "Xem — Dự án" thành ['Xem', 'Dự án']. */
    private static function splitDescription($description, $fallbackResource)
    {
        $parts = preg_split('/\s+—\s+/u', (string) $description, 2);
        $actionLabel = isset($parts[0]) && $parts[0] !== '' ? $parts[0] : '';
        $resourceLabel = isset($parts[1]) && $parts[1] !== '' ? $parts[1] : $fallbackResource;
        return array($actionLabel, $resourceLabel);
    }

    /**
     * Toàn bộ operation (type=0) mà một vai trò có, resolve đệ quy qua vai trò con.
     *
     * @return string[] danh sách tên operation
     */
    public static function effectiveOperations($roleName)
    {
        $auth = Yii::app()->authManager;
        $result = array();
        $visited = array();

        $walk = function ($itemName) use (&$walk, $auth, &$result, &$visited) {
            if (isset($visited[$itemName])) {
                return;
            }
            $visited[$itemName] = true;
            foreach ($auth->getItemChildren($itemName) as $childName => $child) {
                if ($child->type == CAuthItem::TYPE_OPERATION) {
                    $result[$childName] = true;
                } else {
                    $walk($childName); // vai trò/task con
                }
            }
        };
        $walk($roleName);

        return array_keys($result);
    }
}
