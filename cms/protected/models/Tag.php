<?php
/**
 * Thẻ (Tag) dùng chung — danh sách nhãn tái sử dụng, gắn nhiều-nhiều với
 * bài viết tin tức và lĩnh vực kinh doanh (chip hiển thị trên slider Section 2).
 *
 * Thay cho cách lưu chip free-text theo từng bản ghi trước đây: nay một tag
 * khai báo một lần, đổi tên là áp dụng ở mọi nơi đang gắn.
 */
class Tag extends BaseActiveRecord
{
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'pvn_tags';
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
            array('name', 'required', 'message' => 'Tên thẻ không được để trống.'),
            array('name', 'length', 'max' => 150),
            array('slug', 'length', 'max' => 160),
            array('slug', 'unique', 'message' => 'Slug này đã được dùng cho thẻ khác.'),
            array('description', 'length', 'max' => 255),
            array('sort_order', 'numerical', 'integerOnly' => true),
            array('is_active', 'boolean'),
        );
    }

    public function relations()
    {
        return array(
            'newsPosts' => array(self::MANY_MANY, 'NewsPost',
                'pvn_news_post_tags(tag_id, post_id)'),
            'sectors'   => array(self::MANY_MANY, 'BusinessSector',
                'pvn_business_sector_tags(tag_id, sector_id)'),
            'newsCount' => array(self::STAT, 'NewsPost',
                'pvn_news_post_tags(tag_id, post_id)'),
            'sectorCount' => array(self::STAT, 'BusinessSector',
                'pvn_business_sector_tags(tag_id, sector_id)'),
        );
    }

    public function attributeLabels()
    {
        return array(
            'slug'        => 'Slug',
            'name'        => 'Tên thẻ',
            'description' => 'Mô tả',
            'sort_order'  => 'Thứ tự',
            'is_active'   => 'Hiển thị',
        );
    }

    /**
     * Danh sách tag đang bật, dạng [id => name] để render checkbox trong form.
     */
    public static function optionsForSelect()
    {
        $tags = self::model()->notDeleted()->findAll(array(
            'condition' => 't.is_active = 1',
            'order'     => 't.sort_order ASC, t.name ASC',
        ));
        $options = array();
        foreach ($tags as $tag) {
            $options[$tag->id] = $tag->name;
        }
        return $options;
    }

    /**
     * Đồng bộ liên kết tag cho một bản ghi chủ (bài viết / lĩnh vực).
     *
     * Ghi phẳng: xoá toàn bộ liên kết cũ rồi thêm lại theo danh sách được chọn.
     * Chỉ nhận những tag id thực sự tồn tại để tránh chèn rác.
     *
     * @param string $pivotTable  tên bảng liên kết
     * @param string $ownerColumn cột khoá của bản ghi chủ (post_id | sector_id)
     * @param int    $ownerId     id bản ghi chủ
     * @param array  $tagIds      danh sách id tag được chọn
     */
    public static function syncLinks($pivotTable, $ownerColumn, $ownerId, array $tagIds)
    {
        $ownerId = (int) $ownerId;
        $db = Yii::app()->db;

        $transaction = $db->beginTransaction();
        try {
            $db->createCommand()->delete($pivotTable,
                $ownerColumn . ' = :owner', array(':owner' => $ownerId));

            $validIds = self::validTagIds($tagIds);
            foreach ($validIds as $tagId) {
                $db->createCommand()->insert($pivotTable, array(
                    $ownerColumn => $ownerId,
                    'tag_id'     => $tagId,
                ));
            }
            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollback();
            Yii::log('Đồng bộ tag thất bại: ' . $e->getMessage(), CLogger::LEVEL_ERROR, 'app');
            throw $e;
        }
    }

    /**
     * Lọc danh sách id đầu vào, chỉ giữ tag còn tồn tại (chưa xoá mềm).
     */
    private static function validTagIds($tagIds)
    {
        $ids = array_values(array_unique(array_filter(array_map('intval', (array) $tagIds))));
        if ($ids === array()) {
            return array();
        }

        $criteria = new CDbCriteria();
        $criteria->select = 'id';
        $criteria->addInCondition('t.id', $ids);
        $criteria->addCondition('t.deleted_at IS NULL');

        $existing = array();
        foreach (self::model()->findAll($criteria) as $tag) {
            $existing[] = (int) $tag->id;
        }
        return $existing;
    }
}
