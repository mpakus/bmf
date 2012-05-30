<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Modules model
 * 
 * @version $Id
 * @access ALL
 * @author Ibragimov "MpaK" Renat <info@mrak7.com>
 * @copyright Copyright (c) 2009-2012, AOmega.ru
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
    
    public function find_all_for_post( $post_id ){
        return $this->where( 'post_id', $post_id )->find();
    }

}