<div class="ks-dashboard-tabbed-sidebar">
<div class="ks-dashboard-tabbed-sidebar-widgets">
    <div class="row">
        <div class="col-lg-12">
            <div class="card" style="height: 100%">
                <div class="card-block">
                    <h4>Акт приемки-сдачи выполненных работ без НДС</h4>
                    <p>Выберите дату:</p>
                    <form class="form-inline" action="/bill-report/get-akt" id="frm_blankop"
                          target="_blank" method="post">
                        <input type="hidden" name="_csrf" value="<?= Yii::$app->request->getCsrfToken() ?>"/>
                        <input type="hidden" name="no_nds" value="2"/>
                        <select class="form-control" name="year" onchange="showMonthNoNds(this)" style="margin-right: 10px;">
                            <option value="0">Год</option>
							<option value="20">2020</option>
                            <option value="19">2019</option>                            
                        </select>
                        <span id="month_select_no_nds"></span><br><br>
                        <div id='type_report_no_nds'></div>
                        <div id="btn_report_no_nds"></div>
                    </form>

                </div>
            </div>
        </div>

    </div>

    <script>
        function showMonthNoNds(obj) {
            var today = new Date();
            year = parseInt(obj.value);
            year = year + 100;
            year_now = today.getYear();

            $('#month_select_no_nds').html("");
            $('#btn_report_no_nds').html("");
            month_list = ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'];
            if (year < year_now) {
                html = "<select class='form-control' name='month' onchange='getReport_no_nds()' style=\"margin-right: 10px;\">" +
                    "<option value='0'>Месяц</option>" +
                    "<option value='1'>Январь</option>" +
                    "<option value='2'>Февраль</option>" +
                    "<option value='3'>Март</option>" +
                    "<option value='4'>Апрель</option>" +
                    "<option value='5'>Май</option>" +
                    "<option value='6'>Июнь</option>" +
                    "<option value='7'>Июль</option>" +
                    "<option value='8'>Август</option>" +
                    "<option value='9'>Сентябрь</option>" +
                    "<option value='10'>Октябрь</option>" +
                    "<option value='11'>Ноябрь</option>" +
                    "<option value='12'>Декабрь</option>" +
                    "</select>";
            } else {
                month = today.getMonth() + 1;

                if(parseInt(year) == 118){
                    i =7;
                } else {
                    i=0;
                }
                html = "<select class='form-control' name='month' onchange='getReport_no_nds()'><option value='0'>Месяц</option>";
                for (i; i <= month - 2; i++) {
                    html += "<option value='" + (i + 1) + "'>" + month_list[i] + "</option>";
                }
                html += "</select>";
            }
            $('#month_select_no_nds').html(html);
        }

        function get_xlsx_no_nds() {
            $('#type_report_no_nds').html("");
            html = "<input type='hidden' name='load' value='xlsx'/>";
            $('#type_report_no_nds').html(html);
        }

        function get_pdf_no_nds() {
            $('#type_report_no_nds').html("");
            html = "<input type='hidden' name='load' value='pdf'/>";
            $('#type_report_no_nds').html(html);
        }

        function getReport_no_nds() {
            $('#btn_report_no_nds').html("");
            html = "<button class='btn btn-primary-outline' onclick=$('#type_report').html(''); style=\"margin-right: 5px;\">Сформировать акт</button>   <button class=\"btn btn-primary-outline\" onclick='get_xlsx_no_nds()'><span class=\"la la-file-excel-o ks-icon\"></span><span class=\"ks-text\">Скачать в xlsx</span></button>   <button style=\"visibility: hidden;\" class=\"btn btn-primary-outline\" onclick='get_pdf_no_nds()'><span class=\"la la-file-pdf-o ks-icon\"></span><span class=\"ks-text\">Скачать в pdf</span></button>";
            $('#btn_report_no_nds').html(html);
        }
    </script>

</div>
</div>