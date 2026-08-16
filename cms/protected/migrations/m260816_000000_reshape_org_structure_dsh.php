<?php
/**
 * Dựng lại "Hệ thống phân cấp" (pvn_org_units) theo sơ đồ tổ chức chính thức của DSH.
 *
 * Cây mới (được render đệ quy, mọi độ sâu):
 *   Đại hội đồng cổ đông
 *     ├─ Văn phòng Hội đồng quản trị
 *     ├─ Hội đồng quản trị
 *     │    └─ Ban Tổng giám đốc
 *     │         ├─ Văn phòng Giao dịch Đông Anh
 *     │         ├─ Phòng Hành chính - Quản trị
 *     │         ├─ Phòng Kế toán - Tài chính
 *     │         ├─ Phòng Kế hoạch - Kỹ thuật
 *     │         └─ Các chi nhánh
 *     └─ Ban Kiểm soát
 *
 * Thay cho dữ liệu mẫu 3 cấp cũ (HĐQT → Ban TGĐ → 4 khối) ở migration
 * m260807_000000_create_org_structure.
 */
class m260816_000000_reshape_org_structure_dsh extends CDbMigration
{
    public function up()
    {
        $now = date('Y-m-d H:i:s');

        // Xoá sạch dữ liệu sơ đồ cũ (FK tự tham chiếu ON DELETE SET NULL → an toàn).
        $this->execute('SET FOREIGN_KEY_CHECKS = 0');
        $this->delete('pvn_org_units');
        $this->execute('SET FOREIGN_KEY_CHECKS = 1');

        // Cấp 1 — gốc
        $rootId = $this->insertUnit(null, 'Đại hội đồng cổ đông', OrgUnit::LEVEL_BOARD, 1, $now);

        // Cấp 2 — trực thuộc gốc (trái → phải)
        $this->insertUnit($rootId, 'Văn phòng Hội đồng quản trị', OrgUnit::LEVEL_EXEC, 1, $now);
        $boardId = $this->insertUnit($rootId, 'Hội đồng quản trị', OrgUnit::LEVEL_EXEC, 2, $now);
        $this->insertUnit($rootId, 'Ban Kiểm soát', OrgUnit::LEVEL_EXEC, 3, $now);

        // Cấp 3 — Ban Tổng giám đốc (dưới Hội đồng quản trị)
        $execId = $this->insertUnit($boardId, 'Ban Tổng giám đốc', OrgUnit::LEVEL_DEPT, 1, $now);

        // Cấp 4 — các phòng / đơn vị dưới Ban Tổng giám đốc
        $depts = array(
            'Văn phòng Giao dịch Đông Anh',
            'Phòng Hành chính - Quản trị',
            'Phòng Kế toán - Tài chính',
            'Phòng Kế hoạch - Kỹ thuật',
            'Các chi nhánh',
        );
        foreach ($depts as $i => $dept) {
            $this->insertUnit($execId, $dept, OrgUnit::LEVEL_DEPT, $i + 1, $now);
        }
    }

    public function down()
    {
        $now = date('Y-m-d H:i:s');

        // Khôi phục dữ liệu mẫu 3 cấp gốc (như migration tạo bảng ban đầu).
        $this->execute('SET FOREIGN_KEY_CHECKS = 0');
        $this->delete('pvn_org_units');
        $this->execute('SET FOREIGN_KEY_CHECKS = 1');

        $boardId = $this->insertUnit(null, 'Hội đồng quản trị', OrgUnit::LEVEL_BOARD, 1, $now);
        $execId  = $this->insertUnit($boardId, 'Ban Tổng giám đốc', OrgUnit::LEVEL_EXEC, 1, $now);

        $depts = array('Khối Đầu tư', 'Khối Tài chính', 'Khối Kỹ thuật', 'Khối Hành chính');
        foreach ($depts as $i => $dept) {
            $this->insertUnit($execId, $dept, OrgUnit::LEVEL_DEPT, $i + 1, $now);
        }
    }

    /** Chèn một đơn vị, trả về id vừa tạo. */
    private function insertUnit($parentId, $name, $level, $sort, $now)
    {
        $this->insert('pvn_org_units', array(
            'parent_id'  => $parentId,
            'name'       => $name,
            'level'      => $level,
            'sort_order' => $sort,
            'is_active'  => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ));
        return (int) Yii::app()->db->getLastInsertID();
    }
}
