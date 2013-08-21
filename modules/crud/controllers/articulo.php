<?php

class Articulo extends CRUD_Controller {

	function __construct() {
		parent::__construct('crud/articulo','articulo','IdArticulo');
		$this->load->model('Articulo_model','articulo_model',TRUE);
	}
	
	protected function _set_command() {
		$this->db->select('a.*, ca.Nombre AS CategoriaArticulo, ea.Nombre AS EstadoArticulo, p.Nombre AS Proveedor')
			->from('articulo AS a')
			->join('categoria_articulo AS ca','a.IdCategoriaArticulo = ca.IdCategoriaArticulo','left')
			->join('estado_articulo AS ea','a.IdEstadoArticulo = ea.IdEstadoArticulo','inner')
			->join('proveedor AS p','a.IdProveedor = p.IdProveedor','left');
	}
	
	function ventas() {
		$data = $this->input->post('data');
		$show = TRUE;
		if (!empty($data)) {
			$this->data_parts['report'] = $this->articulo_model->ventas($this->season, $data);
			$this->data_parts['css_files'] []= 'reports.css';
			$show = FALSE;
		}
		
		$this->crud->form_view = 'reporte_articulo_ventas';
		
		$this->data_parts['categorias'] = $this->CI->db->order_by('Nombre')->get('categoria_articulo')->result();
		
		$this->_show_header($show);
		
		$this->crud->data_form(NULL, NULL,
			array(
				'operation'=>'reporte_articulo_ventas',
				'legend'=>'Reporte de ventas por articulo',
			),
			array(
				'module_name'=>$this->module_name,
				'table_name'=>$this->table_name,
				'primary_key'=>$this->primary_key,
				'catalog_state'=>$this->catalog_state,
			),
			$this->data_parts);
		
		$this->_show_footer($show);
	}
	
	protected function _get_form_columns() {
		$cols = parent::_get_form_columns();
		$cols[8]->symbol = $this->season->IdMoneda;
		$cols[9]->symbol = $this->season->IdMoneda;
		return $cols;
	}
	
	protected function _get_grid_columns() {
		$cols = parent::_get_grid_columns();
		$cols[3]->symbol = $this->season->IdMoneda;
		$cols[4]->symbol = $this->season->IdMoneda;
		return $cols;
	}
}