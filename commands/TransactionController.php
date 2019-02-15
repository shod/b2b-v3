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
class TransactionController extends Controller {

    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     */
    public function actionSellerOrderDown() {

		\Yii::info('actionSellerOrderDown', 'debug');
		\Yii::$app->db->createCommand("call prc_sys_status_insert('" . __FUNCTION__ . "', '0')")->execute();
        $sql = "select * from sys_job_commands where name = 'order_seller_pay' and is_error=0 ORDER BY priority DESC limit 1000 ;";
        $task_row = \Yii::$app->db_event->createCommand($sql)->queryAll();

        foreach ($task_row as $task) {
            $id = $task['id'];
           // dd($task);
            $params = $task['params'];

            $params = str_replace(["\n\r", "\n", "\r", '&'], "", $params);
           
            $params = json_decode($params, JSON_UNESCAPED_UNICODE);

            switch (json_last_error()) {
                case JSON_ERROR_NONE:
                    echo 'JSON - No error';
                break;
                default:
                    echo 'JSON - Unknown error';
                    \Yii::$app->db_event->createCommand('update sys_job_commands set is_error = 1 where id = '.$id)->execute();
                    continue;
                break;
            }


            $seller_id = $params['seller_id'];
            
            $seller = \app\models\Seller::findOne($seller_id);            
       
            if ($seller->getFlag('type_order')) {
                $curs_te = \app\helpers\SysService::get('curs_te');
                //$seller_order_prc = \app\helpers\SysService::get('seller_order_prc'); // Процент за заказ 
                //$prc_setting = $seller_info->po_prc; // Процент за заказ у продавца    
                $seller_order_prc = $this->getPrcSetting($seller);
                $cost = (isset($params['cost_us_total']))?$params['cost_us_total']:$params['cost_us'];
                
                //$seller_order_prc = (100-$prc_setting)*$seller_order_prc/100; // С учетом скидки
                                
                $value = ($cost / $curs_te) * $seller_order_prc;
				echo "{$seller_id}, 'down_order', {$value}";
				\Yii::info("{$seller_id}, 'down_order', {$value}", 'debug');
                \Yii::$app->billing->transaction($seller_id, 'down_order', $value);
            }
            //\Yii::$app->db_event->createCommand('delete from sys_job_commands where id = ' . $id)->execute();
            \Yii::$app->db_event->createCommand('update sys_job_commands set is_error = 2 where id = '.$id)->execute();
        }

        \Yii::$app->db->createCommand("call prc_sys_status_insert('" . __FUNCTION__ . "', '1')")->execute();
        \Yii::$app->db->createCommand("call prc_sys_status_insert('" . __FUNCTION__ . "', 'ok')")->execute();
    }
    
    /*
     * Транзакция списания за просмотр контактов
     */
    public function actionSellerPopupDown() {
        \Yii::$app->db->createCommand("call prc_sys_status_insert('" . __FUNCTION__ . "', '0')")->execute();
        
        /*Подготовка данных. Для перестарховки*/
        \Yii::$app->db->createCommand("call ps_statpopup_setcost()")->execute();
        
        $sql = "select seller_id, sum(cost_us) as sum_cost
from migombyha.stat_popup as pop
where created_at > UNIX_TIMESTAMP(DATE_FORMAT(DATE_ADD(now(),INTERVAL -1 DAY),'%Y-%m-%d'))
and created_at < UNIX_TIMESTAMP(DATE_FORMAT(now(),'%Y-%m-%d'))
group by seller_id";
        $task_row = \Yii::$app->db->createCommand($sql)->queryAll();
     
        foreach ($task_row as $task) {
            $seller_id = $task['seller_id'];
            $cost_us = $task['sum_cost'];
            $seller = \app\models\Seller::findOne($seller_id);
            $seller_info = \app\models\SellerInfo::findOne($seller_id);
            
            if ($seller->getFlag('type_order') && $seller->getFlag('is_phone')) {
                $curs_te = \app\helpers\SysService::get('curs_te');
                //$seller_order_prc = \app\helpers\SysService::get('seller_order_prc'); // Процент за заказ                
                //$prc_setting = $seller_info->po_prc; // Процент за заказ                
                $seller_order_prc = $this->getPrcSetting($seller);
                
                //$seller_order_prc = (100-$prc_setting)*$seller_order_prc/100; // С учетом скидки
                $value = ($cost_us / $curs_te) * ($seller_order_prc/10);
                $value = round($value, 4);
                //echo "seller={$seller_id}-{$cost_us}-prc={$prc_setting},TE={$curs_te}-{$seller_order_prc}-{$value}";
                try{
                    \Yii::$app->billing->transaction($seller_id, 'down_popup', $value);                    
                } catch (TransactionException $e){
                    echo 'Выброшено исключение: ',  $e->getMessage(), "\n";
                }
            }
        }
        
        \Yii::$app->db->createCommand("call prc_sys_status_insert('" . __FUNCTION__ . "', '1')")->execute();
    }

    /*
     * Транзакция списания за переход на сайт
     */
    public function actionSellerProxyDown() {
        \Yii::$app->db->createCommand("call prc_sys_status_insert('" . __FUNCTION__ . "', '0')")->execute();
                        
        $sql = "select seller_id, sum(cost) as sum_cost
from migombyha.stat_proxy as pop
where created_at > UNIX_TIMESTAMP(DATE_FORMAT(DATE_ADD(now(),INTERVAL -1 DAY),'%Y-%m-%d'))
and created_at < UNIX_TIMESTAMP(DATE_FORMAT(now(),'%Y-%m-%d'))
group by seller_id 
HAVING sum_cost > 0";
        
        $task_row = \Yii::$app->db->createCommand($sql)->queryAll();
     
        foreach ($task_row as $task) {
            $seller_id = $task['seller_id'];
            $cost_us = $task['sum_cost'];
            $seller = \app\models\Seller::findOne($seller_id);
            $seller_info = \app\models\SellerInfo::findOne($seller_id);
            
            if ($seller->getFlag('type_order')) {
                $curs_te = \app\helpers\SysService::get('curs_te');
                $seller_order_prc = $this->getPrcSetting($seller);
                //$seller_order_prc = \app\helpers\SysService::get('seller_order_prc'); // Процент за заказ                
                //$prc_setting = $seller_info->po_prc; // Процент за заказ                
                
                //$seller_order_prc = (100-$prc_setting)*$seller_order_prc/100; // С учетом скидки
                $value = ($cost_us / $curs_te) * ($seller_order_prc/10);
                $value = round($value, 4);
                //echo "seller={$seller_id}-{$cost_us}-prc={$prc_setting},TE={$curs_te}-{$seller_order_prc}-value={$value}";
                try{
                    \Yii::$app->billing->transaction($seller_id, 'down_proxy', $value);                    
                } catch (TransactionException $e){
                    echo 'Выброшено исключение: ',  $e->getMessage(), "\n";
                }
            }
        }
        
        \Yii::$app->db->createCommand("call prc_sys_status_insert('" . __FUNCTION__ . "', '1')")->execute();
    }
    
    /*Данные по базовой процентной ставке*/
    private function getPrcSetting($seller) {
        $seller_prc = $seller->sellerInfo->po_prc;
        if($seller_prc > 0){
            return $seller_prc;
        }else{
            return \app\helpers\SysService::get('seller_order_prc');
        }
    }
}
