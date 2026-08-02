<?php
/**
 * Trang lưu trữ theo thẻ (public): liệt kê mọi tin tức đã xuất bản và lĩnh vực
 * đang hiển thị có gắn thẻ được chọn. URL sạch: /the/<slug>.
 */
class TagController extends FrontendController
{
    public function actionView($slug)
    {
        $tag = Tag::model()->notDeleted()->find(
            't.slug = :slug AND t.is_active = 1',
            array(':slug' => $slug)
        );
        if ($tag === null) {
            throw new CHttpException(404, 'Không tìm thấy thẻ này.');
        }

        // Bài viết đã xuất bản có gắn thẻ — EXISTS trên bảng liên kết, tránh JOIN
        // nhân bản dòng; eager-load ảnh + danh mục để không N+1.
        $posts = NewsPost::model()->with('thumbnail', 'category')->findAll(array(
            'condition' => 't.deleted_at IS NULL AND t.is_active = 1 AND t.status = :st'
                . ' AND EXISTS (SELECT 1 FROM pvn_news_post_tags npt'
                . ' WHERE npt.post_id = t.id AND npt.tag_id = :tid)',
            'params'    => array(':st' => NewsPost::STATUS_PUBLISHED, ':tid' => $tag->id),
            'order'     => 't.published_at DESC',
        ));

        // Lĩnh vực đang hiển thị có gắn thẻ.
        $sectors = BusinessSector::model()->with('image')->findAll(array(
            'condition' => 't.deleted_at IS NULL AND t.is_active = 1'
                . ' AND EXISTS (SELECT 1 FROM pvn_business_sector_tags bst'
                . ' WHERE bst.sector_id = t.id AND bst.tag_id = :tid)',
            'params'    => array(':tid' => $tag->id),
            'order'     => 't.sort_order ASC',
        ));

        $this->pageTitle = 'Thẻ: ' . $tag->name;
        $this->render('view', array(
            'tag'     => $tag,
            'posts'   => $posts,
            'sectors' => $sectors,
        ));
    }
}
