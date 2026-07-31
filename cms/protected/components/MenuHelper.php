<?php
/**
 * Render menu động từ DB thay cho mảng hardcode.
 *
 * - Đọc mục menu của một location, cache theo location (không phụ thuộc user).
 * - Lọc RBAC theo `perm` mỗi request (rẻ, không chạm DB).
 * - Render markup sidebar Hope UI (divider / leaf / submenu collapse).
 *
 * Cache tự động xoá khi MenuItem::afterSave (gồm create/update/soft-delete/reorder).
 */
class MenuHelper
{
    /** Tiền tố khoá cache theo location id. */
    const CACHE_PREFIX = 'dsh.menu.items.';

    /**
     * Mảng row (plain) của mọi mục đang hiển thị trong location, đã cache.
     * Không lọc RBAC ở đây để cache dùng chung cho mọi vai trò.
     *
     * @return array[] mỗi phần tử là mảng thuộc tính mục
     */
    public static function getItems($locationId)
    {
        $cache = Yii::app()->cache;
        $key = self::CACHE_PREFIX . (int) $locationId;

        if ($cache !== null && ($cached = $cache->get($key)) !== false) {
            return $cached;
        }

        $rows = Yii::app()->db->createCommand()
            ->select('id, parent_id, title, item_type, route, url, target, icon, perm, css_class, depth, sort_order')
            ->from('pvn_menu_items')
            ->where('location_id = :l AND is_active = 1 AND deleted_at IS NULL', array(':l' => (int) $locationId))
            ->order('sort_order ASC')
            ->queryAll();

        if ($cache !== null) {
            $cache->set($key, $rows, 3600);
        }
        return $rows;
    }

    /** Xoá cache của một location. */
    public static function clearCache($locationId)
    {
        if (Yii::app()->cache !== null) {
            Yii::app()->cache->delete(self::CACHE_PREFIX . (int) $locationId);
        }
    }

    /**
     * Render sidebar admin cho một location code.
     *
     * @param string      $code         mã location (vd 'admin_sidebar')
     * @param CController  $controller   để tạo URL nội bộ
     * @param string      $currentRoute route hiện tại để tô đậm mục active
     * @return string HTML các <li>
     */
    public static function renderSidebar($code, $controller, $currentRoute)
    {
        $location = MenuLocation::findByCode($code);
        if ($location === null) {
            return '';
        }

        $tree = self::filteredTree(self::getItems($location->id), Yii::app()->user);
        $html = '';
        foreach ($tree as $node) {
            $html .= self::renderNode($node, $controller, $currentRoute);
        }
        return $html;
    }

    // =================================================================
    //  FRONTEND (website công khai) — không lọc RBAC (khách xem được hết)
    // =================================================================

    /**
     * Render thanh menu header website (public_header) — markup navbar Bootstrap
     * của index.html: mục có con → dropdown `.dsh-dropdown`.
     *
     * @return string HTML các <li class="nav-item">
     */
    public static function renderPublicNav($code, $base = '')
    {
        $tree = self::publicTree($code);
        $html = '';
        foreach ($tree as $node) {
            $row = $node['row'];
            $href = CHtml::encode(self::resolveUrl($row['url'], $base));
            $target = $row['target'] && $row['target'] !== '_self'
                ? ' target="' . CHtml::encode($row['target']) . '"' : '';
            $caret = strpos((string) $row['css_class'], 'nav-caret') !== false ? ' nav-caret' : '';

            if (!empty($node['children'])) {
                $items = '';
                foreach ($node['children'] as $child) {
                    $c = $child['row'];
                    $items .= '<li><a class="dropdown-item" href="' . CHtml::encode(self::resolveUrl($c['url'], $base)) . '">'
                        . CHtml::encode($c['title']) . '</a></li>';
                }
                $html .= '<li class="nav-item dropdown">'
                    . '<a class="nav-link nav-caret" href="' . $href . '" role="button"'
                    . ' data-bs-toggle="dropdown" aria-expanded="false">' . CHtml::encode($row['title']) . '</a>'
                    . '<ul class="dropdown-menu dsh-dropdown">' . $items . '</ul></li>';
            } else {
                $html .= '<li class="nav-item"><a class="nav-link' . $caret . '" href="' . $href . '"' . $target . '>'
                    . CHtml::encode($row['title']) . '</a></li>';
            }
        }
        return $html;
    }

    /**
     * Render một cột link footer (danh sách phẳng) — markup `.footer-link`.
     *
     * @return string HTML các <li>
     */
    public static function renderFooterColumn($code, $base = '')
    {
        $tree = self::publicTree($code);
        $html = '';
        foreach ($tree as $node) {
            $row = $node['row'];
            $href = CHtml::encode(self::resolveUrl($row['url'], $base));
            $target = $row['target'] && $row['target'] !== '_self'
                ? ' target="' . CHtml::encode($row['target']) . '"' : '';
            $html .= '<li><a class="footer-link" href="' . $href . '"' . $target . '>'
                . CHtml::encode($row['title']) . '</a></li>';
        }
        return $html;
    }

    /**
     * Phân giải URL của mục menu với base URL của frontend.
     * Giữ nguyên link tuyệt đối, neo (#...), và đường dẫn gốc (/...);
     * chỉ prefix base cho link tương đối (vd about.html).
     */
    protected static function resolveUrl($url, $base)
    {
        $url = (string) $url;
        if ($url === '') {
            return '#';
        }
        if ($base === '' || $url[0] === '#' || $url[0] === '/'
            || strpos($url, '://') !== false || strpos($url, '//') === 0) {
            return $url;
        }
        return rtrim($base, '/') . '/' . $url;
    }

    /** Tên hiển thị của location (dùng làm tiêu đề cột footer). */
    public static function locationName($code)
    {
        $location = MenuLocation::findByCode($code);
        return $location !== null ? $location->name : '';
    }

    /** Cây các mục active của location, KHÔNG lọc RBAC (dùng cho frontend). */
    protected static function publicTree($code)
    {
        $location = MenuLocation::findByCode($code);
        if ($location === null) {
            return array();
        }
        return self::filteredTree(self::getItems($location->id), null);
    }

    /**
     * Dựng cây đã lọc RBAC. Mục không đạt quyền bị loại BỎ cả nhánh con.
     * $user = null → bỏ qua lọc RBAC (chỉ dựa vào is_active, dùng cho frontend).
     */
    protected static function filteredTree(array $rows, $user)
    {
        $byParent = array();
        foreach ($rows as $row) {
            $byParent[(int) $row['parent_id']][] = $row;
        }

        $build = function ($parentId) use (&$build, $byParent, $user) {
            $branch = array();
            if (!isset($byParent[$parentId])) {
                return $branch;
            }
            foreach ($byParent[$parentId] as $row) {
                $isDivider = $row['item_type'] === MenuItem::TYPE_DIVIDER;
                $allowed = $isDivider || empty($row['perm'])
                    || ($user !== null && $user->checkAccess($row['perm']));
                if (!$allowed) {
                    continue;
                }
                $branch[] = array('row' => $row, 'children' => $build((int) $row['id']));
            }
            return $branch;
        };

        return $build(0);
    }

    /** Render một mục (đệ quy cho submenu). */
    protected static function renderNode($node, $controller, $currentRoute)
    {
        $row = $node['row'];
        $children = $node['children'];

        // Divider → nhãn nhóm tĩnh.
        if ($row['item_type'] === MenuItem::TYPE_DIVIDER) {
            return '<li class="nav-item static-item">'
                . '<a class="nav-link static-item disabled" href="#" tabindex="-1">'
                . '<span class="default-icon">' . CHtml::encode($row['title']) . '</span>'
                . '<span class="mini-icon">-</span></a></li>';
        }

        $icon = $row['icon']
            ? '<i class="icon"><i class="fa ' . CHtml::encode($row['icon']) . '"></i></i>'
            : '<i class="icon"><i class="fa fa-circle"></i></i>';
        $name = '<span class="item-name">' . CHtml::encode($row['title']) . '</span>';

        // Có mục con hiển thị → parent dạng collapse.
        if (!empty($children)) {
            $submenuId = 'submenu-' . (int) $row['id'];
            $expanded = self::branchHasActive($node, $currentRoute);

            $childHtml = '';
            foreach ($children as $child) {
                $childHtml .= self::renderNode($child, $controller, $currentRoute);
            }

            return '<li class="nav-item">'
                . '<a class="nav-link' . ($expanded ? '' : ' collapsed') . '" data-bs-toggle="collapse"'
                . ' href="#' . $submenuId . '" role="button" aria-expanded="' . ($expanded ? 'true' : 'false') . '"'
                . ' aria-controls="' . $submenuId . '">'
                . $icon . $name
                . '<i class="right-icon"><svg class="icon-18" xmlns="http://www.w3.org/2000/svg" width="18"'
                . ' fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round"'
                . ' stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg></i></a>'
                . '<ul class="sub-nav collapse' . ($expanded ? ' show' : '') . '" id="' . $submenuId . '"'
                . ' data-bs-parent="#sidebar-menu">' . $childHtml . '</ul></li>';
        }

        // Lá → link thường (route nội bộ hoặc url ngoài).
        $isActive = $row['item_type'] === MenuItem::TYPE_ROUTE && $currentRoute === $row['route'];
        if ($row['item_type'] === MenuItem::TYPE_URL) {
            $href = $row['url'];
            $target = ' target="' . CHtml::encode($row['target'] ?: '_self') . '"';
        } else {
            $href = $controller->createUrl($row['route']);
            $target = '';
        }

        return '<li class="nav-item">'
            . '<a class="nav-link' . ($isActive ? ' active' : '') . '" href="' . CHtml::encode($href) . '"' . $target
            . ' aria-current="' . ($isActive ? 'page' : 'false') . '">'
            . $icon . $name . '</a></li>';
    }

    /** Có mục lá nào trong nhánh trùng route hiện tại không (để mở sẵn submenu). */
    protected static function branchHasActive($node, $currentRoute)
    {
        foreach ($node['children'] as $child) {
            $row = $child['row'];
            if ($row['item_type'] === MenuItem::TYPE_ROUTE && $currentRoute === $row['route']) {
                return true;
            }
            if (!empty($child['children']) && self::branchHasActive($child, $currentRoute)) {
                return true;
            }
        }
        return false;
    }
}
