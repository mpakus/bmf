<?php


function user_signed_in(){
    $user = CI()->user->profile();    
    return ( !empty($user['id']) ) ? TRUE : FALSE;
}

function current_user(){
    return CI()->user->profile();
}

function is_current_user( $user ){
    $current = current_user();
    return ( $current['id'] == $user['id'] ) ? TRUE : FALSE;
}

function user_avatar( $user, $class='normal' ){
    if( empty($user['avatar']) ) return;
    ?>
    <a href="<?= site_url('user/profile/'.$user['login']) ?>"><img src="<?= site_url( 'files/avatars/'.$user['avatar'] ) ?>" class="<?= $class ?>" alt="Профиль <?= $user['login'] ?>" /></a>
    <?
}

function user_can_rule( $post=array() ){
    // $user = CI()->user->profile();
    // if( empty($user['id']) OR ($user['banned'] != 0) ) show_error( 'Увы и ах, у вас нет прав', 500 );    

    if( !user_signed_in() ) show_error('Необходима авторизация',401);

    $user = current_user();
    if( $user['banned'] ) show_error('Вы забанены',403);
    if( !empty($post) )
        if( !user_is('admin') OR ($user['id'] != $post['user_id']) ) show_error('Нет прав и доступа',403);
}

function user_is( $role ){
    if( !user_signed_in() ) return FALSE;
    $user = current_user();
    return ( strtolower($user['role']) == strtolower($role) ) ? TRUE : FALSE;
}