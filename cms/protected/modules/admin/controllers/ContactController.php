<?php
/**
 * Quản lý yêu cầu liên hệ gửi từ website (form "Liên hệ ngay").
 *
 * Danh sách + xử lý (đổi trạng thái, ghi chú nội bộ) + xoá. Không cho tạo mới
 * từ admin — bản ghi chỉ sinh ra từ form công khai.
 */
class ContactController extends AdminCrudController
{
    protected $modelClass         = 'ContactMessage';
    protected $permissionResource = 'contacts';
    protected $titleSingular      = 'Liên hệ';
    protected $titlePlural        = 'Yêu cầu liên hệ';
    protected $sortable           = false;
    protected $defaultOrder       = 't.created_at DESC, t.id DESC';
    protected $formView           = 'admin.views.contact.form';

    public $pageIcon = 'fa-envelope';

    /** Chặn tạo mới — liên hệ chỉ đến từ form công khai. */
    public function actionCreate()
    {
        throw new CHttpException(404, 'Không thể tạo yêu cầu liên hệ từ trang quản trị.');
    }

    protected function gridColumns()
    {
        return array(
            array('name' => 'full_name', 'label' => 'Người gửi', 'type' => 'primary',
                'sub' => array($this, 'renderContactSub')),
            array('name' => 'content', 'label' => 'Nội dung', 'type' => 'text', 'limit' => 70),
            array('name' => 'status', 'label' => 'Trạng thái', 'type' => 'callback',
                'value' => array($this, 'renderStatus'), 'width' => '130px'),
            array('name' => 'created_at', 'label' => 'Thời gian gửi', 'type' => 'callback',
                'value' => array($this, 'renderCreatedAt'), 'width' => '150px'),
        );
    }

    public function renderContactSub($item)
    {
        $parts = array($item->phone);
        if ($item->email) {
            $parts[] = $item->email;
        }
        return implode(' · ', $parts);
    }

    public function renderStatus($item)
    {
        $map = array(
            ContactMessage::STATUS_NEW        => 'bg-danger-subtle text-danger-emphasis',
            ContactMessage::STATUS_PROCESSING => 'bg-warning-subtle text-warning-emphasis',
            ContactMessage::STATUS_DONE       => 'bg-success-subtle text-success-emphasis',
        );
        $class = isset($map[$item->status]) ? $map[$item->status] : 'bg-secondary-subtle';
        return '<span class="badge ' . $class . '">' . CHtml::encode($item->getStatusLabel()) . '</span>';
    }

    public function renderCreatedAt($item)
    {
        return $item->created_at
            ? date('H:i · d/m/Y', strtotime($item->created_at))
            : '<span class="text-muted small">—</span>';
    }

    /**
     * Chỉ 2 trường được sửa: trạng thái + ghi chú nội bộ. Thông tin người gửi
     * hiển thị chỉ-đọc trong view form riêng.
     */
    protected function formFields()
    {
        return array(
            array('name' => 'status', 'type' => 'select', 'width' => 6,
                'options' => ContactMessage::contactStatusOptions()),
            array('name' => 'admin_note', 'type' => 'textarea', 'width' => 12, 'rows' => 4,
                'hint' => 'Ghi chú nội bộ về tiến độ xử lý (không hiển thị ra website).'),
        );
    }
}
