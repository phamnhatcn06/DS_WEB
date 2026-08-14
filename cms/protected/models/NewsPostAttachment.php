<?php
/**
 * Liên kết file đính kèm của bài viết (pvn_news_post_attachments).
 *
 * Bảng junction đơn giản: chỉ dùng làm đích của quan hệ NewsPost::attachments để
 * nạp media theo thứ tự. Việc ghi dữ liệu do NewsPost::syncAttachments đảm nhận
 * (insert phẳng), nên model này không cần behavior soft-delete/timestamp.
 */
class NewsPostAttachment extends CActiveRecord
{
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'pvn_news_post_attachments';
    }

    public function relations()
    {
        return array(
            'post'  => array(self::BELONGS_TO, 'NewsPost', 'post_id'),
            'media' => array(self::BELONGS_TO, 'MediaFile', 'media_id'),
        );
    }
}
