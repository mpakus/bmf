<?php

class PhotoController extends MY_Module{
    protected
        $view = 'photo/'
    ;
    
    public function __construct(){
        parent::__construct();
        user_can_rule();
        $this->load->model( array('photo','module') );
        $this->photo->file_dir = FCPATH.'/files/photo/';
    }
    
    /**
     * Dummy index method (will be remove in future, just for test) 
     */
    public function index()
    {
        $this->data['post_id'] = 53;
        //$this->data['module_id'] = 53;
        $this->template->render( $this->view . 'form', $this->data );
    }
    
    /**
     *
     * @param type $post_id
     * @param type $module_id
     * @return type 
     */    
    public function show(){
        if( !empty($this->module_id) ){
            $this->data['text'] = $this->photo->find( $this->module_id, 1 );
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
        $this->data['text'] = $this->photo->find( $this->module_id, 1 );
        return $this->template->render( $this->view.'form', $this->data );
    }
    
    /**
     * Method saves the picture in post $post_id
     * 
     * @param number $post_id
     */
    public function save( $post_id ){
        
        // Uploading an image file
        if( !empty($_FILES['image']) )
            $this->photo->upload( 'image' );
        
        // Creating new module
        $module = array(
            'post_id' => $post_id,
            'name'    => 'photo'
        );
        $module_id = $this->module->add_new( $module );
        
        // Filling up the photo data and save them into DB
        $photo = array(
            'module_id' => $module_id,
            'alt' => param('alt'),
            'image' => $this->photo->file_name             
        );
        $this->photo->save( $photo ); 
        
        set_flash_ok('Картиночка сохранена');
        redirect( 'post/form/'.$post_id.'/'.$module_id.'#mod-'.$module_id );
    }
    
    /**
     * Method deletes the picture in post $post_id from module $module_id
     * 
     * @param number $post_id
     * @param number $module_id
     */
    public function delete( $post_id='', $module_id='' ){
        if( empty($module_id) ) $module_id = $this->data['module_id'];                
        $this->text->delete( $module_id );
        return TRUE;
    }
}