<?php

class SearchController extends Controller {
    public function actionIndex() {
        $search = Yii::app()->request->getParam('q', null);
        $page =  Yii::app()->request->getParam('page', 0);
        
        if ($search != null) {
            
            $cmd = Yii::app()->db->createCommand("select c.*, cg.seo_url as group_seo_url "
                    . "from content as c "
                    . "left join content_group as cg on cg.id = c.group_id "
                    . "where MATCH (c.long_description) AGAINST(:KEY)");
            $cmd->limit(10);
            $cmd->offset($page);
            $cmd->bindValue(":KEY", $search , PDO::PARAM_STR);
            
            
            $this->render('index', array(
                'model' => $cmd->queryAll(),
                'query' => $search
            ));
        } else {
            $this->redirect("site/index");
        }
    }
}