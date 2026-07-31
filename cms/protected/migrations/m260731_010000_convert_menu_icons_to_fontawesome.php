<?php
/**
 * Chuyển icon menu từ Bootstrap Icons (bi-*) sang Font Awesome 4.7 (fa-*).
 *
 * Toàn bộ giao diện admin đã đổi sang Font Awesome (bootstrap-icons.css không
 * còn được nạp), nên các mục menu đã seed trong `pvn_menu_items` phải đổi tên
 * class icon tương ứng, nếu không sidebar sẽ render `fa bi-*` (mất icon).
 */
class m260731_010000_convert_menu_icons_to_fontawesome extends CDbMigration
{
    /** Ánh xạ bi-* → fa-* (đồng bộ với chuyển đổi trong view/controller). */
    private $map = array(
        'bi-diagram-3'          => 'fa-sitemap',
        'bi-buildings'          => 'fa-building',
        'bi-images'             => 'fa-clone',
        'bi-image'              => 'fa-picture-o',
        'bi-people'             => 'fa-users',
        'bi-newspaper'          => 'fa-newspaper-o',
        'bi-list-nested'        => 'fa-list-ul',
        'bi-gear'               => 'fa-cog',
        'bi-clock-history'      => 'fa-history',
        'bi-award'              => 'fa-trophy',
        'bi-tags'               => 'fa-tags',
        'bi-speedometer2'       => 'fa-tachometer',
        'bi-person-badge'       => 'fa-id-badge',
        'bi-journal-text'       => 'fa-file-text-o',
    );

    public function up()
    {
        // Lưu ý: điều kiện dùng param tên khác `:old` — Yii tự đặt param SET
        // theo tên cột (`:icon`); trùng tên sẽ khiến WHERE khớp 0 dòng.
        foreach ($this->map as $bi => $fa) {
            $this->getDbConnection()->createCommand()->update(
                'pvn_menu_items',
                array('icon' => $fa),
                'icon = :old',
                array(':old' => $bi)
            );
        }
        // Xoá cache menu để render lại với icon mới.
        if (Yii::app()->cache !== null) {
            Yii::app()->cache->flush();
        }
    }

    public function down()
    {
        foreach ($this->map as $bi => $fa) {
            $this->getDbConnection()->createCommand()->update(
                'pvn_menu_items',
                array('icon' => $bi),
                'icon = :icon',
                array(':icon' => $fa)
            );
        }
        if (Yii::app()->cache !== null) {
            Yii::app()->cache->flush();
        }
    }
}
