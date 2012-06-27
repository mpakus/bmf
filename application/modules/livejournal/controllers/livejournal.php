<?php
    class LivejournalController extends MY_Controller{
     
     	protected
     		$view = 'livejournal/'
    	;

    	public function _construct(){
			parent::_construct();
			user_can_rule();
		}

		public function index() {

			$this->load->library('form_validation');

			if( $_POST ) {
				
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

				if ($this->form_validation->run($this) === FALSE) {
					
				} else {

					if(user_signed_in()) {
						//Loading LJ_Reader library
						$params = array(
									  'username' => $_POST['login'],	//LJ login
									  'password' => $_POST['password'],	//LJ password
									  'prefetch' => false, 				//We want to fetch posts manually
								  	  'nlast'	 => 10					//Fetch 10 last records
								  );
						$this->load->library('LJ_Reader', $params);

						//Loading Post model
						$this->load->model('post');

						//fetching posts
						$lj_posts = $this->lj_reader->fetch_posts();

						foreach($lj_posts as $lj_post) {
							var_dump($lj_post);
						}
					} else {
						echo 'You must be a BMF-authorised!';
					}

					$this->data['lj_authorized'] = true;
				}	
			} 	

			$this->template->render_to( 'content', $this->view.'index', $this->data ); 	
			$this->draw();
		}

    }
?>
