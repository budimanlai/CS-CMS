<?php

class ContentController extends Controller {
    
    public $layout = "//layouts/column2";
    public $group;
    
    public function actionSolution() {
        $this->layout = "//layouts/column0";
        $this->actionGroup('solution');
    }
    
    public function actionNews() {
        $this->layout = "//layouts/column2";
        $this->actionGroup('news-articles');
    }
    
    public function actionGroup($group = null) {
        $seo = Yii::app()->request->getParam("name", null);
        $group = Yii::app()->request->getParam("group", $group);
        
        $model = null;
        $view = "index_detail";
        
        // tampilkan list content berdasarkan group
        $mgroup = ContentGroup::model()->find('seo_url = :SEO', array(
            ':SEO' => $group
        ));
        if ($mgroup != null) {
            $this->group = $mgroup->id;
            $view = "index_list";
            $model = new CActiveDataProvider('Content', array(
                'criteria' => array(
                    'condition' => 'group_id = :GROUP AND status = "active"',
                    'params' => array(
                        ':GROUP' => $mgroup->id
                    ),
                    'order' => 'create_datetime DESC'
                ),
                'countCriteria'=>array(
                    'condition' => 'group_id = :GROUP AND status = "active"',
                    'params' => array(
                        ':GROUP' => $mgroup->id
                    ),
                ),
                'pagination'=>array(
                    'pageSize'=>10,
                    'pageVar'=>'page'
                ),
            ));

            $view_t = "group_".$mgroup->id;
            $file = dirname(dirname(dirname(__FILE__)))."/themes/".Yii::app()->theme->name."/views/content/$view_t.php";
            if (file_exists($file)) {
                $view = $view_t;
            } else if (!empty($model)){
                $view_t = "content_".$model->id;
                $file = dirname(dirname(dirname(__FILE__)))."/themes/".Yii::app()->theme->name."/views/content/$view_t.php";
                if (file_exists($file)) {
                    $view = $view_t;
                }
            }

            $this->render($view, array(
                'model' => $model,
                'model_group' => $mgroup,
                'seo' => $seo,
                'group'=> $group
            ));
        } else {
            throw new CHttpException(404, "Page not found");
        }
    }
    
    public function actionRecent() {
        $model = new CActiveDataProvider('Content', array(
            'criteria' => array(
                'condition' => 'group_id = :GROUP AND status = "active"',
                'params' => array(
                    ':GROUP' => 1
                ),
                'order' => 'create_datetime DESC'
            ),
            'countCriteria'=>array(
                'condition' => 'group_id = :GROUP AND status = "active"',
                'params' => array(
                    ':GROUP' => 1
                ),
            ),
            'pagination'=>array(
                'pageSize'=>5,
                'pageVar'=>'page'
            ),
        ));

        $this->render('index_recent', array(
            'model' => $model,
        ));
    }
    
    public function ByYear($year, $month) {
        $start = date("Y-m-d", strtotime("{$year}-{$month}-01 00:00:00"));
        $end = date("Y-m-t", strtotime($start));
        
        $model = new CActiveDataProvider('Content', array(
            'criteria' => array(
                'condition' => 't.group_id = 1 AND (create_datetime >= :START AND create_datetime <= :END) AND status = "active"',
                'params' => array(
                    ':START' => $start,
                    ':END' => $end
                ),
                'order' => 't.create_datetime DESC',
                'limit' => 10,
                'offset'=>0
            ),
            'countCriteria'=>array(
                'condition' => 't.group_id = 1 AND (create_datetime >= :START AND create_datetime <= :END) AND status = "active"',
                'params' => array(
                    ':START' => $start,
                    ':END' => $end
                ),
                'limit' => 10,
                'offset'=>0
            ),
            'pagination'=>array(
                'pageSize'=>10,
                'pageVar'=>'page'
            ),
        ));
        $this->render('index_list', array(
            'model' => $model,
        ));
    }
    
    public function actionIndex() {
        $seo = Yii::app()->request->getParam("name", null);
        $group = Yii::app()->request->getParam("group", null);
        $model = null;
        $view = "index_detail";
        $mgroup = null;
        
        $model = Content::model()->find('seo_url = :SEO AND status = "active"', array(
            ':SEO' => $seo
        ));
        
        if ($model != null) {
            // check apakah template content detail berdasarkan group ada?
            $view_t = "group_".$model->group_id;
            $file = dirname(dirname(dirname(__FILE__)))."/themes/".Yii::app()->theme->name."/views/content/$view_t.php";
            if (file_exists($file)) {
                $view = $view_t;
            } else {
                // // check apakah template content detail berdasarkan id
                $view_t = "content_".$model->id;
                $file = dirname(dirname(dirname(__FILE__)))."/themes/".Yii::app()->theme->name."/views/content/$view_t.php";
                if (file_exists($file)) { $view = $view_t; }
            }
            if ($view == "index_detail") {
                $this->layout = "//layouts/column1";
            }
            
            Yii::app()->clientScript->registerMetaTag(substr(strip_tags($model->long_description), 0, 255), "description");
            Yii::app()->clientScript->registerMetaTag('', 'keyword');
            $this->render($view, array(
                'model' => $model,
                'model_group' => $mgroup,
                'seo' => $seo,
                'group'=> $group
            ));
        } else {
            throw new CHttpException(404, "Page not found");
        }
    }
    
    public function actionIndex2() {
        $seo = Yii::app()->request->getParam("name", null);
        $group = Yii::app()->request->getParam("group", null);
        $model = null;
        $view = "index_detail";
        $mgroup = null;
        
        echo "Group: $group<br/>";
        echo "name: $seo<br/>";
        echo "view: $view";
        Yii::app()->end();
        
        if (isset($seo)) {
            $model = Content::model()->find('seo_url = :SEO', array(
                ':SEO' => $seo
            ));
            if (!isset($model)) {
                $gg = ContentGroup::model()->find('seo_url = :SEO', array(
                    ':SEO' => $seo
                ));
                if (isset($gg)) {
                    $group = $gg->seo_url;
                    if (isset($gg->mContent)) {
                        $model = $gg->mContent;
                        $mgroup = $gg;
                    }
                    
                }
            } else {
                $mgroup = $model->mContentGroup;
            }
        }
        
        if ($model == null) {
            $mgroup = ContentGroup::model()->find('seo_url = :SEO', array(
                ':SEO' => $group
            ));
            if ($mgroup != null) {
                $this->group = $mgroup->id;
                $view = "index_list";
                $model = new CActiveDataProvider('Content', array(
                    'criteria' => array(
                        'condition' => 'group_id = :GROUP AND status = "active"',
                        'params' => array(
                            ':GROUP' => $mgroup->id
                        ),
                        'order' => 'create_datetime DESC'
                    ),
                    'countCriteria'=>array(
                        'condition' => 'group_id = :GROUP AND status = "active"',
                        'params' => array(
                            ':GROUP' => $mgroup->id
                        ),
                    ),
                    'pagination'=>array(
                        'pageSize'=>4,
                        'pageVar'=>'page'
                    ),
                ));
            }
        }
        
        if (!empty($mgroup)) {
            $view_t = "group_".$mgroup->id;
            $file = dirname(dirname(dirname(__FILE__)))."/themes/".Yii::app()->theme->name."/views/content/$view_t.php";
            if (file_exists($file)) {
                $view = $view_t;
            } else if (!empty($model)){
                $view_t = "content_".$model->id;
                $file = dirname(dirname(dirname(__FILE__)))."/themes/".Yii::app()->theme->name."/views/content/$view_t.php";
                if (file_exists($file)) {
                    $view = $view_t;
                }
            }
        }
        
        $this->render($view, array(
            'model' => $model,
            'model_group' => $mgroup,
            'seo' => $seo,
            'group'=> $group
        ));
    }
}
?>