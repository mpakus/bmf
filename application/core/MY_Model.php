<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 *
 * Extended ActiveRecord model
 * 
 * @version 2.1
 * @author Ibragimov "MpaK" Renat <info@mrak7.com>
 * @copyright Copyright (c) 2009-2012 AOmega.ru
 */
class MY_Model extends CI_Model{

    protected
        $table          = '',       // table name in plural form
        $where          = array(),  // global WHERE
        $pkey           = 'id',     
        $select         = '',       // params for what we want to SELECT
        $join           = array(),
        $activerecord   = FALSE
    ;

    public
        $_data          = array(),

		$file_dir	=	'',			// путь к файлам
		$error		=	'',			// сообщение об ошибке
		$file_name	=	'',			// название последнего загруженного файла
		$file_size	=	''			// размер последнего загруженного файла
    ;



	public function __construct( $name='' ){
		parent::__construct();
        $this->set_table( $name );
		$this->file_dir = FCPATH.'files/';
	}

    public function create( ){
        $obj = clone( $this );
        $obj->activerecord = TRUE; // create object who is looks like ActiveRecord
        return $obj;
    }


	/**
	 * Инициализация по умолчанию при загрузке
	 *
	 * @param string $name	название таблицы
	 */
	public function set_table( $name='' ){
		if( empty($name) ) $name = get_class($this);
		if (empty($this->table) OR ($name=='MY_Model') )
            $this->table = plural( str_ireplace('_mod', '', $name ) );
	}
    
    /**
     * Установим условие select что надо выбрать при find запросах
     *
     * @param  string $select
     * @return MY_Model
     */
    public function select( $select ){
        $this->select = $select;
        return $this;
    }

    /**
     * Добавим join условие к нашей выборке
     *
     * @param  string $table
     * @param  string $on
     * @param  string $type
     * @return MY_Model
     */
    public function join( $table, $on, $type='LEFT' ){
        $this->join[] = array(
            'table'     => $table,
            'on'        => $on,
            'type'      => $type,
        );
        return $this;
    }

	/**
	* Инициализация таблиц
	*
	* @param    array|string название главной таблицы или массив параметров
	* @return   void
	*/
	public function init( $params = array() ){
        if( is_array($params) ){
            foreach( $params as $key=>$value ){
                $this->$key = $value;
            }
        }else{
            $this->table = $params;
        }
        return $this;
	}
    public function initialize( $params = array() ){ return $this->init( $params ); }

    /**
     * Установим связь текущей модели с глобальным where для вызовов
     * @param  array $where    условия where например ('block_id' => 10)
     * @return object Model
     */
    public function set_where( $where ){ $this->where = $where; return $this; }

    /**
     * Обработка глобального where
     * @return object Model
     */
    protected function  operate_where(){
        if( !empty($this->where) ) $this->where( $this->where );
        return $this;
    }

    /**
     * Обертка над DB для возврата нас потом
     */
    public function order_by( $field, $cond='DESC' ){
        $this->db->order_by( $field, $cond );
        return $this;
    }

	/**
	* Выбор всего или одного элемента
	*
	* @param	integer		$id	уникальный номер менеджера
	* @param	integer		$from	от
	* @param	integer		$limit	кол-во
	* @return	array
	*/
	public function find( $id=NULL, $limit=NULL, $from=NULL ){
		$data = array();

        // если есть что определенное выбрать
        if( !empty($this->select) ) $this->db->select( $this->table );

        // если есть наши определенные join'ы
        if( !empty($this->join) AND is_array($this->join) )
            foreach( $this->join as $j ) $this->db->join( $j['table'], $j['on'], $j['type'] );

		if( !empty($id) ){
			$this->db->where( 't.'.$this->pkey, $id );
			$from	= 0;
			$limit	= 1;
		}
        $this->operate_where(); // обработаем глобальный where

  		$query  = $this->db->get( $this->table.' AS t', $limit, $from );
        $data   = $this->result( $query, $limit );
        if( $this->activerecord ){
            $this->_data = $data;
            return $this;
        }else
            return $data;
	}

    /**
     * Поиск в определенной таблице
     *
     * @param string $table
     * @param int $id
     * @param int $limit
     * @param int $from
     * @return array
     */
    public function find_in( $table, $id='', $limit=NULL, $from=NULL ){
        $save_table     = $this->table;
        $this->table    = $table;
        $data           = $this->find( $id, $limit, $from );
        $this->table    = $save_table;
        return $data;
    }

	/**
	* Выбор всего по условиям
	*
	* @param	array	$where условия выборки
	* @param	string	$limit кол-во
	* @param	string	$from  с каково
	* @return	array
	*/
	public function find_by( $where='', $limit=NULL, $from=NULL ){
        if( !empty($where) ) $this->db->where( $where );
        return $this->find( '', $limit, $from );
	}

    /**
     * Цепочка условий AND WHERE перед поиском
     *
     * @param array $where
     * @return object
     */
    public function where( $where='', $value='' ){
        if( !empty($where) ){
            if( is_array($where) ) {
                $this->db->where( $where );
            }else{
                $this->db->where( $where, $value );
            }
        }
        return $this;
    }

    /**
     * Цепочка условий  OR WHERE перед поиском
     *
     * @param array $where
     * @return object
     */
    public function or_where( $where='' ){
        if( !empty($where) ) $this->db->or_where( $where );
        return $this;
    }

    /**
     * Получим результаты запроса
     *
     * @param  object $query
     * @return array
     */
    public function result( &$query, $limit='' ){
        $data = array();
        
		if( is_object($query) AND ($query->num_rows() >= 0) ){
            if( $limit==1 ){
				$data = $query->row_array();
            }else{
                foreach( $query->result_array() as $row){
                    $data[] = $row;
                }
            }
		}
		return $data;
    }

	/**
	 * Удалим материал из таблицы
     *
     * @param int $id
     * @return bool
	 */
	public function delete( $id='' ){
        if( !$this->activerecord AND empty($id) )
            if( empty($this->where) ) return FALSE;
        else
            $id = $this->_data[ $this->pkey ];

        $this->operate_where();
        if( !empty($id) ) $this->db->where( array($this->pkey => $id) );
        return $this->db->delete( $this->table );
	}


    /**
     * Вернём всего кол-во записей в базе
     *
     * @param array $where  еще условие помимо глобального where
     * @param string $table может таблица не текущая
     * @return integer  кол-во записей
     */
    public function count( $where='', $table='' ){
        $table = not_empty( $table, $this->table );
        if( !empty($where) ) $this->db->where( $where );
        $this->operate_where(); // обработаем глобальный where

        $res = $this->result( $this->db->select( 'COUNT(*) AS count', FALSE )->get( $table.' AS t', 1 ) , 1 );
        return not_empty($res['count'], 0);
    }

	/**
 	 * Сохраняем данные, если нет id значит добавляем, иначе обновляем старое
     *
     * @param int $id
     * @return bool|int
	 */
	public function save( $data='' ){
        if( $this->activerecord AND empty($data) ) $data = $this->_data;
		return (empty($data[$this->pkey])) ? $this->insert($data) : $this->update($data);
	}

    /**
	 * Обновляется старая запись в таблице
     *
     * @param  array $data
     * @return bool
	 */
	public function update( $data='' ){
		if( empty($data) )
	        if( $this->activerecord ) $data = $this->_data; else return FALSE;

        $id = (integer) $data[$this->pkey];
		unset( $data[$this->pkey] );

        $this->db->where( $this->pkey, $id )->update( $this->table, $data );
        log_message( 'debug', 'update: '.$this->db->last_query() );
		return $id;
	}

    /**
	 * Вставить новую запись в таблицу
     *
     * @param  array $data
     * @return integer
	 */
    public function insert( $data='' ){
		if( empty($data) )
	        if( $this->activerecord ) $data = $this->_data; else return FALSE;

        $this->db->insert( $this->table, $data);
        log_message( 'debug', 'insert: '.$this->db->last_query() );
   		return $this->db->insert_id();
    }

    /**
     * Магия RoR
     *
     * @param   integer $name
     * @param   array $arguments
     * @return  void
     */
    public function  __call($name,  $arguments) {
        $find_by_pos = stripos($name, 'find_by');
        if(  $find_by_pos !== FALSE ){
            // find_by_login_and_email_or_group_id
            $name = substr( $name, 8 );
            return $this->find_by( array($name=>$arguments[0]), $arguments[1], $arguments[2]);
        }else{
            if( method_exists( $this->db, $name ) ) return $this->db->$name( $arguments );
            //throw new Exception( 'method not exits' );
        }
    }

	/**
	 * Загрузка файла
	 *
	 * @param string $file_post
	 * @param string $types
	 * @return MY_Model
	 */
    public function upload( $file_post, $param = array() ){
		if( empty($param['allowed_types']) ) $param['allowed_types'] = 'jpg|gif|png|rar|zip|xls|doc|rtf|txt|odf|txt|7z';

		// проверим если нет нашего каталога, то создадим его
		if( !file_exists( $this->file_dir) ) @mkdir( $this->file_dir );
		// параметры по умолчанию
        $config['upload_path']		= $this->file_dir;
        $config['max_size']			= 0;
        #$config['overwrite']		= TRUE;
		$config['encrypt_name']		= TRUE;
		// если что перетрём нашими параметрами
		foreach( $param as $k=>$v ){
			if( empty($k) ) continue;
			$config[$k] = $v;
		}
        $this->load->library('upload', $config);
        $this->upload->initialize($config); 

        if( $this->upload->do_upload($file_post) ){
            $uploaded		 = $this->upload->data();
			$this->file_name = $uploaded['file_name'];
			$this->file_size = $uploaded['file_size'];
			return $this;
        }else{
			$this->error = $this->upload->display_errors();
            throw new Exception( $this->error );
        }
    }

	/**
	 * Уменьшаем изображение, режем, создаем превьюшки
	 *
	 * @param array $param	список параметров width, height, adaptive, thumb
	 * @return MY_Model
	 */
	public function resize_image( $param ){
		try {
			$this->load->library('thumb_lib');
			$thumb = $this->thumb_lib->create( $this->file_dir.$this->file_name );
			if( $param['adaptive'] ){
				$thumb->adaptiveResize( $param['width'], $param['height'] );
			}else{
				$thumb->resize( $param['width'], $param['height'] );
			}
			if( $param['thumb'] ){
				if( !file_exists($this->file_dir.'mini/') ) @mkdir( $this->file_dir.'mini/' );
				$thumb->save( $this->file_dir.'mini/'.$this->file_name );
			}else{
				$thumb->save( $this->file_dir.$this->file_name );
			}
			return $this;
		}catch (Exception $e) {
			$this->error = $e->getMessage();
			throw new Exception( $this->error );
		}
	}


	/**
	 * Удаляет физически старый файл из документа
	 *
	 * @param string $field		название поля в базе с файлом
	 * @param integer $id		id документа в базе
	 */
	public function delete_file( $field, $id ){
		$data = $this->find( $id, 1 );
		@unlink( $this->file_dir.$data[$field] );
		@unlink( $this->file_dir.'mini/'.$data[$field] );
	}
    
}
