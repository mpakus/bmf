<div class="post">
    <h1>Регистрация</h1>

<?=form_open_multipart( site_url('user/register') , array('id'=>'register_form') )?>
    <div class="error"><?php echo validation_errors(); ?></div>

    <table class="fields">

        <tr>
            <td><b class="red">*</b> Логин:</td>
            <td><input name="login" type="text" class="input size90p" value="<?=form_prep($data['login'])?>" /></td>
        </tr>

        <tr>
            <td><b class="red">*</b> Пароль:</td>
            <td>
                <input type="password" class="input size90p"  id="new_password" name="new_password" /><br/>
                <input id="showcharacters" name="showcharacters" type="checkbox" /> показать
            </td>
        </tr>

<script language="JavaScript" type="text/javascript">
$(document).ready(function() {
	$('#showcharacters').click(function() {
		if ($(this).attr('checked')) {
			$('#new_password').replaceWith('<input id="new_password" name="new_password" class="input size90p" type="text" value="' + $('#new_password').attr('value') + '" />');
		} else {
			$('#new_password').replaceWith('<input id="new_password" name="new_password" class="input size90p" type="password" value="' + $('#new_password').attr('value') + '" />');
		}
	});
});
</script>

        <tr>
            <td><b class="red">*</b> E-mail:</td>
           <td><input name="email" type="text" class="input size90p" value="<?=form_prep($data['email'])?>" /></td>
        </tr>

        <tr>
            <td>Картинка аватара:</td>
           <td><input name="avatar" type="file" class="size90p" /></td>
        </tr>

        <tr>
            <td><b class="red">*</b> Укажите числа если вы не робот:</td>
            <td><?=$captcha['image']?> <input type="text" class="input" style="width:100px;" name="captcha" /></td>
        </tr>

        <tr>
            <td colspan="2">
                <div style="height:200px;overflow:auto">Регистрационное соглашение</div>
                <input type="radio" name="sign" value="1" onClick="$('#submit').removeAttr('disabled')"/> Согласен
                <input type="radio" checked name="sign" value="0" onClick="$('#submit').attr('disabled', 'disabled')" /> Нет
            </td>
        </tr>

        <tr>
            <td></td>
            <td><input type="submit" class="button" id="submit" value="Зарегистрироваться" disabled /></td>
        </tr>

    </table>

</form>
</div>