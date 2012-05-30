<?php

class TextController extends MY_Controller{
    protected
        $view = 'text/'
    ;
    
    public function __construct(){
        parent::__construct();
        user_can_rule();
        $this->load->model( array('text') );
    }
        
    public function show(){
       return "show text"; 
    }
    
    public function form( $post_id, $module_id ){
        $this->data['post_id'] = $post_id;
        $this->data['module_id'] = $module_id;
        $this->data['text'] = $this->text->find( $module_id, 1 );
        return $this->template->render( $this->view.'form', $this->data );
    }
    
    public function save( $post_id, $module_id ){
        $data = array();
        $data['module_id'] = $module_id;
        $data['full'] = param('full');
        $this->text->save( $data );
        set_flash_ok('Текст сохранён');
        redirect( 'post/form/'.$post_id.'/'.$module_id );
    }
}