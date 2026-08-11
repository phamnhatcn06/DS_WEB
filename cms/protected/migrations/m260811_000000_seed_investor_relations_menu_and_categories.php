<?php
/**
 * Nạp danh mục tin tức cho Quan hệ cổ đông & cấu hình menu con của Quan hệ cổ đông trong CMS.
 *
 * - Khởi tạo 5 danh mục tin tức liên quan đến Quan hệ cổ đông nếu chưa có.
 * - Cập nhật mục "Quan hệ cổ đông" trên public_header thành dạng dropdown (css_class='nav-caret').
 * - Nạp 4 mục menu con dưới "Quan hệ cổ đông" trỏ đến từng danh mục tin tức tương ứng.
 */
class m260811_000000_seed_investor_relations_menu_and_categories extends CDbMigration
{
    public function up()
    {
        $now = date('Y-m-d H:i:s');

        // 1. Nạp/đảm bảo các danh mục tin tức cho Quan hệ cổ đông
        $categories = array(
            array('co-dong', 'Quan hệ cổ đông'),
            array('bao-cao-tai-chinh', 'Báo cáo tài chính'),
            array('cong-bo-thong-tin', 'Công bố thông tin'),
            array('bao-cao-thuong-nien', 'Báo cáo thường niên'),
            array('dai-hoi-dong-co-dong', 'Đại hội đồng cổ đông'),
        );

        $order = 10;
        foreach ($categories as $cat) {
            $existing = Yii::app()->db->createCommand()
                ->select('id')->from('pvn_news_categories')
                ->where('slug = :s AND deleted_at IS NULL', array(':s' => $cat[0]))
                ->queryScalar();

            if (!$existing) {
                $this->insert('pvn_news_categories', array(
                    'slug'           => $cat[0],
                    'name'           => $cat[1],
                    'sort_order'     => ++$order,
                    'show_in_filter' => 1,
                    'is_active'      => 1,
                    'created_at'     => $now,
                    'updated_at'     => $now,
                ));
            }
        }

        // 2. Tìm location header public
        $locationId = Yii::app()->db->createCommand()
            ->select('id')->from('pvn_menu_locations')
            ->where('code = :c', array(':c' => 'public_header'))
            ->queryScalar();

        if (!$locationId) {
            return;
        }

        // 3. Tìm hoặc tạo mục "Quan hệ cổ đông" (mục cha)
        $parent = Yii::app()->db->createCommand()
            ->select('*')->from('pvn_menu_items')
            ->where('location_id = :l AND title = :t AND deleted_at IS NULL', array(
                ':l' => $locationId,
                ':t' => 'Quan hệ cổ đông',
            ))->queryRow();

        if ($parent) {
            $parentId = (int) $parent['id'];
            $this->update('pvn_menu_items', array(
                'css_class'  => 'nav-caret',
                'updated_at' => $now,
            ), 'id = :id', array(':id' => $parentId));
        } else {
            $this->insert('pvn_menu_items', array(
                'location_id'  => $locationId,
                'parent_id'    => null,
                'title'        => 'Quan hệ cổ đông',
                'item_type'    => 'url',
                'url'          => '#co-dong',
                'css_class'    => 'nav-caret',
                'sort_order'   => 4,
                'depth'        => 0,
                'is_protected' => 0,
                'is_active'    => 1,
                'created_at'   => $now,
                'updated_at'   => $now,
            ));
            $parentId = $this->dbConnection->getLastInsertID();
        }

        // 4. Thêm các menu con dưới Quan hệ cổ đông
        $submenus = array(
            array('Báo cáo tài chính',     'frontend/news/index?category=bao-cao-tai-chinh', 1),
            array('Công bố thông tin',     'frontend/news/index?category=cong-bo-thong-tin', 2),
            array('Báo cáo thường niên',   'frontend/news/index?category=bao-cao-thuong-nien', 3),
            array('Đại hội đồng cổ đông', 'frontend/news/index?category=dai-hoi-dong-co-dong', 4),
        );

        foreach ($submenus as $sub) {
            $exists = Yii::app()->db->createCommand()
                ->select('id')->from('pvn_menu_items')
                ->where('location_id = :l AND parent_id = :p AND title = :t AND deleted_at IS NULL', array(
                    ':l' => $locationId,
                    ':p' => $parentId,
                    ':t' => $sub[0],
                ))->queryScalar();

            if (!$exists) {
                $this->insert('pvn_menu_items', array(
                    'location_id'  => $locationId,
                    'parent_id'    => $parentId,
                    'title'        => $sub[0],
                    'item_type'    => 'route',
                    'route'        => $sub[1],
                    'target'       => '_self',
                    'sort_order'   => $sub[2],
                    'depth'        => 1,
                    'is_protected' => 0,
                    'is_active'    => 1,
                    'created_at'   => $now,
                    'updated_at'   => $now,
                ));
            }
        }

        // 5. Xoá cache menu
        if (Yii::app()->cache !== null) {
            Yii::app()->cache->delete(MenuHelper::CACHE_PREFIX . (int) $locationId);
        }
    }

    public function down()
    {
        // Gỡ bỏ các menu con dưới Quan hệ cổ đông nếu rollback
        $locationId = Yii::app()->db->createCommand()
            ->select('id')->from('pvn_menu_locations')
            ->where('code = :c', array(':c' => 'public_header'))
            ->queryScalar();

        if ($locationId) {
            $parentId = Yii::app()->db->createCommand()
                ->select('id')->from('pvn_menu_items')
                ->where('location_id = :l AND title = :t AND deleted_at IS NULL', array(
                    ':l' => $locationId,
                    ':t' => 'Quan hệ cổ đông',
                ))->queryScalar();

            if ($parentId) {
                $this->delete('pvn_menu_items', 'parent_id = :p', array(':p' => $parentId));
            }
        }
    }
}
