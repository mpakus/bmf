<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

        <title>BMF - blogs management framework - <?= not_empty( $page['title'], '' ) ?></title>
        <link href="/favicon.ico" rel="shortcut icon" type="image/x-icon">
        <!--[if lt IE 9]>
        <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
        <link rel="stylesheet" type="text/css" href="<?= site_url('static/css/base.css') ?>">
        <link rel="stylesheet" type="text/css" href="<?= site_url('static/themelight/styles.css') ?>">
        <link rel="stylesheet" type="text/css" href="<?= site_url('static/css/jquery.pnotify.default.css') ?>">
        <link rel="stylesheet" type="text/css" href="<?= site_url('static/css/flash.css') ?>">
        <link rel="stylesheet" type="text/css" href="<?= site_url('static/jqueryui/blitzer/jquery-ui-1.8.19.custom.css') ?>" />

        <script type="text/javascript" src="<?= site_url('static/js/jquery-1.7.2.min.js') ?>"></script>
        <script type="text/javascript" src="<?= site_url('static/js/bmf.js') ?>"></script>
        <script type="text/javascript" src="<?= site_url('static/js/jquery.pnotify.min.js') ?>"></script>
        <script type="text/javascript" src="<?= site_url('static/jqueryui/jquery-ui-1.8.19.custom.min.js') ?>"></script>
    </head>
    <body>
		<?=get_flash();?>
        <div id="layout">
            <header>
                <div id="login">
                    <? if( user_signed_in() ){
                        $user = current_user();
                        ?>
                        <? if( user_is('admin') ) echo anchor('post/form','Создать топик'); ?>
                        <?= user_avatar( $user, 'mini' ) ?> <?= anchor( 'user/profile/'.$user['login'], $user['login'] ) ?> | <?= anchor( 'user/logout', 'Выйти' ) ?>
                    <? }else{ ?>
                        <?= anchor( 'user/login', 'Войти' ) ?> | <?= anchor( 'user/register', 'Регистрация' ) ?>
                    <? } ?>                    
                </div>
                <h1><a href="/">BMF - blogs management framework</a></h1>
                <nav>
                    <a href="<?= site_url('page/about') ?>">О блоге</a>
                </nav>                
            </header>

            <div id="page">
                <div id="content">
                    <div class="text">
                        <?= $content ?>                    
                    </div>
                </div>
                <div id="sidebar">
                </div>
            </div>
            
            <footer>
                &copy; 2012 <a href="http://aomega.ru">AOmega</a>
            </footer>

        </div>
    </body>
</html>