<?php

namespace app\commands;

use yii\console\Controller;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @since 2.0
 */
class OrderController extends Controller {

    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     */
    public function actionSellerPay() {

        \Yii::$app->db->createCommand("call prc_sys_status_insert('" . __FUNCTION__ . "', '0')")->execute();
        $sql = "select * from sys_job_commands where name = 'order_seller_pay' and is_error=0 ORDER BY priority DESC limit 1000 ;";
        $task_row = \Yii::$app->db_event->createCommand($sql)->queryAll();

        foreach ($task_row as $task) {
            $id = $task['id'];
            $params = $task['params'];

            $params = str_replace("\n", "", $params);
            $params = json_decode($params, JSON_UNESCAPED_UNICODE);

            $seller_id = $params['seller_id'];

            $seller = \app\models\Seller::findOne($seller_id);
            if ($seller->getFlag('type_order')) {
                
                $curs_te = \app\helpers\SysService::get('curs_te');
                
                $prc = ($params['cost_us'] / $curs_te) * $this->getPrcSetting($seller);
                \Yii::$app->billing->transaction($seller_id, 'down_order', $prc);
            }
            \Yii::$app->db_event->createCommand('delete from sys_job_commands where id = ' . $id)->execute();
        }

        \Yii::$app->db->createCommand("call prc_sys_status_insert('" . __FUNCTION__ . "', '1')")->execute();
    }

    private function getPrcSetting($seller) {
        $seller_prc = $seller->sellerInfo->po_prc;
        if($seller_prc){
            return $seller_prc;
        }else{
            return \app\helpers\SysService::get('seller_order_prc');
        }
    }

}
