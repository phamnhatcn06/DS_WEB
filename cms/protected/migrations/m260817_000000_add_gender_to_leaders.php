<?php
/**
 * Thêm cột `gender` vào pvn_leaders để chọn ảnh đại diện mặc định theo giới tính
 * (nam/nữ) khi lãnh đạo chưa có ảnh chân dung riêng.
 *
 * Giá trị: 'male' | 'female'. Mặc định 'male'.
 */
class m260817_000000_add_gender_to_leaders extends CDbMigration
{
    public function up()
    {
        $this->addColumn('pvn_leaders', 'gender',
            "VARCHAR(10) NOT NULL DEFAULT 'male' COMMENT 'male|female — chọn ảnh mặc định' AFTER role");

        // Nữ Chủ tịch mẫu (Nguyễn Thị Minh Huệ) → female.
        $this->update('pvn_leaders', array('gender' => 'female'),
            "name = :n", array(':n' => 'Nguyễn Thị Minh Huệ'));
    }

    public function down()
    {
        $this->dropColumn('pvn_leaders', 'gender');
    }
}
