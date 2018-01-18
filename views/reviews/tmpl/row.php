<tr>
    <td>
        <h5><?= isset($date) ? $date : "" ?></h5>
        <span class="list-inline rating-list inline m-b-0 m-r-10"><?= isset($stars) ? $stars : "" ?></span> <br />
        <p><?= isset($name) ? $name : "" ?></p>
        <p><?= isset($email) ? $email : "" ?> </p>
        <?= isset($tmpl_conclusion) ? $tmpl_conclusion : "" ?>
    </td>
    <td style="width: 35%">
        <mark><?= isset($title) ? $title : "" ?></mark><br />
        <p><?= isset($review) ? stripcslashes($review) : "" ?></p>
        <?= isset($delivery) ? $delivery : "" ?>
        <?= isset($cost) ? $cost : "" ?>

    </td>
    <td><textarea style="width: 100%; height: 170px;" class="form-control" name="answer[<?= isset($id) ? $id : "" ?>]"><?= isset($answer) ? $answer : "" ?></textarea></td>
</tr>