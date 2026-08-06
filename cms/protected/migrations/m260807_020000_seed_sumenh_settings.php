<?php
/**
 * Seed nhóm cấu hình `sumenh` cho trang Sứ mệnh - Tầm nhìn.
 *
 * Giá trị khởi tạo = đúng nội dung thiết kế gốc (bằng fallback trong view), nên
 * giao diện KHÔNG đổi sau khi seed — chỉ mở khả năng admin sửa văn bản/ảnh tại
 * Cấu hình website > tab "Trang sứ mệnh - tầm nhìn".
 *
 * Ảnh (hero, ảnh sứ mệnh) là kiểu `media` — để trống → SumenhDataService tự dùng
 * asset theme mặc định; admin chọn ảnh trong Thư viện media khi cần.
 */
class m260807_020000_seed_sumenh_settings extends CDbMigration
{
    public function up()
    {
        $now = date('Y-m-d H:i:s');

        // [key, value, value_type, label, hint]
        $rows = array(
            array('sumenh_meta_title', 'Sứ mệnh - Tầm nhìn — Đông Sơn Holdings', 'string',
                'Tiêu đề SEO (<title>)', 'Thẻ title của trang.'),
            array('sumenh_breadcrumb', 'Sứ mệnh - Tầm nhìn', 'string',
                'Breadcrumb', 'Nhãn trang hiện tại trên breadcrumb.'),
            array('sumenh_hero_eyebrow', 'Định hướng chiến lược', 'string',
                'Hero — eyebrow', 'Dòng nhỏ phía trên tiêu đề banner.'),
            array('sumenh_hero_title', 'SỨ MỆNH - TẦM NHÌN', 'string',
                'Hero — tiêu đề', 'Tiêu đề lớn trên banner.'),
            array('sumenh_hero_bg', null, 'media',
                'Hero — ảnh nền', 'Để trống dùng ảnh mặc định của theme.'),

            array('sumenh_vision_title', 'Tầm nhìn', 'string',
                'Tầm nhìn — tiêu đề', null),
            array('sumenh_vision_quote',
                '"Trở thành doanh nghiệp uy tín trong lĩnh vực năng lượng, bất động sản và xây lắp. '
                . 'Kiến tạo các giá trị bền vững và đồng hành cùng sự phát triển của xã hội"', 'string',
                'Tầm nhìn — tuyên ngôn', 'Câu tuyên ngôn tầm nhìn (in nghiêng, căn giữa).'),

            array('sumenh_mission_title', 'Sứ mệnh', 'string',
                'Sứ mệnh — tiêu đề', null),
            array('sumenh_mission_text',
                'Đông Sơn định hướng phát triển trên ba lĩnh vực trọng tâm gồm đầu tư, '
                . 'bất động sản và xây lắp; tập trung mở rộng hoạt động đầu tư vào các dự án '
                . 'khu công nghiệp, năng lượng, hạ tầng và phát triển đô thị, đồng thời không '
                . 'ngừng nâng cao năng lực quản trị, tài chính và triển khai dự án nhằm tạo ra '
                . 'giá trị bền vững cho khách hàng, đối tác và cộng đồng.', 'string',
                'Sứ mệnh — đoạn mô tả', null),
            array('sumenh_mission_image', null, 'media',
                'Sứ mệnh — ảnh minh hoạ', 'Để trống dùng ảnh mặc định của theme.'),
            array('sumenh_mission_tag_1', 'Đầu tư tập trung', 'string', 'Sứ mệnh — chip 1', null),
            array('sumenh_mission_tag_2', 'Năng lực quản trị', 'string', 'Sứ mệnh — chip 2', null),
            array('sumenh_mission_tag_3', 'Giá trị bền vững', 'string', 'Sứ mệnh — chip 3', null),

            array('sumenh_values_title', 'Giá trị cốt lõi', 'string',
                'Giá trị cốt lõi — tiêu đề', 'Lưới 4 thẻ lấy từ mục Giá trị cốt lõi (CoreValue).'),
            array('sumenh_values_sub',
                'Nền tảng vững chắc cho sự phát triển trường tồn của Đông Sơn Holdings', 'string',
                'Giá trị cốt lõi — phụ đề', null),
        );

        $order = 0;
        foreach ($rows as $r) {
            $this->insert('pvn_site_settings', array(
                'setting_key'   => $r[0],
                'setting_value' => $r[1],
                'value_type'    => $r[2],
                'group_name'    => 'sumenh',
                'label'         => $r[3],
                'hint'          => $r[4],
                'sort_order'    => ++$order,
                'is_public'     => 1,
                'created_at'    => $now,
                'updated_at'    => $now,
            ));
        }
    }

    public function down()
    {
        $this->delete('pvn_site_settings', "group_name = 'sumenh'");
    }
}
