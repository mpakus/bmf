<?php

class Text extends MY_Model{
    protected
        $table = DC_TEXTS_TABLE,
        $pkey  = 'module_id'
    ;
    
    public function __construct(){
        parent::__construct();
    }

	/**
 	 * Сохраняем данные, если нет id значит добавляем, иначе обновляем старое
     *
     * @param int $id
     * @return bool|int
	 */
	public function save( $data='' ){
        if( !empty($data[$this->pkey]) ) $this->delete( $data[$this->pkey] );                
		$this->insert($data);
	}
 
}