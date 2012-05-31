<?php

    if( empty($post) ){
        echo 'Странно, но мы не нашли такого топика на нашем сайте';
        return;
    }
    
    echo $post['full'];
?>

<h3>Комментарии к статья:</h3>
<div class="navline"></div>
<?= find_comments( $comments, 0 ) ?>

<h3 style="margin-top:33px;">Добавить комментарий:</h3>
<div class="navline"></div>
<?= comment_form( $post['id'] ) ?>