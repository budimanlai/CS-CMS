<?php

class Security {
    
    /**
     * Nama variable $_SESSION untuk mengetahui berapa kali user gagal login
     * @var String 
     */
    public static $flName = "failed_login";
    
    /**
     * Maksimal gagal login yang diperbolehkan oleh system sebelum fungsi Brute Force di aktifkan
     * 
     * @var Integer 
     */
    public static $login_failed_max_count = 3;
    
    /**
     * Apabila user gagal login lebih dari batas maksimal yang telah di tentukan, maka user harus 
     * menunggu selama $login_failed_wait_interval sebelum user diperbolehkan untuk login kembali.
     * Total waktu yang di harus di tunggu oleh user adalah $login_failed_wait_interval * ($total_gagal - $login_failed_max_count).
     * $login_failed_wait_interval dalam satuan menit.
     * 
     * @var Integer 
     */
    public static $login_failed_wait_interval = 5;
    
    /**
     * Fungsi ini untuk mencek seberapa banyak user gagal login ke system. Apabila melebihi batas yang sudah ditentukan
     * maka system akan otomatis memblock IP Address dari user sehingga user harus menunggu beberapa saat untuk
     * bisa login kembali
     */
    public static function doBruteForceProtect() {
        
        $val = Settings::getValue(array(
            'login_failed_max_count' => self::$login_failed_max_count,
            'login_failed_wait_interval' => self::$login_failed_wait_interval
        ));
        
        if (isset(Yii::app()->session[self::$flName])) {
            Yii::app()->session[self::$flName] = Yii::app()->session[self::$flName] + 1;
        } else {
            Yii::app()->session[self::$flName] = 1;
        }
        
        if (Yii::app()->session[self::$flName] >= $val['login_failed_max_count']) {
            Yii::import('application.modules.users.models.UsersIpBlock');
            
            // jika sudah melebihi batas maksimal gagal login yang diijinkan
            $time = Yii::app()->session[self::$flName] * $val['login_failed_wait_interval'];
            
            $model = UsersIpBlock::model()->findByAttributes(array('ip_address' => Yii::app()->request->getUserHostAddress()));
            if ($model == null) $model = new UsersIpBlock;
            
            $model->ip_address = Yii::app()->request->getUserHostAddress();
            $model->reason = "Gagal login ke system sebanyak ".Yii::app()->session[self::$flName]." kali.";
            $model->start_datetime = date("Y-m-d H:i:s");
            $model->until_datetime = date("Y-m-d H:i:s", strtotime("+{$time} minute"));
            $model->save();
        }
    }
    
    /**
     * Berfungsi untuk men-reset Brute Force protect
     */
    public static function resetBruteForceProtect() {
        Yii::app()->session[self::$flName] = 0;
    }
    
    /**
     * Untuk mencheck apakah IP Address dari user di block atau tidak oleh system
     * <pre>
     * // sama dengan Security::isBlocked($_SERVER['REMOTE_ADDR'])
     * if (Security::isBlocked()) {
     *      echo "Blocked";
     * } else {
     *      echo "tidak di block";
     * }
     * </pre>
     * 
     * @param String $ip_address
     * @return boolean
     */
    public static function isBlocked($ip_address = null) {
        Yii::import('application.modules.users.models.UsersIpBlock');
        
        $ip = isset($ip_address) ? $ip_address : Yii::app()->request->getUserHostAddress();
        
        $model = UsersIpBlock::model()->find('ip_address = :IP AND until_datetime >= :UNTIL', array(
            ':IP' => $ip,
            ':UNTIL' => date('Y-m-d H:i:s')
        ));
        if ($model != null) {
            // ada IP yang di block
            return true;
        } else {
            // tidak ada ip yg diblock
            return false;
        }
    }
    
    /**
     * Untuk mengetahui berapa lama sebuah user harus menunggu untuk bisa login lagi karena terkena Brute Force Protect
     * <pre>
     * // sama dengan Security::getBruteForceWaitTime($_SERVER['REMOTE_ADDR'])
     * echo Security::getBruteForceWaitTime();  // 10 menit
     * </pre>
     * 
     * @param String $ip_address
     * @return int
     */
    public static function getBruteForceWaitTime($ip_address = null) {
        Yii::import('application.modules.users.models.UsersIpBlock');
        
        $ip = isset($ip_address) ? $ip_address : Yii::app()->request->getUserHostAddress();
        
        $model = UsersIpBlock::model()->find('ip_address = :IP AND until_datetime >= :UNTIL', array(
            ':IP' => $ip,
            ':UNTIL' => date('Y-m-d H:i:s')
        ));
        if ($model != null) {
            // ada IP yang di block
            $to_time = strtotime($model->until_datetime);
            $from_time = strtotime(date("Y-m-d H:i:s"));
            return ceil(abs($to_time - $from_time) / 60);
        } else {
            // tidak ada ip yg diblock
            return 0;
        }
    }
    public static function hashPassword($password) {
        if(!function_exists('crypt') || !defined('CRYPT_BLOWFISH') || !CRYPT_BLOWFISH) {
            Yii::import('application.vendor.PasswordHash');
            $t_hasher = new PasswordHash(8, FALSE);
            return $t_hasher->HashPassword($password);
        } else {
            return CPasswordHelper::hashPassword($password);
        }
    }
    
    public static function checkPassword($password, $hash) {
        if(!function_exists('crypt') || !defined('CRYPT_BLOWFISH') || !CRYPT_BLOWFISH) {
            Yii::import('application.vendor.PasswordHash');
            $t_hasher = new PasswordHash(8, FALSE);
            return $t_hasher->CheckPassword($password, $hash);
        } else {
            return CPasswordHelper::verifyPassword($password, $hash);
        }
    }
    
}
?>