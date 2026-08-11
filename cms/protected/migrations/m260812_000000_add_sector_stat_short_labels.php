<?php
/**
 * Nhãn số liệu NGẮN cho khối trích dẫn (Section 2 — Di sản) trang chi tiết lĩnh vực.
 *
 * Theo Figma, Section 2 (panel trích dẫn) và Section 3 (Kế Thừa Di Sản) dùng CÙNG
 * cặp số liệu nhưng nhãn khác nhau:
 *   - Section 2: nhãn ngắn, không gạch đỏ  → "Dự án" / "Nhân sự"
 *   - Section 3: nhãn dài, có gạch đỏ       → "Dự án hoàn thành" / "Nhân sự chất lượng cao"
 *
 * Bổ sung 2 cột stat*_short_label (nullable). Bỏ trống → view tự fallback về nhãn dài.
 * Seed sẵn nhãn ngắn hợp lý cho 4 lĩnh vực có sẵn (admin chỉnh lại tuỳ ý).
 */
class m260812_000000_add_sector_stat_short_labels extends CDbMigration
{
    /** slug lĩnh vực → [nhãn ngắn số liệu 1, nhãn ngắn số liệu 2]. */
    private function shortLabels()
    {
        return array(
            'thi-cong-xay-lap'    => array('Dự án', 'Nhân sự'),
            'dau-tu-bot-ha-tang'  => array('Đầu tư BOT', 'Kinh nghiệm'),
            'nha-o-do-thi'        => array('Căn hộ', 'Vốn đầu tư'),
            'nang-luong-kcn'      => array('Năng lượng', 'Quỹ đất'),
        );
    }

    public function up()
    {
        $t = 'pvn_business_sectors';
        $this->addColumn($t, 'stat1_short_label', 'VARCHAR(60) NULL AFTER stat1_label');
        $this->addColumn($t, 'stat2_short_label', 'VARCHAR(60) NULL AFTER stat2_label');

        foreach ($this->shortLabels() as $slug => $labels) {
            $this->update($t,
                array('stat1_short_label' => $labels[0], 'stat2_short_label' => $labels[1]),
                'slug = :slug', array(':slug' => $slug));
        }
    }

    public function down()
    {
        $t = 'pvn_business_sectors';
        $this->dropColumn($t, 'stat1_short_label');
        $this->dropColumn($t, 'stat2_short_label');
    }
}
