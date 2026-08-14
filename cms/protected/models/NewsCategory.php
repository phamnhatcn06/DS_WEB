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
            array('show_in_filter, is_active, is_project_category, is_investor_category', 'boolean'),
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
            'is_project_category'  => 'Là danh mục dự án',
            'is_investor_category' => 'Là danh mục quan hệ cổ đông',
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

        // Đa danh mục: danh mục có thể được gắn ở bảng liên kết mà không phải
        // category_id chính — vẫn phải chặn để không vấp FK RESTRICT.
        if (Yii::app()->db->getSchema()->getTable('pvn_news_post_categories') !== null) {
            $linked = (int) Yii::app()->db->createCommand()
                ->select('COUNT(*)')->from('pvn_news_post_categories')
                ->where('category_id = :id', array(':id' => (int) $this->id))
                ->queryScalar();
            if ($linked > 0) {
                Yii::app()->user->setFlash('error',
                    'Không thể xoá danh mục đang được gắn cho ' . $linked . ' bài viết.');
                return false;
            }
        }
        return true;
    }

    /**
     * Số bài viết thực tế thuộc danh mục — tính cả cột category_id chính lẫn
     * bảng liên kết nhiều-nhiều pvn_news_post_categories (một bài có thể nằm ở
     * nhiều danh mục). Đếm DISTINCT để không tính trùng khi bài vừa có
     * category_id vừa có liên kết trỏ tới cùng danh mục.
     */
    public function getRealPostCount()
    {
        $db = Yii::app()->db;
        $categoryId = (int) $this->id;

        if ($db->getSchema()->getTable('pvn_news_post_categories') === null) {
            return (int) $this->postCount;
        }

        return (int) $db->createCommand()
            ->select('COUNT(DISTINCT p.id)')
            ->from('pvn_news_posts p')
            ->leftJoin('pvn_news_post_categories npc', 'npc.post_id = p.id')
            ->where('p.deleted_at IS NULL AND (p.category_id = :id OR npc.category_id = :id)',
                array(':id' => $categoryId))
            ->queryScalar();
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

    /**
     * Danh sách danh mục kèm cờ phân loại — dùng để render checkbox trong form
     * bài viết với data-attribute, JS bật/tắt khối trường tương ứng.
     *
     * @return array id => array('name', 'is_project', 'is_investor')
     */
    public static function optionsWithFlags()
    {
        $criteria = new CDbCriteria();
        $criteria->condition = 'deleted_at IS NULL';
        $criteria->order = 'sort_order ASC';

        $result = array();
        foreach (self::model()->findAll($criteria) as $category) {
            $result[$category->id] = array(
                'name'        => $category->name,
                'is_project'  => (int) $category->is_project_category,
                'is_investor' => (int) $category->is_investor_category,
            );
        }
        return $result;
    }

    /**
     * Id các danh mục có bật một cờ phân loại (dự án hoặc quan hệ cổ đông).
     * @param string $flagColumn 'is_project_category' | 'is_investor_category'
     * @return int[]
     */
    public static function idsByFlag($flagColumn)
    {
        $ids = Yii::app()->db->createCommand()
            ->select('id')->from('pvn_news_categories')
            ->where('deleted_at IS NULL AND ' . $flagColumn . ' = 1')
            ->queryColumn();
        return array_map('intval', $ids);
    }
}
