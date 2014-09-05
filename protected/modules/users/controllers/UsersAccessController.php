<?php
/**
 * @author Budiman Lai <budiman.lai@gmail.com>
 * @version 1.0
 */

class UsersAccessController extends MyController {
    
    public $layout='//layouts/column2';
    
    public function actionAccessPage() {
        
        if (isset($_POST['Access'])) {
            $model = $_POST['Access'];
            
            $trans = Yii::app()->db->beginTransaction();
            try {
                AclPage::model()->deleteAll();
                foreach($_POST['Access']['route'] as $group => $route) {
                    foreach($route as $row) {
                        $temp = explode("-", $row);
                        $acl = new AclPage;
                        $acl->user_groups = $group;
                        $acl->page_type = $temp[0];
                        $acl->route = $temp[1];
                        $acl->save(false);
                    }
                }
                
                $trans->commit();
                Yii::app()->user->setFlash('success', "<strong>Success</strong>, save access page");
            } catch (Exception $e) {
                $trans->rollback();
                Yii::app()->user->setFlash('error', $e->getMessage());
            }
            
        } else {
            $model = null;
        }
        
        $this->render('access_page', array(
            'model' => $model,
        ));
    }
    
    public function actionScanDirectory() {
        if (isset($_GET['path'])) {
            $user_id = $_GET['user_id'];
            $acl_data = array();
            
            $data = null;
            CAccessPage::ScanDirectory($_GET['name'], $_GET['path'], $data);
            $group = UserGroups::model()->findAll();
            $acl = AclPage::model()->findAll();
            
            if ($acl != null) {
                foreach($acl as $row) {
                    $ugroup = strtolower($row->user_groups);
                    $route = strtolower($row->route);
                    
                    $acl_data[$ugroup][md5($route)] = $route;
                }
            }
            
            //echo "<pre>" . print_r($acl_data, true) . "</pre>";
            $this->renderPartial('access_table', array(
                'model' => $data,
                'name' => $_GET['name'],
                'path' => $_GET['path'],
                'group' => $group,
                'acl' => $acl_data,
            ));
        }
    }
    
    protected function AclSelected($acl_data, $group, $route) {
        $group = strtolower($group);
        $route = strtolower($route);
        
        //echo "ACL Checked: $group, $controller, $action<br/>";
        if (isset($acl_data[$group][md5($route)])) {
            return " checked";
        } else
            return "";
    }
}
?>
