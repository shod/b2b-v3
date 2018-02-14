<?php
$this->title = "Импорт/экспорт прайса";

$this->registerJs(
    "
    $(document).ready(function () {
        $(\"#btn_download_price\").click(function(){
			var oldValue = $(this).val()
			$(this).attr({value:'Подождите...',disabled: true})
			setTimeout(function(btn){btn.attr({value:oldValue, disabled: false});}, 3000, $(this))
			window.location = '/product/price-download'
		})
		
		$(\"#btn_download_tmpl\").click(function(){
			var oldValue = $(this).val()
			$(this).attr({value:'Подождите...',disabled: true})
			setTimeout(function(btn){btn.attr({value:oldValue, disabled: false});}, 3000, $(this))
			$(\"#frm_download_tmpl\").submit();
		})
		
    });
    "
);
?>
<div class="ks-page-content-body">
    <div class="ks-dashboard-tabbed-sidebar">
        <div class="ks-dashboard-tabbed-sidebar-widgets">
            <div class="row">
                <div class="col-lg-12">
                    <div class="content-end"><a href="#" onclick="$('#help-block').toggle(500)">ПОМОЩЬ</a></div>

                    <div style="display: none;" id="help-block" class="alert alert-primary ks-solid-light" role="alert">
                        <p>Данная вкладка служит для совершения операций с прайс-листами.</p>
                        <p>Для того чтобы скачать свой прайс-лист, нажмите на кнопку «Скачать мой прайс-лист».</p>
                        <p>Для того чтобы скачать шаблон прайс-листа (шаблон прайс-листа для импорта на Migom.by с
                            проставленными названиями товаров),
                            нажмите на кнопку «Скачать шаблон прайса». Его можно скачать для всех товаров (только
                            активные категории товаров),
                            а также для отдельной категории.
                        </p>
                        <p>Скачанный шаблон можно заполнить и импортировать обратно.</p>
                        <p>Если Вы хотите закачать свой прайс-лист, то выберите его на своем компьютере либо напишите
                            ссылку на закачку прайс-листа с источника в интернете.
                            Помните, что Ваш прайс-лист должен удовлетворять требованиям к прайс-листу для размещения на
                            Migom.by.
                        </p>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="card" style="height: 100%">
                        <div class="card-block">
                            <h4>Мой прайс</h4>
                            <div class="row">
                                <div class="col-lg-2">
                                    <b>Файл</b>
                                </div>
                                <div class="col-lg-8">
                                    <input class="btn btn-primary" type="button" value="Скачать мой прайс-лист"
                                           id="btn_download_price"/>
                                </div>
                                <div class="col-lg-2">

                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-2">
                                    <b>Ссылка</b>
                                </div>
                                <div class="col-lg-8">
                                    <a style="width: 100%"
                                       href='http://api.migom.by/api/price/csv/?seller_id=<?= $seller_id ?>&key_str=<?= $md5_seller ?>'
                                       target='_blank'>
                                        http://api.migom.by/api/price/csv/?seller_id=<?= $seller_id ?>
                                        &key_str=<?= $md5_seller ?>
                                    </a>
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
                                    <a href='http://api.migom.by/api/price/yml/?seller_id=<?= $seller_id ?>&key_str=<?= $md5_seller ?>'
                                       target='_blank'>
                                        http://api.migom.by/api/price/yml/?seller_id=<?= $seller_id ?>
                                        &key_str=<?= $md5_seller ?>
                                    </a>
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
                        <div class="card-block">
                            <h4>Шаблон прайса для обновления</h4>
                            <p>Прайс-лист должен удовлетворять шаблону (<a
                                        href="http://files.migom.by/files/template-price-new.xlsx"
                                        type='application/excel'>скачать</a>)</p>


                            <form class="form-inline" method="get" action="/product/price-template"
                                  id="frm_download_tmpl">
                                <input class="btn btn-primary" type="button" value="Скачать шаблон прайса"
                                       id="btn_download_tmpl"/>
                                <span style="margin: 0px 10px">для</span>
                                <select class="form-control" name="catalog_id">
                                    <option value="0">всех товаров (<?= $cnt_all ?>)</option>
                                    <?= $catalog_options ?>
                                </select>
                            </form>

                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card" style="height: 100%">
                        <div class="card-block">
                            <h4>Закачать прайс-лист</h4>

                            <div><b style='color:red'>Внимание!</b> Если вы используете в качестве прайс-листа выгрузку
                                цен &nbsp;с площадки <b>Onliner.by</b>,
                                название загружаемого файла должно быть <b>onliner.csv</b></div>
                            <br>
                            <div class="alert alert-primary ks-solid-light" role="alert">
                                <span class="la la-warning la-2x"></span>
                                Перед закачкой прайса проверьте <b>соответствие</b>
                                валюты в прайсе и в
                                <a class="alert-link" href='/?admin=settings&&action=options'
                                   target="_blank">настройках</a>.
                            </div>

                            <form method="post" action="/product/price-import" id="frm_import"
                                  enctype="multipart/form-data">
                                <input type="hidden" name="_csrf" value="<?= Yii::$app->request->getCsrfToken() ?>"/>
                                <input type="hidden" name="MAX_FILE_SIZE" value="15000000">

                                <div class="row">
                                    <div class="col-lg-2">
                                        <label>
                                            <input type="radio" name="type" value="file">
                                            <b>Файл прайса:</b>
                                        </label>
                                    </div>
                                    <div class="col-lg-10">
                                        <label class="btn btn-primary file-label" for="my-file-selector">
                                            <input name="file" id="my-file-selector" type="file" style="display:none"
                                                   onchange="$('#upload-file-info').html(this.files[0].name)">
                                            Выбрать файл
                                        </label>
                                        <span class='label label-info' id="upload-file-info"></span>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-2">
                                        <label>
                                            <input type="radio" name="type" value="url">
                                            <b>URL прайса:</b>
                                        </label>


                                    </div>
                                    <div class="col-lg-10">
                                        <input class="form-control" type="text" name="url"/>
                                    </div>
                                </div>
                                <div class="content-end">
                                    <input class="btn btn-primary" type="button" value="Закачать"
                                           onclick="this.disabled=true;this.value='Подождите...';this.form.submit();"/>
                                </div>


                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card" style="height: 100%">

                        <?= $results ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>