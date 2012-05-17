<?php

/**
 * Form Prep
 *
 * Formats text so that it can be safely placed in a form field in the event it has HTML tags.
 *
 * @access	public
 * @param	string
 * @return	string
 */
if ( ! function_exists('form_prep') ){
	function form_prep($str = '', $field_name = ''){
		static $prepped_fields = array();

		// if the field name is an array we do this recursively
		if (is_array($str)){
			foreach ($str as $key => $val){
				$str[$key] = form_prep($val);
			}
			return $str;
		}

		if ($str === '') return '';

		// we've already prepped a field with this name
		// @todo need to figure out a way to namespace this so
		// that we know the *exact* field and not just one with
		// the same name
		if (isset($prepped_fields[$field_name])) return $str;
		

		// In case htmlspecialchars misses these.
		$str = str_replace(array("'", '"'), array("&#39;", "&quot;"), $str);
//		$str = htmlspecialchars($str);

		if ($field_name != '')  $prepped_fields[$field_name] = $str;
		return $str;
	}
}

/**
 * Заменим textarea с id на визуальный редактор
 *
 * @param integer $id   id textarea что нужно заменить
 * @param string  $type тип будущего редактора tinymce, ckeditor, markitup
 */
function form_html( $id, $type='tinymce' ){
    $type   = not_empty( $type, 'tinymce' );
    $common = CI()->template_lib->get( 'common' );
    
switch( $type ){
    //------------ CKEDITOR
    case 'ckeditor':
        add_js(	$common['ckeditor'].'/ckeditor.js' );
        echo '
<script type="text/javascript" language="JavaScript">
//<![CDATA[
    CKEDITOR.replace( "', $id ,'",
        {
        fullPage : true
        }
    );
//]]>
</script>
';
        break;
    //------------ MARKIT UP
    case 'markitup':
        add_js(	$common['markitup'].'/jquery.markitup.js' );
        add_js(	$common['markitup'].'/sets/html/set.js' );
        add_css( $common['markitup'].'/skins/markitup/style.css' );
        add_css( $common['markitup'].'/sets/html/style.css' );
        echo '
<script type="text/javascript" language="JavaScript">
//<![CDATA[
   $(document).ready(function() {
      $("#', $id ,'").markItUp(mySettings);
   });
//]]>
</script>
';
        break;
    //------------ TINYMCE
    case 'tinymce':
        add_js( $common['tinymce'].'/jquery.tinymce.js' );
        add_js( $common['tinymce'].'/plugins/tinybrowser/tb_tinymce.js.php' );
        echo '
<script type="text/javascript" language="JavaScript">
//<![CDATA[
	$().ready(function() {
		$("#', $id ,'").tinymce({
			script_url : "', $common['tinymce'] ,'/tiny_mce.js",

            language: "ru",

			// General options
			theme : "advanced",
			plugins : "safari,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",

			// Theme options
			theme_advanced_buttons1 : "code,pasteword,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,formatselect,|,undo,redo,|,bullist,numlist,|,link,unlink,anchor,image,media,|,cleanup,|,forecolor,backcolor",
			//theme_advanced_buttons2 : "undo,redo,|,bullist,numlist,|,link,unlink,anchor,image,media,|,cleanup,|,forecolor,backcolor",
			theme_advanced_buttons2 : "tablecontrols,|,fullscreen",
            theme_advanced_buttons3 : "",
            theme_advanced_buttons4 : "",
			//theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak",
			theme_advanced_toolbar_location : "top",
			theme_advanced_toolbar_align : "left",
			theme_advanced_statusbar_location : "bottom",
			theme_advanced_resizing : true,
            theme_advanced_resizing_max_width : 900,

			// Example content CSS (should be your site CSS)
			content_css : "/asset/common/tinymce/css/styles_edit.css",

			// Drop lists for link/image/media/template dialogs
			template_external_list_url : "lists/template_list.js",
			external_link_list_url : "lists/link_list.js",
			external_image_list_url : "lists/image_list.js",
			media_external_list_url : "lists/media_list.js",

            file_browser_callback : "tinyBrowser",

            width: \'100%\',
            height: 440,
			relative_urls : false,

			// Replace values for the template plugin
			template_replace_values : {
				username : "BESTWEB",
				staffid : "www.bestweb.ru"
			}
		});
	});
//]]>
</script>
';
        break;
    } // switch
   
}

function form_select_date( $id, $date='', $lang = 'russian', $today='' ){
    $common = CI()->config->item('common');
    Asset::add_js  ( $common['js'].'/datepicker.js' );
    add_css ( $common['css'].'/datepicker/datepicker.css' );
    
    $date = not_empty($date, date('d.m.Y'));
    $date = human_date( $date );
    
    if( empty($page['laguage']) OR ($page['language']=='russian') ){
        $locale = '
            locale: {
            days: ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"],
            daysShort: ["Вс", "Пн", "Вт", "Ср", "Чт", "Пт", "Сб", "Вс"],
            daysMin: ["Вс", "Пн", "Вт", "Ср", "Чт", "Пт", "Сб", "Вс"],
            months: ["Январь", "Февраль", "Март", "Апрель", "Май", "Июнь", "Июль", "Август", "Сентябрь", "Октябрь", "Ноябрь", "Декабрь"],
            monthsShort: ["Янв", "Фев", "Март", "Апр", "Май", "Июнь", "Июль", "Авг", "Сен", "Окт", "Нояб", "Дек"],
            weekMin: "№"
            },
        ';
    }

    $out = <<<DOT
<input name="{$id}" id="{$id}" type="text" class="input size100" value="{$date}" />
<script language="JavaScript" type="text/javascript">
	$(function() {
        $('#{$id}').DatePicker({
            format:'d.m.Y',
            date: $('#{$id}').val(),
            starts: 1,
            position: 'right',
            {$locale}
            onBeforeShow: function(){
                $('#{$id}').DatePickerSetDate($('#{$id}').val(), true);
            },
            onChange: function(formated, dates){
                $('#{$id}').val(formated);
            }
        });
	});
</script>
DOT;
    return $out;
}

