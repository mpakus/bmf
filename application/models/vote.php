<?php

class Vote extends MY_Model{
    protected
        $table = DC_VOTES_TABLE
    ;
    
    public function __construct(){
        parent::__construct();
    }

    public function voting( $post_id='', $user_id='' ){
        // check if we alredy have voted for this post
        $data = array('post_id'=>$post_id, 'user_id'=>$user_id);
        $voted = $this->where( $data )->find( NULL, 1 );
        if( $voted ) throw new Exception( 'Извините, вы уже голосовали за этот топик' );
        $data['weight']   = 1;
        $data['added_at'] = now2mysql();
        $this->save( $data );
        $this->db->select('SUM(weight) AS rating')->where( 'post_id', $post_id );
        $res = $this->find( NULL, 1 );
        if( empty($res) ) throw new Exception( 'Извините, какая-то ошибка при подсчете рейтинга' );
        
        $data = array(
            'id' => $post_id,
            'rating' => $res['rating']
        );
        $this->post->save( $data );
        return $res['rating'];
    }
}