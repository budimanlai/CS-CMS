<?php
/**
 * Class untuk mengatur hak akses dari user seperti menambah permission, check permission, load file permission
 * yang ada didalam folder protected/settings/permissions. Contoh format file permission bisa dilihat sbb:
 * <pre>
 * <?xml version="1.0" encoding="UTF-8"?>
 * <premission>
 *      <title>User Management</title>
 *      <description>Module untuk manage, create, update, delete dan mengatur permission user</description>
 *      <rules>
 *          <items title="Manage Users">
 *              <role route="users/default/index">View Users</role>
 *              <role route="users/default/create">Create Users</role>
 *              <role route="users/default/update">Update Users Profile</role>
 *              <role route="users/default/delete">Delete Users</role>
 *          </items>
 *          <items title="Manage Permission">
 *              <role route="users/permission/canManageUserGroup">Manage User Group & Permission</role>
 *          </items>
 *      </rules>
 * </permission>
 * </pre>
 * Keterangan untuk file permission ini adalah sbb:<br/>
 * title = Judul Permission<br/>
 * description = Keterangan permission<br/>
 */

class CAccessPage {
    
    public static $exclude_folder = array('views', 'models', 'extensions', 'DefaultController.php', 'api');
    public static $search = array('controllers/','protected/backend/controllers', 'protected/controllers', 'protected/modules/');
    
    public static function collectController() {
        $path[] = array(
            'name' => 'Backend', 
            'path' => "protected/backend/controllers"
        );
        
        $path[] = array(
            'name' => 'Backend Admin', 
            'path' => "protected/modules"
        );
        
        $path[] = array(
            'name' => 'Frontend', 
            'path' => "protected/controllers"
        );
        
        $data = array();
        foreach($path as $row) {
            CAccessPage::ScanDirectory($row['name'], $row['path'], $data);
        }
        
        return $data;
    }
    
    public static function ScanDirectory($name, $path, &$data = null) {
        
        
        if (is_dir($path)) {
            $d_handle = dir($path);
            while (false !== ($entry = $d_handle->read())) {

                if ($entry != "." && $entry != ".." && $entry != ".DS_Store") {
                    $class_file = $entry;
                    $class_name = str_replace(".php", "", $class_file);
                    $class_path = $path;
                    $class_exists = false;

                    $dir_path = $class_path . "/" . $class_name;
                    $method = null;
                    
                    if (is_dir($dir_path)) {
                        if (!in_array($entry, CAccessPage::$exclude_folder))
                            CAccessPage::ScanDirectory($name, $dir_path, $data);
                    } else {
                        if (class_exists($class_name, false)) {
                            $class_exists = "Found";
                        } else {
                            $p = $class_path . "/" . $class_file;
                            if (file_exists($p)) {
                                require($p);
                            }
                        }

                        $t_method = get_class_methods($class_name);
                        foreach($t_method as $row) {
                            if ($row != "actions") {
                                $action = substr($row, 0, 6);
                                if ($action == "action") {
                                    $method[] = str_replace("action", "", $row);
                                }
                            }
                        }

                        $data[] = array(
                            'class_file' => $class_file,
                            'class_name' => $class_name,
                            'class_path' => $class_path,
                            'class_route' => str_replace(CAccessPage::$search, "", $class_path),
                            'class_exists' => $class_exists,
                            'method' => $method,
                        );
                    }
                }

            }
            $d_handle->close();
        } else {
            echo $path . " not directory<br/>";
        }
            
        return $data;
    }
    
    public static function isAllowed($route = null) {
        if (Yii::app()->user->getState('user_group') == "administrator") {
            return true;
        }
        if (!isset($route)) {
            $route = Yii::app()->controller->route;
        }
        $model = AclPage::model()->find('group_id = :GROUP AND route = :ROUTE', array(
            ':GROUP' => Yii::app()->user->getState('user_group'),
            ':ROUTE' => $route
        ));
        
        if ($model != null)
            return true;
        else
            return false;
    }
    
    public static function getButton($route, $label, $url, $htmlOptions = array()) {
        if (CAccessPage::isAllowed($route)) {
            return array(
                'label'=>$label, 
                'url'=>$url,
                'htmlOptions'=>$htmlOptions);
        } else {
            if (isset($htmlOptions['class'])) {
                $htmlOptions['class'] = "disabled ".$htmlOptions['class'];
            } else {
                $htmlOptions['class'] = "disabled ";
            }
            return array(
                'label'=>$label, 
                'url'=>'#',
                'htmlOptions' => $htmlOptions);
        }
    }
    
    public static function AccessRules() {
        $model = CAccessPage::isAllowed(Yii::app()->controller->route);
        
        if ($model) {
            return array(
                array('allow',
                    'users' => array('@')
                ),
                array('deny',  // deny all users
                    'users'=>array('*'),
                ),
            );
        } else {
            return array(
                array('deny',  // deny all users
                    'users'=>array('*'),
                ),
            );
        }
    }
}
?>