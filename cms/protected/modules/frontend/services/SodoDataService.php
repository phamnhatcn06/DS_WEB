<?php
/**
 * Nạp nội dung động cho trang Sơ đồ - Tổ chức.
 *
 * - Ban lãnh đạo: pvn_leaders (ảnh eager-load tránh N+1). Tách Chủ tịch
 *   (is_chairman = 1, thẻ bento lớn) khỏi lưới thành viên còn lại.
 * - Hệ thống phân cấp: pvn_org_units, dựng cây theo parent_id.
 *
 * Văn bản hero/tiêu đề lấy ở view qua SiteSetting::get (nhóm `sodo`).
 */
class SodoDataService
{
    public static function load()
    {
        $leaders = Leader::model()->with('photo')->active()->findAll();

        $chairman = null;
        $members  = array();
        foreach ($leaders as $leader) {
            if ($chairman === null && (int) $leader->is_chairman === 1) {
                $chairman = $leader;
            } else {
                $members[] = $leader;
            }
        }

        return array(
            'chairman' => $chairman,
            'members'  => $members,
            'orgTree'  => self::buildOrgTree(),
        );
    }

    /**
     * Dựng cây đơn vị từ danh sách phẳng (một truy vấn, không N+1).
     *
     * @return array[] mỗi node: ['unit' => OrgUnit, 'children' => array[]]
     */
    private static function buildOrgTree()
    {
        $units = OrgUnit::model()->active()->findAll(array('order' => 't.level ASC, t.sort_order ASC'));

        $byParent = array();
        foreach ($units as $unit) {
            $byParent[(int) $unit->parent_id][] = $unit;
        }

        $build = function ($parentId) use (&$build, $byParent) {
            $branch = array();
            if (!isset($byParent[$parentId])) {
                return $branch;
            }
            foreach ($byParent[$parentId] as $unit) {
                $branch[] = array('unit' => $unit, 'children' => $build((int) $unit->id));
            }
            return $branch;
        };

        return $build(0);
    }
}
