<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class PostController extends MY_Controller{
    protected
        $view = 'post/',
        $id   = NULL
    ;

    public function __construct() {
        parent::__construct( 'post' );        
        // CHECK PRIVILEGIES!!!
        user_can_rule();

        $this->template->set_layout( 'layouts/inside' );
        $this->load->model( array('post', 'tag', 'text') );
        
        $this->id = (integer)param('id');
        
        $this->post->file_dir = $this->settings['previews_dir'];        
    }
    
    public function index(){ $this->draw(); }
    
    /**
     *
     * @param type $form
     * @param type $type_id
     * @param type $rules
     * @return type 
     */
    public function form( $form, $type=1, $rules=array() ){
        $data = array();

        if( !empty($this->id) AND empty($_POST) ){
            $this->data['data'] = $this->post->find( $this->id, 1 );
        }else{
            // получили параметры пользователя возможно из запроса или же остались что были
            $post = params( array( 'title', 'tags', 'category_id', 'preview', 'link' ) );
            $post['id'] = $this->id;
            $this->data['data']  = $post;
        }
        
        $this->load->library('form_validation');

        $config = array(
           array(
                 'field'   => 'title',
                 'label'   => 'Заголовок сообщения',
                 'rules'   => 'required|trim'
           ),
           array(
                 'field'   => 'tags',
                 'label'   => 'Тэги',
                 'rules'   => 'required|trim|min_length[4]'
           ),
        );
        if( !empty($rules) ) $config = array_merge( $config, $rules );

        $this->form_validation->set_rules($config);

        if( $this->form_validation->run($this) === FALSE ){
            $this->data['addon_form'] = $this->template->render( $this->view.$form, $this->data );
            $this->template->render_to( 'content', $this->view.'form', $this->data );
            return FALSE;
        }else{
            // if have a picture let's try to upload it and resize with parameters
            if( !empty($_FILES['preview']['name']) ){
                if( !empty($post['id']) ){
                    $old_post = $this->post->find( $post['id'], 1 );
                    if( !empty($old_post['preview']) ) $this->post->delete_file( 'preview', $old_post['id'] );
                }
                try{
                    switch( $type ){
                        case blog_type('photo'):
                            $this->post->upload('preview')
                                ->resize_image( array('width' => $this->settings['photos_preview_width'], 'height' => $this->settings['photos_preview_height']) )                        
                                ->resize_image( array('thumb'=>TRUE, 'width' => $this->settings['photos_thumb_width'], 'height' => $this->settings['photos_thumb_height']) );                        
                            break;
                        case blog_type('news'):
                            $this->post->upload('preview')->resize_image( array('width' => $this->settings['news_preview_width'], 'height' => $this->settings['news_preview_height'], 'adaptive'=>TRUE) );
                            break;
                        case blog_type('review'):
                            $this->post->upload('preview')->resize_image( array('width' => $this->settings['reviews_preview_width'], 'height' => $this->settings['reviews_preview_height'], 'adaptive'=>TRUE) );
                            break;
                        default:
                            ;
                    }
                    $post['preview'] = $this->post->file_name;
                }catch( Exception $e ){
//                    echo $e->getMessage();
                    log_message( 'error', $e->getMessage() );
//                    set_flash_error( $e->getMessage() );
//                    return FALSE;
                }
            }

            $post['type']    = (integer)$type;
            $post['user_id'] = (integer)$this->current_user['id'];
            $post['id']      = $this->post->save( $post );
            return $post;
        }        
    }
    
    /**
     * Check our request and keep in safe place TEXT information
     * 
     * @param type $post
     * @param type $msg
     * @return PostController 
     */
    protected function check_and_save_text( $post, $msg='Поздравляем, Вы создали топик' ){
        if( ($post !== FALSE) AND $_POST ){
            $text['post_id']  = not_empty( $this->id, $post['id'] );
            $text['original'] = param( 'original', TRUE, FALSE ) ;
            $text['full']     = prepare_text( $text['original'] );
            $text['short']    = mb_strcut( strip_tags_regular($text['full']), 0, 250 );
            $this->text->save( $text );
            set_flash_ok( $msg );
            redirect( post_link($post) );
        }
        return $this;
    }    
    /**
     * Form for add the text of news
     */
    public function review( $id=NULL ){
        $this->id = $id;
        $rules = array(
            array(
                'field'   => 'original',
                'label'   => 'Текст обзора',
                'rules'   => 'required|trim'
            ),    
        );
        $post = $this->form( 'review', blog_type('review'), $rules );
        $this->check_and_save_text( $post, 'Поздравляем, Вы создали новый обзор' );
        $this->draw();        
    }
    
    
    /**
     * Form for add the text of news
     */
    public function news( $id=NULL ){
        $this->id = $id;
        $rules = array(
            array(
                'field'   => 'original',
                'label'   => 'Текст новости',
                'rules'   => 'required|trim'
            ),    
        );
        $post = $this->form( 'news', blog_type('news'), $rules );
        $this->check_and_save_text( $post, 'Поздравляем, Вы создали новость' );
        $this->draw();        
    }
    
    
    /**
     * Photo add & edit form
     */
    public function photo( $id=NULL ){
        $this->id = $id;
        $rules = array(
            array(
                'field'   => 'original',
                'label'   => 'Краткий текст для фото',
                'rules'   => 'required|trim'
            ),    
        );
        $post = $this->form( 'photo', blog_type('photo'), $rules );
        $this->check_and_save_text( $post, 'Поздравляем, Вы создали новый топик с картинкой' );
        $this->draw();         
    }
    
    /**
     * Form for add a video
     */
    public function video( $id=NULL ){
        $this->id = $id;
        $rules = array(
            array(
                'field'   => 'link',
                'label'   => 'Ссылка на youtube видео',
                'rules'   => 'required|trim'
            ),    
        );
        $post = $this->form( 'video', blog_type('video'), $rules );
        if( ($post !== FALSE) AND $_POST ){            
            set_flash_ok( 'Поздравляем, Вы создали новый видео топик' );
            redirect( post_link($post) );
        }
        $this->draw();           
    }
    
    /**
     * Add new comment to topic
     */
    public function comment(){
        $this->load->model( 'comment' );
        $comment['post_id'] = param( 'post_id' );
        if( empty($comment['post_id']) ) {
            set_flash_error( 'Ошибка в параметрах комментария' );
            redirect();
        }
        $post = $this->post->find( $comment['post_id'], 1 );
        if( empty($post) ){
            set_flash_error( 'Такого топика не существует' );
            redirect();
        }
        $comment['parent_id'] = param( 'parent_id' );
        $comment['text'] = prepare_text( param( 'text', TRUE, FALSE ) );
        if( empty($comment['text']) ){
            set_flash_error( 'Вы не написали свой комментарий к топику' );
            redirect( post_link($post) );
        }
        $comment['user_id'] = $this->current_user['id'];
        $comment['added_at'] = now2mysql();
        
        $id = $this->comment->save( $comment );
        if( $id ){
            set_flash_ok( 'Спасибо за ваш комментарий' );            
        }else{
            set_flash_error( 'Извините, но произошла ошибка и ваш комментарий сохранить не удалось' );
        }
        redirect( post_link($post).'#com'.$id );
    }
    
    /**
     * Mark post as deleted
     * 
     * @param type $id
     * @return type 
     */
    public function destroy(){
        $id = param('id');
        if( empty($id) ) return $this->ajax( array('error'=>'Почему-то не указан ID топика для удаления' ) );
        
        $post = $this->post->find( $id, 1 );
        // who is author?
        if( !user_is('admin') AND ($post['user_id'] != $this->current_user['id']) )
            return $this->ajax( array('error'=>'Увы, вы можете только удалять свои топики') );
        // mark it as deleted
        $this->post->save( array('id'=>$id, 'deleted'=>1) );
        
        return $this->ajax( array('ok'=>'Как жаль, но топик успешно удалён', 'id'=>$id) );
    }
    
    /**
     * Mark comment as deleted
     * 
     * @return type
     */
    public function comment_destroy(){
        if( !user_is('admin') ) return $this->ajax( array('error'=>'Извините, но у вас нет прав на удаление комментариев') );

        $id = param('id');
        $this->load->model( 'comment' );
        if( empty($id) ) return $this->ajax( array('error'=>'Почему-то не указан ID комментария для удаления' ) );
            
        $this->comment->save( array('id'=>$id, 'deleted'=>1 ) );
        
        return $this->ajax( array('ok'=>'Комментарий успешно удалён', 'id'=>$id) );
    }
    
}