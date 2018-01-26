<?php
$this->title = "Импорт/экспорт прайса";
?>

<div class="row">
    <div class="col-lg-12">
        <div class="card" style="height: 100%">
            <div class="card-header">
                Мой прайс
            </div>
            <div class="card-block">
                <div class="row">
                    <div class="col-lg-2">
                        <b>Файл</b>
                    </div>
                    <div class="col-lg-8">
                        <input class="btn btn-primary" type="button" value="Скачать мой прайс-лист" id="btn_download_price" />
                    </div>
                    <div class="col-lg-2">

                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-2">
                        <b>Ссылка</b>
                    </div>
                    <div class="col-lg-8">
                        <a style="width: 100%" href='http://www.migom.by/?block=mtex_seller_price&seller_id=$vars[seller_id]&key_str=$vars[md5_seller]&mode=csv' target='_blank'>http://www.migom.by/?block=mtex_seller_price&seller_id=$vars[seller_id]&key_str=$vars[md5_seller]&mode=csv</a>
                    </div>
                    <div class="col-lg-2">
                        (csv формат)
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-2">
                        <b>Ссылка</b>
                    </div>
                    <div class="col-lg-8">
                        <a href='http://www.migom.by/?block=mtex_seller_price&seller_id=$vars[seller_id]&key_str=$vars[md5_seller]&mode=yml' target='_blank'>http://www.migom.by/?block=mtex_seller_price&seller_id=$vars[seller_id]&key_str=$vars[md5_seller]&mode=yml</a>
                    </div>
                    <div class="col-lg-2">
                        (xml формат Яндекс Маркета)
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card" style="height: 100%">
            <div class="card-header">
                Шаблон прайса для обновления
            </div>
            <div class="card-block">
                <p>Прайс-лист должен удовлетворять шаблону (<a href="http://files.migom.by/files/template-price-new.xlsx" type='application/excel'>скачать</a>)</p>


                <form class="form-inline" method="get" id="frm_download_tmpl" >
                     <input type="hidden" name="block" value="content_products" />
                     <input type="hidden" name="action" value="export_catalog" />

                     <input class="btn btn-primary" type="button" value="Скачать шаблон прайса" id="btn_download_tmpl"/>
                     <span style="margin: 0px 10px">для</span>
                     <select class="form-control" name="catalog_id" >
                          <option value="0">всех товаров ($vars[cnt_all])</option>
                          $vars[catalog_options]
                     </select>
                </form>

            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card" style="height: 100%">
            <div class="card-header">
                Закачать прайс-лист
            </div>
            <div class="card-block">


                <div><b style='color:red'>Внимание!</b> Если вы используете в качестве прайс-листа выгрузку цен &nbsp;с площадки <b>Onliner.by</b>,
                    название загружаемого файла должно быть <b>onliner.csv</b></div><br>

                <div class="alert alert-danger ks-active-border" role="alert"><img src='http://admin.migom.by/ads/img/attention-ico.png'/> Перед закачкой прайса проверьте <b>соответствие</b> валюты в прайсе и в <a class="alert-link" href='/?admin=settings&&action=options' target="_blank">настройках</a>.</div>

                <form method="post" id="frm_import" enctype="multipart/form-data">
                    <input type="hidden" name="block" value="content_products" />
                    <input type="hidden" name="action" value="import" />
                    <input type="hidden" name="MAX_FILE_SIZE" value="15000000">

                    <div class="row">
                        <div class="col-lg-2">
                            <b>Файл прайса:</b>
                        </div>
                        <div class="col-lg-10">
                            <div class="ks-upload-block">
                                <span class="ks-icon la la-cloud-upload"></span>
                                <span class="ks-text">Перетащите файл сюда </span>
                                <span class="ks-upload-btn">
                                            <span class="ks-btn-separator">или</span>
                                            <button class="btn ks-btn-file">
                                                <span class="la la-cloud-upload ks-icon"></span>
                                                <span class="ks-text">Выберите файл</span>
                                                <input type="file">
                                            </button>
                                        </span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-2">
                            <b>URL прайса:</b>
                        </div>
                        <div class="col-lg-10">
                            <input class="form-control form-group-sm" type="text" name="url" value="$vars[url]" />
                        </div>
                    </div>
                    <div class="content-end">
                        <input class="btn btn-primary" type="button" value="Закачать" onclick="this.disabled=true;this.value='Подождите...';this.form.submit();"/>
                    </div>


                </form>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card" style="height: 100%">
            <div class="card-block">
                $vars[results]
            </div>
        </div>
    </div>
</div>
