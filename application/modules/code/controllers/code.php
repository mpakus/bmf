<?php
class CodeController extends MY_Controller{
    
    public function __construct(){
        parent::__construct();
    }
    
    public function show(){
       return "show code"; 
    }
    
    public function form(){
       return "form for code"; 
    }
}