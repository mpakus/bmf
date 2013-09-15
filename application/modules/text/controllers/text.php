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
     * Render page for publishing
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
            return '';
        }
    }
    
    /**
     * Shows text editor form
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
     * Receive data from user, prepare and keep it our databse
     */
    public function save( $post_id, $module_id ){
        $data = array();
        $data['module_id'] = $module_id;
        $data['original'] = param('original', TRUE, FALSE);
        $this->text->save( $data ); // keep in safe place ;)
        set_flash_ok('Текст сохранён');
        redirect( 'post/form/'.$post_id.'#mod-'.$module_id );
    }
    
    /**
     * Delete text information from database
     */
    public function delete( $post_id='', $module_id='' ){
        if( empty($module_id) ) $module_id = $this->data['module_id'];                
        $this->text->delete( $module_id );
        return TRUE;
    }
}