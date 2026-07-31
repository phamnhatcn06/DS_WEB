<?php
/**
 * Chuyển hệ thống menu động sang phục vụ FRONTEND (website công khai).
 *
 * - Gỡ location `admin_sidebar` (sidebar admin quay lại hardcode, không cần cấu hình).
 * - Seed các location frontend lấy đúng từ header/footer của index.html:
 *     public_header          — thanh menu chính (có dropdown "Về chúng tôi")
 *     public_footer_about    — cột "Về Đông Sơn"
 *     public_footer_sectors  — cột "Lĩnh vực"
 *     public_footer_projects — cột "Dự án"
 *     public_footer_investors— cột "Nhà đầu tư"
 */
class m260731_020000_seed_frontend_menus extends CDbMigration
{
    public function up()
    {
        // Giữ nguyên location admin_sidebar (sidebar admin vẫn render động).
        $now = date('Y-m-d H:i:s');

        // -------- header chính (có phân cấp)
        $headerId = $this->seedLocation('public_header', 'Menu Header',
            'Thanh điều hướng chính trên header website.', 1, 2, $now);

        $veChungToi = $this->seedItem($headerId, null, 'Về chúng tôi', 'about.html', 'nav-caret', 1, 0, $now);
        $this->seedItem($headerId, $veChungToi, 'Giới thiệu',        'about.html',        null, 1, 1, $now);
        $this->seedItem($headerId, $veChungToi, 'Sứ mệnh - Tầm nhìn', 'sumenh.html',       null, 2, 1, $now);
        $this->seedItem($headerId, $veChungToi, 'Sơ đồ tổ chức',      'sodo-to-chuc.html', null, 3, 1, $now);
        $this->seedItem($headerId, null, 'Lĩnh vực',          '#linh-vuc',   'nav-caret', 2, 0, $now);
        $this->seedItem($headerId, null, 'Dự án',             '#du-an',      null,        3, 0, $now);
        $this->seedItem($headerId, null, 'Quan hệ cổ đông',   '#co-dong',    null,        4, 0, $now);
        $this->seedItem($headerId, null, 'Tin tức',           'tintuc.html', null,        5, 0, $now);

        // -------- các cột footer (phẳng); tên location = tiêu đề <h6> cột
        $about = $this->seedLocation('public_footer_about', 'Về Đông Sơn', 'Cột 2 của footer.', 0, 1, $now);
        foreach (array(
            array('Giới thiệu', '#gioi-thieu'),
            array('Tầm nhìn & Sứ mệnh', '#gioi-thieu'),
            array('Ban lãnh đạo', '#gioi-thieu'),
            array('Giá trị cốt lõi', '#gioi-thieu'),
            array('Trách nhiệm XH', '#gioi-thieu'),
        ) as $i => $row) {
            $this->seedItem($about, null, $row[0], $row[1], null, $i + 1, 0, $now);
        }

        $sectors = $this->seedLocation('public_footer_sectors', 'Lĩnh vực', 'Cột 3 của footer.', 0, 1, $now);
        foreach (array(
            array('Thi công & Xây lắp', '#linh-vuc'),
            array('Đầu tư BOT', '#linh-vuc'),
            array('Nhà ở & Đô thị', '#linh-vuc'),
            array('Năng lượng & KCN', '#linh-vuc'),
        ) as $i => $row) {
            $this->seedItem($sectors, null, $row[0], $row[1], null, $i + 1, 0, $now);
        }

        $projects = $this->seedLocation('public_footer_projects', 'Dự án', 'Cột 4 của footer.', 0, 1, $now);
        foreach (array(
            array('BOT Hà Nội – Bắc Giang', '#du-an'),
            array('Nhà ở XH Bãi Viên', '#du-an'),
            array('Cao tốc TQ–HG', '#du-an'),
            array('Mỹ Đình – Bái Đính', '#du-an'),
        ) as $i => $row) {
            $this->seedItem($projects, null, $row[0], $row[1], null, $i + 1, 0, $now);
        }

        $investors = $this->seedLocation('public_footer_investors', 'Nhà đầu tư', 'Cột 5 của footer.', 0, 1, $now);
        foreach (array(
            array('Báo cáo tài chính', '#co-dong'),
            array('Công bố thông tin', '#co-dong'),
            array('Báo cáo thường niên', '#co-dong'),
            array('ĐHĐCĐ 2026', '#co-dong'),
        ) as $i => $row) {
            $this->seedItem($investors, null, $row[0], $row[1], null, $i + 1, 0, $now);
        }
    }

    public function down()
    {
        // Gỡ các location frontend (items xoá theo FK CASCADE).
        $this->delete('pvn_menu_locations', "code IN ('public_header','public_footer_about',"
            . "'public_footer_sectors','public_footer_projects','public_footer_investors')");
        // Không khôi phục admin_sidebar: sidebar admin đã hardcode trở lại.
    }

    private function seedLocation($code, $name, $desc, $nesting, $maxDepth, $now)
    {
        $this->insert('pvn_menu_locations', array(
            'code' => $code, 'name' => $name, 'description' => $desc,
            'supports_nesting' => $nesting, 'max_depth' => $maxDepth, 'is_active' => 1,
            'created_at' => $now, 'updated_at' => $now,
        ));
        return $this->dbConnection->getLastInsertID();
    }

    private function seedItem($locationId, $parentId, $title, $url, $cssClass, $sort, $depth, $now)
    {
        $this->insert('pvn_menu_items', array(
            'location_id' => $locationId, 'parent_id' => $parentId,
            'title' => $title, 'item_type' => 'url', 'url' => $url, 'target' => '_self',
            'css_class' => $cssClass, 'sort_order' => $sort, 'depth' => $depth,
            'is_protected' => 0, 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now,
        ));
        return $this->dbConnection->getLastInsertID();
    }
}
