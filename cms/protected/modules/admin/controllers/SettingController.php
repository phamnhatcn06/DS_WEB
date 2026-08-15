<?php
/**
 * Cấu hình website — sửa nhiều khoá cùng lúc, nhóm theo tab.
 */
class SettingController extends AdminController
{
    public $pageIcon = 'fa-cog';

    public function actionIndex()
    {
        $this->requirePermission('settings.view');

        $post = Yii::app()->request->getPost('Setting');
        if ($post !== null) {
            $this->requirePermission('settings.update');
            $this->saveSettings($post);
        }

        $settings = SiteSetting::model()->findAll(array(
            'order' => 'group_name ASC, sort_order ASC',
        ));

        // Gom theo nhóm để render thành tab.
        $grouped = array();
        foreach ($settings as $setting) {
            $grouped[$setting->group_name][] = $setting;
        }

        // Sắp nhóm theo thứ tự khai báo trong groupLabels() (Thông tin chung
        // trước), thay vì theo alphabet group_name — để tab đầu tiên đúng ý đồ.
        $labels  = SiteSetting::groupLabels();
        $ordered = array();
        foreach (array_keys($labels) as $group) {
            if (isset($grouped[$group])) {
                $ordered[$group] = $grouped[$group];
            }
        }
        foreach ($grouped as $group => $items) {
            if (!isset($ordered[$group])) {
                $ordered[$group] = $items; // nhóm chưa khai báo nhãn — giữ cuối.
            }
        }

        $this->pageTitle = 'Cấu hình website';
        $this->render('index', array(
            'grouped' => $ordered,
            'labels'  => $labels,
        ));
    }

    /**
     * Lưu hàng loạt trong một transaction: hoặc tất cả đổi, hoặc không đổi gì.
     */
    private function saveSettings($values)
    {
        $transaction = Yii::app()->db->beginTransaction();
        try {
            $changed = 0;
            foreach ($values as $key => $value) {
                // Khoá chọn nhiều (category_multi) gửi lên dạng mảng → chuẩn hoá
                // về JSON mảng số nguyên trước khi so sánh & lưu.
                if (is_array($value)) {
                    $value = json_encode(
                        array_values(array_map('intval', array_filter($value, 'strlen'))),
                        JSON_UNESCAPED_UNICODE
                    );
                }
                $setting = SiteSetting::model()->findByAttributes(array('setting_key' => $key));
                if ($setting === null) {
                    continue; // bỏ qua khoá lạ gửi kèm từ client
                }
                if ((string) $setting->setting_value === (string) $value) {
                    continue;
                }
                $setting->setting_value = $value;
                if (!$setting->save()) {
                    throw new CException('Không lưu được khoá "' . $key . '".');
                }
                $changed++;
            }
            $transaction->commit();

            // Nội dung đổi → xoá cache trang chủ.
            Yii::app()->cache->delete(BaseActiveRecord::CACHE_KEY_HOMEPAGE);

            $this->redirectWith(array('index'), 'success',
                $changed > 0 ? 'Đã lưu ' . $changed . ' thay đổi.' : 'Không có thay đổi nào.');
        } catch (Exception $e) {
            $transaction->rollback();
            Yii::log('Lưu cấu hình thất bại: ' . $e->getMessage(), CLogger::LEVEL_ERROR, 'app');
            $this->redirectWith(array('index'), 'error',
                'Lưu cấu hình thất bại, không có thay đổi nào được áp dụng.');
        }
    }
}
