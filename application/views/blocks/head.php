            <div id="head">
                <a href="/"><img src="/imgs/applecrush-logo.png" title="AppleCrush - топ обзоры новинок" alt="AppleCrush - на главную страницу" id="logo" /></a>
                <ul id="menu">
                    <!--li><a href="<?= site_url() ?>">Главная</a></li-->
                    <li><a href="<?= site_url( 'blog/reviews' ) ?>" class="active">Обзоры</a></li>
                    <li><a href="<?= site_url( 'blog/videos' ) ?>" class="active">Видео</a></li>
                    <li><a href="<?= site_url( 'blog/photos' ) ?>" class="active">Картинки</a></li>
                    <li><a href="<?= site_url( 'page/about' ) ?>">О нас</a></li>
                </ul>
                <div id="login">
<?

if( user_signed_in() ){
    $user = current_user();
    ?>
    <?= user_avatar( $user, 'mini' ) ?> <?= anchor( 'user/profile/'.$user['login'], $user['login'] ) ?> | <?= anchor( 'user/logout', 'Выйти' ) ?>
    <?
}else{
    ?>
    <?= anchor( 'user/login', 'Войти' ) ?> | <?= anchor( 'user/register', 'Регистрация' ) ?> <!--span class="social_net"><a href="/"></a></span-->
    
    <script src="http://loginza.ru/js/widget.js" type="text/javascript"></script>
    <a href="http://loginza.ru/api/widget?token_url=http://applecrush.ru" class="loginza">
        <img src="http://loginza.ru/img/sign_in_button_gray.gif" alt="Войти через loginza"/>
    </a>
    <?
}
?>                    
                </div>
                <div class="line"></div>
            </div>
