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
					$this->data['lj_authorized'] = true;
				}	
			} 	

			$this->template->render_to( 'content', $this->view.'index', $this->data ); 	
			$this->draw();
		}

    }
?>
