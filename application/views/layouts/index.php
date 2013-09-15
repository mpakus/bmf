<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta charset="utf-8">
        <title><?= not_empty($post['title'], '') ?> Gems from Hell</title>
        <meta name="title" content="<?= form_prep(not_empty($post['title'], '')) ?> Gems from Hell" />
        <meta name="description" content="<?= form_prep(not_empty($post['description'], 'Gems from Hell')) ?>" />

        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta property="og:title" content="<?= form_prep(not_empty($post['title'], '')) ?> Gems from Hell" />
        <meta property="og:description" content="<?= form_prep(not_empty($post['description'], 'Gems from Hell')) ?>" />
        <meta property="og:url" content="http://gemsfromhell.com<?= current_url() ?>" />
        <meta name='yandex-verification' content='654cd8a0f7b91402' />

        <?php
            Asset::add_js(array(
                site_url('static/js/jquery-1.10.0.min.js'),
                site_url('static/jqueryui/jquery-ui-1.10.3.custom.min.js'),
                site_url('static/js/bmf.js'),
                site_url('static/js/jquery.pnotify.min.js'),
                site_url('static/bootstrap/js/bootstrap.min.js'),
                'http://yandex.st/highlightjs/6.2/highlight.min.js',
            ));
            // Asset::$cache_js = TRUE; # cache js in production
            echo Asset::out_js();
        ?>
        <!--[if lt IE 9]>
          <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
        <link rel="shortcut icon" href="/favicon.ico">
        <?php
            Asset:add_css(array(
                site_url('static/bootstrap/css/bootstrap.min.css'),
                'http://fonts.googleapis.com/css?family=Denk+One',
                'http://fonts.googleapis.com/css?family=PT+Sans+Narrow',
                site_url('static/css/base.css'),
                site_url('static/themelight/gemsfromhell.css'),
                site_url('static/css/jquery.pnotify.default.css'),
                site_url('static/css/flash.css'),
                site_url('static/jqueryui/blitzer/jquery-ui-1.10.3.custom.min.css'),
                site_url('static/highlightjs/monokai.css'),
            ));
            // Asset::$cache_css = TRUE; # cache css in production
            echo Asset::out_css();
        ?>
    </head>

    <body>
        <?= get_flash(); ?>

        <div class="navbar navbar-fixed-top">
            <div class="navbar-inner">
                <div class="container">
                    <a class="brand" href="/">Gems from Hell</a>
                    <ul class="nav">
                        <li><a href="/">Главная</a></li>
                        <li><a href="/page/about">О блоге</a></li>
                        <li><a href="/blog/feed.rss" class="rss"><span class="icon icon-fire"></span>RSS</a></li>
                    </ul>
                    
                    <ul class="nav pull-right" id="login">
                        <? if (user_signed_in()) {
                            $user = current_user();
                            ?>
                            <? if (user_is('admin')) echo '<li>'.anchor('post/form', 'Создать топик').'</li>'; ?>
                            <li class="nav-avatar"><?= user_avatar($user, 'mini') ?></li><li><?= anchor('user/profile/' . $user['login'], $user['login']) ?></li><li><?= anchor('user/logout', 'Выйти') ?></li>
                        <? }else { ?>
                            <li><a href="<?= site_url('user/login') ?>"><span class="icon icon-user"></span> Войти</a></li><? /*| <?= anchor('user/register', 'Регистрация') ?> */ ?>
                        <? } ?> 
                    </ul>

                </div>
            </div>
        </div>

        <div class="container">
            <?= $content ?>            
        </div>

        <footer class="footer navbar navbar-fixed-bottom">
            <div class="container">
                Gems from Hell &copy; <a href="http://mrak7.com">DESIGN4UNDERGROUND</a> and <a href="http://aomega.ru">AOmega.ru</a> | Powered by <a href="http://github.com/mpakus/bmf">Open Source Blog System</a>
            </div>
        </footer>

        <script type="text/javascript">
        $(function() {
            hljs.initHighlightingOnLoad();
            $('a.delete').click( BMF.Post.destroy );
            // $('a.delcomment').click( BMF.Comment.destroy );
            // $('a.reply').click( BMF.Comment.reply );
        });
        </script>

        <noindex>
            <script type="text/javascript">
            (function (d, w, c) {
                (w[c] = w[c] || []).push(function() {
                    try {
                        w.yaCounter21295213 = new Ya.Metrika({id:21295213,
                                clickmap:true,
                                accurateTrackBounce:true});
                    } catch(e) { }
                });

                var n = d.getElementsByTagName("script")[0],
                    s = d.createElement("script"),
                    f = function () { n.parentNode.insertBefore(s, n); };
                s.type = "text/javascript";
                s.async = true;
                s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js";

                if (w.opera == "[object Opera]") {
                    d.addEventListener("DOMContentLoaded", f, false);
                } else { f(); }
            })(document, window, "yandex_metrika_callbacks");
            </script>
            <noscript><div><img src="//mc.yandex.ru/watch/21295213" style="position:absolute; left:-9999px;" alt="" /></div></noscript>

            <script type="text/javascript">
            var disqus_shortname = 'gemsfromhell';
            (function () {
            var s = document.createElement('script'); s.async = true;
            s.type = 'text/javascript';
            s.src = 'http://' + disqus_shortname + '.disqus.com/count.js';
            (document.getElementsByTagName('HEAD')[0] || document.getElementsByTagName('BODY')[0]).appendChild(s);
            }());
            </script>
        </noindex>
    </body>
</html>
