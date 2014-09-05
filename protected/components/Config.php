<?php

/**
 * Class untuk membaca ataupun menulis config yang akan digunakan oleh Application.
 * Format struktur data config menggunakan format array atau sama seperti file config Yii. Contoh:
 * <pre>
 * return array(
 *      'variable1' => 'Value 1',
 *      'variable2' => 'Value 2'
 * );
 * </pre>
 * 
 */
class Config {
    
    /**
     * Variable yang berisi nilai config
     * 
     * @var Array 
     */
    private $items;
    
    /**
     * Path file config
     * 
     * @var String 
     */
    private $configFile;
    
    /**
     * Construct class
     */
    public function init() {
        $this->items = array();
        $this->configFile = dirname(__FILE__)."/../config/appconfig.php";
        if (file_exists($this->configFile)) {
            $this->items = include($this->configFile);
        } else {
            throw new CHttpException(500,'File config not found');
        }
    }
    
    /**
     * Untuk mengambil nilai dari sebuah properti yang ada didalam file config. Jika properti tidak ada
     * maka nilai balik nya berupa string kosong.
     * <pre>
     * file appconfig.php
     * return array(
     *      'key1' => 'Ini adalah Key1'
     * );
     * 
     * echo Yii::app()->config->get('key1');    // Ini adalah Key1
     * echo Yii::app()->config->get('key2');    // ''
     * </pre>
     * 
     * @param String $key
     * @return Any
     */
    public function get($key) {
        return isset($this->items[$key]) ? $this->items[$key] : "";
    }
    
    /**
     * Untuk mengubah atau men-set nilai properti config. Apabila properti config belum ada maka akan ditambahkan
     * atau dibuat baru.
     * <pre>
     * Yii::app()->config->set('key2', 'Ini adalah Key2');
     * echo Yii::app()->config->get('key2');    // Ini adalah Key2
     * </pre>
     * 
     * @param String $key
     * @param Any $value
     */
    public function set($key, $value) {
        $this->items[$key] = $value;
    }
    
    /**
     * Untuk menyimpan properti kedalam file config. Pastikan file appconfig.php dapat ditulis (writeable).
     * File appconfig.php ada di dalam folder proteceted/config/appconfig.php
     */
    public function save() {
        $str = "<"."?php\n";
        $str.= "return array(\n";
        
        foreach($this->items as $key => $value) {
            $str.= "\t'$key' => '".str_replace("'", "\'", $value)."',\n";
        }
        
        $str.= ");\n";
        $str.= "?>";
        $fo = fopen($this->configFile, "w");
        fwrite($fo, $str);
        fclose($fo);
    }
}
?>