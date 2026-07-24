<?php
/**
 * File trong thư viện media.
 *
 * @property int    $id
 * @property int    $folder_id
 * @property string $file_name
 * @property string $file_path
 * @property string $mime_type
 * @property int    $file_size
 * @property int    $width
 * @property int    $height
 * @property string $alt_text
 */
class MediaFile extends BaseActiveRecord
{
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'media_files';
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['json'] = array(
            'class'      => 'JsonAttributeBehavior',
            'attributes' => array('variants'),
        );
        return $behaviors;
    }

    public function rules()
    {
        return array(
            array('file_name, file_path, mime_type', 'required',
                'message' => '{attribute} không được để trống.'),
            // alt_text bắt buộc với ảnh: markup tĩnh hiện tại làm rất tốt phần
            // alt, CMS không được phép làm mất chất lượng SEO/a11y đó.
            array('alt_text', 'required', 'message' => 'Cần nhập mô tả ảnh (alt) cho SEO.',
                'on' => 'image'),
            array('file_name', 'length', 'max' => 255),
            array('file_path, file_url', 'length', 'max' => 500),
            array('mime_type', 'length', 'max' => 100),
            array('alt_text', 'length', 'max' => 300),
            array('title, caption', 'length', 'max' => 255),
            array('folder_id, width, height, file_size, uploaded_by', 'numerical',
                'integerOnly' => true),
            array('id, file_name, alt_text, mime_type', 'safe', 'on' => 'search'),
        );
    }

    public function relations()
    {
        return array(
            'folder'   => array(self::BELONGS_TO, 'MediaFolder', 'folder_id'),
            'uploader' => array(self::BELONGS_TO, 'User', 'uploaded_by'),
        );
    }

    public function attributeLabels()
    {
        return array(
            'file_name' => 'Tên file',
            'file_path' => 'Đường dẫn',
            'mime_type' => 'Định dạng',
            'file_size' => 'Dung lượng',
            'width'     => 'Rộng (px)',
            'height'    => 'Cao (px)',
            'alt_text'  => 'Mô tả ảnh (alt)',
            'title'     => 'Tiêu đề',
            'caption'   => 'Chú thích',
            'folder_id' => 'Thư mục',
        );
    }

    /**
     * URL công khai của file.
     */
    public function getPublicUrl()
    {
        if ($this->file_url !== null && $this->file_url !== '') {
            return $this->file_url;
        }
        return Yii::app()->baseUrl . '/' . ltrim($this->file_path, '/');
    }

    public function getIsImage()
    {
        return strpos((string) $this->mime_type, 'image/') === 0;
    }

    public function getIsVector()
    {
        return $this->mime_type === 'image/svg+xml';
    }

    public function getDisplayName()
    {
        return $this->alt_text !== null && $this->alt_text !== ''
            ? $this->alt_text
            : $this->file_name;
    }

    public function getFormattedSize()
    {
        return MediaHelper::formatFileSize($this->file_size);
    }

    /**
     * Danh sách dùng cho dropdown chọn ảnh trong form.
     */
    public static function optionsForSelect()
    {
        $files = self::model()->notDeleted()->findAll(array('order' => 'file_name ASC'));
        $options = array('' => '— Không chọn —');
        foreach ($files as $file) {
            $options[$file->id] = $file->file_name
                . ($file->alt_text ? ' — ' . TextHelper::truncate($file->alt_text, 45) : '');
        }
        return $options;
    }
}
