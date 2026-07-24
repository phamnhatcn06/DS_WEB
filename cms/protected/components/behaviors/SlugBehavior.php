<?php
/**
 * Tự sinh slug kebab-case (bỏ dấu tiếng Việt) từ một thuộc tính nguồn,
 * đảm bảo duy nhất bằng hậu tố -2, -3, …
 */
class SlugBehavior extends CActiveRecordBehavior
{
    /** @var string thuộc tính nguồn để sinh slug */
    public $sourceAttribute = 'name';

    /** @var string thuộc tính lưu slug */
    public $slugAttribute = 'slug';

    public function beforeValidate($event)
    {
        $owner = $this->getOwner();
        $slug = trim((string) $owner->{$this->slugAttribute});

        if ($slug === '') {
            $slug = TextHelper::slugify($owner->{$this->sourceAttribute});
        } else {
            $slug = TextHelper::slugify($slug);
        }

        if ($slug === '') {
            return; // để validator 'required' báo lỗi thay vì sinh slug rỗng
        }

        $owner->{$this->slugAttribute} = $this->makeUnique($slug);
    }

    /**
     * Thêm hậu tố số cho tới khi slug không trùng bản ghi khác.
     */
    private function makeUnique($slug)
    {
        $owner = $this->getOwner();
        $base = $slug;
        $suffix = 1;

        while ($this->slugExists($slug)) {
            $suffix++;
            $slug = $base . '-' . $suffix;
        }
        unset($owner);

        return $slug;
    }

    private function slugExists($slug)
    {
        $owner = $this->getOwner();

        $criteria = new CDbCriteria();
        $criteria->condition = $this->slugAttribute . ' = :slug';
        $criteria->params = array(':slug' => $slug);

        if (!$owner->getIsNewRecord()) {
            $pk = $owner->tableSchema->primaryKey;
            $criteria->condition .= ' AND ' . $pk . ' <> :pk';
            $criteria->params[':pk'] = $owner->getPrimaryKey();
        }

        // Bỏ qua defaultScope để không cho slug trùng với bản ghi đã xoá mềm.
        return $owner->model(get_class($owner))->resetScope()->exists($criteria);
    }
}
