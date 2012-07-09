<?php if($redirect) { ?>

        <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
        <html>
        <head>
        <script type="text/javascript">
        <!--
        location.replace("<?=site_url('livejournal/index')?>"); 
        //-->
        </script>
        <noscript>
        <meta http-equiv="refresh" content="0; url=<?=site_url('livejournal/index')?>"> <!-- if JS is disabled (0.0000001% probability but who knows!) -->
        </noscript>
        </head>
        <body>
        </body>
        </html>

<?php } else { ?>
        <h1>LJ posts import</h1>
                <div class="row">

        <?php if(!($lj_authorized)) { ?>

                <form method="POST" action="<?=site_url('livejournal/index')?>">

                        <div class="error"><?php echo validation_errors(); ?></div>

                        <table class="fields">
                                <tr><td>Логин:</td><td><input name="login" type="text" class="string size90p" /></td></tr>
                                <tr><td>Пароль:</td><td><input type="password" name="password" class="string size90p"/></td></tr>
                                <td><td colspan="2"><input type="submit" value="Go!" class="button" /></td></tr>
                        </table>

                        </form>
                </div>

        <?php } else { ?>

                <?php echo $import_complete; ?>

<?php } 

}

?>
