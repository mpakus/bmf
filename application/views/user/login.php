<div class="post">
  <h1>Авторизация</h1>

  <form method="POST" action="<?=site_url('user/login')?>">
      <table class="fields table table-hover">
          <tr><td>Логин:</td><td><input name="login" type="text" class="string size90p" /></td></tr>
          <tr><td>Пароль:</td><td><input type="password" name="password" class="string size90p"/></td></tr>
          <td><td colspan="2"><input type="submit" value="Войти" class="btn btn-primary" /> <a href="<?=site_url('user/remember')?>" class="btn btn-info">Забыли пароль?</a></td></tr>
      </table>      
  </form>
   <small>
      <?/*<a href="<?=site_url('user/register')?>">Регистрация</a> | */?>
  </small>
</div>