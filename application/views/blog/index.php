<?php
//dump( $posts );
if( empty($posts) ){
    echo '<h2>В блог еще никто не написал</h2>';
    return;
}
?>

<div class="row">
    <div class="span12">
        <? foreach( $posts as $post ){ ?>

            <div id="post-<?=$post['id']?>">
                <h1><a href="<?=post_link($post)?>"><?= $post['title'] ?></a></h1>
                <?= post_control($post) ?>
                <?= $post['cut'] ?>
                <a href="<?= site_url('blog/show/'.$post['id']) ?>" class="btn btn-info">Прочитать</a>
                <hr/>
            </div>
        <? } ?>
    </div>
</div>

<div id="pagingbar">
    <a href="#" class="up">Наверх</a>
    <div id="pagination"><?= $pagination ?></div>
</div>
