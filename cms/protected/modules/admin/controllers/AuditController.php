<?php
/**
 * Nhật ký hệ thống — chỉ đọc.
 */
class AuditController extends AdminController
{
    public $pageIcon = 'bi-journal-text';

    public function actionIndex()
    {
        $this->requirePermission('audit.view');

        $criteria = new CDbCriteria();
        $criteria->with = array('user');
        $criteria->together = true;
        $criteria->order = 't.created_at DESC';

        $action = Yii::app()->request->getParam('action');
        if ($action !== null && $action !== '') {
            $criteria->addCondition('t.action = :a');
            $criteria->params[':a'] = $action;
        }

        $entity = Yii::app()->request->getParam('entity');
        if ($entity !== null && $entity !== '') {
            $criteria->addCondition('t.entity_type = :e');
            $criteria->params[':e'] = $entity;
        }

        $dataProvider = new CActiveDataProvider('AuditLog', array(
            'criteria'   => $criteria,
            'pagination' => array('pageSize' => 50),
        ));

        $this->pageTitle = 'Nhật ký hệ thống';
        $this->render('index', array(
            'dataProvider' => $dataProvider,
            'action'       => $action,
            'entity'       => $entity,
            'entityTypes'  => $this->distinctEntityTypes(),
        ));
    }

    private function distinctEntityTypes()
    {
        $rows = Yii::app()->db->createCommand()
            ->selectDistinct('entity_type')
            ->from('audit_logs')
            ->order('entity_type ASC')
            ->queryColumn();

        return array_combine($rows, $rows);
    }
}
