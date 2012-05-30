<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Post create and admin control
 * 
 * @version $Id
 * @access All
 * @author Ibragimov "MpaK" Renat <info@mrak7.com>
 * @copyright Copyright (c) 2009-2012, AOmega.ru
 */
class PostController extends MY_Controller {

    protected
        $view = 'post/',
        $id   = NULL,
        $modules = array()            
    ;

    public function __construct() {
        parent::__construct('blog');
        // CHECK PRIVILEGIES!!!
        user_can_rule();
        $this->load->model( array('post', 'tag', 'text', 'module') );
        $this->post->file_dir = $this->settings['previews_dir'];
    }

    public function index() {
        $this->draw();
    }

    /**
     *
     * @param type $form
     * @param type $type_id
     * @param type $rules
     * @return type 
     */
    public function form( $id='', $module_id='' ) {
        $data = array();

        if (!empty($id) AND empty($_POST)) {
            $this->data['post'] = $this->post->find($id, 1);
        } else {
            $post = params(array('title', 'tags', 'category_id',));
            $post['id'] = $id;
            $this->data['data'] = $post;
        }

        $this->load->library('form_validation');

        $config = array(
            array(
                'field' => 'title',
                'label' => 'Заголовок топика',
                'rules' => 'required|trim|max_length[255]'
            ),
            array(
                'field' => 'tags',
                'label' => 'Тэги',
                'rules' => 'required|trim|min_length[4]|max_length[255]'
            ),
        );
        $this->form_validation->set_rules($config);

        if ($this->form_validation->run($this) === FALSE) {
            $this->data['modules_for_add'] = $this->settings['modules'];
            if( !empty($id) ){
                $this->modules = $this->module->find_all_for_post( $id );
                $this->data['modules'] = $this->modules;
                $this->call_modules( $id, $module_id );
            }
            $this->template->render_to('content', $this->view . 'form', $this->data);
        } else {
            $post['user_id'] = (integer) $this->current_user['id'];
            $post['id'] = $this->post->save( $post );
            if( $post['id'] ){
                set_flash_ok( 'Отлично, топик создан, теперь можно его наполнять' );
            }else{
                set_flash_error( 'Извини, ошибочка вышла' );
            }
            redirect( 'post/form/'.$post['id'] );
        }
        $this->draw();
    }

    /**
     * Add new module to post
     * 
     * @param type $post_id 
     */
    public function add_module( $post_id='' ){
        if( empty($post_id) ){
            set_flash_error( 'Ошибка post_id не указан' );
            redirect( 'post/form' );
        }
        $module  = param('add_module');
        if( empty($module) ){
            set_flash_error( 'Модуль для добавления не указан' );
            redirect( 'post/form/'.$post_id );
        }
        if( empty($this->settings['modules'][$module['name']]) ){
            set_flash_error( 'А у нас такой модуль не найден' );
            redirect( 'post/form/'.$post_id );
        }
        $data = array(
            'post_id' => $post_id,
            'name' => $module['name']
        );
        $module_id = $this->module->add_new( $data );
        set_flash_ok( 'Отлично, новый модуль, давай заполним его' );
        redirect( 'post/form/'.$post_id.'/'.$module_id );
    }
    
    protected function call_modules( $post_id, $module_id='' ){
        if( empty($this->modules) ) return;
        foreach( $this->modules as $module ){
            $method = ($module['id'] == $module_id) ? 'form' : 'show';
            $this->data['modules_results'][] = array(
                'id'     => $module['id'],
                'result' => modules::run( $this->module['name'].'/'.$method )
            );
        }
        return;
    }

    /**
     * Check our request and keep in safe place TEXT information
     * 
     * @param type $post
     * @param type $msg
     * @return PostController 
     */
    protected function check_and_save_text($post, $msg='Поздравляем, Вы создали топик') {
        if (($post !== FALSE) AND $_POST) {
            $text['post_id'] = not_empty($this->id, $post['id']);
            $text['original'] = param('original', TRUE, FALSE);
            $text['full'] = prepare_text($text['original']);
            $text['short'] = mb_strcut(strip_tags_regular($text['full']), 0, 250);
            $this->text->save($text);
            set_flash_ok($msg);
            redirect(post_link($post));
        }
        return $this;
    }

    /**
     * Add new comment to topic
     */
    public function comment() {
        $this->load->model('comment');
        $comment['post_id'] = param('post_id');
        if (empty($comment['post_id'])) {
            set_flash_error('Ошибка в параметрах комментария');
            redirect();
        }
        $post = $this->post->find($comment['post_id'], 1);
        if (empty($post)) {
            set_flash_error('Такого топика не существует');
            redirect();
        }
        $comment['parent_id'] = param('parent_id');
        $comment['text'] = prepare_text(param('text', TRUE, FALSE));
        if (empty($comment['text'])) {
            set_flash_error('Вы не написали свой комментарий к топику');
            redirect(post_link($post));
        }
        $comment['user_id'] = $this->current_user['id'];
        $comment['added_at'] = now2mysql();

        $id = $this->comment->save($comment);
        if ($id) {
            set_flash_ok('Спасибо за ваш комментарий');
        } else {
            set_flash_error('Извините, но произошла ошибка и ваш комментарий сохранить не удалось');
        }
        redirect(post_link($post) . '#com' . $id);
    }

    /**
     * Mark post as deleted
     * 
     * @param type $id
     * @return type 
     */
    public function destroy() {
        $id = param('id');
        if (empty($id))
            return $this->ajax(array('error' => 'Почему-то не указан ID топика для удаления'));

        $post = $this->post->find($id, 1);
        // who is author?
        if (!user_is('admin') AND ($post['user_id'] != $this->current_user['id']))
            return $this->ajax(array('error' => 'Увы, вы можете только удалять свои топики'));
        // mark it as deleted
        $this->post->save(array('id' => $id, 'deleted' => 1));

        return $this->ajax(array('ok' => 'Как жаль, но топик успешно удалён', 'id' => $id));
    }

    /**
     * Mark comment as deleted
     * 
     * @return type
     */
    public function comment_destroy() {
        if (!user_is('admin'))
            return $this->ajax(array('error' => 'Извините, но у вас нет прав на удаление комментариев'));

        $id = param('id');
        $this->load->model('comment');
        if (empty($id))
            return $this->ajax(array('error' => 'Почему-то не указан ID комментария для удаления'));

        $this->comment->save(array('id' => $id, 'deleted' => 1));

        return $this->ajax(array('ok' => 'Комментарий успешно удалён', 'id' => $id));
    }

}