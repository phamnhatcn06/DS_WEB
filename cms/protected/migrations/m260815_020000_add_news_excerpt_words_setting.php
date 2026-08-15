<?php
/**
 * Thêm khoá cấu hình số TỪ của trích dẫn hiển thị trên thẻ tin nhỏ ở trang
 * Tin tức (/tin-tuc).
 *
 * Thẻ tin ưu tiên hiển thị trường "Trích dẫn"; nếu bài không nhập trích dẫn
 * thì cắt một đoạn đầu từ nội dung. Số từ tối đa lấy theo khoá này (kiểu
 * number). Bỏ trống / <= 0 → dùng mặc định 30.
 */
class m260815_020000_add_news_excerpt_words_setting extends CDbMigration
{
    const SETTING_KEY = 'news_excerpt_words';

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
            'setting_value' => '30',
            'value_type'    => 'number',
            'group_name'    => 'news',
            'label'         => 'Số từ trích dẫn trên thẻ tin',
            'hint'          => 'Số từ tối đa của trích dẫn hiển thị trên thẻ tin nhỏ ở trang '
                . 'Tin tức. Bài không có trích dẫn sẽ tự cắt đoạn đầu từ nội dung. '
                . 'Bỏ trống hoặc <= 0 = dùng mặc định 30.',
            'sort_order'    => 10,
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
