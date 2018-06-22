<?php

namespace app\commands;

use yii\console\Controller;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class TestController extends Controller {

    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     */
    public function actionTransaction() {
        $seller_id = 1500;
       
        // добавляем
        
        $balance_0 = $this->getBillAccountBalance($seller_id);
        \Yii::$app->billing->transaction($seller_id, 'up_promice_pay', 1);
        $balance_1 = $this->getBillAccountBalance($seller_id);
        
        if($balance_1 != $balance_0 + 1 ){
            $this->alert('up_promice_pay : Error');
        }
        
        $balance_0 = $this->getBillAccountBalance($seller_id);
        \Yii::$app->billing->transaction($seller_id, 'Up_cash', 1);
        $balance_1 = $this->getBillAccountBalance($seller_id);
        
        if($balance_1 != $balance_0 + 1 ){
            $this->alert('Up_cash : Error');
        }
        
        $balance_0 = $this->getBillAccountBalance($seller_id);
        \Yii::$app->billing->transaction($seller_id, 'Up_cash_bonus', 1);
        $balance_1 = $this->getBillAccountBalance($seller_id);
        
        if($balance_1 != $balance_0 + 1 ){
            $this->alert('Up_cash_bonus : Error');
        }
        
        $balance_0 = $this->getBillAccountBalance($seller_id);
        \Yii::$app->billing->transaction($seller_id, 'Up_click', 1);
        $balance_1 = $this->getBillAccountBalance($seller_id);

        if($balance_1 != $balance_0 + 1 ){
            $this->alert('Up_click : Error');
        }
        
        // отнимаем
        
        $balance_0 = $this->getBillAccountBalance($seller_id);
        \Yii::$app->billing->transaction($seller_id, 'Down_auction', ['value' => 1, 'catalog_id' => 1]);
        $balance_1 = $this->getBillAccountBalance($seller_id);
        
        if($balance_1 + 1 != $balance_0 ){
            $this->alert('Down_auction : Error');
        }
        
        $balance_0 = $this->getBillAccountBalance($seller_id);
        \Yii::$app->billing->transaction($seller_id, 'Down_auction_bonus', ['value' => 1, 'catalog_id' => 1]);
        $balance_1 = $this->getBillAccountBalance($seller_id);
        
        if($balance_1 + 1 != $balance_0 ){
            $this->alert('Down_auction_bonus : Error');
        }
        
        $balance_0 = $this->getBillAccountBalance($seller_id);
        \Yii::$app->billing->transaction($seller_id, 'Down_posms', ['value' => 1, 'po_balance_count' => 100]);
        $balance_1 = $this->getBillAccountBalance($seller_id);
        
        if($balance_1 + 1 != $balance_0 ){
            $this->alert('Down_auction_bonus : Error');
        }
        
        
        \Yii::$app->billing->transaction($seller_id, 'Activate');
        $seller = \app\models\Seller::findOne($seller_id);
        if($seller->active != 1 ){
            $this->alert('Activate : Error');
        }
        
        $balance_0 = $this->getBillAccountBalance($seller_id);
        \Yii::$app->billing->transaction($seller_id, 'Deactivate');
        $balance_1 = $this->getBillAccountBalance($seller_id);
        $seller = \app\models\Seller::findOne($seller_id);
        if($seller->active != 0 || $balance_0 != $balance_1){
            $this->alert('Deactivate : Error');
        }
        
        $balance_0 = $this->getBillAccountBalance($seller_id);
        \Yii::$app->billing->transaction($seller_id, 'Activate_b2b');
        $balance_1 = $this->getBillAccountBalance($seller_id);
        $seller = \app\models\Seller::findOne($seller_id);
        if($seller->active != 1 || $balance_0 != $balance_1){
            $this->alert('Activate_b2b : Error');
        }
        
        $balance_0 = $this->getBillAccountBalance($seller_id);
        \Yii::$app->billing->transaction($seller_id, 'Deactivate_b2b');
        $seller = \app\models\Seller::findOne($seller_id);
        $balance_1 = $this->getBillAccountBalance($seller_id);

        if($seller->active != 0 || $balance_0 - \app\modules\billing\transaction\Deactivate_b2b::COST != $balance_1){
            $this->alert('Deactivate_b2b : Error');
        }
        
        \Yii::$app->billing->transaction($seller_id, 'Section_group', [["section_deactivate", "484"], ["Section_activate", "223"]]);
        
    }
    
    private function alert($message) {
        echo "\033[01;31m FAIL - {$message}. \033[0m";
        die;
    }
    
    private function getBillAccountBalance($seller_id) {
        $seller = \app\models\Seller::findOne($seller_id);
        $BillAccount = \app\models_ex\BillAccount::findOne($seller->bill_account_id);
        $bonus = \app\models_ex\BillAccount::find()->where(['owner_id' => $BillAccount->id])->one();
        return $BillAccount->balance + $bonus->balance + $BillAccount->balance_clicks;
    }

}
