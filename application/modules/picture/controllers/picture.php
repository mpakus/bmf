<?php

class PictureController extends MY_Module{
    
    protected
        $view = 'picture/',
        $path = 'files/picture/'
    ;
    
    public function __construct(){
        parent::__construct( 'picture' );
        user_can_rule();
        $this->load->model( array('picture','module') );
        $this->load->library( array('thumb_lib') );
        $this->picture->file_dir = FCPATH.$this->path;
        $this->data['picture_path'] = site_url( $this->path );
    }
    
    /**
     *
     * @param type $post_id
     * @param type $module_id
     * @return type 
     */    
    public function show(){
        if( empty($this->module_id) ) return '';

        $this->data['picture'] = $this->picture->find( $this->module_id, 1 );
        return $this->template->render( $this->view.'show', $this->data );
    }
    
    /**
     *
     * @param type $post_id
     * @param type $module_id
     * @return type 
     */
    public function form(){
        $this->data['picture'] = $this->picture->find( $this->module_id, 1 );
        return $this->template->render( $this->view.'form', $this->data );
    }
    
    /**
     * Method saves the picture in post $post_id, module $module_id
     * 
     * @param number $post_id
     * @param number $module_id
     */
    public function save( $post_id, $module_id ){        
        $post = $this->post->find( $post_id, 1 );
        user_can_rule( $post );

        // Uploading an image file & resize
        if( !empty($_FILES['image']) ){            
            try {
                $this->picture->upload('image')->resize_image( array(
                    'width'  => $this->settings['max_width'], 
                    'height' => $this->settings['max_height'],
                    'thumb'  => TRUE, 
                    // 'adaptive' => TRUE
                ));                
                $data['image'] = $this->picture->file_name;                
                $data['alt'] = param('alt');
                $data['module_id'] = $module_id;
                $this->picture->save( $data ); 

                set_flash_ok('Изображение сохранено');
                
            } catch( Exception $e ) {                
                log_message( 'error', $e->getCode() . ' : ' . $e->getMessage() );
                set_flash_ok('Ошибка во время сохранения изображения');                
                redirect( post_form_save_path($post, $module_id) );
            }
        }        
        redirect( post_form_path($post) );
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
        $this->picture->delete_file( 'image', $module_id );
        $this->picture->delete_file( 'original', $module_id );
        $this->picture->delete( $module_id );
        return TRUE;
    }
}