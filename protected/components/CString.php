<?php

/**
 * Author Budiman Lai
 * Email budiman.lai@gmail.com 
 */
class CString {
    /**
    * Create URL Title
    *
    * Takes a "title" string as input and creates a
    * human-friendly URL string with either a dash
    * or an underscore as the word separator.
    *
    * @access	public
    * @param	string	the string
    * @param	string	the separator: dash, or underscore
    * @return	string
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
}
?>
