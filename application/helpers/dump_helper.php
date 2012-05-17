<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Помощник для отладки
 *
 * @version 1.0
 * @author Ibragimov "MpaK" Renat <renat@bestweb.ru>
 * @copyright Copyright (c) 2009-2010, BESTWEB ltd. www.BESTWEB.ru
 */
function TextDump( &$Var, $Level=0 ) {
		$out = '';
        if(is_array($Var)) $Type="Array[".count($Var)."]";
        else if(is_object($Var)) $Type="Object";
        else $Type="";
        if($Type) {
                $out .= "$Type\n";
                for(Reset($Var),$Level++; list($k,$v)=each($Var);) {
                        if(is_array($v) && $k==="GLOBALS") continue;
                        for($i=0; $i<$Level*3; $i++) $out .= " ";
                        $out .= "<b>".HtmlSpecialChars($k)."</b> => " . TextDump($v,$Level);
                }
        } else {
        	$out .= '"' . HtmlSpecialChars($Var) . '"'."\n";
        }
        return $out;
}

function Dump( &$Var, $need_return = FALSE ) {
	$out = '';
	if((is_array($Var)||is_object($Var)) && count($Var)){
        $out .= "<pre>\n" . TextDump( $Var ) . "</pre>\n";
 	}else{
        $out .= "<tt>" . TextDump($Var) . "</tt>\n";
  	}

  	if($need_return) return $out;

  	echo $out;
}

function Bug( $Var, $msg = '' ) {
    $site = CI()->config->item('site');
    if( !$site['debug'] ) return;
    
	if( !empty($msg) ) CI()->template_lib->append( 'debug', "<br /><hr><h2>$msg</h2><hr>" );
	CI()->template_lib->append( 'debug', Dump( $Var, TRUE ) );
}