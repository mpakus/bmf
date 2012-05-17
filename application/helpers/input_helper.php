<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Хэлпер для работы с данными приходящими от пользователя
 * администрирования наполнения
 * 
 * @version 1.1
 * @author Ibragimov "MpaK" Renat <renat@pureandco.com>
 */

/**
 * Возвращает параметры присланные через адресную строку или $_POST
 *
 * @param  string $name
 * @param bool	$clean		флаг очистки от XSS
 * @param bool	$clean_html	флаг очистки от html кода
 * @param bool	$rparam     флаг реверсивного параметра 100.html это html=>100
 * @return mixed
 */
function param( $name, $xss = TRUE, $clean_html = TRUE, $rparam = FALSE  ){

	list($name, $type) = explode('|', $name);
	
	if( is_array($name) ) return CI()->params( $name, $xss, $clean_html, $rparam );

	$value = '';
	$value = CI()->input->post( $name, $xss );
	if( empty($value) AND isset($_GET[$name]) ) $value = CI()->security->xss_clean($_GET[$name]);

	// если нужно экранировать html
	if( !empty($value) AND $clean_html AND is_string($value) ) $value = htmlspecialchars( $value, ENT_QUOTES, 'UTF-8' );
	if( empty($value) ) $value = '';
	
	if( isset($type) AND ($type == 'int') )		$value = intval($value);
	if( isset($type) AND ($type == 'float') )	$value = floatval($value);
	if( isset($type) AND ($value === 0) ) $value = NULL;

	return $value;
}

/**
* Взять параметры c помощью функции param для целого массива
* по умолчанию фильтруется сразу же XSS и html теги
*
* @param array	$params		массив названий нужных параметров
* @param bool	$xss		флаг очистки от XSS
* @param bool	$clean_html	флаг очистки от html кода
* @param bool	$rparam     флаг реверсивного параметра 100.html это html=>100
* @return array
*/
function params( $params='', $xss = TRUE, $clean_html = TRUE, $rparam = FALSE ){
	$result = array();
	if( !is_array($params) ) return param($name, $xss, $clean_html, $rparam );

	foreach($params as $name){
		list($name_set, $type)	= explode('|', $name);
		$result[$name_set]		= param($name, $xss, $clean_html, $rparam);
	}
	return $result;
}

/**
* Проверяет откуда нас вызвали из ajax или нет
*
* @param	void
* @return	void
*/
function is_ajax(){
	$xhttp = isset($_SERVER['HTTP_X_REQUESTED_WITH']) ? $_SERVER['HTTP_X_REQUESTED_WITH'] : '';
	return ( $xhttp == 'XMLHttpRequest') ? TRUE : FALSE;
}


/**
 * Разобьем CSV строку
 *
 * @param  string $line
 * @param  string $glue
 * @return array
 */
function explode_csv( $line, $glue = ';' ){
    $row = explode( $glue, trim($line) );
    for( $i=0; $i<count($row); $i++ ){
        $row[$i] = trim( $row[$i], '"' );
    }
    return $row;
}

function debug( $var ){
  ob_start();
  var_dump($var);
  $dump = ob_get_clean();
  log_message( 'debug', $dump );
}