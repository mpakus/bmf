<?php

class PhotoController extends MY_Module{
    
    protected
        $view = 'photo/',
        $path = 'files/photo'
    ;
    
    public function __construct(){
        parent::__construct( 'photo' );
        user_can_rule();
        $this->load->model( array('photo','module') );
        $this->load->library( array('thumb_lib') );
        $this->photo->file_dir = FCPATH.$this->path;
    }
    
    /**
     *
     * @param type $post_id
     * @param type $module_id
     * @return type 
     */    
    public function show(){
        if( !empty($this->module_id) ){
            $this->data['photo'] = $this->photo->find( $this->module_id, 1 );
            $this->data['photo_path'] = site_url($this->path);
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
        $this->data['photo'] = $this->photo->find( $this->module_id, 1 );
        return $this->template->render( $this->view.'form', $this->data );
    }
    
    /**
     * Method saves the picture in post $post_id, module $module_id
     * 
     * @param number $post_id
     * @param number $module_id
     */
    public function save( $post_id, $module_id ){
        
        // Uploading an image file & resize
        if( !empty($_FILES['image']) ) {
            $upload = $this->photo->upload( 'image' );
            
            $image = $this->photo->file_dir . '/' . $upload->file_name;
            
            // Creating thumbnail object
            $thumb = $this->thumb_lib->create( $image );
            $thumb->resize( $this->settings['max_width'] );
            $thumb->save( $image );
                    
        }
        
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
        
        // Find the photo
        $photo = $this->photo->find( $module_id,1  );
        
        // Delete the photo from DB
        $this->photo->delete( $module_id );
        
        // Delete related file
        unlink($this->photo->file_dir.'/'.$photo['image']);

        return TRUE;
    }
}