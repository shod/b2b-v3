<?php
$this->title = 'Информация для покупателей';
$this->registerJsFile('/web/scripts/ajaxupload.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile(Yii::$app->request->baseUrl . '/web/scripts/js/settings.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJs(
    "
    $(function () {
		if (!$('#datepicker').is('[readonly]')){
			/*$('#datepicker').pickmeup({
				position		: 'bottom',
				hide_on_select	: true,
				format: 'd.m.Y'
			});*/
			$(\"#datepicker\").flatpickr({
                locale: {
                    firstDayOfWeek: 1
                }
            });
		}
	});
    "
);
?>
<div class="ks-page-content-body">
    <div class="ks-dashboard-tabbed-sidebar">
        <div class="ks-dashboard-tabbed-sidebar-widgets">
            <div class="row">
                <div class="col-lg-12">

                </div>
            </div>

            <form method="post" action="/settings/process" id="userForm" enctype="multipart/form-data">
                <input type="hidden" name="_csrf" value="<?= Yii::$app->request->getCsrfToken() ?>"/>
                <input type="hidden" name="action" value="save_user"/>

                <div class="row" style="padding-top: 10px; margin-top: 0px;">
                    <div class="col-lg-12">
                        <div class="alert alert-success  ks-solid-light" role="alert">Для смены обязательной информации
                            обратитесь в службу технической поддержки <a href="mailto:admin@migom.by">admin@migom.by</a>
                            или по телефону <a href="tel:+375291114545">+375(29)111-45-45</a> .
                        </div>
                    </div>
                </div>

                <div class="row" style="margin-top: 0px; padding-bottom: 15px;">
                    <div class="col-lg-4">
                        <label style="font-weight: 650;"><span style="color:red;font-size:12px;">*</span> Название
                            магазина
                            <small>(до 30 символов)</small>
                        </label>

                    </div>
                    <div class="col-lg-8" style="">
                        <input <?= (isset($seller->name) && $seller->name != "") ? "readonly" : "" ?>
                                style="width:300px" class="form-control yes" data-validation="length"
                                data-validation-length="min3"
                                data-validation-error-msg="Введите название магазина"
                                type="text" name="name" value="<?= $seller->name; ?>" maxlength="30">
                    </div>
                </div>
                <div class="row" style=" margin-top: 0px; padding-bottom: 15px;">
                    <div class="col-lg-4">
                        <label style="font-weight: 650;"><span style="color:red;font-size:12px;">*</span> Логотип <br>
                            <small>(максимальный размер 90х35, формат JPG)</small>
                        </label>
                    </div>
                    <div class="col-lg-8" style="">
                        <table>
                            <tbody>
                            <tr>
                                <td style="margin-left:0px;"><?= $logo ?></td>
                                <td>
                                    <!--button class="btn btn-<?= isset($dis_logo) ? $dis_logo : "primary" ?> ks-btn-file">
                                                                <span class="la la-cloud-upload ks-icon"></span>
                                                                <span class="ks-text">Выберите файл</span>
                                                                <input type="file" name="logo-file" <?= isset($dis_logo) ? $dis_logo : "" ?>>
                                                            </button-->
                                    <input type="file" name="logo" <?= isset($dis_logo) ? $dis_logo : "" ?>>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <br>
                        <?= isset($dis_logo_text) ? $dis_logo_text : "" ?>
                    </div>
                </div>

                <div class="row" style="margin-top: 0px; padding-bottom: 15px;">
                    <div class="col-lg-4">
                        <label style="font-weight: 600;"><span style="color:red;font-size:12px;">*</span> Образцы
                            документов,
                            подтверждающих оплату <br>
                            <small>(максимальный размер 10мб, формат JPG, GIF, PNG)</small>
                        </label>

                    </div>
                    <div class="col-lg-8" style="">
                        <button type="button" id="upload-documents" class="upload btn btn-sm btn-primary">Загрузить фотографии</button>
                        <div id="status" class="status"></div>
                        <div class="clear"><!-- --></div>
                        <br>
                        <div id="files" class="cont-files">
                            <?= $img_documents ?>
                        </div>
                        <div class="procces_load_img" id="procces_load_img">&nbsp;</div>
                    </div>
                </div>

                <div class="row" style="margin-top: 0px; padding-bottom: 15px;">
                    <div class="col-lg-4">
                        <label style="font-weight: 650;"><span style="color:red;font-size:12px;">*</span> Дата
                            регистрации в
                            торговом реестре <br>
                            <small>(пример 28.12.2013)</small>
                        </label>

                    </div>
                    <div class="col-lg-8" style="">
                        <input id="datepicker" <?= $seller->register_date ? "readonly" : "" ?> class="form-control yes"
                               type="text" data-date-format="d.m.Y"
                               name="register_date" value="<?= $seller->register_date ?>" maxlength="11"
                               data-validation="length" data-validation-length="min5"
                               data-validation-error-msg="Введите дату регистрации в торговом реестре">
                    </div>
                </div>

                <div class="row" style="margin-top: 0px; padding-bottom: 15px;">
                    <div class="col-lg-4">
                        <label style="font-weight: 650;">Кратко о магазине
                            <small>(до 1000 символов)</small>
                        </label>

                    </div>
                    <div class="col-lg-8" style="">
                        <textarea style="width: 100%" name="description" maxlength="1000"
                                  id='description'><?= $seller->description ?></textarea>
                    </div>
                </div>

                <div class="row" style="margin-top: 0px; padding-bottom: 15px;">
                    <div class="col-lg-4">
                        <label style="font-weight: 600;">Контактные телефоны <br/>
                            <small>дубликаты удаляются автоматически</small>
                        </label>

                    </div>
                    <div class="col-lg-8" style="overflow-x: auto;overflow-y: hidden;">
                        <table id="tablePhones">
                            <?= $phones ?>
                        </table>
                        <input class="btn btn-primary" type="button" value="Добавить телефон" onclick='add_phone();'
                               $vars[dis_phone]/>
                    </div>
                </div>

                <div class="row" style="margin-top: 0px; padding-bottom: 15px;">
                    <div class="col-lg-4">
                        <label style="font-weight: 600;">ICQ-консультант</label>

                    </div>
                    <div class="col-lg-8" style="">
                        <input class="form-control" type="text" name="icq" value="<?= $seller->icq ?>"
                               maxlength="9">
                    </div>
                </div>

                <div class="row" style="margin-top: 0px; padding-bottom: 15px;">
                    <div class="col-lg-4">
                        <label style="font-weight: 600;">Skype</label>

                    </div>
                    <div class="col-lg-8" style="">
                        <input class="form-control" type="text" name="skype" value="<?= $seller->skype ?>"
                               maxlength="64">
                    </div>
                </div>


                <div class="row" style="margin-top: 0px; padding-bottom: 15px;">
                    <div class="col-lg-4">
                        <label style="font-weight: 600;">Email для связи</label>

                    </div>
                    <div class="col-lg-8" style="">
                        <input class="form-control" type="text" name="email"
                               value="<?= $seller->email ?>">
                    </div>
                </div>
                <div class="row" style="margin-top: 0px; padding-bottom: 15px;">
                    <div class="col-lg-4">
                        <label><span style="font-weight: 600;">Веб сайт</span>
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
                        </label>

                    </div>
                    <div class="col-lg-8" style="">
                        Ссылка на сайт: <input class="form-control" type="text" name="site"
                                               value="<?= $seller->site ?>"><br>
                        Название ссылки: <input class="form-control" type="text"
                                                name="site_alias" value="<?= $seller->site_alias ?>">
                    </div>
                </div>
                <div class="row" style="margin-top: 0px; padding-bottom: 15px;">
                    <div class="col-lg-4">
                        <label><span style="font-weight: 600;">Описание для предложений по умолчанию</span><br>
                            <div style="font-size:13px">Описание Ваших предложений будет заменено на
                                введенный текст. <br>Оставьте поле пустым, если не хотите добавлять
                                описание по умолчанию.
                            </div>
                        </label>

                    </div>
                    <div class="col-lg-8" style="">
                        <textarea class="form-control" style="width: 100%"
                                  name="offer_default_desc"
                                  id="offer_default_desc"> <?= $seller_info->offer_default_desc ?> </textarea>
                    </div>
                </div>
                <div class="row" style="margin-top: 0px; padding-bottom: 15px;">
                    <div class="col-lg-4">
                        <label style="font-weight: 600;">Не применять к предложениям с уже имеющимся описанием</label>

                    </div>
                    <div class="col-lg-8" style="">
                        <input type="checkbox" name="f_description"
                               value="1" <?= $seller_info->f_b2b_description ? "checked" : "" ?>>
                    </div>
                </div>

                <div class="row"
                     style="padding-top: 10px; margin-top: 0px;border-bottom: 1px solid #dfdfdf; padding-bottom: 5px;">
                    <div class="col-lg-12" style="font-weight: 600;">
                        Способы оплаты
                    </div>
                </div>

                <div class="row" style="margin-top: 0px; padding-bottom: 2px;">
                    <div class="col-lg-4 col-sm-auto col-xs-auto">
                        <label style="font-weight: 600;">Наличные</label>

                    </div>
                    <div class="col-lg-8 col-sm-auto col-xs-auto" style="">
                        <input type="checkbox" name="f_nal" value="1" <?= $seller->f_nal ? "checked" : "" ?>>
                    </div>
                </div>

                <div class="row" style="margin-top: 0px; padding-bottom: 2px;">
                    <div class="col-lg-4">
                        <label style="font-weight: 600;">Безналичный расчет</label>

                    </div>
                    <div class="col-lg-8" style="">
                        <input type="checkbox" name="f_beznal" value="1" <?= $seller->f_beznal ? "checked" : "" ?>>
                    </div>
                </div>

                <div class="row" style="margin-top: 0px; padding-bottom: 2px;">
                    <div class="col-lg-4">
                        <label style="font-weight: 600;">Кредит</label>

                    </div>
                    <div class="col-lg-8" style="">
                        <input type="checkbox" name="f_credit" value="1" <?= $seller->f_credit ? "checked" : "" ?>>
                    </div>
                </div>

                <div class="row" style="margin-top: 0px; padding-bottom: 2px;">
                    <div class="col-lg-4">
                        <label style="font-weight: 600;">Рассрочка</label>

                    </div>
                    <div class="col-lg-8" style="">
                        <input id="f_rassrochka_check" type="checkbox" name="f_rassrochka"
                               value="1" <?= $seller->f_rassrochka ? "checked" : "" ?>>
                    </div>
                </div>

                <div class="row" style="margin-top: 0px; padding-bottom: 2px;">
                    <div class="col-lg-4">
                        <label style="font-weight: 600;">Банковская пластиковая карточка</label>

                    </div>
                    <div class="col-lg-8" style="">
                        <input type="checkbox" name="bit_setting[bit_card]" value="1" <?= $bit_card ?>>
                    </div>
                </div>

                <div class="row" style="margin-top: 0px; padding-bottom: 2px;">
                    <div class="col-lg-4">
                        <label style="font-weight: 600;">ЕРИП (АИС "Расчет")</label>

                    </div>
                    <div class="col-lg-8" style="">
                        <input type="checkbox" name="bit_setting[bit_erip]" value="1" <?= $bit_erip ?>>
                    </div>
                </div>

                <div class="row" style="margin-top: 0px; padding-bottom: 2px;">
                    <div class="col-lg-4">
                        <label style="font-weight: 600;">iPay</label>

                    </div>
                    <div class="col-lg-8" style="">
                        <input type="checkbox" name="bit_setting[bit_ipay]" value="1" <?= $bit_ipay ?>>
                    </div>
                </div>

                <div class="row" style="margin-top: 0px; padding-bottom: 2px;">
                    <div class="col-lg-4">
                        <label style="font-weight: 600;">WebMoney</label>

                    </div>
                    <div class="col-lg-8" style="">
                        <input type="checkbox" name="bit_setting[bit_webmoney]" value="1" <?= $bit_webmoney ?>>
                    </div>
                </div>

                <div class="row" style="margin-top: 0px; padding-bottom: 2px;">
                    <div class="col-lg-4">
                        <label style="font-weight: 600;">Почтовый перевод</label>

                    </div>
                    <div class="col-lg-8" style="">
                        <input type="checkbox" name="bit_setting[bit_post]" value="1" <?= $bit_post ?>>
                    </div>
                </div>

                <div class="row" style="margin-top: 0px; padding-bottom: 2px;">
                    <div class="col-lg-4">
                        <label style="font-weight: 600;">Наложенный платеж</label>

                    </div>
                    <div class="col-lg-8" style="">
                        <input type="checkbox" name="bit_setting[bit_nal]" value="1" <?= $bit_nal ?>>
                    </div>
                </div>

                <div class="row" style="margin-top: 0px; padding-bottom: 2px;">
                    <div class="col-lg-4">
                        <label style="font-weight: 600;">Банковский перевод</label>

                    </div>
                    <div class="col-lg-8" style="">
                        <input type="checkbox" name="bit_setting[bit_bank]" value="1" <?= $bit_bank ?>>
                    </div>
                </div>

                <div class="row" style="margin-top: 0px; padding-bottom: 2px;">
                    <div class="col-lg-4">
                        <label style="font-weight: 600;">"Халва" от МТБанк</label>

                    </div>
                    <div class="col-lg-8" style="">
                        <input class="bank-card" type="checkbox" name="bit_setting[bit_halva]"
                               value="1" <?= $bit_halva ?>>
                    </div>
                </div>

                <div class="row" style="margin-top: 0px; padding-bottom: 2px;">
                    <div class="col-lg-4">
                        <label style="font-weight: 600;">"Карта покупок" от Белгазпромбанк</label>

                    </div>
                    <div class="col-lg-8" style="">
                        <input class="bank-card" type="checkbox" name="bit_setting[bit_purchase]"
                               value="1" <?= $bit_purchase ?>>
                    </div>
                </div>

                <div class="row" style="margin-top: 0px; padding-bottom: 2px;">
                    <div class="col-lg-4">
                        <label style="font-weight: 600;">"Черепаха" от Банк ВТБ</label>

                    </div>
                    <div class="col-lg-8" style="">
                        <input class="bank-card" type="checkbox" name="bit_setting[bit_turtle]"
                               value="1" <?= $bit_turtle ?>>
                    </div>
                </div>
                <div class="row" style="margin-top: 0px; padding-bottom: 2px;">
                    <div class="col-lg-4">
                        <label style="font-weight: 600;">"SMART карта" от Банк Москва-Минск</label>

                    </div>
                    <div class="col-lg-8" style="">
                        <input class="bank-card" type="checkbox" name="bit_setting[bit_smart]"
                               value="1" <?= $bit_smart ?> >
                    </div>
                </div>
                <div class="row" style="margin-top: 0px; padding-bottom: 2px;">
                    <div class="col-lg-4">
                        <label style="font-weight: 600;">"Fun карта" от БПС-СБЕРБАНК</label>

                    </div>
                    <div class="col-lg-8" style="">
                        <input class="bank-card" type="checkbox" name="bit_setting[bit_fun]" value="1" <?= $bit_fun ?> >
                    </div>
                </div>
                <div class="row" style="margin-top: 0px; padding-bottom: 2px;">
                    <div class="col-lg-4">
                        <label style="font-weight: 600;">"Любимая карта" от Паритетбанк</label>

                    </div>
                    <div class="col-lg-8" style="">
                        <input class="bank-card" type="checkbox" name="bit_setting[bit_like]"
                               value="1" <?= $bit_like ?> >
                    </div>
                </div>
                <div class="row" style="margin-top: 0px; padding-bottom: 2px;">
                    <div class="col-lg-4">
                        <label style="font-weight: 600;">"Магнит" от Беларусбанк</label>

                    </div>
                    <div class="col-lg-8" style="">
                        <input class="bank-card" type="checkbox" name="bit_setting[bit_magnet]"
                               value="1" <?= $bit_magnet ?> >
                    </div>
                </div>
                <div class="row" style="margin-top: 0px; border-bottom: 1px solid #dfdfdf; padding-bottom: 2px;">
                    <div class="col-lg-4">
                        <label style="font-weight: 600;">Время работы (приёма заказов)</label>

                    </div>
                    <div class="col-lg-8" style="overflow: auto">
                        <table class="table" style="width: 100%; min-width: 350px;">
                            <?= $work_time ?>
                        </table>
                    </div>
                </div>

                <div class="row" style="margin-top: 0px; padding-bottom: 15px;">
                    <div class="col-lg-4">
                        <label style="font-weight: 600;">Импортеры <br>(указывать ссылку запрещено)</label>

                    </div>
                    <div class="col-lg-8" style="">
                        <?= $cont_importers ?>
                        <span class="link" id="importers" style="cursor:pointer">Добавить импортера</span>
                        <div id="cont_importers"></div>
                    </div>
                </div>

                <div class="row" style="margin-top: 0px; padding-bottom: 15px;">
                    <div class="col-lg-4">
                        <label style="font-weight: 600;">Сервисные центры <br>(указывать ссылку запрещено)</label>

                    </div>
                    <div class="col-lg-8" style="">
                        <?= $cont_service_centers ?>
                        <span class="link" id="service_centers" style="cursor:pointer">Добавить сервисный центр</span>
                        <div id="cont_service_centers"></div>
                    </div>
                </div>

                <div class="row" style="padding-top: 10px; margin-top: 0px;">
                    <div class="col-lg-12">
                        * - поля обязательные для заполнения
                        (после первого сохранения меняются по запросу)<br><br>
                        <input class="btn btn-primary" type="submit" value="Сохранить"/>
                    </div>
                </div>

            </form>
        </div>



    </div>
</div>

