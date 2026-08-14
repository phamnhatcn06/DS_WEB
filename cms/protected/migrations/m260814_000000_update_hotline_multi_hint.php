<?php
/**
 * Bổ sung gợi ý cho khoá `hotline`: cho phép nhập NHIỀU số điện thoại, tách nhau
 * bằng dấu phẩy hoặc xuống dòng. Footer sẽ render mỗi số thành một liên kết tel:.
 *
 * Chỉ cập nhật cột hint (không đụng giá trị người dùng đã nhập). `down()` trả hint
 * về rỗng như lúc seed ban đầu.
 */
class m260814_000000_update_hotline_multi_hint extends CDbMigration
{
    private $hint = 'Có thể nhập nhiều số, tách nhau bằng dấu phẩy hoặc xuống dòng.';

    public function up()
    {
        $this->update('pvn_site_settings',
            array('hint' => $this->hint),
            'setting_key = :k', array(':k' => 'hotline'));
    }

    public function down()
    {
        $this->update('pvn_site_settings',
            array('hint' => null),
            'setting_key = :k', array(':k' => 'hotline'));
    }
}
