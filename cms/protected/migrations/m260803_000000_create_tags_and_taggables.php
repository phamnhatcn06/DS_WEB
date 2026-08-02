<?php
/**
 * Phase 2: tách "chip/tag" thành bảng tag dùng chung + liên kết nhiều-nhiều.
 *
 * - Tạo `pvn_tags` (danh sách tag tái sử dụng toàn site).
 * - Tạo 2 bảng liên kết: `pvn_news_post_tags`, `pvn_business_sector_tags`.
 * - Chuyển toàn bộ chip free-text đang lưu JSON trong `pvn_business_sectors.tags`
 *   sang tag dùng chung + liên kết, rồi bỏ cột `tags` cũ.
 * - Seed RBAC `tags.view/create/update/delete` cho admin/super_admin.
 * - Thêm mục "Thẻ (Tag)" vào sidebar admin.
 */
class m260803_000000_create_tags_and_taggables extends CDbMigration
{
    const TABLE_OPTIONS = 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci';

    private $operations = array(
        'tags.view'   => 'Xem — Thẻ (Tag)',
        'tags.create' => 'Thêm — Thẻ (Tag)',
        'tags.update' => 'Sửa — Thẻ (Tag)',
        'tags.delete' => 'Xoá — Thẻ (Tag)',
    );

    public function up()
    {
        // ------------------------------------------------------- bảng tag chính
        $this->createTable('pvn_tags', array(
            'id'          => 'INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
            'slug'        => 'VARCHAR(160) NOT NULL',
            'name'        => 'VARCHAR(150) NOT NULL',
            'description' => 'VARCHAR(255) NULL',
            'sort_order'  => 'INT NOT NULL DEFAULT 0',
            'is_active'   => 'TINYINT(1) NOT NULL DEFAULT 1',
            'created_at'  => 'DATETIME NULL',
            'updated_at'  => 'DATETIME NULL',
            'deleted_at'  => 'DATETIME NULL',
        ), self::TABLE_OPTIONS);
        $this->createIndex('uniq_tags_slug', 'pvn_tags', 'slug', true);
        $this->createIndex('idx_tags_sort_order', 'pvn_tags', 'sort_order');

        // ------------------------------------------- liên kết tag ↔ bài viết
        $this->createTable('pvn_news_post_tags', array(
            'post_id' => 'INT UNSIGNED NOT NULL',
            'tag_id'  => 'INT UNSIGNED NOT NULL',
        ), self::TABLE_OPTIONS);
        $this->addPrimaryKey('pk_news_post_tags', 'pvn_news_post_tags', array('post_id', 'tag_id'));
        $this->createIndex('idx_news_post_tags_tag', 'pvn_news_post_tags', 'tag_id');
        $this->addForeignKey('fk_news_post_tags_post', 'pvn_news_post_tags', 'post_id',
            'pvn_news_posts', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_news_post_tags_tag', 'pvn_news_post_tags', 'tag_id',
            'pvn_tags', 'id', 'CASCADE', 'CASCADE');

        // ------------------------------------------- liên kết tag ↔ lĩnh vực
        $this->createTable('pvn_business_sector_tags', array(
            'sector_id' => 'INT UNSIGNED NOT NULL',
            'tag_id'    => 'INT UNSIGNED NOT NULL',
        ), self::TABLE_OPTIONS);
        $this->addPrimaryKey('pk_business_sector_tags', 'pvn_business_sector_tags', array('sector_id', 'tag_id'));
        $this->createIndex('idx_business_sector_tags_tag', 'pvn_business_sector_tags', 'tag_id');
        $this->addForeignKey('fk_business_sector_tags_sector', 'pvn_business_sector_tags', 'sector_id',
            'pvn_business_sectors', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_business_sector_tags_tag', 'pvn_business_sector_tags', 'tag_id',
            'pvn_tags', 'id', 'CASCADE', 'CASCADE');

        // ------------------ chuyển chip free-text của lĩnh vực sang tag dùng chung
        $this->migrateSectorTags();

        // Bỏ cột JSON cũ — nguồn dữ liệu chip nay là bảng liên kết.
        $this->dropColumn('pvn_business_sectors', 'tags');

        // ------------------------------------------------------ RBAC: tags.*
        $auth = Yii::app()->authManager;
        if ($auth !== null) {
            foreach ($this->operations as $name => $description) {
                if ($auth->getAuthItem($name) === null) {
                    $auth->createOperation($name, $description, null, null);
                }
                foreach (array('admin', 'super_admin') as $role) {
                    if ($auth->getAuthItem($role) !== null && !$auth->hasItemChild($role, $name)) {
                        $auth->addItemChild($role, $name);
                    }
                }
            }
        }

        // ------------------------------------------ mục menu trong admin_sidebar
        $this->seedSidebarItem();

        $this->clearHomepageCache();
    }

    public function down()
    {
        // Khôi phục cột JSON để không mất khả năng rollback hoàn toàn.
        $this->addColumn('pvn_business_sectors', 'tags',
            "LONGTEXT NULL COMMENT 'Chip/tag; tách bảng riêng ở phase 2'");
        $this->restoreSectorTags();

        Yii::app()->db->createCommand()->delete('pvn_menu_items',
            'route = :route', array(':route' => '/admin/tag/index'));

        $auth = Yii::app()->authManager;
        if ($auth !== null) {
            foreach (array_keys($this->operations) as $name) {
                if ($auth->getAuthItem($name) !== null) {
                    $auth->removeAuthItem($name);
                }
            }
        }

        $this->dropTable('pvn_business_sector_tags');
        $this->dropTable('pvn_news_post_tags');
        $this->dropTable('pvn_tags');

        $this->clearHomepageCache();
    }

    // ------------------------------------------------------------------ helpers

    /**
     * Đọc JSON tags của từng lĩnh vực, tạo tag dùng chung (gộp trùng theo slug)
     * và ghi liên kết vào pvn_business_sector_tags.
     */
    private function migrateSectorTags()
    {
        $db = Yii::app()->db;
        $rows = $db->createCommand()
            ->select('id, tags')->from('pvn_business_sectors')
            ->where('tags IS NOT NULL')
            ->queryAll();

        $now = date('Y-m-d H:i:s');
        $sortOrder = 0;
        $tagIdBySlug = array();

        foreach ($rows as $row) {
            $names = json_decode((string) $row['tags'], true);
            if (!is_array($names)) {
                continue;
            }

            foreach ($names as $name) {
                $name = trim((string) $name);
                if ($name === '') {
                    continue;
                }
                $slug = TextHelper::slugify($name);
                if ($slug === '') {
                    continue;
                }

                if (!isset($tagIdBySlug[$slug])) {
                    $db->createCommand()->insert('pvn_tags', array(
                        'slug'       => $slug,
                        'name'       => $name,
                        'sort_order' => ++$sortOrder,
                        'is_active'  => 1,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ));
                    $tagIdBySlug[$slug] = (int) $db->getLastInsertID();
                }

                // Bỏ qua nếu liên kết đã tồn tại (một lĩnh vực lặp tag).
                $exists = $db->createCommand()
                    ->select('COUNT(*)')->from('pvn_business_sector_tags')
                    ->where('sector_id = :s AND tag_id = :t',
                        array(':s' => (int) $row['id'], ':t' => $tagIdBySlug[$slug]))
                    ->queryScalar();
                if ((int) $exists === 0) {
                    $db->createCommand()->insert('pvn_business_sector_tags', array(
                        'sector_id' => (int) $row['id'],
                        'tag_id'    => $tagIdBySlug[$slug],
                    ));
                }
            }
        }
    }

    /**
     * Rollback: ghi lại chip JSON cho từng lĩnh vực từ bảng liên kết.
     */
    private function restoreSectorTags()
    {
        $db = Yii::app()->db;
        $rows = $db->createCommand()
            ->select('st.sector_id, t.name')
            ->from('pvn_business_sector_tags st')
            ->join('pvn_tags t', 't.id = st.tag_id')
            ->queryAll();

        $bySector = array();
        foreach ($rows as $row) {
            $bySector[(int) $row['sector_id']][] = $row['name'];
        }
        foreach ($bySector as $sectorId => $names) {
            $db->createCommand()->update('pvn_business_sectors',
                array('tags' => json_encode($names, JSON_UNESCAPED_UNICODE)),
                'id = :id', array(':id' => $sectorId));
        }
    }

    /**
     * Thêm mục "Thẻ (Tag)" vào sidebar admin nếu chưa có.
     */
    private function seedSidebarItem()
    {
        $locationId = Yii::app()->db->createCommand()
            ->select('id')->from('pvn_menu_locations')
            ->where('code = :code', array(':code' => 'admin_sidebar'))
            ->queryScalar();

        if ($locationId === false || $locationId === null) {
            return;
        }

        $exists = Yii::app()->db->createCommand()
            ->select('COUNT(*)')->from('pvn_menu_items')
            ->where('location_id = :loc AND route = :route',
                array(':loc' => $locationId, ':route' => '/admin/tag/index'))
            ->queryScalar();
        if ((int) $exists > 0) {
            return;
        }

        $maxSort = (int) Yii::app()->db->createCommand()
            ->select('COALESCE(MAX(sort_order), 0)')->from('pvn_menu_items')
            ->where('location_id = :loc AND parent_id IS NULL', array(':loc' => $locationId))
            ->queryScalar();

        $now = date('Y-m-d H:i:s');
        $this->insert('pvn_menu_items', array(
            'location_id'  => $locationId,
            'parent_id'    => null,
            'title'        => 'Thẻ (Tag)',
            'item_type'    => 'route',
            'route'        => '/admin/tag/index',
            'target'       => '_self',
            'icon'         => 'fa-tags',
            'perm'         => 'tags.view',
            'sort_order'   => $maxSort + 1,
            'depth'        => 0,
            'is_protected' => 1,
            'is_active'    => 1,
            'created_at'   => $now,
            'updated_at'   => $now,
        ));
    }

    private function clearHomepageCache()
    {
        if (Yii::app()->hasComponent('cache') && Yii::app()->cache) {
            Yii::app()->cache->delete(BaseActiveRecord::CACHE_KEY_HOMEPAGE);
        }
    }
}
