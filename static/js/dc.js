// start
$(document).ready(function() {
    $('#open_debug').click( function(){
        $('#codeigniter_profiler').toggle('clip');
    });
});

// our main object
var DC = {
    on_load: function( ) {

	},

    message_ok: function ( msg, title ){
        if( title=='' ) title = 'OK';
        $.pnotify({
            pnotify_title: title,
            pnotify_text: msg,
            pnotify_notice_icon: "ui-icon ui-icon-mail-closed",
            pnotify_animate_speed: "fast",
            pnotify_animation: {effect_in: "fade", effect_out: "drop"}
        });
    },

    message_error: function ( msg, title ){
        if( title=='' ) title = 'ERROR';
        $.pnotify({
            pnotify_title: title,
            pnotify_text: msg,
            pnotify_notice_icon: "pnotify_error_icon ui-icon-alert",
            pnotify_type: "error",
            pnotify_animate_speed: "fast",
            pnotify_animation: {effect_in: "fade", effect_out: "drop"}
        });
    },

    // да или нет у подтверждения
    confirm: function( msg, code ){
		if( confirm( msg ) ){
			if( code != '') code();
            return true;
		}
		return false;
	},

    // устанавливаем подтвердитель на всех сказанных
    set_confirm: function ( elements, msg ){
        $(elements).click(function(e) {
            //console.log( $(this) );
            e.preventDefault();
            var target = $(this).attr('href');
            //$('#dialog-confirm').dialog('destroy');
            $('#dialog-confirm').html( '<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>'+msg+'</p>' );

            $('#dialog-confirm').dialog({
                autoOpen: false,
                bgiframe: true,
                resizable: false,
                height: 220,
                width: 340,
                closeOnEscape: true,
                modal: true,
                overlay: {
                    backgroundColor: '#000',
                    opacity: 0.8
                },
                buttons: {
                    'ДА, точно': function() {
                        window.location.href = target;
                    },
                    'НЕТ, отменить': function() {
                        $(this).dialog('close');
                    }
                }
            });

            $('#dialog-confirm').dialog('open');
            return false;
        });

    }

};
