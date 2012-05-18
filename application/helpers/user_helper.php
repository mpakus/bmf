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

function user_can_rule(){
    $user = CI()->user->profile();
    if( empty($user['id']) OR ($user['banned'] != 0) ) {
        show_error( 'Увы и ах, у вас нет прав', 500 );
//        redirect( 'user/login' );    
    }
}

function user_is( $role ){
    if( !user_signed_in() ) return FALSE;
    $user = current_user();
    return ( strtolower($user['role']) == strtolower($role) ) ? TRUE : FALSE;
}