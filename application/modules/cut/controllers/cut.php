<?php

class CutController extends MY_Module{
    protected
        $view = 'cut/'
    ;
    
    public function __construct(){
        parent::__construct();
        user_can_rule();
    }
        
    /**
     *
     * @param type $post_id
     * @param type $module_id
     * @return type 
     */    
    public function show(){
        return $this->template->render( $this->view.'show', $this->data );
    }
    
    /**
     *
     * @param type $post_id
     * @param type $module_id
     * @return type 
     */
    public function form(){
        return '';
    }
    
}