<?php

require APPPATH."third_party/MX/Controller.php";

/*
Controlador base, carga las librerias requeridas y hace funciones
basicas para los demas controladores
*/

class MY_Controller extends MX_Controller {
	// Lleva el ID del usuario logueado
	public $user_id = NULL;
	public $user_info = NULL;
	
	// Especifica el modulo (HMVC) que se utilizara
	public $module_name = '';
	
	// Especifica el nombre del controlador
	public $controller_name = FALSE;
	
	// Contiene informacion base que se transmitira a las vistas
	public $data_parts = array(
            'title'=>'App Igniter',
            'user_info'=>NULL,
            'menu'=>array(),
            'js_files'=>array(),
            'js_scripts'=>array(),
            'css_files'=>array(),
            'selected'=>'',
            'errors'=>array(),
       );
				
	// Contiene el objeto general de CI
	protected $CI;
	
	protected $auth_required = FALSE;
	
	function __construct($module_name = NULL) {
		parent::__construct();
		
		$this->load->library('auth_access');
		$this->load->helper('language');
		
		$this->CI =& get_instance();
		//$this->CI->load->database();
		
		// Load language file
		$this->lang->load('auth_access');
		
		if ($this->auth_required && !$this->auth_access->is_logged_in()) {
			$this->session->set_userdata('redirect_after_login', current_url());
			redirect('auth/login');
			return;
		}

		$this->module_name = $module_name;
		$this->controller_name = str_replace('_controller','',strtolower(get_class($this)));
		
		$this->CI =& get_instance();
		$this->CI->load->database();
		
		$this->data_parts['selected'] = $module_name;
		// Carga informacion del usuario
		if ($this->auth_access->is_logged_in()) {
			$this->data_parts['user_id'] = $this->user_id = $this->auth_access->get_user_id();
			$this->data_parts['user_info'] = $this->user_info = $this->auth_access->get_user_by_id($this->user_id, 1);
			$this->data_parts['menu'] = /*$this->auth_access->get_user_menu($this->user_id, */config_item('main_menu');//);
		}
		
		$this->_retrieve_errors();
		
		$this->lang->load('crud');
	}
	
	// Esta funcion carga el "header" (parte superior del HTML, contiene el encabezado HTML, 
	// estilos CSS menu y breadcrumbs) del sitio.
	// El parametro $show especifica si se muestra el encabezado (menu, breadcrumbs, etc.)
	protected function _show_header($show = TRUE) {
		$this->data_parts['show'] = $show;
		$this->load->view('header', $this->data_parts);
	}
	
	// Esta funcion carga el "footer" (parte inferior del HTML, contiene el footer y scripts 
	// java) del sitio.
	// El parametro $show especifica si se muestra el footer (version, etc.)
	protected function _show_footer($show = TRUE) {
		$this->data_parts['show'] = $show;
		$this->load->view('footer', $this->data_parts);
	}
	
	// Esta funcion agrega un mensaje de error para mostrar en la siguiente pagina. Generalmente
	// utilizado para errores de validacion o ingreso, pero tambi�n para advertencias y mensajes
	// de exito
	protected function _add_error($type, $msg) {
		$this->data_parts['errors'] []= (object)array('type'=>$type,'msg'=>$msg);
	}
	
	// Esta funcion obtiene de la sesion los errores cargados en la pagina anterior
	protected function _retrieve_errors() {
		$this->data_parts['errors'] = $this->session->flashdata('errors') OR array();
	}
	
	// Esta funcion almacena los errores ingresados en la sesion para poder ser obtenidos
	// en la siguiente pagina
	protected function _store_errors() {
		$this->session->set_flashdata('errors', $this->data_parts['errors']);
	}
	
}