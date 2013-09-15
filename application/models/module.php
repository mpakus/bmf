<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Modules model
 * 
 * @version $Id
 * @access ALL
 * @author Ibragimov "MpaK" Renat <info@mrak7.com>
 * @copyright Copyright (c) 2009-2013, AOmega.ru
 */
class Module extends MY_Model {
    protected
        $table          = DC_MODULES_TABLE
    ;
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Add new module to the post
     * 
     * @param array $module
     * @return type 
     */
    public function add_new( $module ){
        $this->db->trans_start();
        $this->db->select_max('ord')
                ->where('post_id', (integer)$module['post_id'])
        ;
        $res = $this->find( NULL, 1 );
        $max_ord = $res['ord'];
        $module['ord'] = ++$max_ord;
        $id = $this->save( $module );
        $this->db->trans_complete();
        return $id;
    }

    /**
     * Update modules order
     *
     * @param array $post_modules - ordered modules ids
     */
    public function resort( $post_modules ){
        /*Array[3]
        0 => "5"
        1 => "112"
        2 => "111"
        */
        $this->db->trans_start();
        foreach( $post_modules as $ord => $id )
            $this->db->where('id', $id)->update( $this->table, array('ord'=>$ord+1) );
        $this->db->trans_complete();
        return $this->db->trans_status();
    }    
    
    public function find_all_for_post( $post_id ){
        return $this->where( 'post_id', $post_id )->order_by('ord', 'asc')->find();
    }

}