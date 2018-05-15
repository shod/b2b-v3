<?php

namespace app\components\widgets;

use yii;
use yii\helpers\Url;

class Complaint extends \yii\base\Widget {

    public $viewFile = 'complaint/show';
    public $sid;
    public function run() {
        $sql = "SELECT
								f.id,
								f.seller_id,
								f.phone,
								FROM_UNIXTIME(f.created_at, '%d/%m %H:%I') AS date,
								f.product_id,
								f.status,
								si.po_balance,
								si.po_phone,
								si.po_email,
								s.work_time,

							IF (
								po_active = 1
								AND po_balance > 0
								AND (
									(po_phone IS NOT NULL)
									OR (po_email IS NOT NULL)
								),
								1,
								0
							) AS po_active,
							 s.name
							FROM
								migombyha.stat_seller_phone_fail f
							JOIN migomby.seller_info si ON (f.seller_id = si.seller_id)
							JOIN migomby.seller AS s ON (s.id = si.seller_id)
							WHERE
								f. STATUS = 0
                            AND FROM_UNIXTIME(f.created_at) > (DATE_SUB(NOW(), INTERVAL 1 DAY ))
							AND f.seller_id = {$this->sid} order by f.created_at desc limit 5";
        $res = \Yii::$app->db->createCommand($sql)->queryAll();

        $complaints = '';
        foreach((array)$res as $r)
        {
            $complaints .= $this->render('//statistic/tmpl/complaint-item', $r);
        }
        if($complaints == ""){
            $complaints = "<span class=\"ks-card-widget-datetime\">Все отлично, жалоб на недоступность телефонов нет!</span>";
        }
        echo $this->render($this->viewFile, ['complaints' => $complaints]);
    }
}
