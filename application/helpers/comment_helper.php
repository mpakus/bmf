<?php

// comments
    
function find_comments( $comments, $parent_id, $lvl=0 ){
    for( $i=0; $i<count($comments); $i++ ){
        $comment = $comments[$i];
        if($comment['parent_id'] == $parent_id){
            show_comment( $comment, $lvl );
            find_comments( $comments, $comment['id'], $lvl+1 );
            //    echo '</div>'; //the comment div stays open until there are no more children
        }
    }
}

function show_comment( $comment, $lvl=0 ){
    $style = 'style="margin-left: ' . $lvl*20 . 'px"';
    ?>
    <div class="comment" id="comment-<?= $comment['id'] ?>" <?= $style ?>>
        <a name="com<?= $comment['id'] ?>"></a>
        <?= avatar( $comment ) ?>
        <div class="author">Написал: <a href="<?= site_url('user/profile/'.$comment['login']) ?>"><?= $comment['login'] ?></a> <?= human_read_date($comment['added_at']) ?></div>
        <p><?= ( $comment['deleted']==1 ) ? '[комментарий был удалён администратором]' : $comment['text'] ?></p>
        <? if( user_is('admin') ) { ?><a href="#" class="delcomment ctrl" id="destroy-<?= $comment['id'] ?>"><span class="ui-icon ui-icon-trash"></span> удал.</a><? } ?>        
        <a href="#comment_form" id="reply-<?= $comment['id'] ?>" class="reply">ответить</a>
    </div>
    <?
}

function comment_form( $post_id ){
    if( !user_signed_in() ){
        ?>
        <p>
            Извините, комментарии могут оставлять только <a href="<?= site_url('user/register') ?>">зарегистрированные</a> пользователи.<br/>
            Если вы помните свой логин и пароль, то вы можете <a href="<?= site_url('user/login') ?>">войти здесь</a>
        </p>
        <?
        return;
    }
    $user = current_user();
    ?>
    <a name="comment_form"></a>
    <form method="POST" action="<?= site_url('post/comment/'.$post_id) ?>" onSubmit="return AC.comment.on_submit()">
        <input type="hidden" name="post_id" id="post_id" value="<?= form_prep($post_id) ?>"/>
        <input type="hidden" name="parent_id" id="parent_id" value="0" />
        <textarea name="text" id="text" style="width:90%;height:195px"></textarea><br/>
        <input type="submit" value="Написать" class="btn btn-success" />
    </form>
    <?
}

/**
 * Shows last comments in the right Sidebar
 * 
 * @param type $limit
 * @return type 
 */
function last_comments_widget( $limit=10 ){
    CI()->load->model( 'comment' );
    $comments = CI()->comment->order_by('added_at', 'DESC')->find( NULL, $limit );
    if( empty($comments) ) return;
    foreach( $comments as $c ){
        $type = array_search( $c['type'], blog_types() ); // now we have got 'news' key
        $alias = nice_title( $c['title'] );
        $c['post_url'] = site_url( 'blog/'.$type.'/'.$c['post_id'].'/'.$alias.'.html#com'.$c['id'] );
        ?>
        <div>
            <?= anchor( $c['post_url'], $c['title'] ) ?>
            <br/><span class="author">Написал: <a href="<?= site_url('user/profile/'.$c['login']) ?>"><?= $c['login'] ?></a></span>
        </div>
        <?
    }
}