<?php
    class LivejournalController extends MY_Module {
     
     	protected
     		$view = 'livejournal/'
    	;

        /**
         * Constructor 
         */
    	public function __construct()
    	{
			parent::__construct( 'livejournal' );
                        user_can_rule();
	}

        /**
         * Index default method. Main LJ import logic implements here. 
         */
	public function index() 
	{
			//Loading necessary libs and models
			$this->load->library( array('form_validation','session') );
			$this->load->model( array('post','tag','module','user','comment') );

                        //N last posts from LJ
			$nlast = $this->settings['nlast'];
                        //Import comments
                        $comments = $this->settings['comments'];
			//Before date
			$beforedate = '';
			//Posts count
			$nposts = 0;

			//If user has not logged in - redirect him at login page	
			$user = current_user();
                        if( $user['id'] == '') {
                            set_flash_error('Прежде чем импортировать вам надо войти на сайт');
                            redirect( 'user/login' );
                        }

			//If user have already sent login & password ...
			//... do import posts from LJ
			if($this->session->userdata('lj_cookie') != '') {

				$this->data['lj_authorized'] = true;
				$beforedate = $this->session->userdata('beforedate');

				//Check if user has logged in
				$user = current_user();
					
				//Loading LJ_Reader library
				$params = array(
							'cookie' => $this->session->userdata('lj_cookie'),	//LJ cookie
							'username' => $this->session->userdata('lj_user'),      //LJ user
                                                        'prefetch' => false,                                    //We want to fetch posts manually
							'nlast'	 => $nlast,                                     //Fetch N last records
                                                        'comments' => $comments                                 //Import comments (TRUE|FALSE)
				);
                                
				$this->load->library('LJ_Reader', $params);

				//fetching posts
				$lj_posts = $this->lj_reader->fetch_posts('', $beforedate);

				foreach($lj_posts as $lj_post) {

                                        //Creating the post
					$post_id = $this->create_post(
							$user['id'],
							$lj_post['subject'], 
							$lj_post['event']
					);
                                        
                                        //Adding the comments
                                        if(!empty($lj_post['comments']))
                                            foreach($lj_post['comments'] as $comment)
                                                $this->add_comment($post_id, $comment);
                                        
                                        //Incrementing current posts count and saving beforedate param
					$nposts++; 
                                        $beforedate = $lj_post['eventtime'];
				}
                                
                                if($nposts == $nlast) {
                                        
                                        $this->session->set_userdata(array(
                                            'beforedate' => $beforedate,
                                            'ntotal' => ($this->session->userdata('ntotal') + $nposts)
                                        ));
                                     
					$this->data['redirect'] = true; //redirect(current_url());
                                        
				} else {
					
                                        $this->data['import_complete'] = 'Импорт постов из жж завершен успешно. Добавлено записей : ' . ($this->session->userdata('ntotal') + $nposts);
                                        $this->session->unset_userdata(array(
                                                'lj_cookie' => '',
                                                'lj_user' => '',
                                                'beforedate' => '',
                                                'ntotal' => ''
                                        ));
				} 

			//If user just entered user data - validate it and redirect with lj_authorized=true		
			} elseif( $_POST ) {

						$config = array(
								            array(
								                'field' => 'login',
								                'label' => 'Имя пользователя',
								                'rules' => 'required|trim|max_length[15]'
								            ),
								            array(
								                'field' => 'password',
								                'label' => 'Пароль',
								                'rules' => 'required|trim|min_length[1]|max_length[255]'
								            )
						);

						$this->form_validation->set_rules($config);

                                                // If form has been validated - do lj authorise and getting cookie to the session
						if ($this->form_validation->run($this) === TRUE) {
                                                        $params = array(
                                                            'username' => $_POST['login'],			//LJ login
                                                            'password' => $_POST['password'],			//LJ password
                                                            'prefetch' => false,  				//We want to fetch posts manually                                                            
                                                            'nlast' => $nlast
                                                        );
                                                        $this->load->library('LJ_Reader', $params);
                                                        
                                                        if( $this->lj_reader->get_cookie() != '')    
                                        			$this->data['lj_authorized'] = true;
                                                        
                                                        //Put cookie to the session
                                                        $this->session->set_userdata(array(
                                                            'lj_cookie' => $this->lj_reader->get_cookie(),
                                                            'lj_user' => $_POST['login'],
                                                            'ntotal' => 0
                                                        ));
                                                        
                                                        $this->data['redirect'] = true; //redirect(current_url());
						                                                        
                                                } else {
							$this->data['lj_authorized'] = false;
						}	
				
				
			} 	

			$this->template->render_to( 'content', $this->view.'index', $this->data ); 	
			$this->draw();
		}

		/**
		* Method implements post create
                * 
                * @param string $i_userid BMF user ID
                * @param string $i_title Post title (subject)
                * @param string $i_content Post contents
		*/
		private function create_post($i_userid, $i_title, $i_content) 
		{
		
			//Creating the post
			$post = array(
                            'title'   => $i_title,
                            'tags'    => 'lj import',
                            'user_id' => $i_userid
                        );

                        $post_id = $this->post->save( $post );
                        // if( !post_$id ) значи бяда какая-то, надо писатьнуть в лог запись или отдельный файл неудач
                        // чтобы потом заимпортировать
                        $this->template->set( 'content', "Создали новый пост (импорт из ЖЖ) с post_id: {$post_id}<br/>" );

                        // 2. добавляем модуль к посту ----------------
                        // теперь с постом свяжем наш модуль TEXT
                        $module = array(
                            'post_id' => $post_id,
                            'name'    => 'text'
                        );
                        $module_id = $this->module->add_new( $module );
                        $this->template->append( 'content', "Добавили модуль TEXT к нашему посту с module_id: {$module_id}<br/>" );

                        // 3. заполняем модуль контентом ----------------
                        // теперь засунем наполнение этого модуля text
                        $this->load->model('text/text'); // загрузим модель text модуля text (модуль/модель точнее)
                        $text =  array(
                            'module_id' => $module_id,
                            'original'  => $i_content
                        );
                        $this->text->save( $text ); // модель text не возвращает text_id так как у нее Primary Key это module_id в коде видно замену
                        $this->template->append( 'content', "Заполнили модуль TEXT новым текстом<br/>" );

                        // 4. сборка и публикация нашего поста -----------------
                        // сбора мы используем HMVC чтобы из этого контроллера вызвать Post с публичным методом make( $post_id ) для сборки
                        $post = Modules::run( 'post/make', $post_id );            
                        $post['published'] = 1; // флаг опубликованности
                        $this->post->save( $post ); // и сохранили
                        $this->template->append( 'content', "Топик собран и опубликован <a href='/blog/show/{$post_id}' target='_blank'>здесь</a><br/>" );
                
                        return $post_id;
                        
                }
                
                /**
		* Method implements comments import
                * 
                * @param number $i_post_id BMF post ID
                * @param array $i_comments Post title (subject)
                */
                private function add_comment($i_post_id, $i_comment, $i_parent_id = 0) 
                {
                    
                    //We should register the comment author in BMF
                    //(If he haven't registered yet, else we should get his ID)
                    if($i_comment['postername'] != '')
                        $user_id = $this->get_user_id($i_comment['postername']);
                    else
                        $user_id = $this->get_user_id('anonymous');
                    
                    //Add comment
                    $comment = array(
                        'post_id' => $i_post_id,
                        'parent_id' => $i_parent_id,
                        'text' => prepare_text($i_comment['body']),
                        'user_id' => $user_id,
                        'added_at' => now2mysql()
                    );

                    $comment_id = $this->comment->save($comment);

                    //Recursive search GO!
                    if(!empty($i_comment['children'])) {
                        foreach($i_comment['children'] as $child) 
                            $this->add_comment($i_post_id, $child, $comment_id);
                        
                    }
                    
                }
                
                /**
		* Method implements getter for user_id or new user registration
                * 
                * @param string $i_user LJ username
                */
                private function get_user_id($i_user)
                {
                    
                    //Check if LJ user is already in BMF DB
                    $user_info = $this->user->where('login', $i_user)->find( NULL, 1 );
                    
                    //If user does not exist in BMF DB - we should create a new user
                    if(empty($user_info)) {
                        
                        $new_user = array( 
                            'id' => '', 
                            'login' => $i_user, 
                            'new_password' => 'livejournal', 
                            'email' => $i_user . '@livejournal.com',
                            'provider' => 'livejournal'
                        );
                        
                        $user_id = $this->user->save( $new_user );
                        
                        return $user_id;
                        
                    } else {
                        
                        return $user_info['id'];
                        
                    }
                           
                }

    }
?>
