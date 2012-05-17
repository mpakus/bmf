<?php

class PageController extends MY_Controller{
    protected
        $view = 'page/'
    ;
    
    public function __construct() {
        parent::__construct();
    }
    
    public function index( $page='about' ){
        $this->template->render_to( 'content', $this->view.$page );
        $this->draw();
    }
}