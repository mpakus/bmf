<?php
class TextController extends MX_Controller{
    
    public function __construct(){
        parent::__construct();
    }
    
    public function show(){
       return "show"; 
    }
    
    public function form(){
       return "form"; 
    }
}