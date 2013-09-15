<?php

function tags( $post ){
    $tags = explode( ',', $post['tags'] );
    if( empty($tags) ) return;
    $out = array();
    foreach( $tags as $tag ){
        $tag = trim($tag);
        $out[] = '<a href="'.site_url( 'blog/tag/'.$tag ).'">'.$tag.'</a>';
    }
    return implode( ' ', $out );
}

function avatar( $post ){
    if( empty($post['avatar']) ) return;
    
    return '<img src="/files/avatars/'.$post['avatar'].'" alt="'.$post['login'].'" class="avatar" />';
}


function video_preview( $post ){
    $link = $post['link'];    
    if( empty($link) ) return;
    
    $video = get_video_id( $link );    
    $video = '<img src="http://img.youtube.com/vi/'.$video.'/0.jpg" alt="'.$post['title'].'" class="preview" />';
    return $video;
}

function video_embed( $post ){
    $link = $post['link'];    
    if( empty($link) ){
        echo 'Не указана ссылка на видео';
        return;
    }
    $video = get_video_id( $link );
    $video = '<iframe width="470" height="264" src="http://www.youtube.com/embed/'.$video.'" frameborder="0" allowfullscreen></iframe>';
    return $video;
}

function get_video_id( $link ){
    $vidparser = parse_url( $link );
    parse_str( $vidparser['query'], $query );
    $video = $query['v'];
    return $video;
}

function blog_types(){
    $config = CI()->config->item('blog');
    if( empty($config) ){
        CI()->config->load('blog');
        $config = CI()->config->item('blog');
    }
    return $config['type'];    
}

function blog_type( $name ){
    $types = blog_types();
    return $types[$name];    
}

/**
 * Buttons to edit, delete post
 * 
 * @param type $post
 * @return type 
 */
function post_control( $post ){
    if( !user_signed_in() ) return;
    
    $user = current_user();
    if( $user['banned'] ) return;
    
    $type = array_search( $post['type'], blog_types() ); // now we have got 'news' key
    
    if( user_is('admin') OR ($user['id'] == $post['user_id']) ){
        ?>
        <span class="post-control">
            <a href="<?= site_url( 'post/form/'.$post['id'] ) ?>"  title="Редактировать"><i class="icon-pencil"></i></a>
            <a href="#" class="delete" id="destroy-<?= $post['id'] ?>" title="Удалить"><i class="icon-trash"></i></a>
        </span>
        <?
    
    }
}

function post_form_path( $post, $module_id='' ){
    return site_url( 'post/form/'.$post['id'].'/'.$module_id );    
}
function post_form_save_path( $post, $module_id='' ){
    return site_url( 'post/form/'.$post['id'].'#mod-'.$module_id );    
}

function blog_post_path( $post ){
    return site_url( 'blog/post/'.$post['id'] );
}
function edit_blog_post_path( $post, $module_id ){
    return site_url( 'blog/post/'.$post['id'].'/'.$module_id.'#mod-'.$module_id );
}

function post_link( $post ){
    $type = array_search( $post['type'], blog_types() );
    $alias = form_prep( nice_title( $post['title'] ) );
    return site_url( 'blog/show/'.$post['id'].'-'.$alias.'.html' );
}


function last_reviews_widget( $limit=5 ){
    CI()->load->model( 'post' );
    $posts = CI()->post->find_last_reviews($limit );
    if( empty($posts) ) return;
    foreach( $posts as $post ){
        $image = ( empty($post['preview']) ) ? '' : '<img src="/files/posts/'.$post['preview'].'" alt="'.form_prep($post['title']).'" />';
        ?>
        <div>
            <a href="<?= site_url( post_link($post) ) ?>"><?= $image ?> <?= $post['title'] ?></a>
            <br/><span class="author">Написал: <a href="<?= site_url('user/profile/'.$post['login']) ?>"><?= $post['login'] ?></a></span>
        </div>
        <?
    }
}

/**
 *  he he, just for fun experiment
 * @example:
 * post_do();
 * ... bla=blak content out
 * post_end( $post );
 * decorate content and out
 */

function post_do(){
    ob_start();
}

function post_end( $post ){
    $content = ob_get_contents();
    ob_end_clean();
    ?>
    <div class="post" id="post-<?= $post['id']?>">
        <div class="rating" id="rating-<?= $post['id'] ?>">
            <span><?= $post['rating'] ?></span>
            <a href="#">голосовать</a>
        </div>
        <div class="text">
            <?= avatar( $post ) ?>
            <h2><?= $post['title'] ?></h2>
            <div class="author">Написал: <a href="<?= site_url('user/profile/'.$post['login']) ?>"><?= $post['login'] ?></a></div>
            <?= post_control( $post ) ?>
            <?= $content ?>
            <div class="tags">
                <?= tags( $post ) ?>
            </div>
        </div>
    </div>    
    <?
}