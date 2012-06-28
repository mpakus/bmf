<?php

class CutController extends MY_Module{
    
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
        return '<hr/';
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