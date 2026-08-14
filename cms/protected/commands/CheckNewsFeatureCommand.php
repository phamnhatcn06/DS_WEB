<?php
/**
 * Kiểm tra nhanh runtime cho tính năng mới (tạm thời — xoá sau khi chạy).
 */
class CheckNewsFeatureCommand extends CConsoleCommand
{
    public function run($args)
    {
        echo "optionsWithFlags: ";
        $flags = NewsCategory::optionsWithFlags();
        echo count($flags) . " danh mục\n";
        foreach ($flags as $id => $info) {
            if ($info['is_project'] || $info['is_investor']) {
                echo "  #$id {$info['name']} project={$info['is_project']} investor={$info['is_investor']}\n";
            }
        }

        echo "idsByFlag(project): " . implode(',', NewsCategory::idsByFlag('is_project_category')) . "\n";
        echo "idsByFlag(investor): " . implode(',', NewsCategory::idsByFlag('is_investor_category')) . "\n";

        // Nạp một bài để kiểm tra quan hệ attachments + getter không lỗi.
        $post = NewsPost::model()->find(array('order' => 't.id DESC'));
        if ($post !== null) {
            echo "post #{$post->id}: project_name=" . var_export($post->project_name, true)
                . " content_en_len=" . strlen((string) $post->content_en) . "\n";
            echo "attachmentIds: " . implode(',', $post->getAttachmentIds()) . "\n";
            echo "attachmentFiles: " . count($post->getAttachmentFiles()) . "\n";
            echo "projectStatusOptions: " . implode('|', array_keys(NewsPost::projectStatusOptions())) . "\n";
        }
        echo "OK\n";
    }
}
