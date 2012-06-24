var BMF = {
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
        if( data.error ) return BMF.message_error( data.error );
        if( data.ok )    return BMF.message_ok( data.ok );
    },
    
    get_id : function( str ){
        var m = str.match(/(\d+)$/g);
        return parseInt( m[0] );
    }
};

BMF.Blog = {
    id : '',
    
    vote : function( e ){
        var attr = $(this).parent().attr('id').split('-');
        if( attr[1] == '' ) return false;
        BMF.Blog.id = attr[1];
        
        $.ajax({
            type : 'POST',
            url  : '/blog/vote/',
            data : {
              'id'    : attr[1]
            },
            success  : BMF.Blog.after_vote,
            dataType : 'JSON'
        });
        return false;
    },

    after_vote : function( data ){
        if( data.error ) return BMF.message_error( data.error );
        if( data.ok ){
            BMF.message_ok( data.ok );
            // and update rating
            $('#rating-'+BMF.Blog.id+' span').html( data.rating );
        }
        return true;
    }
};

BMF.Post = {
    destroy : function( e ){
        var id = BMF.get_id( $(this).attr('id') );
        if( id == '' ) return false;
        if( !BMF.confirm('Вы уверены, что хотите удалить этот топик?') ) return false;
        
        $.ajax({
            type : 'POST',
            url  : '/post/destroy/',
            data : {
              'id'    : id
            },
            success  : BMF.Post.after_destroy,
            dataType : 'JSON'
        });
        return false;        
    },
    
    after_destroy : function( data ){
        BMF.check_status( data );
        // hide it
        $('#post-'+data.id).hide('slow');
    }
};

BMF.Comment = {
    reply : function( e ){
        var id = BMF.get_id( $(this).attr('id') );
        $('.comment').removeClass( 'replayed' );
        $('#comment-'+id).addClass( 'replayed' );
        $('#parent_id').val( id );
        return true;
    },
    on_submit : function(){
        if( $('#text').val() == '' ){
            BMF.message_error( 'Извините, вам надо бы текст комментария написать' );
            return false;
        }
        return true;
    },
    
    destroy : function( e ){
        var id = BMF.get_id( $(this).attr('id') );
        if( id == '' ) return false;
        if( !BMF.confirm('Вы уверены, что хотите удалить этот комментарий?') ) return false;
        
        $.ajax({
            type : 'POST',
            url  : '/post/comment_destroy/',
            data : {
              'id'    : id
            },
            success  : BMF.Comment.after_destroy,
            dataType : 'JSON'
        });
        return false;        
    },
    
    after_destroy : function( data ){
        BMF.check_status( data );
        // hide it
        $('#comment-'+data.id).hide('slow');
    }    
};