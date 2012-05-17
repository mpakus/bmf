<?php

    if( empty($post) ){
        echo 'Странно, но мы не нашли такого топика на нашем сайте';
        return;
    }

    $config = $this->config->item('blog');
    $type = $config['type'];
    
    post_do();  
    // NEWS && REVIEWS
    if( ($post['type'] == blog_type('news')) OR ($post['type'] == blog_type('review')) ){
        ?>
        <p>
            <?= $post['full'] ?>
        </p>
        <?
    // VIDEO
    }elseif( $post['type'] == blog_type('video') ){
        ?>
        <p>
            <?= video_embed( $post ) ?>
        </p>
        <?
    // PHOTOS
    }elseif( $post['type'] == blog_type('photo') ){
        ?>
        <p><img src="/files/posts/<?= $post['preview'] ?>" alt="<?= form_prep($post['title']) ?>" /></p>
        <p><?= $post['full'] ?></p>
        <?
    }
    post_end( $post );

?>

<h3>Комментарии к статья:</h3>
<div class="navline"></div>
<?= find_comments( $comments, 0 ) ?>

<h3 style="margin-top:33px;">Добавить комментарий:</h3>
<div class="navline"></div>
<?= comment_form( $post['id'] ) ?>