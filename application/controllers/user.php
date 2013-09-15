<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 *
 * USER's controller
 *
 * @version 1.0
 * @author Ibragimov "MpaK" Renat <info@mrak7.com>
 * @copyright Copyright (c) 2009-2013, AOmega.ru
 */
class UserController extends MY_Controller {
    protected
        $view     = 'user/',
        $settings = array()
    ;

	public function __construct(){
        parent::__construct( 'user' );
        $this->load->model( 'user' );
        $this->user->file_dir = $this->settings['avatars_dir'];

	}

    /**
     * Shows user profile
     */
    public function profile( $login='' ){
        if( empty($login) ){
            set_flash_error( 'Пустой ник пользователя, нам нечего вам показать' );
            redirect();
        }
        // find user with this login
        $this->data['user'] = $this->user->where('login', $login)->find( NULL, 1 );
        if( empty($this->data['user']) ){
            set_flash_error( 'У нас нет человека с таким ником, возможно вы ошиблись' );
            redirect();
        }
        
        $this->load->library('form_validation');
        if( $_POST ){
            $config = array(
                // @todo: add uniq validation to LOGIN
               // array(
               //       'field'   => 'email',
               //       'label'   => 'E-mail',
               //       'rules'   => 'required|max_length[128]|valid_email|trim'
               // ),
               array(
                     'field'   => 'email',
                     'label'   => 'E-mail',
                     'rules'   => 'required|max_length[128]|valid_email|trim'
               ),
            );
            $this->form_validation->set_rules($config);

            if( $this->form_validation->run($this) === FALSE ){
                $this->profile_form();
            }else{
                if( !(is_current_user($this->data['user'])  OR user_is('admin')) ){
                    show_error( 'Проблемы с доступом?', 500 );
                    return;
                }
                $new_user = array(
                    'id'           => $this->data['user']['id'],
                    'email'        => param('email'),
                    'new_password' => param('new_password')
                );
                if( !empty($_FILES['avatar']['name']) ){
                    try{
                        if( !empty($this->data['user']['avatar']) ) $this->user->delete_file( 'avatar', $this->data['user']['id'] );
                        
                        $this->user->upload('avatar')->resize_image( array('width' => $this->settings['avatar_width'], 'height' => $this->settings['avatar_height'], 'adaptive'=>TRUE) );
                        $new_user['avatar'] = $this->user->file_name;                        
                    }catch( Exception $e ){
                        set_flash_error( $e->getMessage() );
                        redirect_path( 'user/profile/'.$this->data['user']['login'] );
                        return;
                    }
                }
                $this->user->save( $new_user );
                set_flash_ok( 'Ваши изменения в профиле сохранены' );
                redirect( 'user/profile/'.$this->data['user']['login'] );
            }
        }else{
            $this->profile_form();
        }
        $this->draw();
    }
    
    /**
     * Shows profile form, usual and edit
     */
    protected function profile_form(){
        $this->load->model( array('post','comment') );
        $this->data['user']['posts_count']    = $this->post->count_for_user( $this->data['user']['id'] );
        $this->data['user']['comments_count'] = $this->comment->count_for_user( $this->data['user']['id'] );
        $this->template->render_to( 'content', $this->view.'profile', $this->data );        
    }
    
    // Покажем список пользователей
	public function index(){
        $this->data['users'] = $this->user->find();
        $this->template->render_to( 'contnet', $this->view.'index', $this->data );
        $this->draw();
	}

    /**
     * Показать просто форму входа и если вошел то форму пользователя
     */
    public function login(){
        if( $_POST ){
            $this->try_login();
        }else{
            if( $this->user->is_logined() ){
                set_flash_ok( 'Вы уже вошли под своим аккаунтом' );
                redirect();
                //$this->template->render_to( 'content', $this->view.'logined_form', $this->data );
            }else{
                $this->template->render_to( 'content', $this->view.'login', $this->data );
                $this->draw();
            }
        }
    }


    /**
     * Показать форму регистрации
     * и при случае заполнения зарегистрировать нового пользователя
     *
     * @return void
     */
    public function register(){
        exit; // not yet
        $data = array();

        $this->load->helper('captcha');
        $random = md5( mt_rand( time()-60000, time() ) );
        $thrash = array('o', 0, 'i', 8, 'B', 9, 'G', 0);
        $random = str_ireplace($thrash, '', $random);
        
        $vals = array(
            'word'		 => substr( $random, 0, 4 ),
            'img_path'	 => './captcha/',
            'img_url'	 => '/captcha/',
            'img_width'	 => 120,
            'img_height' => 40,
            'expiration' => 7200,
        );
#            'font_path'	 => BASEPATH.'fonts/ARIALN.TTF',

    	$cap = create_captcha($vals);
        $data['captcha'] = $cap;

        // получили параметры пользователя возможно из запроса или же остались что были
        $params = array( 'id', 'login', 'new_password', 'email', );
        $user = params( $params );

        $data['data']  = $user;

        $this->load->library('form_validation');

        $config = array(
           array(
                 'field'   => 'login',
                 'label'   => 'Логин',
                 'rules'   => 'required|trim|alpha_numeric|callback_login_check'
           ),
           array(
                 'field'   => 'new_password',
                 'label'   => 'Пароль',
                 'rules'   => 'required|trim|min_length[6]'
           ),
           array(
                 'field'   => 'email',
                 'label'   => 'E-mail',
                 'rules'   => 'required|max_length[128]|valid_email|trim'
           ),
        );
        $this->form_validation->set_rules($config);

        if( $this->form_validation->run($this) === FALSE ){
            $this->template->render_to( 'content', $this->view.'register', $data );
        }else{
            // регистрация
            // если есть аватар попробуем его загрузить и показать
            if( !empty($_FILES['avatar']['name']) ){
                // если есть изображение пробуем загрузить, откорректировать и сделать превью
                try{
//                    if( !empty($user['image']) ) $this->user->delete_file( 'avatar', $user['id'] );
                    $this->user->upload('avatar')->resize_image( array('width' => $this->settings['avatar_width'], 'height' => $this->settings['avatar_height'], 'adaptive'=>TRUE) );
                    $user['avatar'] = $this->user->file_name;
                    // удалим старое + превью
                }catch( Exception $e ){
                    set_flash_error( $e->getMessage() );
                    redirect_path( 'user/register' );
                    return;
                }
            }
            
//            $user['activate_code'] = sha1( mt_rand( time()-666000, time()) );
            $user['id'] = $this->user->save( $user );
/*
            $this->load->library('email');
            $s = &$this->settings;

            $this->email->from( $s['email'], $s['name'] );
            $this->email->to( $user['email'] );

            $this->email->subject('Регистрация на сайте '.$s['name']);
            $this->email->message("
Спасибо вам за регистрацию, дорогой {$user['fio']}!

Вы можете подтвердить своё желание зарегистироваться пройдя по ссылке
http://{$s['url']}/user/action.activate.id.{$user['id']}.code.{$user['activate_code']}

            ");

            $this->email->send();
*/
            set_flash_ok( 'Пользователь успешно зарегистрирован, активируйте его' );
            redirect( 'user/index' );
        }
        $this->draw();
    }

    /**
     * Проверка логина на уникальность
     *
     * @param bool
     */
    public function login_check( $login ){
        if( $this->user->is_uniq($login, param('id')) ){
            return TRUE;
        }else{
            $this->form_validation->set_message('login_check', 'Извините пользователь с таким логином уже зарегистрирован');
            return FALSE;
        }
    }

    /**
     * Войти по логину и паролю, с проверкой активации
     */
    protected function try_login(){
        $login      = param('login');
        $password   = param('password');
        if( !empty($login) AND !empty($password) ){
            if( $this->user->try_login( $login, $password ) ){                
                set_flash_ok( 'Вы вошли в свой кабинет' );
                redirect();
            }else{
                if( $this->user->error == 2 ){
                    set_flash_error( 'Ваша учетная запись еще не активирована' );
                }elseif( $this->user->error == 4 ){
                    set_flash_error( 'Видимо вы плохо себя вели и администратор вас забанил. Селя ви!' );
                }else{
                    set_flash_error( 'Логин или пароль не верный, проверьте и попробуйте еще раз' );
                }
                redirect( 'user/login' );
            }
        }
        set_flash_error( 'Вы не указали логин или свой пароль' );
        redirect( 'user/login' );
    }

    /**
     * Выход пользователя из своего кабинета
     */
    public function logout(){
        $this->user->logout();
        set_flash_ok( 'Вы вышли из своего кабинета' );
        redirect();
    }

    /**
     *  Форма напомнить пароль
     *
     * @return string
     */
    public function remember(){
        $this->template->render_to( 'content', $this->view.'remember', $this->data );
        $this->draw();
    }

    /**
     *  Активация пользователя по его коду ак-ии
     *
     */
    public function activate(){
       /*
        * http://projectrf/user/action.activate.id.10.code.247a506f88696a5455b38ae7a85a25a5d775f169
        */
        $code   = param('code');
        $id     = param('id');

        if( empty($code) OR empty($id) ){
            set_flash_error( 'Извините, указан пустой код активации или id пользователя' );
            redirect();
        }

        $user = $this->user->activate($id, $code);

        if( $user === FALSE ){
            set_flash_error( 'Извините, активировать данного пользователя не удалось' );
            redirect();
        }

        if( $this->user->try_login( $user['login'], $user['password'], FALSE ) ){
            set_flash_ok( 'Приветствуем, вас '.$user['fio'] );
            redirect('cabinet');
        }else{
            set_flash_error( 'Извините, но автоматом войти не удалось, попробуйте вручную' );
            redirect();
        }
    }
    
    public function ban(){
        $user = $this->get_admin_user_params();
        $ban = array('id'=>$user['id'], 'banned'=>1);
        $this->user->save( $ban );
        set_flash_ok( 'Yes! Пользователь забанен' );
        redirect( 'user/profile/'.$user['login'] );
    }
    
    public function unban(){
        $user = $this->get_admin_user_params();
        $ban = array('id'=>$user['id'], 'banned'=>0);
        $this->user->save( $ban );
        set_flash_ok( 'Пользователь разбанен :(' );
        redirect( 'user/profile/'.$user['login'] );
    }
    
    protected function get_admin_user_params(){
        if( !user_is('admin') ){
            show_error( 'Что-то нет прав? Не получается, да?', 500 );
            exit;
        }
        $user_id = param('user_id');
        if( empty($user_id) ){
            set_flash_error( 'Нет id нужного пользователя' );
            redirect();
        }
        $user = $this->user->find( $user_id, 1 );
        if( empty($user) ){
            set_flash_error( 'Такого пользователя у нас нет' );
        }
        return $user;
    }
        
}