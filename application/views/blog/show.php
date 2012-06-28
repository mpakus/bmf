<?php
if( empty($post) ){
    echo 'Странно, но мы не нашли такого топика на нашем сайте';
    return;
}
?>
<div class="row">
    <div class="span12">
<<<<<<< HEAD
        <div id="post-<?= $post['id'] ?>">
=======
        <div id="post-<?=$post['id']?>">
>>>>>>> 2dab470ac8304b665edd02445d50a3d31623f1f8
            <h1><?= $post['title'] ?></h1>
            <?= post_control($post) ?>
            <?= $post['full'] ?>
        </div>
    </div>
</div>

<h3>Комментарии к статья:</h3>
<div class="navline"></div>
<?= find_comments( $comments, 0 ) ?>

<h3 style="margin-top:33px;">Добавить комментарий:</h3>
<div class="navline"></div>
<?= comment_form( $post['id'] ) ?>