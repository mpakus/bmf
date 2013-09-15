<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


/**
 *  Библиотека ассетов для DC.CMS
 *
 * @version 1.0
 * @author Ibragimov "MpaK" Renat <info@mrak7.com>
 * @copyright Copyright (c) 2010-2013, <http://aomega.ru>
 */
class Asset{
    static protected
        $env            =   '',         // среда окружения
        $common         =   array(),    // основные наши настройки из конфига core
        $js_container   =   array(),    // наш контейнер для JS файлов
        $css_container  =   array()     // наш контейнер для CSS файлов
    ;

    static public
        $cache_css      =   FALSE,      // вкл/выкл сливания-кэширования CSS
        $cache_js       =   FALSE       // вкл/выкл сливания-кэширования JS
    ;

    public function __construct(){
        self::$common   = CI()->config->item('common');
    }

    /**
     *  Аккумулирует все JS ссылки для последующего вывода
     *
     * @param  string $file
     * @param  string $module
     * @return bool
     */
    static public function add_js( $file, $module='', $insert_before = FALSE ) {
        if( is_string($file) ){
            return self::add_one_js( $file, $module, $insert_before );
        }elseif( is_array($file) ){
            if( $insert_before ) $file = array_reverse( $file );
            foreach( $file as $f ){ // если массив то пути сразу надо указывать или один на всех будет
                $res = self::add_one_js( $f, $module, $insert_before );
            }
        }
        return TRUE;
    }

    /**
     *  Вставляет JSки раньше всех других
     *
     * @param  string $file
     * @param  string $module
     * @return bool
     */
    static public function insert_before_js( $file, $module='' ) {
        return self::add_js( $file, $module, TRUE );
    }

    /**
     * Добавляет в накопитель одиночный JS файл
     *
     * @param   string $file    URI к файлу
     * @param   string $module  модуль и его папка если надо
     * @return  bool
     */
    static protected function add_one_js( $file, $module='', $insert_before = FALSE ){
        if( !empty($module) ) $file = self::$common['modules'].$module.'/js/'.$file.'.js';
        if( in_array($file, self::$js_container) ) return FALSE;
        if( $insert_before )
            array_unshift( self::$js_container, $file );
        else
            self::$js_container[] = $file;

        return TRUE;
    }

    /**
     * Выводит накопленные ссылки на JS файлы
     *
     * @return string
     */
    static public function out_js(){
        $out    = '';
        if( !empty(self::$js_container) ){
            // если мы в продакшене
            if( self::$cache_js ){
                $file_name = md5(implode('', array_values( self::$js_container ))).'.js';
                $cache_file = self::$common['front_cache_path'].$file_name;
                $cache_url  = self::$common['front_cache_url'].$file_name;

                if( file_exists($cache_file) ){
                    self::$js_container = array();
                    return self::content_tag( 'script', 'src="'.$cache_url.'" language="JavaScript" type="text/javascript"' );
                }elseif( is_writable(self::$common['front_cache_path']) ){
                    $buffer = '';
                    foreach( self::$js_container as $file ){
                        $rp = realpath(FCPATH.$file);
                        if( file_exists($rp) ){
                            $buffer .= "\n/*--- $file --- */\n".file_get_contents( $rp );
                        }else{
                            @$buffer .= "\n/*--- $file --- */\n".file_get_contents( $file );                            
                        }
                    }
                    file_put_contents($cache_file, $buffer);
                    unset($buffer);
                    $out = self::content_tag( 'script', 'src="'.$cache_url.'" language="JavaScript" type="text/javascript"' );
                }
            }else{
                foreach( self::$js_container as $file ){
                    $out .= self::content_tag( 'script', 'src="'.$file.'" language="JavaScript" type="text/javascript"' );
                }
            }
            self::$js_container = array();
        };
        return $out;
    }

//--------------- CSS

    /**
     *  Аккумулирует все CSS ссылки для последующего вывода
     *
     * @param  string $file
     * @param  string $module
     * @return bool
     */
    static public function add_css( $file, $module='', $insert_before=FALSE ) {
        if( is_string($file) ){
            return self::add_one_css( $file, $module, $insert_before );
        }elseif( is_array($file) ){
            if( $insert_before ) $file = array_reverse( $file );
            foreach( $file as $f ){ // если массив то пути сразу надо указывать или один на всех будет
                $res = self::add_one_css( $f, $module, $insert_before );
            }
        }
        return TRUE;
    }

    /**
     *  Вставляет CSSки раньше всех других
     *
     * @param  string $file
     * @param  string $module
     * @return bool
     */
    static public function insert_before_css( $file, $module='' ) {
        return self::add_css( $file, $module, TRUE );
    }
    /**
     * Добавляет в накопитель одиночный CSS файл
     *
     * @param   string $file    URI к файлу
     * @param   string $module  модуль и его папка если надо
     * @return  bool
     */
    static protected function add_one_css( $file, $module='', $insert_before=FALSE ){
        if( !empty($module) ) $file = self::$common['modules'].$module.'/css/'.$file.'.css';
        if( in_array($file, self::$css_container) ) return FALSE;
        if( $insert_before )
            array_unshift( self::$css_container, $file );
        else
            self::$css_container[] = $file;
        return TRUE;
    }

    /**
     * Выводит накопленные ссылки на JS файлы
     *
     * @return string
     */
    static public function out_css(){
        $out    = '';
        if( !empty(self::$css_container) ){
            // если мы в продакшене
            if( self::$cache_css ){
                $file_name = md5(implode('', array_values( self::$css_container ))).'.css';
                $cache_file = self::$common['front_cache_path'].$file_name;
                $cache_url  = self::$common['front_cache_url'].$file_name;

                if( file_exists($cache_file) ){
                    self::$css_container = array();
                    return self::content_tag( 'link', 'rel="stylesheet" href="'.$cache_url.'" type="text/css" media="all"' );
                }elseif( is_writable(self::$common['front_cache_path']) ){
                    $buffer = '';
                    foreach( self::$css_container as $file ){
                        $rp = realpath(FCPATH.$file);
                        if( file_exists($rp) ) {
                            $buffer .= "\n/*--- $file --- */\n".file_get_contents( $rp );
                        }else{
                            @$buffer .= "\n/*--- $file --- */\n".file_get_contents( $file );                            
                        }
                    }
                    file_put_contents($cache_file, $buffer);
                    unset($buffer);
                    $out = self::content_tag_one( 'link', 'rel="stylesheet" href="'.$cache_url.'" type="text/css" media="all"' );
                }else{
                    echo "fuck";
                }
            }else{
                foreach( self::$css_container as $file ){
                    $out .= self::content_tag_one( 'link', 'rel="stylesheet" href="'.$file.'" type="text/css" media="all"' );
                }
            }
            self::$css_container = array();
        };
        return $out;
    }

    
//----------------- Constructors for Content Tags

    /**
     * Формирует тэг
     *
     * @param  string $tag          тэг
     * @param  string $options      его атрибуты
     * @return string
     */
    static public function content_tag( $tag, $options ){
        return "<{$tag} $options></{$tag}>\n";
    }

    /**
     * Формирует одиночный тэг
     *
     * @param  string $tag          тэг
     * @param  string $options      его атрибуты
     * @return string
     */
    static public function content_tag_one( $tag, $options ){
        return "<{$tag} $options/>\n";
    }


}