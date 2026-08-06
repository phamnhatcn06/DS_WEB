<?php
/**
 * Quản trị Thẻ (Tag) dùng chung — gắn cho tin tức và lĩnh vực kinh doanh.
 */
class TagController extends AdminCrudController
{
    protected $modelClass         = 'Tag';
    protected $permissionResource = 'tags';
    protected $titleSingular      = 'Thẻ';
    protected $titlePlural        = 'Thẻ (Tag)';
    protected $defaultOrder       = 't.sort_order ASC, t.name ASC';

    public $pageIcon = 'fa-tags';

    protected function gridColumns()
    {
        return array(
            array('name' => 'name', 'label' => 'Tên thẻ', 'type' => 'primary',
                'sub' => array($this, 'slugSummary')),
            array('name' => 'usage', 'label' => 'Đang dùng', 'type' => 'callback',
                'value' => array($this, 'renderUsage'), 'width' => '180px'),
            array('name' => 'is_active', 'label' => 'Trạng thái', 'type' => 'bool', 'width' => '110px'),
        );
    }

    public function slugSummary($item)
    {
        return $item->slug;
    }

    /**
     * Đếm số nơi đang gắn thẻ này để biên tập viên biết mức độ ảnh hưởng.
     */
    public function renderUsage($item)
    {
        $badges = array();
        if ($item->newsCount > 0) {
            $badges[] = '<span class="badge bg-light text-dark border">'
                . (int) $item->newsCount . ' tin tức</span>';
        }
        if ($item->sectorCount > 0) {
            $badges[] = '<span class="badge bg-light text-dark border">'
                . (int) $item->sectorCount . ' lĩnh vực</span>';
        }
        return $badges === array()
            ? '<span class="text-muted small">Chưa dùng</span>'
            : implode(' ', $badges);
    }

    protected function formFields()
    {
        return array(
            array('name' => 'name', 'width' => 6),
            array('name' => 'slug', 'width' => 6,
                'hint' => 'Để trống sẽ tự sinh từ tên thẻ.'),
            array('name' => 'description', 'width' => 12,
                'hint' => 'Mô tả ngắn (không bắt buộc).'),
            array('name' => 'sort_order', 'type' => 'number', 'width' => 6),
            array('name' => 'is_active', 'type' => 'checkbox', 'width' => 6),
        );
    }
}
