<?php

class Auth extends MY_Controller {

	function __construct() {
		$this->auth_required = FALSE;
		parent::__construct();
		
		$this->load->library('form_validation');
		$this->load->helper('form');
		$this->data_parts['allow_forgot_password'] = TRUE;
	}
	
	function login() {
		if ($this->auth_access->is_logged_in())
			redirect();
		else {			
			$this->form_validation->set_rules('username', 'Usuario', 'trim|required|xss_clean');
			$this->form_validation->set_rules('password', 'Contrase&ntilde;a', 'trim|required|xss_clean');
			$this->form_validation->set_error_delimiters('','');
			
			if ($this->form_validation->run()) {
				if ($this->auth_access->login($this->input->post('username'), $this->input->post('password'))) {
					$return_url = $this->session->userdata('return_url');
					if (!empty($return_url))
						redirect($return_url);
					else
						redirect();
				}
				else {
					$this->_add_error('error', $this->auth_access->get_error_message());
				}
			}
			
			$this->data_parts['selected'] = $this->module_name;
			$this->_show_header();
			
			$this->load->view('login_form');
			
			$this->_show_footer();
		}
	}
	
	function logout() {
		if ($this->auth_access->is_logged_in()) {
			$this->auth_access->logout();
			redirect('auth/login');
		}
		else redirect();
	}
	
	function forgot_password() {
		
		$this->form_validation->set_rules('username', 'Usuario', 'trim|required');
		if ($this->form_validation->run() !== FALSE) {
			if ($this->auth_access->reset_password($this->input->post('username'))) {
				$this->_add_error('info', 'Se envio un mensaje a su cuenta de correo registrada en el sistema con instrucciones para resetear su clave.');
				$this->_store_errors();
				redirect('auth/login');
				return;
			}
			else {
				$this->form_validation->set_error('username', 'El usuario no existe');
			}
		}
		else {
			$username = $this->input->post('username');
			if (!empty($username))
			$this->_add_error('error','Hubo algunos errores de validacion');
		}
		
		$this->_show_header();
		$this->load->view('forgot_password', $this->data_parts);
		$this->_show_footer();
	}
	
	function reset_password() {
		$key = $this->input->get_post('key', TRUE);
		if (!empty($key)) {
			$reset_data = $this->auth_access->get_unencrypted_reset($key);
			if (empty($reset_data) || $reset_data->key->expires < time()) {
				$key = FALSE;
			}
			else {
				$this->data_parts['key'] = $key;
			}
		}
		
		if (empty($key)) {
			$this->_add_error('error','La URL es invalida o ya expiro');
			$this->_store_errors();
			redirect('auth/login');
			return;
		}
		$data = $this->input->post('data');
		
		if (!empty($data)) {
			$this->form_validation->set_rules('data[clave]', 'Clave', 'trim|required|exact_length[8]|alpha_numeric');
			$this->form_validation->set_rules('data[password]', 'Clave nueva', 'trim|required|min_length[5]|max_length[10]|alpha_dash');
			$this->form_validation->set_rules('data[passconf]', 'Repetir clave nueva', 'trim|required|min_length[5]|max_length[10]|alpha_dash');
			if ($this->form_validation->run() == FALSE) {
				$this->_add_error('error','Hubieron algunos errores de validacion');
			}
			else {
				$valid_form = TRUE;
				if ($data['clave'] != $reset_data->key->hash) {
					$this->form_validation->set_error('data[clave]', 'La clave es invalida');
					$valid_form = FALSE;
				}
				if ($data['password'] != $data['passconf']) {
					$this->form_validation->set_error('data[password]', 'Las contrase&ntilde;as no coinciden');
					$valid_form = FALSE;
				}
				elseif ($this->auth_access->change_password($reset_data->id, $data['password'])) {
					$this->_add_error('info', 'La contrase&ntilde;a fue actualizada.');
					$this->_store_errors();
					redirect('auth/login');
					return;
				}
				else {
					$this->form_validation->set_error('username', 'El usuario o correo no existen');
					$valid_form = FALSE;
				}
				if (!$valid_form) {
					$this->_add_error('error','Hubieron algunos errores de validacion');
				}
			}
			$this->data_parts['data'] = $data;
		}
		else {
			$this->data_parts['data'] = array('clave'=>NULL,'password'=>NULL,'passconf'=>NULL);
		}
		
		$this->_show_header();
		$this->load->view('reset_password', $this->data_parts);
		$this->_show_footer();
	}
	
}