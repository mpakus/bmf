<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta charset="utf-8">
        <title>Blogging framework - <?= not_empty($page['title'], '') ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">

        <link href="<?= site_url('static/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
        <style type="text/css">
            body {
                padding-top: 60px;
                padding-bottom: 40px;
            }
        </style>
        <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
          <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->

        <link rel="shortcut icon" href="/favicon.ico">
        <link rel="stylesheet" type="text/css" href="<?= site_url('static/css/base.css') ?>">
        <link rel="stylesheet" type="text/css" href="<?= site_url('static/themelight/styles.css') ?>">
        <link rel="stylesheet" type="text/css" href="<?= site_url('static/css/jquery.pnotify.default.css') ?>">
        <link rel="stylesheet" type="text/css" href="<?= site_url('static/css/flash.css') ?>">
        <link rel="stylesheet" type="text/css" href="<?= site_url('static/jqueryui/blitzer/jquery-ui-1.8.19.custom.css') ?>" />
        <!--link rel="stylesheet" href="http://yandex.st/highlightjs/6.2/styles/default.min.css" -->
        <link rel="stylesheet" type="text/css" href="<?= site_url('static/highlightjs/monokai.css') ?>" />

        <script type="text/javascript" src="<?= site_url('static/js/jquery-1.7.2.min.js') ?>"></script>
        <script type="text/javascript" src="<?= site_url('static/js/bmf.js') ?>"></script>
        <script type="text/javascript" src="<?= site_url('static/js/jquery.pnotify.min.js') ?>"></script>
        <script type="text/javascript" src="<?= site_url('static/jqueryui/jquery-ui-1.8.19.custom.min.js') ?>"></script>
        
        <script type="text/javascript" src="http://yandex.st/highlightjs/6.2/highlight.min.js"></script>
    </head>

    <body>
        <?= get_flash(); ?>

        <div class="navbar navbar-fixed-top">
            <div class="navbar-inner">
                <div class="container">
                    <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </a>
                    <a class="brand" href="/">BMF</a>
                    <div class="nav-collapse">
                        <ul class="nav">
                            <li class="active"><a href="/">Главная</a></li>
                            <li><a href="/page/about">О блоге</a></li>
                            <li><a href="/page/help">Помощь</a></li>
                        </ul>
                    </div>
                    
                    <div id="login">
                        <? if (user_signed_in()) {
                            $user = current_user();
                            ?>
                            <? if (user_is('admin')) echo anchor('post/form', 'Создать топик'); ?>
                            <?= user_avatar($user, 'mini') ?> <?= anchor('user/profile/' . $user['login'], $user['login']) ?> | <?= anchor('user/logout', 'Выйти') ?>
                        <? }else { ?>
                            <?= anchor('user/login', 'Войти') ?> | <?= anchor('user/register', 'Регистрация') ?>
                        <? } ?>                    
                    </div>

                </div>
            </div>
        </div>

        <div class="container">
            <?= $content ?>
            <hr>
            <footer>
                <p>&copy; <a href="http://aomega.ru">AOmega.ru</a> 2013</p>
            </footer>
        </div>

        <script type="text/javascript" src="<?= site_url('static/bootstrap/js/bootstrap.min.js') ?>"></script>
        <script type="text/javascript">
            $(function() {
                hljs.initHighlightingOnLoad();
                $('a.delete').click( BMF.Post.destroy );

                $('a.delcomment').click( BMF.Comment.destroy );
                $('a.reply').click( BMF.Comment.reply );
            });
        </script>
    </body>
</html>
