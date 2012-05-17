<?php

class Comment extends MY_Model{
    protected
        $table = DC_COMMENTS_TABLE,
        $users_table = DC_USERS_TABLE,
        $posts_table = DC_POSTS_TABLE
    ;
    
    public function __construct(){
        parent::__construct();
    }
    
    public function find_for_post( $post_id ){
        return $this->order_by( 'parent_id', 'ASC' )->where( 'post_id', $post_id )->find();
    }
    
    public function find($id = NULL, $limit = NULL, $from = NULL) {
        $this->db->select( 't.*, p.title, p.type, u.id AS user_id, u.login, u.email, u.avatar, u.banned, u.role' )
                ->join( $this->posts_table.' AS p', 't.post_id = p.id', 'LEFT' )
                ->join( $this->users_table.' AS u', 't.user_id = u.id', 'LEFT' )
        ;
        return parent::find($id, $limit, $from);
    }    

    /**
     * Get number of user comments
     * 
     * @param type $user_id
     * @return type 
     */
    public function count_for_user( $user_id ){
        return $this->where('user_id', $user_id)->where('deleted',0)->count();
    }
}