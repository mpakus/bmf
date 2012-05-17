<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 *  Хэлпер адресной строки url (uri)
 *
 * @version 1.0
 * @author Ibragimov "MpaK" Renat <renat@bestweb.ru>
 * @copyright Copyright (c) 2009-2010, BESTWEB ltd. www.BESTWEB.ru
 */

/**
* Отдает текущий путь с заменой экшена
*
* @param    string  $action действие в текущем адресе
* @param    bool    $site_url это надо обработать site_url()
* @param    bool    $rewrite надо ли перезаписать
* @return   string
*/
function path( $action='', $site_url=FALSE, $rewrite=TRUE ){
    $params = path_page( $action, $rewrite );

    if( $site_url ){
        $path = site_url( current_url().'/'.$params );
    }else{
        $path = current_url().'/'.$params;
    }
    return $path;
}

/**
* Клеим и заменям параметры страницы
*
* @param    string  $action действие в текущем адресе
* @param    bool    $rewrite надо ли перезаписать
* @return   string
*/
function path_page( $action='', $rewrite=TRUE ){
    $CI =& get_instance();

    $params     = array();
    $section    = array_shift( $CI->page_mod->path() );
    if( $rewrite ) $section['params'] = '';

    if( is_string($action) ){
        if( strstr($action,'.') !== FALSE ){
            $s_a = $action;
            $action = $CI->uri->parse( $action );
        }else{
            $section['params']['action'] = $action;
        }
    }

    if ( is_array($action) ){
        foreach( $action as $name => $value ){
            if( $name   == '?')     $name = rparam($value);
            if( $value  == '?')     $value = param($name);
            $section['params'][$name] = $value;
        }
    }

    if( !empty($section['params']) ){
        foreach( $section['params'] as $name => $value ){
            if( $name   == '?') $name = rparam($value);

            if( $value  == '?')     $value = param($name);
            $params[] = $name.'.'.$value;
        }
    }
    return implode('.', $params);
}

/**
* Отдаёт текущий путь но уже после site_url
*
* @param    string  $action действие в текущем адресе
* @param    bool    $rewrite надо ли перезаписать
* @return   string
*/
function site_path( $action='', $rewrite=TRUE ){
    return path($action, TRUE, $rewrite );
}

/**
* Редирект по пути со сменой действия в модуле
*
* @param    string  $action действией
* @param    bool    $rewrite надо ли перезаписать
* @return   string
*/
function redirect_path( $action = '', $rewrite=TRUE ){
    redirect( path($action, FALSE, $rewrite) );
}

/**
 * Кодирует нашу обычную строку в base64
 *
 * @param  string $str  строка для кодирования
 * @return string
 */
function base64_url_encode($str='') {
    if( empty($str) ) return $str;
    return strtr(base64_encode($str), '+/=', '-_:');
}

/**
 * Декодирует строку из base64 в обычную
 *
 * @param  string $str  строка для декодирования
 * @return string
 */
function base64_url_decode($str='') {
    if( empty($str) ) return $str;
    if( preg_match('/[А-Яа-я]/', $str) ) return $str;   // оно уже и так UTF-8
    return base64_decode(strtr($str, '-_:', '+/='));
}
