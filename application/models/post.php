<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Post model
 * 
 * @version $Id
 * @access ALL
 * @author Ibragimov "MpaK" Renat <info@mrak7.com>
 * @copyright Copyright (c) 2009-2012, AOmega.ru
 */
class Post extends MY_Model {
    protected
        $type           = array(), // content types
        $table          = DC_POSTS_TABLE,
        $texts_table    = DC_TEXTS_TABLE,
        $users_table    = DC_USERS_TABLE        
    ;
    
    public function __construct() {
        parent::__construct();
        $this->type = blog_types();
    }
    
    /**
     * 
     * 
     * @param type $post
     * @return type 
     */
    public function save( $post ){
        if( empty($post[$this->pkey]) ) $post['added_at']   = now2mysql();

        $post[$this->pkey] = parent::save( $post );
        
        // after that check tags, add them and link with our new post
        if( !empty($post['tags']) ) {
            $post['type'] = blog_type('post');
            $post['tags'] = $this->tag->save( $post );
            parent::save( $post );
        }
        return $post['id'];
    }
    
    /**
     * Find posts with linked text and author's profile
     * 
     * @param type $id
     * @param type $limit
     * @param type $from
     * @return type 
     */
    public function find($id = NULL, $limit = NULL, $from = NULL) {
        $this->db->select( 't.*, u.id AS user_id, u.login, u.email, u.avatar, u.banned, u.role' )
//                ->join( $this->texts_table.' AS te', 't.id = te.post_id', 'LEFT' )
                ->join( $this->users_table.' AS u', 't.user_id = u.id', 'LEFT' )
//                ->order_by( 'added_at', 'DESC')
        ;
        return parent::find($id, $limit, $from);
    }
    
    public function find_by_tag( $tag ){
        $posts = $this->tag->find_posts( $tag );
        if( empty($posts) ) return array();
        $in = array();
        foreach( $posts as $post ) $in[] = $post['object_id'];
        
        $this->db->where_in( 't.id', $in );
        return $this->find();
    }
    
    public function find_published( $limit=NULL, $from=NULL ){
        return $this->where_published()->find( NULL, $limit, $page );
    }
    public function count_published(){
        return $this->where_published()->count();
    }
    public function where_published(){
        $this->db->where('deleted',0)->where('published',1)->order_by( 'added_at,t.rating' );
        return $this;
    }

    /**
     * Get number of user posts
     * 
     * @param type $user_id
     * @return type 
     */
    public function count_for_user( $user_id ){
        return $this->where('user_id', $user_id)->where('deleted',0)->count();
    }
}