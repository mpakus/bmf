<?php

class PhotoController extends MY_Module{
    protected
        $view = 'photo/'
    ;
    
    public function __construct(){
        parent::__construct();
        user_can_rule();
        $this->load->model( array('photo') );
        $this->photo->file_dir = FCPATH.'/files/photo/';
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
        $data['alt'] = param('alt');
        
        if( !empty($_FILE['image']) ){
            $this->photo->upload( 'image' );
            $data['image'] = $this->photo->file_name;
        }        
        $this->photo->save( $data ); // keep in safe place ;)
        set_flash_ok('Картиночка сохранена');
        redirect( 'post/form/'.$post_id.'/'.$module_id.'#mod-'.$module_id );
    }
    
    /**
     * 
     */
    public function delete( $post_id='', $module_id='' ){
        if( empty($module_id) ) $module_id = $this->data['module_id'];                
        $this->text->delete( $module_id );
        return TRUE;
    }
}