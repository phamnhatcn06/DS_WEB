<?php
/**
 * Danh mục tin tức — nguồn dữ liệu của thanh tab lọc ở Section 9.
 *
 * `slug` phải khớp giá trị `data-filter` dùng trong markup frontend.
 */
class NewsCategory extends BaseActiveRecord
{
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'pvn_news_categories';
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
            array('name', 'required', 'message' => 'Tên danh mục không được để trống.'),
            array('name', 'length', 'max' => 150),
            array('slug', 'length', 'max' => 120),
            array('slug', 'unique', 'message' => 'Slug này đã tồn tại.'),
            array('parent_id, sort_order', 'numerical', 'integerOnly' => true),
            array('show_in_filter, is_active', 'boolean'),
            array('description', 'safe'),
        );
    }

    public function relations()
    {
        return array(
            'parent'    => array(self::BELONGS_TO, 'NewsCategory', 'parent_id'),
            'children'  => array(self::HAS_MANY, 'NewsCategory', 'parent_id'),
            'posts'     => array(self::HAS_MANY, 'NewsPost', 'category_id'),
            'postCount' => array(self::STAT, 'NewsPost', 'category_id',
                'condition' => 'deleted_at IS NULL'),
        );
    }

    public function attributeLabels()
    {
        return array(
            'slug'           => 'Slug (khớp data-filter)',
            'name'           => 'Tên danh mục',
            'description'    => 'Mô tả',
            'parent_id'      => 'Danh mục cha',
            'show_in_filter' => 'Hiện trên thanh lọc',
            'sort_order'     => 'Thứ tự',
            'is_active'      => 'Hiển thị',
        );
    }

    protected function beforeDelete()
    {
        if (!parent::beforeDelete()) {
            return false;
        }

        // FK là RESTRICT — chặn sớm để báo lỗi thân thiện thay vì lỗi SQL.
        if ($this->postCount > 0) {
            Yii::app()->user->setFlash('error',
                'Không thể xoá danh mục đang có ' . $this->postCount . ' bài viết.');
            return false;
        }
        return true;
    }

    public static function optionsForSelect($excludeId = null)
    {
        $criteria = new CDbCriteria();
        $criteria->condition = 'deleted_at IS NULL';
        $criteria->order = 'sort_order ASC';
        if ($excludeId !== null) {
            $criteria->condition .= ' AND id <> :id';
            $criteria->params[':id'] = $excludeId;
        }

        $options = array();
        foreach (self::model()->findAll($criteria) as $category) {
            $options[$category->id] = $category->name;
        }
        return $options;
    }
}
