<?php

class BlogController extends MY_Controller{
    protected
        $view = 'blog/'
    ;
    
    public function __construct() {
        parent::__construct( 'blog' );
        $this->load->model( array('post', 'text/text', 'tag', 'comment') );        
     }
    
    /**
     * Shows posts feed on main page
     * 
     * @param type $page 
     */
    public function index( $page=0 ){
//        $this->template->set_layout( 'layouts/index' );
//        $this->pagination( 'blog/index/page', $this->post->count_top_reviews_and_video() );
        $this->data['posts'] = $this->post->find_published( $this->settings['post_per_page'], $page );
        $this->template->render_to( 'content', $this->view.'index', $this->data );
        $this->draw();
    }    

    /**
     * Create pagination 
     * 
     * @param type $uri
     * @param type $count 
     */
    protected function pagination( $uri, $count=0 ){
        $this->load->library('pagination');
        $config['first_link']   = $config['last_link'] = FALSE;
        $config['next_link']    = 'Следующая';
        $config['next_tag_open']  = '<span class="next">';
        $config['next_tag_close'] = '</span>';        
        $config['prev_link']    = 'Предыдущая';
        $config['prev_tag_open']  = '<span class="prev">';
        $config['prev_tag_close'] = '</span>';
        $config['base_url']		= site_url( $uri );
        $config['uri_segment']  = 4;
        $config['total_rows']	= $count;
        $config['per_page']		= $this->settings['post_per_page'];
        $config['cur_page']     = $page;
        $config['num_links'] 	= $this->settings['num_links'];
        $this->pagination->initialize( $config );
        $this->data['pagination'] = $this->pagination->create_links();        
    }
    
    /**
     * Shows full post universal method for NEWS and REVIEWS because they looks same
     * 
     * @param type $id
     * @param type $alias 
     */
    public function show2( $id, $alias ){
        $this->data['post']     = $this->post->where('deleted',0)->find( $id, 1 );
        $this->load->helper( 'comment' );
        $this->data['comments'] = $this->comment->find_for_post( $id );
        $this->template->render_to( 'content', $this->view.'show', $this->data );
        $this->draw();
    }
    
    public function show( $id ){
        $this->data['post'] = $this->post->find( $id, 1 );
        $this->load->helper( 'comment' );
        $this->data['comments'] = $this->comment->find_for_post( $id );
        $this->template->render_to( 'content', $this->view.'show', $this->data );
        $this->draw();
    }
    
    /**
     * Filter posts by tag
     * 
     * @param type $tag 
     */
    public function tag( $tag ){
        $tag = urldecode( $tag );
        $tag = $this->tag->where( 'tag', $tag )->find( NULL, 1 );
        if( empty($tag) ){
            $this->template->set('content', 'Такой тэг не найден на нашем блоге');
        }else{
            $this->data['posts'] = $this->post->find_by_tag( $tag );
            $this->template->render_to( 'content', $this->view.'index', $this->data );
        }
        $this->draw();
    }
    
//    /**
//     * Shows opened news
//     * 
//     * @param type $id
//     * @param type $alias 
//     */
//    public function show_news( $id, $alias ){
//        $this->data['post'] = $this->post->find( $id, 1 );
//        $this->template->render_to( 'content', $this->view.'show', $this->data );
//        $this->draw();        
//    }
    
    /**
     * Take a vote
     * 
     * @return type 
     */
    public function vote(){
        $post_id = param( 'id' );
        if( empty($post_id) ) return $this->ajax( array('error'=>'Не указан ID топика для голосования') );
        if( empty($this->current_user) ) return $this->ajax( array('error'=>'Извините, голосовать могут только авторизованные пользователи') );
        $this->load->model('vote');
        try{
           $rating = $this->vote->voting( $post_id, $this->current_user['id'] );
        }catch( Exception $e ){
            return $this->ajax( array('error'=>$e->getMessage()) );            
        }
        $this->ajax( array('ok'=>'Ваш голос принят, спасибо', 'rating'=>$rating) );
    }
    
    public function subscribe(){
        $email = param('email');
        if( empty($email) ) return $this->ajax( array('error'=>'Вы забыли написать свой E-mail') );
        
        $this->load->model( 'mail' );
        $mail = $this->mail->where( 'email', $email )->find( NULL, 1 );
        if( !empty($mail) )
                return $this->ajax( array('error'=>'Вы уже подписаны на нашу рассылку, спасибо') );
       
        $data = array(
            'email' => $email,
            'added_at' => now2mysql()
        );
        $this->mail->save( $data );
        $this->ajax( array('ok'=>'Спасибо, вы подписались на нашу рассылку') );        
    }
    
}