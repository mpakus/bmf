<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta charset="utf-8">
        <title>BMF - blogs management framework - <?= not_empty($page['title'], '') ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">

        <!-- Le styles -->
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
                    <a class="brand" href="#">BMF</a>
                    <div class="nav-collapse">
                        <ul class="nav">
                            <li class="active"><a href="/">Blog</a></li>
                            <li><a href="/page/about">About</a></li>
                            <li><a href="/page/contact">Contact</a></li>
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
            <!-- 
            <div class="hero-unit">
                <h1>Hello, world!</h1>
                <p>This is a template for a simple marketing or informational website. It includes a large callout called the hero unit and three supporting pieces of content. Use it as a starting point to create something more unique.</p>
                <p><a class="btn btn-primary btn-large">Learn more &raquo;</a></p>
            </div>

            <div class="row">
                <div class="span4">
                    <h2>Heading</h2>
                    <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
                    <p><a class="btn" href="#">View details &raquo;</a></p>
                </div>
                <div class="span4">
                    <h2>Heading</h2>
                    <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
                    <p><a class="btn" href="#">View details &raquo;</a></p>
                </div>
                <div class="span4">
                    <h2>Heading</h2>
                    <p>Donec sed odio dui. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Vestibulum id ligula porta felis euismod semper. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus.</p>
                    <p><a class="btn" href="#">View details &raquo;</a></p>
                </div>
            </div>
-->
            <hr>

            <footer>
                <p>&copy; <a href="http://aomega.ru">AOmega.ru</a> 2012</p>
            </footer>

        </div>

        <script src="<?= site_url('static/js/bootstrap.min.js') ?>"></script>
        <script>hljs.initHighlightingOnLoad();</script>
    </body>
</html>
