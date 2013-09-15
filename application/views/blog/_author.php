<div class="author">
    <?= avatar( $post ) ?><br/>
    <strong><a href="<?= site_url('user/profile/'.$post['login']) ?>"><?= $post['login'] ?></a></strong><br/>
    <?= human_date( $post['added_at'] ) ?><br/>
    <span class="icon icon-comment"></span> <a href="<?= post_link($post)?>#disqus_thread"></a>
</div>
