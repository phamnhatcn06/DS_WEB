<?php
/**
 * Trỏ 4 menu con "Quan hệ cổ đông" sang controller Investor (bố cục report list)
 * thay cho News. Route đổi frontend/news/index?category=... → frontend/investor/index?category=...
 *
 * Bổ sung cho m260811_000000 (đã tạo danh mục + menu). Idempotent: chỉ cập nhật
 * đúng mục có route cũ.
 */
class m260811_010000_repoint_investor_menu_to_investor_controller extends CDbMigration
{
    /** slug danh mục ↔ tiêu đề mục menu con. */
    private $map = array(
        'bao-cao-tai-chinh'    => 'Báo cáo tài chính',
        'cong-bo-thong-tin'    => 'Công bố thông tin',
        'bao-cao-thuong-nien'  => 'Báo cáo thường niên',
        'dai-hoi-dong-co-dong' => 'Đại hội đồng cổ đông',
    );

    public function up()
    {
        $this->repoint('frontend/news/index?category=', 'frontend/investor/index?category=');
        $this->clearMenuCache();
    }

    public function down()
    {
        $this->repoint('frontend/investor/index?category=', 'frontend/news/index?category=');
        $this->clearMenuCache();
    }

    private function repoint($fromPrefix, $toPrefix)
    {
        $now = date('Y-m-d H:i:s');
        foreach ($this->map as $slug => $title) {
            $this->update('pvn_menu_items',
                array('route' => $toPrefix . $slug, 'updated_at' => $now),
                'route = :old',
                array(':old' => $fromPrefix . $slug)
            );
        }
    }

    private function clearMenuCache()
    {
        $locationId = Yii::app()->db->createCommand()
            ->select('id')->from('pvn_menu_locations')
            ->where('code = :c', array(':c' => 'public_header'))
            ->queryScalar();

        if ($locationId && Yii::app()->cache !== null) {
            Yii::app()->cache->delete(MenuHelper::CACHE_PREFIX . (int) $locationId);
        }
    }
}
