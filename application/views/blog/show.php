<?php
if( empty($post) ){
    echo 'Странно, но мы не нашли такого топика на нашем сайте';
    return;
}
?>
<div class="row">
    <div class="span12">
        <div id="post-<?=$post['id']?>" class="post">
            <?= $this->template->render( 'blog/_author', array('post'=>$post) ) ?>
            <h1><?= $post['title'] ?> <?= post_control($post) ?></h1>            
            <?= $post['full'] ?>
            <div class="tags"><span class="icon icon-tags"></span>Тэги: <?= tags( $post) ?></div>
        </div>
        <hr/>
        <div class="share">
          <noindex>
<!-- AddThis Button BEGIN -->
<div class="addthis_toolbox addthis_default_style addthis_32x32_style">
<a class="addthis_button_facebook"></a>
<a class="addthis_button_vk"></a>
<a class="addthis_button_twitter"></a>
<a class="addthis_button_livejournal"></a>
<a class="addthis_button_google_plusone_share"></a>
<a class="addthis_button_blogger"></a>
<a class="addthis_button_evernote"></a>
<a class="addthis_button_delicious"></a>
<a class="addthis_button_digg"></a>
<a class="addthis_button_moikrug"></a>
<a class="addthis_button_myspace"></a>
<a class="addthis_button_odnoklassniki_ru"></a>
<a class="addthis_button_compact"></a><a class="addthis_counter addthis_bubble_style"></a>
</div>
<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=undefined"></script>
<!-- AddThis Button END -->
          </noindex>
        </div>
        <hr/>
        <div id="disqus_thread"></div>
        <script type="text/javascript">
            var disqus_config = function () { 
              this.language = "ru";
            };          
            var disqus_shortname  = 'gemsfromhell';
            var disqus_identifier = 'gemsfromhell<?= $post['id']?>';
            (function() {
                var disqus_config = function () { 
                  this.language = "ru";
                };                
                var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
                dsq.src = '//' + disqus_shortname + '.disqus.com/embed.js';
                (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
            })();
        </script>
        <noindex>
          <noscript>Please enable JavaScript to view the <a href="http://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>
          <a href="http://disqus.com" class="dsq-brlink">comments powered by <span class="logo-disqus">Disqus</span></a>
        </noindex>
    </div>
</div>

<?/*
<h3>Комментарии к статья:</h3>
<div class="navline"></div>
<?= find_comments( $comments, 0 ) ?>

<h3 style="margin-top:33px;">Добавить комментарий:</h3>
<div class="navline"></div>
<?= comment_form( $post['id'] ) ?>
*/?>