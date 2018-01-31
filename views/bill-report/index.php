<?php
$this->title = "Финансовый отчет";
$this->registerJs(
    "
    $(document).ready(function () {
        $(function () {
            if (!$('.datepicker').is('[readonly]')){
                var min_date = new Date;
                min_date.addDays(-70); // Note that this is method that PickMeUp adds during modification of Date object
                var max_date = new Date;
                max_date.addDays(1);
                $('.datepicker').pickmeup({
                    position		: 'bottom',
                    hide_on_select	: true,
                    format: 'Y-m-d',
                    render : function (current_date) {
                        if (current_date < min_date || current_date > max_date) {
                            return {disabled: true};
                        }
                    }
                });
            }
        });
		
    });
    "
);
?>
<div class="row" data-dashboard-widget>
    <div class="col-lg-12">
        <div>
            <div>

                <div class="row">
                    <div class="col-lg-4">
                        <form class="form-inline" method="get">
                            <table width=100%>
                                <tr>
                                    <td style="padding-left: 0px;">
                                        Месяц:
                                        <select class="form-control" id='select_m' name="m" onchange="this.form.submit()">
                                            <?= $month_options ?>
                                        </select>
                                    </td>
                                    <td align=right>
                                    </td>
                                </tr>
                            </table>
                        </form>
                    </div>
                    <div class="col-lg-6">
                        <form class="form-inline" method="get">
                            <input style="margin-top: 5px" id='date_from' class="form-control yes datepicker" name='date_from' type="text" maxlength="11" value='<?= isset($date_from) ? $date_from : ""?>' style="width:100px"/>
                            <input style="margin-top: 5px" id='date_to' class="form-control yes datepicker" name='date_to' type="text" maxlength="11" value='<?= isset($date_to) ? $date_to: ""?>' style="width:100px"/>
                            <input style="margin-top: 5px" type='submit' value='Показать за этот период' class='btn btn-primary' />
                        </form>
                    </div>
                    <div class="col-lg-2">
                        <button style="margin-top: 5px" class='btn btn-primary' data-remote="/bill-report/all-report" data-toggle="ajaxModal" data-target=".bd-example-modal-lg">Общая статистика</button>
                    </div>
                </div>
                <br>


                <div style="overflow: auto">
                <table class="table ks-payment-table-invoicing">
                    <thead>
                    <tr>
                        <th width="80">Дата</th>
                        <th width="50">Время</th>
                        <th>Транзакция</th>
                        <th>Счет до транзакции</th>
                        <th>Сумма</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?= isset($data) ? $data : "" ?>
                    </tbody>
                </table>
            </div>
        </div>
        </div>
    </div>

</div>

