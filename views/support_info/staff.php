<div class="nav-item dropdown ks-messages">
                    <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                        <span class="la la-phone la-2x ks-icon" aria-hidden="true">
                        </span>
                        <span class="ks-text">Служба поддержки</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="Preview" style="height: 235px;">
                        <ul class="nav nav-tabs ks-nav-tabs ks-info" role="tablist">

                            <li class="nav-item">
                                <a class="nav-link active" href="#" style="margin: 10px;" data-toggle="tab" data-target="#ks-navbar-users" role="tab">Ваш менеджер</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#" style="margin: 10px;" data-toggle="tab" data-target="#ks-navbar-tech" role="tab">Служба технической поддержки</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#" style="margin: 10px;" data-toggle="tab" data-target="#ks-navbar-reviews" role="tab">Модератор отзывов</a>
                            </li>
                        </ul>
                        <div class="tab-content">


                            <div class="tab-pane ks-messages-tab active" id="ks-navbar-users" role="tabpanel" style="height: 150px;">
                                <div class="ks-wrapper">
                                    <div class="ks-message" style="height: 61%;">
                                        <div class="">
                                            <img src="/img/design/maxi_logo.png" style="margin-right: 10px;" height="130">
                                        </div>
                                        <div class="ks-info">
                                            <div class="ks-user-name"><h4>Жанна</h4></div>
                                            <div class="ks-text">
                                                <b>Тел:</b><span style="color:#AA0000; font-size: 13px;">временно не назначен</span> &nbsp; <br>                                                
                                                <b>E-mail:</b> <a href="mailto:<?= Yii::$app->params['saleManager'] ?>"><?= Yii::$app->params['saleManager'] ?></a> <br>
                                                <b>Время работы:</b> с 9:00 до 18:00
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane ks-messages-tab " id="ks-navbar-tech" role="tabpanel" style="height: 150px;">
                                <div class="ks-wrapper">
                                    <div class="ks-message" style="height: 61%;">
                                        <div class="">
                                            <img src="/img/design/maxi_logo.png" style="margin-right: 10px;" height="130">
                                        </div>
                                        <div class="ks-info">
                                            <div class="ks-user-name"><h4>Екатерина</h4></div>
                                            <div class="ks-text">
                                                <b>Тел:</b><span style="color:#AA0000; font-size: 13px;">временно не назначен</span><br>                                                
                                                <b>E-mail:</b> <a href="mailto:<?= Yii::$app->params['adminEmail'] ?>"><?= Yii::$app->params['adminEmail'] ?></a> <br>
                                                <b>Время работы:</b> с 9:00 до 18:00
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane ks-messages-tab" id="ks-navbar-reviews" role="tabpanel" style="height: 150px;">
                                <div class="ks-wrapper">
                                    <div class="ks-message" style="height: 61%;">
                                        <div class="ks-info">
                                            <div class="ks-user-name"></div>
                                            <div class="ks-text">
                                                <b>E-mail:</b> <a href="mailto:<?= Yii::$app->params['reportEmail'] ?>"><?= Yii::$app->params['reportEmail'] ?></a> <br>
                                                <!--b>Контактное лицо:</b> Ольга<br /-->
                                                <b>Время работы:</b> с 9:00 до 18:00
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>