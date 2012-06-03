<?php

class MY_Module extends MY_Controller{
    public function __construct( $config='' ) {
        parent::__construct( $config );
    }
    
    /**
     *
     * @param type $post_id
     * @param type $module_id
     * @return TextController 
     */
    public function set_params( $post_id, $module_id ){
        $this->data['post_id']   = $post_id;
        $this->data['module_id'] = $module_id;
        
        $this->post_id   = $post_id;
        $this->module_id = $module_id;
        return $this;
    }

    public function delete(){
        return TRUE;
    }
}