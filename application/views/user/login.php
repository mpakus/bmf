<div class="post">

<h1>Авторизация</h1>

<form method="POST" action="<?=site_url('user/login')?>">

    <table class="fields">
        <tr><td>Логин:</td><td><input name="login" type="text" class="string size90p" /></td></tr>
        <tr><td>Пароль:</td><td><input type="password" name="password" class="string size90p"/></td></tr>
        <td><td colspan="2"><input type="submit" value="Войти" class="button" /></td></tr>
    </table>
    
</form>
 <small>
    <a href="<?=site_url('user/register')?>">Регистрация</a> | <a href="<?=site_url('user/remember')?>">Забыли?</a>
</small>

</div>