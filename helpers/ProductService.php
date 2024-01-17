<?php
namespace app\helpers;
use yii;

class ProductService
{
    public static function getDateUpdate($seller_id){
		$res_data = file_get_contents(\Yii::$app->params['up_domain']."/?load_block=all_seller_process&mode=b2b&sid=".$seller_id, False);
        $res_data = unserialize($res_data);
		$res = isset($res_data[$seller_id]) ? $res_data[$seller_id] : "";
		
		if ($res){			
			$res['date'] = strtotime($res["cdate"]);
		}
		else{
			$res = \Yii::$app->db->createCommand("select UNIX_TIMESTAMP(last_dat_update) as date from seller_export_info where seller_id={$seller_id}")->queryOne();
			if (!$res){
				$res = \Yii::$app->db->createCommand("select max(start_date) as date from product_seller where seller_id={$seller_id}")->queryOne();
			} else {
				$time1 = $res['date'];
				$res1 = \Yii::$app->db->createCommand("select max(start_date) as date from product_seller where seller_id={$seller_id}")->queryOne();
				$time2 = $res1['date'];
				if ($time2 > $time1){
					$res = $res1;
				}
			}
		}
        $now = time();
        $time = $res['date'];

        $dt = $now - $time;
        $days = floor($dt / 86400);

        if (date("Y.m.d", $time) == date("Y.m.d")) {
            $res = "<font color=\"#009900\">сегодня</font> " . date("H:i", $time);
        }
        elseif (date("Y.m.d", $time) == date("Y.m.d", mktime(0, 0, 0, date("m"), date("d") - 1, date("Y"))))
        {
            $res = "<font color=\"#009900\">вчера</font> " . date("H:i", $time);
        }
        elseif (date("Y.m.d", $time) == date("Y.m.d", mktime(0, 0, 0, date("m"), date("d") - 2, date("Y"))))
        {
            $res = "<font color=\"#009900\">позавчера</font> " . date("H:i", $time);
        }
        elseif ($days > 14)
        {

            $m = SiteService::getDataMothStr($time);
            $res = "<font color=\"#ff0000\">давно " . " {$m}</font>";
        }
        else
        {
            $days = ceil($dt / 86400);
            $w = ($days % 10 == 1 && $days != 11) ? "день" : (
            (($days % 10 == 2 && $days != 12) || ($days % 10 == 3 && $days != 13) || ($days % 10 == 4 && $days != 14)) ? "дня" : "дней"
            );

            $m = SiteService::getDataMothStr($time);
            $res = "<font color=\"#FC9E10\">{$days} {$w} назад </font>, " .  " {$m}";
        }

        return $res;
    }
	
	public static function getDateFormat($time){
		$now = time();        

        $dt = $now - $time;
        $days = floor($dt / 86400);

        if (date("Y.m.d", $time) == date("Y.m.d")) {
            $res = "<font color=\"#009900\">сегодня</font> " . date("H:i", $time);
        }
        elseif (date("Y.m.d", $time) == date("Y.m.d", mktime(0, 0, 0, date("m"), date("d") - 1, date("Y"))))
        {
            $res = "<font color=\"#009900\">вчера</font> " . date("H:i", $time);
        }
        elseif (date("Y.m.d", $time) == date("Y.m.d", mktime(0, 0, 0, date("m"), date("d") - 2, date("Y"))))
        {
            $res = "<font color=\"#009900\">позавчера</font> " . date("H:i", $time);
        }
        elseif ($days > 14)
        {

            $m = SiteService::getDataMothStr($time);
            $res = "<font color=\"#ff0000\">давно " . " {$m}</font>";
        }
        else
        {
            $days = ceil($dt / 86400);
            $w = ($days % 10 == 1 && $days != 11) ? "день" : (
            (($days % 10 == 2 && $days != 12) || ($days % 10 == 3 && $days != 13) || ($days % 10 == 4 && $days != 14)) ? "дня" : "дней"
            );

            $m = SiteService::getDataMothStr($time);
            $res = "<font color=\"#FC9E10\">{$days} {$w} назад </font>, " .  " {$m}";
        }

        return $res;
	}
}