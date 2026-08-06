<?php
/**
 * Thư viện media: xem, tải lên, sửa alt text, xoá.
 */
class MediaController extends AdminController
{
    public $pageIcon = 'fa-picture-o';

    public function actionIndex()
    {
        $this->requirePermission('media.view');

        $criteria = new CDbCriteria();
        $criteria->condition = 'deleted_at IS NULL';
        $criteria->order = 'created_at DESC, id DESC';

        // Lọc nhanh các ảnh còn thiếu alt — lối vào từ cảnh báo ở dashboard.
        if (Yii::app()->request->getParam('missingAlt')) {
            $criteria->condition .= ' AND mime_type LIKE :m AND (alt_text IS NULL OR alt_text = "")';
            $criteria->params[':m'] = 'image/%';
        }

        $search = trim((string) Yii::app()->request->getParam('q', ''));
        if ($search !== '') {
            $criteria->condition .= ' AND (file_name LIKE :q OR alt_text LIKE :q)';
            $criteria->params[':q'] = '%' . $search . '%';
        }

        $dataProvider = new CActiveDataProvider('MediaFile', array(
            'criteria'   => $criteria,
            'pagination' => array('pageSize' => 48),
        ));

        $this->pageTitle = 'Thư viện media';
        $this->render('index', array(
            'dataProvider' => $dataProvider,
            'search'       => $search,
            'missingAlt'   => (bool) Yii::app()->request->getParam('missingAlt'),
            'uploadModel'  => new MediaFile(),
        ));
    }

    /**
     * Tải file lên. Chỉ nhận POST.
     */
    public function actionUpload()
    {
        $this->requirePermission('media.create');

        if (!Yii::app()->request->getIsPostRequest()) {
            throw new CHttpException(405, 'Tải lên chỉ chấp nhận phương thức POST.');
        }

        $files = CUploadedFile::getInstancesByName('mediaFiles');
        if ($files === array()) {
            $this->redirectWith(array('index'), 'error', 'Bạn chưa chọn file nào.');
        }

        $uploaded = 0;
        $errors = array();

        foreach ($files as $file) {
            $error = MediaHelper::validateUpload($file);
            if ($error !== null) {
                $errors[] = $file->getName() . ': ' . $error;
                continue;
            }

            $result = $this->storeFile($file);
            if ($result instanceof MediaFile) {
                $uploaded++;
            } else {
                $errors[] = $file->getName() . ': ' . $result;
            }
        }

        if ($uploaded > 0) {
            Yii::app()->user->setFlash('success', 'Đã tải lên ' . $uploaded . ' file. '
                . 'Đừng quên nhập mô tả (alt) cho ảnh.');
        }
        if ($errors !== array()) {
            Yii::app()->user->setFlash('error', implode(' | ', $errors));
        }

        $this->redirect(array('index'));
    }

    /**
     * Lưu file xuống đĩa và tạo bản ghi. Trả true hoặc thông báo lỗi.
     */
    private function storeFile(CUploadedFile $file)
    {
        $checksum = hash_file('sha256', $file->getTempName());

        $existing = MediaFile::model()->findByAttributes(array('checksum' => $checksum));
        if ($existing !== null) {
            return 'file này đã có trong thư viện (' . $existing->file_name . ').';
        }

        $mime = MediaHelper::detectMimeType($file->getTempName());
        $fileName = MediaHelper::buildFileName($file->getName(), $file->getExtensionName());
        $directory = MediaHelper::uploadDir();
        $fullPath = $directory . DIRECTORY_SEPARATOR . $fileName;

        if (!$file->saveAs($fullPath)) {
            return 'không ghi được file xuống đĩa.';
        }

        list($width, $height) = MediaHelper::readDimensions($fullPath, $mime);

        $relativePath = Yii::app()->params['uploadPath'] . '/' . date('Y') . date('m') . '/' . $fileName;

        $media = new MediaFile();
        $media->file_name   = $fileName;
        $media->file_path   = $relativePath;
        $media->mime_type   = $mime;
        $media->file_size   = filesize($fullPath);
        $media->width       = $width;
        $media->height      = $height;
        $media->checksum    = $checksum;
        $media->uploaded_by = Yii::app()->user->id;

        if (!$media->save()) {
            @unlink($fullPath);
            return 'không lưu được thông tin file: ' . implode(', ', $media->getErrors('file_name'));
        }

        return true;
    }

    /**
     * Sửa thông tin mô tả của file.
     */
    public function actionUpdate($id)
    {
        $this->requirePermission('media.update');

        $model = $this->loadModel('MediaFile', $id);
        $model->setScenario($model->getIsImage() ? 'image' : 'insert');

        $post = Yii::app()->request->getPost('MediaFile');
        if ($post !== null) {
            $model->attributes = $post;
            if ($model->save()) {
                $this->redirectWith(array('index'), 'success', 'Đã cập nhật thông tin file.');
            }
        }

        $this->pageTitle = 'Sửa thông tin file';
        $this->render('update', array('model' => $model));
    }

    public function actionDelete($id)
    {
        $this->requirePermission('media.delete');

        if (!Yii::app()->request->getIsPostRequest()) {
            throw new CHttpException(405, 'Thao tác xoá chỉ chấp nhận phương thức POST.');
        }

        $model = $this->loadModel('MediaFile', $id);

        // Xoá mềm: các bảng nội dung đang trỏ tới file này vẫn giữ được FK,
        // và có thể khôi phục nếu xoá nhầm. File vật lý không bị xoá.
        $model->softDelete();

        $this->redirectWith(array('index'), 'success',
            'Đã ẩn “' . $model->file_name . '” khỏi thư viện.');
    }
}
