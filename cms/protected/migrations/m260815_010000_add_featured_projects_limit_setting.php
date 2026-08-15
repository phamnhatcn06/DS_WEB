<?php
/**
 * Thêm khoá cấu hình số bài hiển thị cho mục "Dự án tiêu biểu" (Section 5)
 * ở trang chủ.
 *
 * Trước đây giới hạn cứng 10 bài trong HomepageDataService::loadFeaturedProjects.
 * Giờ lấy theo khoá này (kiểu number). Bỏ trống / <= 0 → dùng mặc định 10.
 */
class m260815_010000_add_featured_projects_limit_setting extends CDbMigration
{
    const SETTING_KEY = 'featured_projects_limit';

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
            'setting_value' => '10',
            'value_type'    => 'number',
            'group_name'    => 'general',
            'label'         => 'Số bài hiển thị ở mục Dự án tiêu biểu',
            'hint'          => 'Số lượng bài tối đa hiển thị ở mục "Dự án tiêu biểu" '
                . 'trang chủ. Bỏ trống hoặc <= 0 = dùng mặc định 10.',
            'sort_order'    => 51,
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
