<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Users model
 * 
 * @version
 * @access ALL
 * @author Ibragimov "MpaK" Renat <info@mrak7.com>
 * @copyright Copyright (c) 2009-2012, AOmega.ru
 */
class User extends MY_Model {
    protected
        $table          = DC_USERS_TABLE,
        $user           = array()
    ;

    public
        $error = '';    // код ошибки
    /**
     * 1 - не подошел логин или пароль
     * 2 - запись еще не активирована
     */

    public function __construct() {
        parent::__construct();
        $this->user = $this->session->userdata('user');
        
        $login    = $this->input->cookie('login');
        $password = $this->input->cookie('password');        
        if( !empty($login) AND !empty($password) AND empty($this->user) )
            if( !$this->try_login( $login, $password, TRUE ) ) $this->logout();            
    }

    /**
     * Проверим залогинился ли пользователь
     *
     * @return bool
     */
    public function is_logined() {
        return ( !empty($this->user['id']) ) ? TRUE : FALSE;
    }
    
    protected function make_password( $password, $salt ){
       return sha1( $salt . $password ); 
    }

    /**
     * Попробуем залогинется переданным логином и паролем
     * 
     * @param   string $login
     * @param   string $password
     * @return bool 
     */
    public function try_login( $login, $password, $not_crypted=TRUE, $remember=TRUE ) {
        
        if( $not_crypted ){
            $user = $this->where( 'login', $login )->find( NULL, 1 );
            if( empty($user) ){
                $this->error = 3;
                return FALSE;
            }
            if( $user['banned'] == 1){
                $this->error = 4;
                return FALSE;
            }
            // если уже и так шифрованно, то не надо
            $new_password = ( $not_crypted ) ? $this->make_password( $password, $user['salt'] ) : $password;            
            if( $user['password'] != $new_password ){
                $this->error = 1;
                return FALSE;
            }
        }
        
        $this->user = $user;
        $this->session->set_userdata( 'user', $user );
        log_message( 'debug', TextDump( $this->user) );
        
        if($remember){
            setcookie('login', $this->user['login'], time()+12*60*60*24*30, '/', '.'.$_SERVER['HTTP_HOST']);
            setcookie('password', $this->user['password'], time()+12*60*60*24*30, '/', '.'.$_SERVER['HTTP_HOST']);
        }        

        // обновим дату визита пользователя на текущую
        $this->db->set( 'logined_at', 'NOW()', FALSE )->where( 'id', $user['id'] )->update( $this->table );        
        return TRUE;
    }

    /**
     * Узнает правда ли у текущего пользователя такая роль
     *
     * @param   string $name имя роли
     * @return  bool
     */
    public function is_role( $name ){
        return ( $this->user['role']==strtolower($name) ) ? TRUE : FALSE;
    }

    /**
     * Получим все данные пользователя
     *
     * @return array
     */
    public function profile(){
        return $this->user;
    }

	/**
	 * Удалить данные сессии пользователя и выход
	 */
	 public function logout() {
		$this->session->unset_userdata('user');
		$this->session->sess_destroy();
        $this->user = array();
		setcookie('login', '', time()+12*60*60*24*30, '/', $_SERVER['HTTP_HOST']);     #@todo: domain
		setcookie('password', '', time()+12*60*60*24*30, '/', $_SERVER['HTTP_HOST']);
    }

	/**
	* Новая запись вставляется в таблицу
	*/
	public function insert( $user='' ){
		if( empty($user) ) return FALSE;
        unset( $user[$this->pkey] );
      
        if( !empty($user['birth_at']) ) $user['birth_at'] = transform_date( $user['birth_at'] );

        if( !empty($user['new_password']) ){
            srand( time() );
            $user['salt'] = substr( md5(rand(100, time())), 0, 12 );            
            $user['password'] = $this->make_password( $user['new_password'], $user['salt'] );
        }
        unset($user['new_password']);
        
        $user['registered_at']   = not_empty( transform_date($user['registered_at']), now2mysql() );

        $this->db->insert( $this->table, $user);
		return $this->db->insert_id();

    }

    /**
     * Update user profile
     * 
     * @param type $user
     * @return type 
     */
	public function update( $user='' ){
		if( empty($user) ) return FALSE;
        
        $pre_user = $this->find( $user[$this->pkey], 1 );

        if( !empty($user['birth_date']) ) $user['birth_date'] = transform_date( $user['birth_date'] );
        
        if( !empty($user['new_password']) ) $user['password'] = $this->make_password( $user['new_password'], $prev_user['salt'] );
        unset($user['new_password']);

		$this->db->where( $this->pkey, $user[$this->pkey] )->update( $this->table, $user );
		return TRUE;
	}

    /**
     * Удаляем аватар пользователя физически с диска 
     * и если надо обновляем его запись в базе
     *
     * @param int $id       пользователя
     * @param bool $update  надо ли обновлять запись в базе
     * @return bool
     */
    public function delete_pic( $id, $update=TRUE ){
        $data = $this->find( $id );
        if( !empty($data['avatar']) ){
            unlink( $this->avatars_dir.$data['avatar'] );
            if( $update )
                $this->db->update( $this->table, array('avatar'=>''), array('id'=>$id));
        }
        return TRUE;
    }

    /**
     * Проверка логина пользователья на уникальность
     *
     * @param  string $login
     * @return bool
     */
    public function is_uniq( $login, $id='' ){
        if( !empty($id) ){
            $where          = array($this->table.'.id !='=>$id);
            $where['login'] = $login;
            $user = $this->find_by( $where, 1 );
            return ( empty($user) ) ? TRUE : FALSE;
        }else{
            $where = array('login'=>$login);
            $user = $this->find_by( $where, 1 );
            return ( empty($user) ) ? TRUE : FALSE;
        }
    }

    /**
     * Активация пользователя по его id и code
     *
     * @param  int      $id
     * @param  string   $code
     * @return bool|array
     */
    public function activate( $id, $code ){
        $user = $this->find_by( array('id'=>$id, 'activate_code'=>$code), 1 );
        if( !empty($user) ){
            $this->db->where('id', $id);
            $this->db->update( $this->table, array('active'=>1));
            return $user;
        }

        return FALSE;
    }

        
}