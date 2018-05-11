<div class="wtitle">Пауза</div>
<div class="wcontent">
    <form method="get" id="ajaxForm" action="/seller/activate-process">
        <input type="hidden" name="action" value="deactivate"/>
        <p style="margin-bottom: 10px">Ценовые предложения и контактная информация вашего магазина будут недоступны для
            посетителей Migom.by. </p>
        <p style="margin-bottom: 10px"><span style="color: red; font-weight: bold;">ВНИМАНИЕ!</span> За услугу "Пауза"
            взимается плата – <b>5 ТЕ</b>.</p>
        <p style="margin-bottom: 40px">Поставить аккаунт на паузу? </p>
        <table width=100%>
            <tr align=center>
                <td width=50%><input class="btn btn-success" type="submit" value="Да" style="width: 100px;"/></td>
                <td><input class="btn btn-danger" type="button" value="Нет"
                           onclick="$('#myDefaultModal').modal('hide')" style="width: 100px;"/></td>
            </tr>
        </table>
    </form>
</div>
