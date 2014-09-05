<?php

/**
 * @author Budiman Lai <budiman.lai@gmail.com>
 * @copyright (c) 2012, Budiman Lai
 * @version 1.0
 */

class CSUsers extends Users {
    
    /**
     * Get User folder path
     * @param Integer $user_id
     * @return String
     */
    public static function getPath($model) {
        $path = Yii::app()->params['users']['path'];
        
        $user_id = str_pad($model->id, 4, "0", STR_PAD_LEFT);
        $search = array('{user_id}', '{path_code}');
        $value = array($user_id, $model->path_code);
        return str_replace($search, $value, $path);
    }
    
    /**
     * Create user folder for save images file, themes, etc
     * @param Integer $user_id
     */
    public static function createUserFolder($model) {
        $path = CSUsers::getPath($model);
        
        try {
            @mkdir($path, 0777, true);
            @mkdir($path . "images/", 0777, true);
        } catch (Exception $e) {
            // nothing todo
            echo $e->getMessage() . "<br/>";
            echo $e->getTraceAsString() . "<br/>";
        }
    }
    
    public static function getImagesPath($model) {
        return CSUsers::getPath($model) . "images/";
    }
    
    /**
     * Get user avatar images
     * @param Integer $user_id
     * @return String
     */
    public static function getAvatarImages($model) {
        $img = CSUsers::getImagesPath($model) . $model->avatar;
        if ($model->avatar != "" && file_exists($img)) {
            return $img;
        } else {
            return "uploads/images/" . Yii::app()->params['no_avatar'];
        }
    }
    
    /**
     * Get user album path
     * @param Integer $user_id
     * @param Integer $album_id
     * @return String
     */
    public static function getAlbumPath($model, $album_id) {
        return CSUsers::getImagesPath($model) . "albums/$album_id/";
    }
}
?>
