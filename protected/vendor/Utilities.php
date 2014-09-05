<?php
class Utilities {
    
    public static function monthName($number) {
        $month = array(
            '01' => 'January',
            '02' => 'February',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'May',
            '06' => 'Juni',
            '07' => 'July',
            '08' => 'August',
            '09' => 'September',
            '10' => 'October',
            '11' => 'November',
            '12' => 'December',
        );
        return isset($month[$number]) ? $month[$number] : "Unknown month";
    }
    
    /**
     * Untuk generate tag input hidden untuk field CSRF token yang digunakan untuk form POST
     * <pre>
     * echo Utilities::csrfField();
     * // <input type="hidden" name="CSRF_YII_TOKEN_NAME" value="$YII_CSRF_YOKEN_VALUE"/>
     * </pre>
     * 
     * @return String
     */
    public static function csrfField() {
        return CHtml::hiddenField(Yii::app()->request->csrfTokenName, Yii::app()->request->csrfToken);
    }
    
    /**
     * Untuk generate tag img dan text untuk menampilkan gambar loading ajax dan text
     * <pre>
     * echo Utilities:loadingSpan("Please wait...");
     * // <img src="/images/loading_16x16.gif"/> Please wait...
     * </pre>
     * 
     * @param String $text
     * @return String
     */
    public static function loadingSpan($text = "Loading...") {
        return "<div>".CHtml::image(Yii::app()->theme->baseUrl."/images/loading_16x16.gif", "Loading", array(
            'width' => 16,
            'height' => 16,
            'align' => 'center'
        ))." ".$text."</div>";
    }
    
    /**
     * Convert array error yang didapat dari model kedalam bentuk string.
     * <pre>
     * if ($model->validate()) {
     *      // code if success
     * } else {
     *      // invalid
     *      echo Utilities::errorToString($model->getErrors());
     * }
     * </pre>
     * 
     * @param Array $error
     * @return String
     */
    public static function errorToString($error) {
        $str = "";
        foreach($error as $field) {
            if (is_array($field)) {
                foreach($field as $row) {
                    $str.= $row."<br/>";
                }
            } else {
                $str.= $field."<br/>";
            }
        }
        return $str;
    }
    
    /**
     * Membuat URL title. Fungsi ini akan mengganti spasi atau simbol yang ada didalam sebuah string dengan
     * tanda - atau _.
     * <pre>
     * echo Utilities::url_title("Ini adalah URL", "dash", true);
     * // ini-adalah-url
     * </pre>
     * 
     * @param String $str
     * @param String $separator
     * @param Boolean $lowercase
     * @return String
     */
    public static function url_title($str, $separator = 'dash', $lowercase = FALSE)
    {
        if ($separator == 'dash')
        {
            $search	= '_';
            $replace	= '-';
        }
        else
        {
            $search	= '-';
            $replace	= '_';
        }

        $trans = array(
            '&\#\d+?;'          => '',
            '&\S+?;'            => '',
            '\s+'               => $replace,
            '[^a-z0-9\-\._]'    => '',
            $replace.'+'        => $replace,
            $replace.'$'        => $replace,
            '^'.$replace        => $replace,
            '\.+$'              => ''
        );

        $str = strip_tags($str);

        foreach ($trans as $key => $val)
        {
            $str = preg_replace("#".$key."#i", $val, $str);
        }

        if ($lowercase === TRUE)
        {
            $str = strtolower($str);
        }

        return trim(stripslashes($str));
    }
    
    /**
     * Mengambil full URL yang di request oleh user.
     * <pre>
     * // misalnya user membuka halaman http://www.example.com/test/page.php?id=123&key=value1
     * echo Utilities::getFullURL();
     * // Output:
     * // http://www.example.com/test/page.php?id=123&key=value1
     * </pre>
     * 
     * @return String
     */
    public static function getFullURL()
    {
	$s = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : "";
	$protocol = substr(strtolower($_SERVER["SERVER_PROTOCOL"]), 0, strpos(strtolower($_SERVER["SERVER_PROTOCOL"]), "/")) . $s;
	$port = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":".$_SERVER["SERVER_PORT"]);
	return $protocol . "://" . $_SERVER['SERVER_NAME'] . $port . $_SERVER['REQUEST_URI'];
    }
    
    /**
     * Convert byte to KB or MB
     * @param long $a_bytes
     * @return String
     */
    public static function format_bytes($a_bytes)
    {
        if ($a_bytes < 1024) {
            return $a_bytes .' B';
        } elseif ($a_bytes < 1048576) {
            return round($a_bytes / 1024, 2) .' KiB';
        } elseif ($a_bytes < 1073741824) {
            return round($a_bytes / 1048576, 2) . ' MiB';
        } elseif ($a_bytes < 1099511627776) {
            return round($a_bytes / 1073741824, 2) . ' GiB';
        } elseif ($a_bytes < 1125899906842624) {
            return round($a_bytes / 1099511627776, 2) .' TiB';
        } elseif ($a_bytes < 1152921504606846976) {
            return round($a_bytes / 1125899906842624, 2) .' PiB';
        } elseif ($a_bytes < 1180591620717411303424) {
            return round($a_bytes / 1152921504606846976, 2) .' EiB';
        } elseif ($a_bytes < 1208925819614629174706176) {
            return round($a_bytes / 1180591620717411303424, 2) .' ZiB';
        } else {
            return round($a_bytes / 1208925819614629174706176, 2) .' YiB';
        }
    }
    
    public static function wordTruncate($string, $max) {
        return current(explode("\n", wordwrap($string, $max, "...\n")));
    }
    
    public static function getFilename($filename) {
        $temp = explode("/", $filename);
        return $temp[count($temp)-1];
    }
}
?>