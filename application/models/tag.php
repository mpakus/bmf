<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Tag extends MY_Model{
    protected
        $table = DC_TAGS_TABLE,
        $tagged_objects_table = DC_TAGGED_OBJECTS_TABLE
    ;
    
    public function __construct() {
        parent::__construct();
    }

    /**
     * Save tag and relations between tag and object
     * 
     * @param type $post
     * @return type 
     */
    public function save( $post ){
        if( empty($post['id']) OR empty($post['tags']) ) return;
        $tags = explode(',', mb_strtolower($post['tags']) );
        if( !empty($tags) ){
            // delete all old relations
            $this->db->where('object_id', $post['id'])->where('object_type', $post['type'])->delete( $this->tagged_objects_table );
            foreach( $tags as $tag ){
                $tag    = preg_replace('/\W+/u', '', $tag);
                $tagged_objects = array();
                if( empty($tag) ) continue;
                $tag_id = $this->find_or_create( $tag );
                if( $tag_id !== FALSE ) {
                    $new_tags[] = $tag;                
                    // insert relation between POSTS and TAGS
                    $this->db->insert( $this->tagged_objects_table, array('object_id'=>$post['id'],'object_type'=>$post['type'],'tag_id'=>(integer)$tag_id) );
                }
            }
            return implode(', ', $new_tags);
        }
    }
    
    public function find_or_create( $tag ){
        $in = $this->where('tag', $tag)->find( NULL, 1 );
        if( empty($in) ) $in['id'] = parent::save( array('tag'=>$tag) );        
        return $in['id'];
    }
    
    public function find_posts( $tag, $type=1 ){
        $posts = $this->where( 'tag_id', $tag['id'] )->where( 'object_type', $type)->find_in( $this->tagged_objects_table );
        return $posts;
    }
}