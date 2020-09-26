<?php
$this->title = "Реквизиты магазина";
$this->registerJsFile('/scripts/ajaxupload.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile(Yii::$app->request->baseUrl . '/scripts/js/settings.js?v=2', ['depends' => [\yii\web\JqueryAsset::className()]]);
?>
<form method="post" action="/settings/process">
    <input type="hidden" name="_csrf"
           value="<?= Yii::$app->request->getCsrfToken() ?>"/>
    <input type="hidden" name="action" value="save"/>
    <div class="ks-page-content-body">
        <div class="ks-dashboard-tabbed-sidebar">
            <div class="ks-dashboard-tabbed-sidebar-widgets">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="alert alert-danger  ks-solid-light" role="alert">Все поля обязательны для заполнения</div>
                    </div>
                    <div class="col-lg-12">
                        <div class="alert alert-success  ks-solid-light" role="alert">Для смены обязательной информации обратитесь к вашему менеджеру <a href="mailto:<?= Yii::$app->params['saleManager'] ?>"><?= Yii::$app->params['saleManager'] ?></a> или по телефону <a href="tel:+375291124545">+375(29)112-45-45</a> .</div>
                    </div>
                    <h4 class="col-lg-12">Контактная информация</h4>

                    <div class="col-lg-4">
                        <label>Ф.И.О. Полностью</label>
                        <input name="fio" type="text"
                               class="form-control" <?= (isset($fio) && $fio != "") ? "readonly" : "" ?>
                               value="<?= (isset($fio) && $fio != "") ? $fio : "" ?>" data-validation="length" data-validation-length="min5"
                               data-validation-error-msg="Введите Ф.И.О">
                    </div>
                    <div class="col-lg-4">
                        <label>Адрес электронной почты</label>
                        <input name="email" type="text"
                               class="form-control" <?= (isset($email) && $email != "") ? "readonly" : "" ?>
                               placeholder="" value="<?= (isset($email) && $email != "") ? $email : "" ?>" data-validation="length"
                               data-validation-length="min5"
                               data-validation-error-msg="Введите адрес электронной почты">
                    </div>
                    <div class="col-lg-4">
                        <label>Телефон</label>
                        <input name="phone" type="text"
                               class="form-control" <?= (isset($phone) && $phone != "") ? "readonly" : "" ?> placeholder=""
                               value="<?= (isset($phone) && $phone != "") ? $phone : "" ?>" data-validation="length" data-validation-length="min5"
                               data-validation-error-msg="Введите телефон, факс">
                    </div>
                </div>

                <div class="row">
                    <br>
                    <h4 class="col-lg-12">Информация о компании</h4>

                    <div class="col-lg-3">
                        <label>Название организации</label>
                        <input name="company_name" type="text"
                               class="form-control" <?= (isset($company_name) && $company_name != "") ? "readonly" : "" ?>
                               placeholder="" value="<?= (isset($company_name) && $company_name != "") ? $company_name : "" ?>" data-validation="length"
                               data-validation-length="min5" data-validation-error-msg="Введите название организации">
                    </div>
                    <div class="col-lg-3">
                        <label>Ф.И.О. Директора</label>
                        <input name="fio_director" type="text"
                               class="form-control" <?= (isset($fio_director) && $fio_director != "") ? "readonly" : "" ?>
                               placeholder="" value="<?= (isset($fio_director) && $fio_director != "") ? $fio_director : "" ?>" data-validation="length"
                               data-validation-length="min5" data-validation-error-msg="Введите Ф.И.О. Директора">
                    </div>
                    <div class="col-lg-3">
                        <label>Действует на основании</label>
                        <input name="basis" type="text" class="form-control"
                               placeholder="" <?= (isset($basis) && $basis != "") ? "readonly" : "" ?>
                               value="<?= (isset($basis) && $basis != "") ? $basis : "" ?>" data-validation="length" data-validation-length="min5"
                               data-validation-error-msg="Введите информацию">
                    </div>
                    <div class="col-lg-3">
                        <label>УНП</label>
                        <input name="unn" type="text"
                               class="form-control" <?= (isset($unn) && $unn != "") ? "readonly" : "" ?> placeholder=""
                               value="<?= (isset($unn) && $unn != "") ? $unn : "" ?>" data-validation="length" data-validation-length="min5"
                               data-validation-error-msg="Введите УНП">
                    </div>
                </div>

                <div class="row"><br>
                    <h4 class="col-lg-12">Адреса</h4>
                    <div class="col-lg-6">
                        <label>Почтовый адрес</label>
                        <table>
                            <tbody>
                            <tr>
                                <td style="padding-left: 0px;"><span><small>индекс</small><br><input
                                                class="form-control" <?= (isset($zip_code) && $zip_code != "") ? "readonly" : "" ?> type="text" name="zip_code" value="<?= (isset($zip_code) && $zip_code != "") ? $zip_code : "" ?>" data-validation="length"
                                                data-validation-length="min5" data-validation-error-msg="Введите индекс"></span>
                                </td>
                                <td><span><small>город</small><br><input class="form-control" type="text"
                                                                         name="city" value="<?= (isset($city) && $city != "") ? $city : "" ?>" <?= (isset($city) && $city != "") ? "readonly" : "" ?> data-validation="length"
                                                                         data-validation-length="min5" data-validation-error-msg="Введите город"></span></td>
                                <td><span><small>улица, дом/офис</small><br><input class="form-control"
                                                                                            type="text" name="address"
                                                                                            value="<?= (isset($address) && $address != "") ? $address : "" ?>" <?= (isset($address) && $address != "") ? "readonly" : "" ?> data-validation="length"
                                                                                            data-validation-length="min5" data-validation-error-msg="Введите улица, дом, корпус, офис"></span></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-lg-6">
                        <label>Юридический адрес</label>
                        <table>
                            <tbody>
                            <tr>
                                <td style="padding-left: 0px;"><span><small>индекс</small><br><input
                                                class="form-control red" type="text" name="zip_code_law" value="<?= (isset($zip_code_law) && $zip_code_law != "") ? $zip_code_law : "" ?>" <?= (isset($zip_code_law) && $zip_code_law != "") ? "readonly" : "" ?> data-validation="length"
                                                data-validation-length="min5" data-validation-error-msg="Введите улица, дом, корпус, офис"></span>
                                </td>
                                <td><span><small>город</small><br><input class="form-control red" type="text"
                                                                         name="city_law" value="<?= (isset($city_law) && $city_law != "") ? $city_law : "" ?>" <?= (isset($city_law) && $city_law != "") ? "readonly" : "" ?> data-validation="length"
                                                                         data-validation-length="min5" data-validation-error-msg="Введите улица, дом, корпус, офис"></span></td>
                                <td><span><small>улица, дом/офис</small><br><input class="form-control red"
                                                                                            type="text"
                                                                                            name="address_law"
                                                                                            value="<?= (isset($address_law) && $address_law != "") ? $address_law : "" ?>" <?= (isset($address_law) && $address_law != "") ? "readonly" : "" ?> data-validation="length"
                                                                                            data-validation-length="min5" data-validation-error-msg="Введите улица, дом, корпус, офис"></span></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>


                <div class="row"><br>
                    <h4 class="col-lg-12">Банковские реквизиты</h4>

                    <div class="col-lg-3">
                        <label>Наименование банка</label>
                        <input name="bank_name" type="text"
                               class="form-control" <?= (isset($bank_name) && stripcslashes($bank_name) != "" && stripcslashes($bank_name) != " ") ? "readonly" : "" ?>
                               placeholder="" value='<?= (isset($bank_name) && stripcslashes($bank_name) != "" && stripcslashes($bank_name) != " ") ? stripcslashes($bank_name) : "" ?>' data-validation="length"
                               data-validation-length="min1" data-validation-error-msg="Введите наименование банка">
                    </div>
                    <div class="col-lg-3">
                        <label>Код банка (БИК)</label>
                        <input name="bank_code" type="text"
                               class="form-control" <?= (isset($bank_code) && $bank_code != "") ? "readonly" : "" ?>
                               placeholder="" value="<?= (isset($bank_code) && $bank_code != "") ? stripcslashes($bank_code) : "" ?>" data-validation="length"
                               data-validation-length="min1" data-validation-error-msg="Введите код банка">
                    </div>
                    <div class="col-lg-3">
                        <label>Расч. счет (IBAN)</label>
                        <input name="ras_schet" type="text"
                               class="form-control" <?= (isset($ras_schet) && $ras_schet != "") ? "readonly" : "" ?>
                               placeholder="" value="<?= (isset($ras_schet) && $ras_schet != "") ? stripcslashes($ras_schet) : "" ?>" data-validation="length"
                               data-validation-length="min1" data-validation-error-msg="Введите расч. счет">
                    </div>
                    <div class="col-lg-3">
                        <label>Адрес банка</label>
                        <input name="bank_address" type="text"
                               class="form-control" <?= (isset($bank_address) && $bank_address != "") ? "readonly" : "" ?>
                               placeholder="" value="<?= (isset($bank_address) && $bank_address != "") ? stripcslashes($bank_address) : "" ?>" data-validation="length"
                               data-validation-length="min1" data-validation-error-msg="Введите адрес банка">
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="alert alert-success  ks-solid-light" role="alert">Для смены пароля обратитесь в службу технической поддержки.</div>
                    </div>
                    <div class="col-lg-12 content-end">
                        <input class="btn btn-success" type="submit" value="Сохранить">
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-3"> Копия свидетельства о гос. регистрации(максимальный размер 10мб, формат JPG, GIF, PNG)
                        <br><br>
                        <button id="upload" class="upload btn btn-sm btn-primary">Загрузить
                            фотографии
                        </button>
                    </div>
                    <div class="col-lg-3">

                        <div id="status" class="status"></div>
                        <div id="files" class="cont-files">
                            <?= $img_registration ?>
                        </div>
                        <div class="procces_load_img" id="procces_load_img">&nbsp;</div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</form>


