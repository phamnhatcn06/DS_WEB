<?php
/**
 * Tài khoản quản trị.
 *
 * @property int    $id
 * @property string $email
 * @property string $password_hash
 * @property string $full_name
 * @property int    $is_active
 */
class User extends BaseActiveRecord
{
    /** @var string mật khẩu nhập từ form — không lưu vào DB */
    public $newPassword;
    public $newPasswordRepeat;

    /** @var string vai trò RBAC chọn ở form */
    public $roleName;

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'users';
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        // Không ghi audit trên chính bảng users để tránh lộ thông tin nhạy cảm
        // vào log; các thao tác đăng nhập được ghi riêng.
        unset($behaviors['audit']);
        return $behaviors;
    }

    public function rules()
    {
        return array(
            array('email, full_name', 'required',
                'message' => '{attribute} không được để trống.'),
            array('email', 'email', 'message' => 'Email không hợp lệ.'),
            array('email', 'unique', 'message' => 'Email này đã được sử dụng.'),
            array('email', 'length', 'max' => 255),
            array('full_name', 'length', 'max' => 150),
            array('phone', 'length', 'max' => 30),
            array('is_active', 'boolean'),
            array('avatar_media_id', 'numerical', 'integerOnly' => true),

            array('newPassword', 'required', 'on' => 'insert',
                'message' => 'Cần đặt mật khẩu cho tài khoản mới.'),
            array('newPassword', 'length', 'min' => 10, 'max' => 128,
                'tooShort' => 'Mật khẩu phải có ít nhất 10 ký tự.'),
            array('newPassword', 'match',
                'pattern' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
                'message' => 'Mật khẩu phải gồm cả chữ thường, chữ hoa và chữ số.',
                'allowEmpty' => true),
            array('newPasswordRepeat', 'compare', 'compareAttribute' => 'newPassword',
                'message' => 'Xác nhận mật khẩu không khớp.'),

            array('roleName', 'safe'),
        );
    }

    public function relations()
    {
        return array(
            'avatar' => array(self::BELONGS_TO, 'MediaFile', 'avatar_media_id'),
            'posts'  => array(self::HAS_MANY, 'NewsPost', 'author_id'),
        );
    }

    public function attributeLabels()
    {
        return array(
            'email'             => 'Email',
            'full_name'         => 'Họ và tên',
            'phone'             => 'Điện thoại',
            'is_active'         => 'Đang hoạt động',
            'newPassword'       => 'Mật khẩu',
            'newPasswordRepeat' => 'Nhập lại mật khẩu',
            'roleName'          => 'Vai trò',
            'last_login_at'     => 'Đăng nhập gần nhất',
            'avatar_media_id'   => 'Ảnh đại diện',
        );
    }

    protected function beforeSave()
    {
        if (!parent::beforeSave()) {
            return false;
        }

        $this->email = strtolower(trim($this->email));

        if ($this->newPassword !== null && $this->newPassword !== '') {
            $this->setPassword($this->newPassword);
        }

        return true;
    }

    /**
     * Băm và gán mật khẩu. Không bao giờ lưu mật khẩu thô.
     */
    public function setPassword($plainPassword)
    {
        $cost = isset(Yii::app()->params['bcryptCost']) ? Yii::app()->params['bcryptCost'] : 12;
        $this->password_hash = password_hash($plainPassword, PASSWORD_BCRYPT, array('cost' => $cost));
    }

    public function isLocked()
    {
        return $this->locked_until !== null && strtotime($this->locked_until) > time();
    }

    /**
     * Ghi nhận một lần đăng nhập sai; khoá tài khoản khi vượt ngưỡng.
     */
    public function registerFailedLogin($maxAttempts, $lockoutMinutes)
    {
        $this->failed_login_count = (int) $this->failed_login_count + 1;

        $attributes = array('failed_login_count' => $this->failed_login_count);
        if ($this->failed_login_count >= $maxAttempts) {
            $this->locked_until = date('Y-m-d H:i:s', time() + $lockoutMinutes * 60);
            $attributes['locked_until'] = $this->locked_until;
        }

        $this->saveAttributes($attributes);
    }

    public function registerSuccessfulLogin()
    {
        $this->failed_login_count = 0;
        $this->locked_until = null;
        $this->last_login_at = date('Y-m-d H:i:s');
        $this->last_login_ip = Yii::app() instanceof CWebApplication
            ? Yii::app()->request->getUserHostAddress()
            : null;

        $this->saveAttributes(array(
            'failed_login_count' => 0,
            'locked_until'       => null,
            'last_login_at'      => $this->last_login_at,
            'last_login_ip'      => $this->last_login_ip,
        ));
    }

    public function getDisplayName()
    {
        return $this->full_name;
    }

    /**
     * Vai trò RBAC đang gán cho user này.
     */
    public function getAssignedRole()
    {
        $roles = Yii::app()->authManager->getRoles($this->id);
        if ($roles === array()) {
            return null;
        }
        $first = reset($roles);
        return $first->name;
    }

    /**
     * Danh sách vai trò cho dropdown.
     */
    public static function roleOptions()
    {
        $roles = Yii::app()->authManager->getRoles();
        $options = array();
        foreach ($roles as $name => $role) {
            if ($name === 'guest') {
                continue;
            }
            $options[$name] = $role->description !== '' ? $role->description : $name;
        }
        return $options;
    }
}
