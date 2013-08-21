<?php

class Auth_access {

	private $CI;
	private $user_info;
	private $config;
	private $error;

	function __construct() {
		$this->CI =& get_instance();
		$this->CI->load->config('auth_access');
		$this->CI->load->language('auth_access');

          $this->config = config_item('auth_access');
		
		$model_name = $this->config['model_name'];
		if (!empty($model_name))
			$this->CI->load->model($model_name,'auth_model',TRUE);
		
		$this->user_info = $this->_get_user_info();
		$this->error = '';
	}
	
	function login($username, $password) {
		if ($this->CI->auth_model->login($username, $password)) {
			$this->user_info = $this->CI->auth_model->get_user_info($username);
			$this->_store_user_info();
			return TRUE;
		}
		else {
			$this->error = 'Usuario o contrase&ntilde;a inv&aacute;lidos.';//$this->CI->lang->line('invalid_login');
			return FALSE;
		}
	}
	
	function logout() {
		$this->_remove_user_info();
		$this->CI->session->sess_destroy();
	}

	function is_logged_in() {
		return !empty($this->user_info);
	}
	
	function get_user_id() {
		return $this->is_logged_in() ? $this->user_info->{$this->config['user_id_field']} : NULL;
	}
	
	function get_user_info() {
		return $this->user_info;
	}
	
	function get_user_menu($user_id, $menu_items) {
		$isAdmin = 1;//$this->user_info->es_admin == 1;
		foreach ($menu_items as $menu_item) {
			if (isset($menu_item->only_admin) && $menu_item->only_admin && !$isAdmin)
				unset($menu_item);
			if (isset($menu_item->items) && is_array($menu_item)) {
				$menu_item->items = $this->get_user_menu($user_id, $menu_item->items);
			}
		}
		return $menu_items;
	}
	
	private function _store_user_info() {
		$this->CI->session->set_userdata('user_info',json_encode($this->user_info));
	}
	
	private function _get_user_info() {
		$data = $this->CI->session->userdata('user_info');
		return !empty($data) ? json_decode($data) : NULL;
	}
	
	private function _remove_user_info() {
		$this->CI->session->unset_userdata('user_info');
	}
	
	public function get_error_message() {
		return $this->error;
	}
	
	public function reset_password($username) {
		$this->CI->load->library('encrypt');
		
		$userinfo = $this->CI->auth_model->get_user_info($username);
		
		if (!empty($userinfo) && !empty($userinfo->{$this->config['email_field']})) {
			$hash = random_string('alnum', 8);
			$key = json_encode((object)array(
				'hash'=>$hash,
				'expires'=>time() + 15 * 24 * 60 * 60,
			));
			$encrypted_key = $this->CI->encrypt->encode($key);
			if ($this->CI->auth_model->reset_password($userinfo->id, $encrypted_key)) {
				
				$config = Array(
					'protocol' => 'smtp',
					'smtp_host' => 'smtp.sendgrid.net',
					'smtp_port' => 587,
					'smtp_user' => 'todoticket',
					'smtp_pass' => 'tickTick',
					'mailtype'  => 'html', 
					'charset'   => 'utf-8'
				);
				
				$this->CI->load->library('email', $config);
				$this->CI->email->set_newline("\r\n");
				$this->CI->email->from('envios@todoticket.me','Tihany');
				$this->CI->email->to($userinfo->{$this->config['email_field']});
				$this->CI->email->subject('Recuperacion de contrase�a - Sistema Tihany Inventario');
				$this->CI->email->message($this->CI->load->view('eml_auth_reset_pwd', array('user'=>$userinfo, 'hash'=>$hash, 'encrypted_key'=>$encrypted_key), TRUE));
				$this->CI->email->send();
				return TRUE;
			}
			else {
				return FALSE;
			}
		}
		
		else {
			return FALSE;
		}
	}
	
	public function get_unencrypted_reset($key) {
		$userinfo = $this->CI->auth_model->get_user_key($key);
		if (empty($userinfo)) {
			return FALSE;
		}
		$this->CI->load->library('encrypt');
		$key = json_decode($this->CI->encrypt->decode($key));
		$userinfo->key = $key;
		return $userinfo;
	}
	
	public function change_password($userid, $newpwd) {
		$userinfo = $this->CI->auth_model->get_user($userid);
		if (empty($userinfo)) {
			return FALSE;
		}
		$this->CI->auth_model->change_password($userid, $newpwd);
		return TRUE;
	}
}