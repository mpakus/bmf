<?php

/**
 * Model for Text module
 */
class Text extends MY_Model{
    protected
        $table = DC_TEXTS_TABLE,
        $pkey  = 'module_id'
    ;
    
    public function __construct(){
        parent::__construct();
    }

	/**
 	 * Prepare the text fields and save them
     *
     * @param int $id
     * @return bool|int
	 */
	public function save( $data='' ){
        if( !empty($data[$this->pkey]) ) $this->delete( $data[$this->pkey] );
        $data['full']  = prepare_text( $data['original'] );
        $data['short'] = mb_strcut( /*strip_tags_regular($data['full'])*/$data['full'], 0, 250 ); // @todo: 250 should be in CONFIG        
		return parent::insert($data);
	}
 
}