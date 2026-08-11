<?php
/**
 * Tiếp nhận form "Liên hệ ngay" từ website và lưu vào DB.
 *
 * Chỉ phục vụ AJAX POST từ popup liên hệ trong layout main.php. Trả JSON để
 * JavaScript hiển thị thông báo mà không cần tải lại trang.
 */
class ContactController extends FrontendController
{
    /**
     * Lưu một yêu cầu liên hệ. Nhận POST (đã qua kiểm tra CSRF của Yii).
     */
    public function actionSubmit()
    {
        if (!Yii::app()->request->getIsPostRequest()) {
            throw new CHttpException(405, 'Thao tác này chỉ chấp nhận phương thức POST.');
        }

        $model = new ContactMessage();
        $post = Yii::app()->request->getPost('ContactMessage', array());

        $model->full_name = isset($post['full_name']) ? trim((string) $post['full_name']) : '';
        $model->phone     = isset($post['phone']) ? trim((string) $post['phone']) : '';
        $model->email     = isset($post['email']) ? trim((string) $post['email']) : '';
        $model->content   = isset($post['content']) ? trim((string) $post['content']) : '';
        $model->status    = ContactMessage::STATUS_NEW;
        $model->ip_address = Yii::app()->request->getUserHostAddress();
        $model->user_agent = substr((string) Yii::app()->request->getUserAgent(), 0, 255);

        if (!$model->save()) {
            $this->renderJson(array(
                'success' => false,
                'message' => 'Thông tin chưa hợp lệ, vui lòng kiểm tra lại.',
                'errors'  => $model->getErrors(),
            ), 422);
        }

        $this->renderJson(array(
            'success' => true,
            'message' => 'Cảm ơn bạn! Chúng tôi đã nhận được thông tin và sẽ liên hệ lại sớm.',
        ));
    }

    /**
     * Trả JSON và kết thúc request.
     */
    private function renderJson($data, $statusCode = 200)
    {
        header('Content-Type: application/json; charset=utf-8', true, $statusCode);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        Yii::app()->end();
    }
}
