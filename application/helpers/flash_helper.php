<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 *		flash_pi.php
 *		v 0.95
 * 	A simple implementation of a session persistent messaging helper
 *		(c) 2007 Daniel Fone - daniel.fone@gmail.com
 *
 *
 *		---- BASIC USAGE EXAMPLE ----
 *
 *		In controller:
 *			# Create a new thingy
 *			function create()
 *			{
 *				$this->thingy_model->create($_POST);
 *
 *				set_flash('Created a new thingy.');
 *
 *				redirect('/thingy/index/');
 *			}
 *
 *		In view file:
 *			<?=get_flash()?>
 *
 *		In stylesheet:
 *			#flash {
 *				position:	relative;
 *				float:		right;
 *				border:		1px solid #FF6600;
 *				background:	#FFCC00;
 *				text-align:	center;
 *				width:		20%;
 *				padding:		5px;
 *			}
 *
 *		---- REQUIREMENTS ----
 *
 *		This plugin needs:
 *			- The session class (must be loaded before any view)
 *			- The script.aculo.us library (optional but default -- you'll need
 *			  to override `on_timeout` and `toggle` to disable)
 *
 *
 *		----- REFERENCE ----
 *
 *		bool set_flash ( string message, array options )
 *			@params message: 	The message to be displayed.
 *			@params options: 	An associative array containing a list of
 *									optional settings. See below for details.
 *			@returns:			TRUE on success, FALSE on failure.
 *
 *		string get_flash ( array options )
 *			@params options:	An associative array containing a list of
 *									optional settings. See below for details.
 *									NB. Any options set in the get_flash will
 *									override any that were set in the set_flash.
 *			@returns:			HTML/Javascript to display message
 *
 *
 *		----- OPTIONAL PARAMETERS ----
 *
 *		-> div_id => 'flash'
 *				ID of containing div (needs to correspond to the stylesheet, obviously)
 *
 *		-> duration	=> '2000'
 *				Length of time (ms) to display message before fading if auto_fade
 *				is set to true.
 *
 *		-> auto_fade => 'true'
 *				Automatically fade message after `duration`.
 *
 *		->	p_style => 'margin:0px';
 *				Style tag for the text of the message itself.
 *
 *		-> image => site_url('/images/close.gif')
 *				Path of image to display for user to click to dismiss message box.
 *				e.g. a red cross or something similar. Image is highlighted on mouseOver
 *				and cursor is set to pointer.
 *
 *		-> toggle => 'new Effect.toggle("$divID", "appear", { duration: 0.5 })'
 *				Javascript to run when close img is clicked. If you don't wish to use the
 *				default script.aculo.us effects, modify this.
 *
 *		-> on_timeout => 'new Effect.Fade("$div_id")'
 *				Javascript to run after `duration` ms have passed.
 *
 *		-> Any other variable specified under the BEGIN DEFAULT BLOCK in the code below.
**/


#
# Set the flash message, persistent over a session
#
function set_flash($message, $opts=array()){
	$CI =& get_instance();
	if ( ! is_object($CI->session) 	) show_error('Session class must be loaded!');
	if ( ! is_string($message)			) show_error('Error: First parameter must be a string!');

	 return $CI->session->set_flashdata(
		array(
			'flash_message' 	=> $message,
			'flash_opts'		=> serialize($opts),
		)
	);
}

function set_flash_error($message, $opts=array()){
    $opts['flash_type'] = 'error';
	set_flash($message, $opts );
}
function set_flash_ok($message){
    $opts['flash_type'] = 'ok';
	set_flash($message, $opts );
}

function get_flash($opts=''){
	$CI =& get_instance();
	if ( ! is_object($CI->session) ) show_error('Session class must be loaded!');
	$CI->load->helper('url');

	# Check for any messages
	$message		= $CI->session->flashdata('flash_message');
	$opts           = $CI->session->flashdata('flash_opts');
    if( !empty($opts) ) $opts = unserialize( $opts );

	if ( $message == '' ) return '';

    $type   = '';
    $title = 'OK';
    $icon  = "pnotify_notice_icon: 'ui-icon ui-icon-mail-closed',";
    
    if( empty($opts['title']) ){
        switch ($opts['flash_type']) {
            case 'ok':
                break;
            case 'error':
                $title  = 'Ошибка';
                $type   = 'error';
                $icon   = "pnotify_error_icon: 'ui-icon ui-icon-alert',";
                break;
            case 'alert':
                $title = 'Внимание';
                break;
            default:
                $title = 'Информация';
                break;
        }
    }else{
        $title  = $opts['title'];
    }

    return '
    <script type="text/javascript" language="JavaScript">
    $(document).ready( function(){
        $.pnotify({
            pnotify_title: "' . $title . '",
            pnotify_text: "' . $message . '",
            pnotify_type: "' . $type . '",
            ' . $icon . '
            pnotify_animate_speed: "fast",
            pnotify_animation: {effect_in: "fade", effect_out: "drop"}
        });
    });
    </script>
    ';

}
