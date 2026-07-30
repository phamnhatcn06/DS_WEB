<?php
/**
 * Thư mục phân loại media.
 */
class MediaFolder extends BaseActiveRecord
{
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'pvn_media_folders';
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['slug'] = array(
            'class'           => 'SlugBehavior',
            'sourceAttribute' => 'name',
            'slugAttribute'   => 'slug',
        );
        return $behaviors;
    }

    public function rules()
    {
        return array(
            array('name', 'required', 'message' => 'Tên thư mục không được để trống.'),
            array('name', 'length', 'max' => 150),
            array('slug', 'length', 'max' => 160),
            array('slug', 'unique', 'message' => 'Slug này đã tồn tại.'),
            array('parent_id, sort_order', 'numerical', 'integerOnly' => true),
        );
    }

    public function relations()
    {
        return array(
            'parent'   => array(self::BELONGS_TO, 'MediaFolder', 'parent_id'),
            'children' => array(self::HAS_MANY, 'MediaFolder', 'parent_id'),
            'files'    => array(self::HAS_MANY, 'MediaFile', 'folder_id'),
            'fileCount' => array(self::STAT, 'MediaFile', 'folder_id'),
        );
    }

    public function attributeLabels()
    {
        return array(
            'name'       => 'Tên thư mục',
            'slug'       => 'Slug',
            'parent_id'  => 'Thư mục cha',
            'sort_order' => 'Thứ tự',
        );
    }
}
