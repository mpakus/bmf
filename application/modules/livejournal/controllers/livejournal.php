<?php
    class LivejournalController extends MY_Controller {
     
     	protected
     		$view = 'livejournal/'
    	;

        /**
         * Constructor 
         */
    	public function _construct()
    	{
			parent::_construct();
			user_can_rule();
	}

        /**
         * Index default method. Main LJ import logic implements here. 
         */
	public function index() 
	{
			//Loading necessary libs and models
			$this->load->library( array('form_validation','session') );
			$this->load->model( array('post','tag','module') );

			//N last posts from LJ
			$nlast = 10;
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
							'nlast'	 => $nlast                                      //Fetch N last records
				);
                                
				$this->load->library('LJ_Reader', $params);

				//fetching posts
				$lj_posts = $this->lj_reader->fetch_posts('', $beforedate);

				foreach($lj_posts as $lj_post) {

					$this->create_post(
							$user['id'],
							$lj_post['subject'], 
							$lj_post['event']
					);

					$nposts++; 

                                        $beforedate = $lj_post['eventtime'];
				}
                                
                                if($nposts == $nlast) {
                                        
                                        $this->session->set_userdata(array(
                                            'beforedate' => $beforedate,
                                            'ntotal' => ($this->session->userdata('ntotal') + $nposts)
                                        ));
                                     
					redirect(current_url());
                                        
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
                                                        
                                                        redirect(current_url());
						                                                        
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
                }

    }
?>
