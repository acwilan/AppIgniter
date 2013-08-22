<?php

/***************************
* Controlador Crud
* - Este controlador se encarga de 
*   renderear los controles HTML
*   dependiendo del tipo de datos
****************************/
class Crud {

	private $CI;
	
	public $meta_default;
	public $grid_default;
	public $form_default;
	public $form_view = 'crud/form';
	
	function __construct() {
		$this->CI =& get_instance();
		$this->meta_default = (object)array(
			'module_name'=>'',
			'table_name'=>'',
			'primary_key'=>'id',
			'catalog_state'=>(object)array(
					'sort'=>(object)array(
							'enabled'=>FALSE,
							'field'=>'',
							'order'=>'asc',
						),
					'pagination'=>(object)array(
							'rpp'=>10,
							'current_page'=>1,
							'total_pages'=>1,
							'total_records'=>1,
						),
					'search'=>(object)array(
							'enabled'=>FALSE,
							'term'=>'',
						),
				),
		);
		$this->grid_default = (object)array(
			'allow_add'=>TRUE,
			'allow_edit'=>TRUE,
			'allow_delete'=>TRUE,
			'allow_search'=>TRUE,
			'custom_buttons'=>FALSE,
			'striped'=>TRUE,
			'show_actions'=>TRUE,
			'row_status_field'=>FALSE,
			'edit_label'=>'Editar',
			'custom_actions'=>FALSE,
			'empty_message'=>'No hay registros a mostrar.',
		);
		$this->form_default = (object)array(
			'legend'=>'',
			'operation'=>'add',
			'readonly'=>FALSE,
		);
	}
	
	function data_grid($column_data, $table_data, $options, $meta_data) {
		$options = $this->_extend_default($this->grid_default, $options);
		$meta_data = $this->_extend_default($this->meta_default, $meta_data);
	
		$data = array(
			'column_data'=>$column_data,
			'table_data'=>$table_data,
			'options'=>$options,
			'meta'=>$meta_data,
		);
		
		$this->CI->load->view('crud/grid', $data);
	}
	
	function data_form($field_data, $obj, $options, $meta_data, $additional = array()) {
		$options = $this->_extend_default($this->form_default, $options);
		$meta_data = $this->_extend_default($this->meta_default, $meta_data);
	
		$data = array(
			'field_data'=>$field_data,
			'obj'=>$obj,
			'options'=>$options,
			'meta'=>$meta_data,
		);
		foreach ($additional as $key=>$value)
			$data[$key] = $value;
		
		$this->CI->load->view($this->form_view, $data);
	}
	
	private function _extend_default($defaults, $values) {
		foreach ($values as $key=>$value)
			//if (isset($defaults->{$key}))
				$defaults->{$key} = $value;
		return $defaults;
	}
}