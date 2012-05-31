<?php
error_reporting( E_ALL ^ E_NOTICE );

class MY_Controller extends MX_Controller{
    protected
        $data     = array(),
        $settings = array(),
            
        $current_user = array()
    ;
    public function __construct( $config='' ) {
        parent::__construct();
        if( !empty($config) ) $this->load_config( $config );
        $this->current_user = current_user();
        $this->data['current_user'] = $this->current_user;
        $this->load->helper( array('blog','user') );
        $this->load->model( array('post') );
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

    /**
     * Yeah, set layout
     * @param type $layout 
     */
    protected function initialize( $layout='index' ){
        $this->template->set_layout( $layout );
    }
    
    /**
     * Finish all works and draw main layout with custom blocks
     */
    protected function draw(){
        $this->template->set( 'top_news', $this->post->top_news( 15 ) );
        $this->template->show();
    }
    
    /**
     * Load and get config
     * 
     * @param type $name 
     */
    protected function load_config( $name ){
        $this->config->load( $name );
        $this->settings = $this->config->item( $name );                
    }
    
    /**
     * AJAX return results
     * 
     * @param type $res 
     */
    protected function ajax( $res ){ echo json_encode( $res ); }
    
}