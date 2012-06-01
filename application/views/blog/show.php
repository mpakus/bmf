<?php
if( empty($post) ){
    echo 'Странно, но мы не нашли такого топика на нашем сайте';
    return;
}
?>
<div class="row">
    <div class="span12">
        <h1><?= $post['title'] ?></h1>
        <?= post_control($post) ?>
        <?= $post['full'] ?>
    </div>
</div>

<h3>Комментарии к статья:</h3>
<div class="navline"></div>
<?= find_comments( $comments, 0 ) ?>

<h3 style="margin-top:33px;">Добавить комментарий:</h3>
<div class="navline"></div>
<?= comment_form( $post['id'] ) ?>