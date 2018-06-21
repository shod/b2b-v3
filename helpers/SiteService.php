<?php

namespace app\helpers;
use yii;

class SiteService {

    public static function timeToDate($time, $day = false)
    {
        if($day){
            $result = self::getRusMonth(date("j m Y", $time), ' ', '2');
        } else {
            $result = date("Y-m-d H:i", $time);
        }
        return $result;
    }

    public static function dateFormat($dbDate){
        $aDate = explode('-', $dbDate);
        $return = round($aDate[2]) . ' ' . $aDate[1] . ' ' . $aDate[0];
        return self::getRusMonth($return, ' ', '2');
    }

    public static function dateTimeFormat($dbDate){
        $aDate = explode('-', $dbDate);
        $return = round($aDate[2]) . ' ' . $aDate[1] . ' ' . $aDate[0];

        $time = date("H:i", strtotime($dbDate));
        return self::getRusMonth($return, ' ', '2') ." в " . $time;
    }

    public static function getRusMonth($date,$delimiter,$month_position){
        $temp=explode($delimiter,$date);
        if($temp[$month_position-1] > 12 || $temp[$month_position-1] < 1) return FALSE;
        $aMonth = array('января', 'февраля', 'марта', 'апреля', 'мая', 'июня', 'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря');
        $temp[$month_position-1]= Yii::t('Site', $aMonth[$temp[$month_position-1] - 1]);
        return implode($delimiter,$temp);
    }

    public static function getDataMothStr($date) {
        if($date > 10000) {
            $date = $date;
        } else {
            $date = strtotime($date);
        }

        $month = array(1 => 'января', 'февраля', 'марта', 'апреля', 'мая', 'июня', 'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря');
        $res = date('j ' . $month[date('n', $date)].' Y',$date);
        return $res;
    }


    public static function strToCost($str){
        $str = (float)str_replace(' ','',$str);
        $str = $str/10000;
        $str = number_format($str, 2, '.', ' ');
        return $str;
    }

    public static function strToCostFormat($str){
        $str = (float)str_replace(' ','',$str);
        $str = number_format($str, 0, ' ', ' ');
        return $str;
    }

    /*public static function costToCurrency($cost){
        $costNewU = str_replace(' ', '', $cost);
        $costNewU = $costNewU/SysService::get('currency_nbrb');
        $costNewU = round($costNewU);
        return $costNewU;
    }*/

    public static function sellerCurrencyToCost($cost){
        $costNewU = str_replace(' ', '', $cost);
        $costNewU = $costNewU*SysService::get('currency_nbrb');
        $costNewU = round($costNewU);
        return $costNewU;
    }

    public static function strToCostOld($str){
        $str = (float)str_replace(' ','',$str);
        $str = $str*10000;
        $str = number_format($str, 0, ' ', ' ');
        return $str;
    }

    public static function getStrDate($date) {
        if($date > 10000) {
            $date = $date;
        } else {
            $date = strtotime($date);
        }

        if (date('Y.m.d', $date) == date("Y.m.d")) {
            $res = "сегодня в " . date('H:i', $date);
        }
        elseif (date('Y.m.d', $date) == date("Y.m.d", mktime(0, 0, 0, date("m"), date("d") - 1, date("Y"))))
        {
            $res = "вчера в " . date('H:i', $date);
        }
        elseif (date('Y.m.d', $date) == date("Y.m.d", mktime(0, 0, 0, date("m"), date("d") - 2, date("Y"))))
        {
            $res = "позавчера в " . date('H:i', $date);
        }
        else {
            $month = array(1 => 'января', 'февраля', 'марта', 'апреля', 'мая', 'июня', 'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря');
            $res = date('j ' . $month[date('n', $date)] . ' Y',$date) . " в " . date('H:i', $date);; // в H:i
        }
        return $res;
    }

    public static function getStrDateNoTime($date) {
        if($date > 10000) {
            $date = $date;
        } else {
            $date = strtotime($date);
        }

        if (date('Y.m.d', $date) == date("Y.m.d")) {
            $res = "сегодня";
        }
        elseif (date('Y.m.d', $date) == date("Y.m.d", mktime(0, 0, 0, date("m"), date("d") - 1, date("Y"))))
        {
            $res = "вчера";
        }
        elseif (date('Y.m.d', $date) == date("Y.m.d", mktime(0, 0, 0, date("m"), date("d") - 2, date("Y"))))
        {
            $res = "позавчера";
        }
        else {
            $month = array(1 => 'января', 'февраля', 'марта', 'апреля', 'мая', 'июня', 'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря');
            $res = date('j ' . $month[date('n', $date)] . ' Y',$date); // в H:i
        }
        return $res;
    }

    public static function getStrDateSimple($date) {
        if($date > 10000) {
            $date = $date;
        } else {
            $date = strtotime($date);
        }


        $month = array(1 => 'января', 'февраля', 'марта', 'апреля', 'мая', 'июня', 'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря');
        $res = date('j ' . $month[date('n', $date)] . ' Y',$date);

        return $res;
    }

    public static function getMonthByIndex($m){
        $month = array(1 => 'январь', 'февраль', 'март', 'апрел', 'май', 'июнь', 'июль', 'август', 'сентябрь', 'октябрь', 'ноябрь', 'декабрь');
        return $month[$m];
    }

    public static function starIterate($cnt_star){
        $round_cnt_star = floor($cnt_star);
        $isHalfExist = ($cnt_star > $round_cnt_star );
        $star = '';
        for($i=0; $i<5; $i++){
            if($i < $round_cnt_star){
                $star .= '<span  class="rl-li la la-star"></span >';
            }elseif($isHalfExist && $i  == $round_cnt_star){
                $star .= '<span  class="rl-li la la-star-half-o"></span >';
            }else{
                $star .= '<span  class="rl-li la la-star-o"></span >';
            }
        }
        return $star;
    }

    public static function getPublicObjectVars($obj) {
        return get_object_vars($obj);
    }

    public static function unserialize($str, $options = array()){
        if(version_compare(PHP_VERSION, '7.0.0', '>='))
        {
            $parms = @unserialize($str, $options); //глушилка из-за #6545
            if(!$parms){
                $fixed_serialized_data = preg_replace_callback ( '!s:(\d+):"(.*?)";!',
                    function($match) {
                        return ($match[1] == strlen($match[2])) ? $match[0] : 's:' . strlen($match[2]) . ':"' . $match[2] . '";';
                    },
                    $str );
                $parms = @\unserialize($fixed_serialized_data);
            }
            return $parms;
        }

        $allowed_classes = isset($options['allowed_classes']) ?
            $options['allowed_classes'] : true;
        if(is_array($allowed_classes) || !$allowed_classes)
        {
            $str = preg_replace_callback(
                '/(?=^|:)(O|C):\d+:"([^"]*)":(\d+):{/',
                function($matches) use ($allowed_classes)
                {
                    if(is_array($allowed_classes) &&
                        in_array($matches[2], $allowed_classes))
                    { return $matches[0]; }
                    else
                    {
                        return $matches[1].':22:"__PHP_Incomplete_Class":'.
                            ($matches[3] + 1).
                            ':{s:27:"__PHP_Incomplete_Class_Name";'.
                            serialize($matches[2]);
                    }
                },
                $str
            );
        }
        unset($allowed_classes);
        return unserialize($str);
    }

    public static function mb_ucfirst($str, $encoding = "UTF-8", $lower_str_end = false) {
        $first_letter = mb_strtoupper(mb_substr($str, 0, 1, $encoding), $encoding);
        $str_end = "";
        if ($lower_str_end) {
            $str_end = mb_strtolower(mb_substr($str, 1, mb_strlen($str, $encoding), $encoding), $encoding);
        } else {
            $str_end = mb_substr($str, 1, mb_strlen($str, $encoding), $encoding);
        }
        $str = $first_letter . $str_end;
        return $str;
    }

    public static function checkRemoteFile($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        // don't download content
        curl_setopt($ch, CURLOPT_NOBODY, 1);
        curl_setopt($ch, CURLOPT_FAILONERROR, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        if(curl_exec($ch)!==FALSE)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public static function formatPhone($pref, $phone_str){

        $formats = array(
            '7' => '###-##-##',
            '6' => '##-##-##',
        );

        $pref = str_replace('0', '', $pref);
        $phone_str = SiteService::phone_format_str($phone_str, $formats, '#');
        return $pref . " " . $phone_str;
    }

    public static function phone_format_str($phone, $format, $mask = '#')
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);

        if (is_array($format)) {
            if (array_key_exists(strlen($phone), $format)) {
                $format = $format[strlen($phone)];
            } else {
                return false;
            }
        }
        $pattern = '/' . str_repeat('([0-9])?', substr_count($format, $mask)) . '(.*)/';

        $format = preg_replace_callback(
            str_replace('#', $mask, '/([#])/'),
            function () use (&$counter) {
                return '${' . (++$counter) . '}';
            },
            $format
        );
        return ($phone) ? trim(preg_replace($pattern, $format, $phone, 1)) : false;
    }

    public static function safe_unserialize($str){
        return unserialize(preg_replace_callback ( '!s:(\d+):"(.*?)";!', function($match) {
            return ($match[1] == strlen($match[2])) ? $match[0] : 's:' . strlen($match[2]) . ':"' . $match[2] . '";';
        },$str ));
    }

    public static function get_pages($page, $first, $last, $url){
        $pages = '';
        if (($page > 1) && ($last >= 8)){
            $pages .= "<li class='page-item'><a class=\"page-link\" href=\"{$url}\" >1</a></li>";
            if($page > 2){
                $pages .= "<li class='page-item'><a class=\"page-link\" >...</a></li>";
            }
        }

        for ($p = $first; $p < $last+1; $p++)
        {
            if ($last < 8) {
                $a_selected = ($p == ($page + 1)) ? "active" : "";
                $pages .= "<li class='page-item {$a_selected}'><a class=\"page-link\" href=\"{$url}&page={$p}\" >{$p}</a></li>";
            } else {

                if (($p == $page+1) || ($p==$page) || ($p == $page + 2))
                {
                    $a_selected = ($p == ($page + 1)) ? "active" : "";
                    $pages .= "<li class='page-item {$a_selected}'><a class=\"page-link\" href=\"{$url}&page={$p}\" >{$p}</a></li>";
                }
            }
        }
        if (((($page+1 < $last)) && ($last >= 8))){
            $pages .= "<li class='page-item'><a class=\"page-link\" >...</a></li>";
            $pages .= "<li class='page-item'><a class=\"page-link\" href=\"{$url}&page={$last}\" >{$last}</a></li>";
        }
        return $pages;
    }

    public static function transliterate($st){
        $st = strtr($st, "абвгдежзийклмнопрстуфыэАБВГДЕЖЗИЙКЛМНОПРСТУФЫЭ", "abvgdegziyklmnoprstufieABVGDEGZIYKLMNOPRSTUFIE"
        );
        $st = strtr($st, array(
            'ё' => "yo", 'х' => "h", 'ц' => "ts", 'ч' => "ch", 'ш' => "sh",
            'щ' => "shch", 'ъ' => '', 'ь' => '', 'ю' => "yu", 'я' => "ya",
            'Ё' => "Yo", 'Х' => "H", 'Ц' => "Ts", 'Ч' => "Ch", 'Ш' => "Sh",
            'Щ' => "Shch", 'Ъ' => '', 'Ь' => '', 'Ю' => "Yu", 'Я' => "Ya",
        ));
        // Remove any remaining non-safe characters
        $st = preg_replace('/[^0-9A-Za-z_.-]/', '', $st);
        $st = strtolower($st);
        return $st;
    }

    public static function set_bitvalue($data, $setting_bit, $value){
        if(empty($value)){
            $value = 0;
        }
        return $data = $data & ~ (int)$setting_bit | ($value * $setting_bit);
    }

    public static function resize($file, $sizes)
    {
        list($width, $height, $type) = getimagesize($file);
        list($new_width, $new_height) = SiteService::format_size($width, $height, array(0, 0, $sizes[0], $sizes[1]));
        switch ($type)
        {
            case IMAGETYPE_GIF: $image = imagecreatefromgif($file);
                break;
            case IMAGETYPE_PNG: $image = imagecreatefrompng($file);
                break;
            case IMAGETYPE_WBMP: $image = imagecreatefromwbmp($file);
                break;
            case IMAGETYPE_JPEG:
            default: $image = imagecreatefromjpeg($file);
        }
        if ($image)
        {
            // Resample
            $image_p = imagecreatetruecolor($new_width, $new_height);
            imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);


            // Output
            touch($file);
            imagejpeg($image_p, $file, 100);
        }
    }

    public static function format_size($width, $height, $s)
    {
        if ($width && $height && $s)
        {
            // width
            if ($width < $s[0])
                $k_w = $s[0] / $width;
            elseif ($width > $s[2])
                $k_w = $s[2] / $width;
            else
                $k_w = 1;
            // height
            if ($height < $s[1])
                $k_h = $s[1] / $height;
            elseif ($height > $s[3])
                $k_h = $s[3] / $height;
            else
                $k_h = 1;

            $k = min($k_w, $k_h);
            $width = intval($width * $k);
            $height = intval($height * $k);
        }
        return array($width, $height);
    }

    public static function human_plural_form($number, $titles=array('комментарий','комментария','комментариев'))
    {
        $cases = array (2, 0, 1, 1, 1, 2);

        $debt = "";
        if ($number < 0){
            $debt = "Долг - ";
            $number = $number * (-1);
        }

        return $debt . $number." ".$titles[ ($number%100>4 && $number%100<20)? 2 : $cases[min($number%10, 5)] ];
    }

}
