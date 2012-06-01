<?php

class TextController extends MY_Module{
    protected
        $view = 'text/'
    ;
    
    public function __construct(){
        parent::__construct();
        user_can_rule();
        $this->load->model( array('text') );
    }
        
    /**
     *
     * @param type $post_id
     * @param type $module_id
     * @return type 
     */    
    public function show(){
        if( !empty($this->module_id) ){
            $this->data['text'] = $this->text->find( $this->module_id, 1 );
            return $this->template->render( $this->view.'show', $this->data );
        }else{
            return '--- empty ---';
        }
    }
    
    /**
     *
     * @param type $post_id
     * @param type $module_id
     * @return type 
     */
    public function form(){
        $this->data['text'] = $this->text->find( $this->module_id, 1 );
        return $this->template->render( $this->view.'form', $this->data );
    }
    
    /**
     * 
     */
    public function save( $post_id, $module_id ){
        $data = array();
        $data['module_id'] = $module_id;
        $data['full'] = param('full');
        $this->text->save( $data ); // keep in safe place ;)
        set_flash_ok('Текст сохранён');
        redirect( 'post/form/'.$post_id.'/'.$module_id );
    }
}