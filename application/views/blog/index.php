<?php
if( empty($posts) ){
    echo '<div class="post"><h2>В блог еще никто не написал</h2></div>';
    return;
}
?>

<div class="row">
    <div class="span12">
        <? foreach( $posts as $post ){ ?>
            <div id="post-<?= $post['id'] ?>" class="post">
                <?= $this->template->render( 'blog/_author', array('post'=>$post) ) ?>
                <h2><a href="<?= post_link($post)?>"><?= form_prep($post['title']) ?></a> <?= post_control($post) ?></h2>                
                <?= $post['cut'] ?>
            </div>
            <hr/>
        <? } ?>
    </div>
</div>

<!--a href="#" class="up">Наверх</a-->
<?php if( !empty($pagination) ) {?><div id="pagination"><?= $pagination ?></div><?php } ?>
