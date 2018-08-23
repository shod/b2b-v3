<div class="col-lg-12">
    <div class="card" style="height: 100%">
        <div class="card-block">
            <form id="curs_form" method="post" action="/product/save-curs">
                <input type="hidden" name="_csrf" value="<?= Yii::$app->request->getCsrfToken() ?>"/>
                <div class="row">
                    <div class="col-lg-6">Валюта прайса</div>
                    <div class="col-lg-6">
                        <select id="curr_select" name="currency_base" onchange="check_currency()" class="form-control">
                            <option value="br" <?= isset($selected_br) ? $selected_br : "" ?> >BYR</option>
                            <option value="usd" <?= isset($selected_usd) ? $selected_usd : "" ?> >USD</option>
                            <option value="byn" <?= isset($selected_byn) ? $selected_byn : "" ?>>BYN</option>
                        </select>
                    </div>
                </div>
                <div class="row sh_tr" style="margin-top: 5px;">
                    <div class="col-lg-6">Курс USD<br>(<span id='curr_ot'><?= $curr_ot ?></span> - <span id='curr_do'><?= $curr_do ?></span>)</div>
                    <div class="col-lg-6">
                        <input id="curr_seller" class="form-control" type="text" name="currency_rate" value="<?= $currency_rate_byn ?>">
                        <b>Курс: <span style="color:red">BYR</span> - <?= $currency_rate ?></b>;&nbsp;<b><span style="color:red">BYN</span> - <?= $currency_rate_byn ?> </b><br>
                    </div>
                </div>
                <div class="row" style="margin-top: 5px;">
                    <div class="col-lg-6">Округление</div>
                    <div class="col-lg-6">
                        <select name="cost_round_num" class="form-control">
                            <option value="2" <?= isset($selected_round_2) ? $selected_round_2 : "" ?> >1 коп.</option>
                            <option value="3" <?= isset($selected_round_3) ? $selected_round_3 : "" ?> >10 коп.</option>
                            <option value="4" <?= isset($selected_round_4) ? $selected_round_4 : "" ?> >1 руб.</option>
                        </select>
                    </div>
                </div>
            </form>
            <div class="row">
                <div class="col-lg-12">
                    <button class="btn btn-primary" onclick="ajaxSubmit(this,'curs_form')">Сохранить</button>
                </div>
            </div>
        </div>
    </div>
</div>
