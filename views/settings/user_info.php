<?php
$this->title = 'Информация для покупателей';
$this->registerJsFile('https://b2b.migom.by/js/ajaxupload.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile(Yii::$app->request->baseUrl . '/web/scripts/js/settings.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
?>
<div class="ks-page-content-body">
    <div class="ks-dashboard-tabbed-sidebar">
        <div class="ks-dashboard-tabbed-sidebar-widgets">
            <div class="row">
                <div class="col-lg-12">

                </div>
            </div>

            <form method="post" action="/settings/process">
                <input type="hidden" name="_csrf" value="<?= Yii::$app->request->getCsrfToken() ?>"/>
                <input type="hidden" name="action" value="save_user"/>

                <div class="row" style="padding-top: 10px; margin-top: 0px;">
                    <div class="col-lg-12">
                        <div class="ks-nav-body">
                            <div class="ks-nav-body-wrapper">
                                <div class="container-fluid" style="overflow: auto">
                                    <table id="ks-datatable" class="table table-bordered table-condenced"
                                           width="100%">
                                        <thead>
                                        <tr>
                                            <th class="head-title">Параметр</th>
                                            <th class="head-title">Значение</th>
                                        </tr>
                                        </thead>
                                        <tfoot>
                                        <tr>
                                            <th class="head-title">Параметр</th>
                                            <th class="head-title">Значение</th>
                                        </tr>
                                        </tfoot>
                                        <tbody>
                                        <tr>
                                            <td><span style="color:red;font-size:12px;">*</span> Название магазина
                                                <small>(до 30 символов)</small>
                                            </td>
                                            <td><input readonly="" style="width:300px" class="form-control yes"
                                                       type="text" name="name" value="<?= $seller->name; ?>" maxlength="30"></td>
                                        </tr>
                                        <tr>
                                            <td><span style="color:red;font-size:12px;">*</span> Логотип <br>
                                                <small>(максимальный размер 90х35, формат JPG)</small>
                                            </td>
                                            <td>
                                                <table>
                                                    <tbody>
                                                    <tr>
                                                        <td style="margin-left:0px;"><?= $logo ?></td>
                                                        <td><input type="file" name="logo" <?= $dis_logo ?>></td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                                <br>
                                                <?= $dis_logo_text ?>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td><span style="color:red;font-size:12px;">*</span> Образцы документов,
                                                подтверждающих оплату <br>
                                                <small>(максимальный размер 10мб, формат JPG, GIF, PNG)</small>
                                            </td>
                                            <td>
                                                <button id="upload-doc" class="upload">Загрузить фотографии</button>
                                                <div id="status" class="status"></div>
                                                <div class="clear"><!-- --></div>
                                                <br>
                                                <div id="files" class="cont-files">
                                                    <?= $img_documents ?>
                                                </div>
                                                <div class="procces_load_img" id="procces_load_img">&nbsp;</div>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Импортеры <br>(указывать ссылку запрещено)</td>
                                            <td class="importers">
                                                <?= $cont_importers ?>
                                                <span class="link" id="importers" style="cursor:pointer">Добавить импортера</span>
                                                <div id="cont_importers"></div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Сервисные центры <br>(указывать ссылку запрещено)</td>
                                            <td class="service_centers">
                                                <?= $cont_service_centers ?>
                                                <span class="link" id="service_centers" style="cursor:pointer">Добавить сервисный центр</span>
                                                <div id="cont_service_centers"></div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><span style="color:red;font-size:12px;">*</span> Дата регистрации в
                                                торговом реестре <br>
                                                <small>(пример 28.12.2013)</small>
                                            </td>
                                            <td><input id="datepicker" <?= $seller->register_date ? "readonly": "" ?> class="form-control yes" type="text"
                                                       name="register_date" value="<?= $seller->register_date ?>" maxlength="11"></td>
                                        </tr>
                                        <tr>
                                            <td>Кратко о магазине <small>(до 1000 символов)</small></td>
                                            <td><textarea style="width: 100%" name="description" maxlength="1000" $vars[dis_description] id='description'><?= $seller->description ?></textarea></td>
                                        </tr>
                                        <tr>
                                            <td>Контактные телефоны <br/><small>дубликаты удаляются автоматически</small></td>
                                            <td>
                                                <table id="tablePhones">
                                                    <?= $phones ?>
                                                </table>
                                                <input class="btn btn-primary" type="button" value="Добавить телефон" onclick='add_phone();'$vars[dis_phone] />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>ICQ-консультант</td>
                                            <td><input class="form-control" type="text" name="icq" value="<?= $seller->icq ?>"
                                                       maxlength="9"></td>
                                        </tr>
                                        <tr>
                                            <td>Skype</td>
                                            <td><input class="form-control" type="text" name="skype" value="<?= $seller->skype ?>"
                                                       maxlength="64"></td>
                                        </tr>
                                        <tr>
                                            <td>Email для заказов</td>
                                            <td><input class="form-control" type="text" name="<?= $seller->email ?>"
                                                       value="sale@migom.by"></td>
                                        </tr>
                                        <tr>
                                            <td>Веб сайт
                                                <br>
                                                <span style="color:red">Внимание! Сайт должен быть доступен для показа в iframe.</span>
                                                <br>
                                                <a href="#"
                                                   style="border-bottom: 1px dotted #000; text-decoration: none;"
                                                   onclick="openbox('box'); return false">Открыть инструкцию</a>
                                                <div id="box" style="display: none;">
                                                    <p>
						<span style="color:gray">
							Настройки на сервере.
							<br>
							Apache
							<br>
							Добавьте следующую строку в httpd.conf и для проверки перезагрузите веб-сервер:
							<br>
							Header always append X-Frame-Options DENY<br>
							Header always append X-Frame-Options: SAMEORIGIN<br>
							Header always append X-Frame-Options: ALLOW-FROM http://migom.by/<br>

							Nginx<br>

							Добавьте следующее в nginx.conf в директиве Server:<br>

							add_header X-Frame-Options “DENY”;<br>
							add_header X-Frame-Options “SAMEORIGIN”;<br>
							add_header X-Frame-Options “ALLOW-FROM http://migom.by/”;<br>

							Для проверки результатов необходима перезагрузка.
						</span>
                                                    </p>
                                                </div>
                                            </td>
                                            <td>Ссылка на сайт: <input class="form-control" type="text" name="site"
                                                                       value="<?= $seller->site ?>" readonly=""><br>
                                                Название ссылки: <input class="form-control" type="text"
                                                                        name="site_alias" value="<?= $seller->site_alias ?>">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Описание для предложений по умолчанию<br>
                                                <div style="font-size:13px">Описание Ваших предложений будет заменено на
                                                    введенный текст. <br>Оставьте поле пустым, если не хотите добавлять
                                                    описание по умолчанию.
                                                </div>
                                            </td>
                                            <td><textarea class="form-control" style="width: 100%"
                                                          name="offer_default_desc" id="offer_default_desc"> <?= $seller_info->offer_default_desc ?> </textarea>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Не применять к предложениям с уже имеющимся описанием</td>
                                            <td><input type="checkbox" name="f_description" value="1" <?= $seller_info->f_b2b_description ? "checked" : "" ?>></td>
                                        </tr>
                                        <tr>
                                            <th colspan="2">Оплата</th>
                                        </tr>
                                        <tr>
                                            <td>Возможна оплата наличными</td>
                                            <td><input type="checkbox" name="f_nal" value="1" <?= $seller->f_nal ? "checked" : "" ?>></td>
                                        </tr>
                                        <tr>
                                            <td>Возможна оплата по безналу</td>
                                            <td><input type="checkbox" name="f_beznal" value="1" <?= $seller->f_beznal ? "checked" : "" ?>></td>
                                        </tr>
                                        <tr>
                                            <td>Возможность продажи в кредит</td>
                                            <td><input type="checkbox" name="f_credit" value="1" <?= $seller->f_credit ? "checked" : "" ?>></td>
                                        </tr>
                                        <tr>
                                            <td>Возможность продажи в рассрочку</td>
                                            <td><input type="checkbox" name="f_rassrochka" value="1" <?= $seller->f_rassrochka ? "checked" : "" ?>></td>
                                        </tr>
                                        <tr>
                                            <th colspan="2">Способы оплаты</th>
                                        </tr>
                                        <tr>
                                            <td>Возможна оплата банковской пластиковой карточкой</td>
                                            <td><input type="checkbox" name="bit_setting[bit_card]" value="1" <?= $bit_card ?>></td>
                                        </tr>
                                        <tr>
                                            <td>Возможна оплата через ЕРИП (АИС "Расчет")</td>
                                            <td><input type="checkbox" name="bit_setting[bit_erip]" value="1" <?= $bit_erip ?>></td>
                                        </tr>
                                        <tr>
                                            <td>Возможна оплата через iPay</td>
                                            <td><input type="checkbox" name="bit_setting[bit_ipay]" value="1" <?= $bit_ipay ?>></td>
                                        </tr>
                                        <tr>
                                            <td>Возможна оплата через WebMoney</td>
                                            <td><input type="checkbox" name="bit_setting[bit_webmoney]" value="1" <?= $bit_webmoney ?>></td>
                                        </tr>
                                        <tr>
                                            <td>Возможна оплата через EasyPay</td>
                                            <td><input type="checkbox" name="bit_setting[bit_easypay]" value="1" <?= $bit_easypay ?>></td>
                                        </tr>
                                        <tr>
                                            <td>Возможна оплата через почтовый перевод</td>
                                            <td><input type="checkbox" name="bit_setting[bit_post]" value="1" <?= $bit_post ?>></td>
                                        </tr>
                                        <tr>
                                            <td>Возможна оплата наложным платежом</td>
                                            <td><input type="checkbox" name="bit_setting[bit_nal]" value="1" <?= $bit_nal ?>></td>
                                        </tr>
                                        <tr>
                                            <td>Возможна оплата банковским переводом</td>
                                            <td><input type="checkbox" name="bit_setting[bit_bank]" value="1" <?= $bit_bank ?>></td>
                                        </tr>
                                        <tr>
                                            <td>Возможна оплата по системе Халва</td>
                                            <td><input type="checkbox" name="bit_setting[bit_halva]" value="1" <?= $bit_halva ?>></td>
                                        </tr>
                                        <tr>
                                            <th colspan="2">Время работы (приёма заказов)</th>
                                        </tr>

                                        <?= $work_time ?>
                                        <tr class="title">
                                            <td colspan="2" style="color:red">* - поля обязательные для заполнения
                                                (после первого сохранения меняются по запросу)
                                            </td>
                                        </tr>
                                        </tbody>

                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="content-end">
                            <input id="btnSaveFix" class="btn btn-primary" type="button" value="Сохранить"
                                   onclick="this.disabled=true;this.value='Подождите...';this.form.submit();"/>
                        </div>
                    </div>
                </div>

            </form>
            <span id="upload" class="upload"></span>


        </div>
    </div>
</div>
