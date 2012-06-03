var AC = {
    confirm: function( msg ){
		return confirm( msg );
	},
    
    message_ok: function ( msg, title ){
        if( title=='' ) title = 'Информация';
        $.pnotify({
            pnotify_title: title,
            pnotify_text: msg,
            pnotify_notice_icon: "ui-icon ui-icon-mail-closed",
            pnotify_animate_speed: "fast",
            pnotify_animation: {effect_in: "fade", effect_out: "drop"}
        });
    },

    message_error: function ( msg, title ){
        if( title=='' ) title = 'Ошибка';
        $.pnotify({
            pnotify_title: title,
            pnotify_text: msg,
            pnotify_notice_icon: "pnotify_error_icon ui-icon-alert",
            pnotify_type: "error",
            pnotify_animate_speed: "fast",
            pnotify_animation: {effect_in: "fade", effect_out: "drop"}
        });
    },
    
    check_status : function( data ){
        if( data.error ) return AC.message_error( data.error );
        if( data.ok )    return AC.message_ok( data.ok );
    },
    
    get_id : function( str ){
        var m = str.match(/(\d+)$/g);
        return parseInt( m[0] );
    }
};

AC.Blog = {
    id : '',
    
    vote : function( e ){
        var attr = $(this).parent().attr('id').split('-');
        if( attr[1] == '' ) return false;
        AC.Blog.id = attr[1];
        
        $.ajax({
            type : 'POST',
            url  : '/blog/vote/',
            data : {
              'id'    : attr[1]
            },
            success  : AC.Blog.after_vote,
            dataType : 'JSON'
        });
        return false;
    },

    after_vote : function( data ){
        if( data.error ) return AC.message_error( data.error );
        if( data.ok ){
            AC.message_ok( data.ok );
            // and update rating
            $('#rating-'+AC.Blog.id+' span').html( data.rating );
        }
        return true;
    }
};

AC.Post = {
    destroy : function( e ){
        var id = AC.get_id( $(this).attr('id') );
        if( id == '' ) return false;
        if( !AC.confirm('Вы уверены, что хотите удалить этот топик?') ) return false;
        
        $.ajax({
            type : 'POST',
            url  : '/post/destroy/',
            data : {
              'id'    : id
            },
            success  : AC.Post.after_destroy,
            dataType : 'JSON'
        });
        return false;        
    },
    
    after_destroy : function( data ){
        AC.check_status( data );
        // hide it
        $('#post-'+data.id).hide('slow');
    }
};

AC.Comment = {
    reply : function( e ){
        var id = AC.get_id( $(this).attr('id') );
        $('.comment').removeClass( 'replayed' );
        $('#comment-'+id).addClass( 'replayed' );
        $('#parent_id').val( id );
        return true;
    },
    on_submit : function(){
        if( $('#text').val() == '' ){
            AC.message_error( 'Извините, вам надо бы текст комментария написать' );
            return false;
        }
        return true;
    },
    
    destroy : function( e ){
        var id = AC.get_id( $(this).attr('id') );
        if( id == '' ) return false;
        if( !AC.confirm('Вы уверены, что хотите удалить этот комментарий?') ) return false;
        
        $.ajax({
            type : 'POST',
            url  : '/post/comment_destroy/',
            data : {
              'id'    : id
            },
            success  : AC.Comment.after_destroy,
            dataType : 'JSON'
        });
        return false;        
    },
    
    after_destroy : function( data ){
        AC.check_status( data );
        // hide it
        $('#comment-'+data.id).hide('slow');
    }    
};
