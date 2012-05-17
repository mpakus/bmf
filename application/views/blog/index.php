
<?php
//dump( $posts );
if( empty($posts) ){
    echo '<div class="post"><h3>В блог еще никто не написал</h3></div>';
    return;
}

foreach( $posts as $post ){
    post_do();
    if( ($post['type'] == blog_typE('news')) OR ($post['type'] == blog_type('review')) ){
    ?>
    <p>
        <?= $post['short'] ?> <a href="<?= post_link( $post ) ?>">Далее</a>
    </p>
    <?
    }elseif( $post['type'] == blog_type('video') ){
    ?>
    <p>
        <a href="<?= post_link( $post ) ?>"><?= video_preview( $post ) ?></a>
    </p>
    <?
    }elseif( $post['type'] == blog_type('photo') ){
    ?>
    <p>
        <?= $post['short'] ?>
    </p>
    <p>
        <a href="<?= post_link( $post ) ?>"><img src="/files/posts/mini/<?= $post['preview'] ?>" alt="<?= form_prep($post['title']) ?>" /></a>
    </p>
    <?
    }
    post_end( $post );
}
?>

<div id="pagingbar">
    <a href="#" class="up">Наверх</a>
    <div id="pagination"><?= $pagination ?></div>
</div>
