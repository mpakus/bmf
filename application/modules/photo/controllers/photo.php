<?php

class PhotoController extends MY_Module{
    
    protected
        $view = 'photo/',
        $path = 'files/photo/'
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
            $this->data['photo_path'] = site_url( $this->path );
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
        $this->data['photo_path'] = site_url( $this->path );
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
            
            // если есть изображение пробуем загрузить, откорректировать и сделать превью
            try {
                $this->photo->upload('image')->resize_image( array(
                                                'width'=>$this->settings['max_width'], 
                                                'height'=>$this->settings['max_height'], 
                                                'adaptive'=>TRUE
                ));
                
                $data['image'] = $this->photo->file_name;
                
                $data['alt'] = param('alt');
                $data['module_id'] = $module_id;
                $this->photo->save( $data ); 

                set_flash_ok('Картиночка сохранена');
                
            } catch( Exception $e ) {
                
                set_flash_ok('Ошибка во время сохранения изображения!');
                var_dump($e);
                die;
            }
            

                    
        }
        
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
        
        // Delete the photo from DB and delete the file
        $this->photo->delete_file( 'image', $module_id );
        $this->photo->delete( $module_id );

        return TRUE;
    }
}