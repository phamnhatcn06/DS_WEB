<?php
/**
 * Chuyển cụm menu "Về chúng tôi" (header) từ link file tĩnh (.html) sang route
 * nội bộ, để trỏ tới các trang động của module frontend:
 *
 *   Về chúng tôi        → frontend/about/index   (/gioi-thieu)
 *   Giới thiệu          → frontend/about/index   (/gioi-thieu)
 *   Sứ mệnh - Tầm nhìn  → frontend/sumenh/index  (/su-menh-tam-nhin)
 *   Sơ đồ tổ chức       → frontend/sodo/index    (/so-do-to-chuc)
 *
 * MenuHelper::publicHref sinh URL sạch qua createUrl cho mục item_type='route',
 * nên link đúng dù CMS chạy ở domain gốc hay thư mục con.
 */
class m260807_010000_convert_about_menu_to_routes extends CDbMigration
{
    /** Ánh xạ tiêu đề mục (trong header) → route nội bộ. */
    private function map()
    {
        return array(
            'Về chúng tôi'       => 'frontend/about/index',
            'Giới thiệu'         => 'frontend/about/index',
            'Sứ mệnh - Tầm nhìn' => 'frontend/sumenh/index',
            'Sơ đồ tổ chức'      => 'frontend/sodo/index',
        );
    }

    public function up()
    {
        $headerId = $this->headerLocationId();
        if ($headerId === null) {
            echo "    > Bỏ qua: chưa có location public_header.\n";
            return;
        }

        foreach ($this->map() as $title => $route) {
            $this->update('pvn_menu_items',
                array('item_type' => 'route', 'route' => $route, 'url' => null),
                'location_id = :loc AND title = :title',
                array(':loc' => $headerId, ':title' => $title));
        }

        $this->flushMenuCache();
    }

    public function down()
    {
        $headerId = $this->headerLocationId();
        if ($headerId === null) {
            return;
        }

        // Khôi phục về link file tĩnh như bản seed gốc (m260731_020000).
        $revert = array(
            'Về chúng tôi'       => 'about.html',
            'Giới thiệu'         => 'about.html',
            'Sứ mệnh - Tầm nhìn' => 'sumenh.html',
            'Sơ đồ tổ chức'      => 'sodo-to-chuc.html',
        );
        foreach ($revert as $title => $url) {
            $this->update('pvn_menu_items',
                array('item_type' => 'url', 'route' => null, 'url' => $url),
                'location_id = :loc AND title = :title',
                array(':loc' => $headerId, ':title' => $title));
        }

        $this->flushMenuCache();
    }

    /** Id của location menu header (null nếu chưa seed). */
    private function headerLocationId()
    {
        return $this->dbConnection->createCommand()
            ->select('id')->from('pvn_menu_locations')
            ->where('code = :c', array(':c' => 'public_header'))
            ->queryScalar() ?: null;
    }

    /** Xoá cache menu để đổi mục có hiệu lực ngay (guard vì console có thể thiếu cache). */
    private function flushMenuCache()
    {
        if (Yii::app()->hasComponent('cache') && Yii::app()->cache !== null) {
            Yii::app()->cache->flush();
        }
    }
}
