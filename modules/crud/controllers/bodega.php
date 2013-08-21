<?php

class Bodega extends CRUD_Controller {
	
	public $opp;

	function __construct() {
		parent::__construct('crud/bodega','bodega','IdBodega');
		$this->load->model('bodega_model','bodega_model',TRUE);
		$this->load->helper('text');
	}
	
	function reporte_desecho() {
		$data = $this->input->post('data');
		$show = TRUE;
		if (!empty($data)) {
			$this->data_parts['report'] = $this->bodega_model->reporte_desecho($this->season->IdTemporada, $data['FechaInicio'], $data['FechaFin']);
			$this->data_parts['css_files'] []= 'reports.css';
			$show = FALSE;
		}
		
		$this->crud->form_view = 'reporte_desecho';
		
		$this->_show_header($show);
		
		$this->crud->data_form(NULL, NULL,
			array(
				'operation'=>'reporte_desecho',
				'legend'=>'Reporte de desecho',
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
	
	function reporte_existencias() {
		$data = $this->input->post('data');
		$show = TRUE;
		if (!empty($data)) {
			$this->data_parts['report'] = $this->bodega_model->reporte_existencias($this->season, $data);
			$this->data_parts['css_files'] []= 'reports.css';
			$this->data_parts['data'] = $data;
			$show = FALSE;
		}
		
		$this->data_parts['bodegas'] = $this->bodega_model->get_puntos_venta();
		$this->data_parts['articulos'] = $this->load->model('articulo_model',NULL,TRUE)->get_all('Nombre');
		$this->data_parts['categoriasArticulos'] = $this->load->model('categoria_articulo_model',NULL,TRUE)->get_all('Nombre');
		$this->data_parts['agruparPor'] = $data['AgruparPor'];
		
		$this->crud->form_view = 'reporte_existencias';
		
		$this->_show_header($show);
		
		$this->crud->data_form(NULL, NULL,
			array(
				'operation'=>'reporte_existencias',
				'legend'=>'Reporte de existencias',
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
	
	protected function _set_command() {
		$this->db->select('b.*, eb.Nombre AS EstadoBodega, tb.Nombre AS TipoBodega, bd.Nombre AS BodegaDespacho')
			->from('bodega AS b')
			->join('estado_bodega AS eb','b.IdEstadoBodega = eb.IdEstadoBodega','inner')
			->join('tipo_bodega AS tb','b.IdTipoBodega = tb.IdTipoBodega','inner')
			->join('bodega AS bd','b.IdBodegaDespacho = bd.IdBodega','left');
	}
	
	function _process_form($id = FALSE) {
		$this->primary_key = 'b.IdBodega';
		parent::_process_form($id);
	}
	
	function articulos($id) {
		$this->opp = 'multi';
		$this->primary_key = 'b.IdBodega';
		$this->crud->form_view = 'articulos_por_bodega';
		
		$data = $this->input->post('data');
		if (!empty($data)) {
			foreach ($data['Details'] as $detail) {
				$new = $detail['IsNew'] == 1;
				unset($detail['IsNew']);
				if ($new) {
					$this->bodega_model->add_articulo($id, $detail);
				}
				else {
					$detid = $detail['IdArticuloBodega'];
					unset($detail['IdArticuloBodega']);
					$this->bodega_model->update_articulo($id, $detid, $detail);
				}
			}
			$this->_add_error('success', 'Se actualizaron los articulos');
		}
		
		list($articles,$as) = $this->_get_typeahead_data('IdArticulo AS id, Nombre AS value','articulo');
		$this->data_parts['typeahead'] = array(
			'articulo'=>$articles,
		);
		$this->data_parts['bodega'] = $this->CI->db->where('IdBodega',$id)->get('bodega')->row();		
		$this->data_parts['articulos'] = $articles;
		
		$this->data_parts['js_files'] []= 'jquery.bootstrap-grid.js';
		$this->data_parts['typeahead'] = array(
			'articulo' => $this->load->model('articulo_model',NULL,TRUE)->get_autocomplete(),
		);
		$colmodels = config_item('detail_col_model');
		$this->data_parts['js_scripts'] []= "
			jQuery(function($) {
			$('#grid_".str_replace('/','_',$this->module_name)."').bootstrapGrid({
				colModel: ".json_encode($colmodels['articulo_bodega']).",
				moduleUrl: '".site_url('crud/bodega')."',
				crudUrl: '".site_url('crud')."',
				currencySymbol: '{$this->season->IdMoneda}',
				defaultCurrencyId: '{$this->season->IdMoneda}',
				getAutocompleteData: function() { return taheadInfo; }
			});
		});
		";
		
		parent::_process_form($id);
	}
	
	// Eliminar un detalle de orden ya ingresado. Se envia como parametro el ID del detalle
	public function delete_detail_ajax($id) {
		$success = TRUE;
		$msg = NULL;
		if (!$this->CI->db->where('IdArticuloBodega', $id)->delete('articulo_bodega')) {
			$success = FALSE;
			$msg = 'No se pudo eliminar el registro';
		}
		$this->output->set_content_type('application/json')
		->set_output(json_encode((object)array(
		'success'=>$success,
		'message'=>$msg,
		)));
	}
	
	function table_data() {
		$query = $this->db->select('IdArticulo AS id, Nombre AS value')->order_by('nombre')->get('articulo');
		die(json_encode($query->result()));
	}
	
	protected function _get_object_model($id = NULL) {
		$obj = parent::_get_object_model($id);
		if ($this->opp == 'multi') {
			if (!empty($id)) {
				$query = $this->CI->db->select('ab.*, a.nombre AS Articulo')
					->from('articulo_bodega AS ab')
					->join('articulo AS a','ab.IdArticulo = a.IdArticulo','inner')
					->where('IdBodega',$id)->get();
				$rows = $query->result();
				foreach ($rows as $row) {
					$row->IsNew = FALSE;
					$obj->Details[$row->IdArticuloBodega] = $row;
				}
			}
			else {
				$obj->Details = array();
			}
		}
		return $obj;
	}
	
	private function _get_typeahead_data($select, $table) {
		$rows = $this->CI->db->select($select,FALSE)->get($table)->result();
		$data = array();
		foreach ($rows as $row) {
			$row->value = str_replace('"','',convert_accented_characters($row->value));
			$data []= '"'.$row->value.'"';
		}
		return array($rows, $data);
	}
}