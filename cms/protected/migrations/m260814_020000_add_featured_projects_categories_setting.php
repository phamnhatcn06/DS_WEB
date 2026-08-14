<?php
/**
 * Thêm khoá cấu hình chọn danh mục tin cho mục "Dự án tiêu biểu" (Section 5)
 * ở trang chủ.
 *
 * Section này lấy các bài viết có is_featured_project = 1 thuộc những danh mục
 * được chọn tại đây. Giá trị lưu dạng JSON mảng id danh mục (kiểu category_multi
 * → trang Cấu hình website render thành ô chọn nhiều). Bỏ trống = lấy mọi bài
 * is_featured_project = 1 (không giới hạn danh mục).
 */
class m260814_020000_add_featured_projects_categories_setting extends CDbMigration
{
    const SETTING_KEY = 'featured_projects_category_ids';

    public function up()
    {
        $exists = Yii::app()->db->createCommand()
            ->select('COUNT(*)')->from('pvn_site_settings')
            ->where('setting_key = :k', array(':k' => self::SETTING_KEY))
            ->queryScalar();
        if ($exists) {
            return;
        }

        $now = date('Y-m-d H:i:s');
        $this->insert('pvn_site_settings', array(
            'setting_key'   => self::SETTING_KEY,
            'setting_value' => '', // rỗng = không giới hạn danh mục
            'value_type'    => 'category_multi',
            'group_name'    => 'general',
            'label'         => 'Danh mục cho mục Dự án tiêu biểu',
            'hint'          => 'Chọn 1 hoặc nhiều danh mục tin. Mục "Dự án tiêu biểu" '
                . 'trang chủ chỉ hiển thị bài thuộc các danh mục này VÀ được đánh dấu '
                . '"Dự án trọng điểm". Bỏ trống = lấy mọi bài "Dự án trọng điểm".',
            'sort_order'    => 50,
            'is_public'     => 1,
            'created_at'    => $now,
            'updated_at'    => $now,
        ));
    }

    public function down()
    {
        $this->delete('pvn_site_settings', 'setting_key = :k',
            array(':k' => self::SETTING_KEY));
    }
}
