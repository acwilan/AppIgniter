<?php

class Rendicion extends CRUD_Controller {

	function __construct() {
		parent::__construct('crud/rendicion','rendicion','IdRendicion');
		$this->load->helper('text');
		$this->load->model('rendicion_model','rendicion_model',TRUE);
	}
	
	function imprimir($id_rendicion = FALSE) {
		$data = $this->input->post('data');
		$show = TRUE;
		if (empty($data) && $id_rendicion !== FALSE) {
			$this->data_parts['report'] = $this->rendicion_model->imprimir_single($this->season->IdTemporada, $id_rendicion);
			$this->data_parts['css_files'] []= 'reports.css';
			$show = FALSE;
		}
		elseif (!empty($data)) {
			$this->data_parts['report'] = $this->rendicion_model->imprimir($this->season->IdTemporada, $data['IdBodega']);
			$this->data_parts['css_files'] []= 'reports.css';
			$show = FALSE;
		}
		
		$this->load->model('bodega_model','bmodel',TRUE);
		$this->data_parts['bodegas'] = $this->bmodel->get_puntos_venta();
		$this->crud->form_view = 'rendicion_print';
		
		$this->_show_header($show);
		
		$this->crud->data_form(NULL, NULL,
			array(
				'operation'=>'print',
				'legend'=>'Imprimir rendicion',
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
	
	// Eliminar un detalle de orden ya ingresado. Se envia como parametro el ID del detalle
	public function delete_detail_ajax($id) {
		$success = TRUE;
		$msg = NULL;
		if (!$this->CI->db->where('IdRendicionDetalle', $id)->delete('rendicion_detalle')) {
			$success = FALSE;
			$msg = 'No se pudo eliminar el registro';
		}
		$this->output->set_content_type('application/json')
			->set_output(json_encode((object)array(
					'success'=>$success,
					'message'=>$msg,
				)));
	}
	
	protected function _set_command() {
		$this->CI->db->select('r.*, t.nombre AS Temporada, b.Nombre as PuestoVenta')
			->from('rendicion AS r')
			->join('temporada AS t','r.IdTemporada = t.IdTemporada','inner')
			->join('bodega AS b','r.IdPuestoVenta = b.IdBodega','inner')
			->order_by('r.IdRendicion','desc');
	}
	
	protected function _insert($data) {
		//var_dump($data);die();
		$data['IdTemporada'] = $this->season->IdTemporada;
		$details = $data['Details'];
		unset($data['Details']);
		unset($data['PuestoVenta']);
		$data['IdRendicion'] = parent::_insert($data);
		$this->rendicion_model->process_details($data, $details);
		return $data['IdRendicion'];
	}
	
	protected function _update($id, $data) {
		$data['IdTemporada'] = $this->season->IdTemporada;
		$details = $data['Details'];
		unset($data['Details']);
		unset($data['PuestoVenta']);
		$data['IdRendicion'] = $id;
		$res = parent::_update($id, $data);
		$this->rendicion_model->process_details($data, $details);
		return $res;
	}
	
	protected function _process_form($id = FALSE) {
		$this->crud->form_view = 'rendicion_form';
		list($articles,$as) = $this->_get_typeahead_data('IdArticulo AS id, Nombre AS value','articulo');
		list($bodegas,$bs) = $this->_get_typeahead_data('IdBodega AS id, Nombre AS value','bodega');
		$this->data_parts['typeahead'] = array(
				'articulo'=>$articles,
				'bodega'=>$bodegas,
			);
		parent::_process_form($id);
	}
	
	protected function _get_object_model($id = NULL) {
		$obj = parent::_get_object_model($id);
		if (!isset($obj->PuestoVenta))
			$obj->PuestoVenta = NULL;
		$obj->Details = array();
		if (!empty($id)) {
			$query = $this->CI->db->select('rd.*,a.Nombre AS Articulo, 0 AS IsNew',FALSE)
				->from('rendicion_detalle AS rd')
				->join('articulo AS a','rd.IdArticulo = a.IdArticulo','inner')
				->where('rd.IdRendicion',$id)
				->get();
			$rows = $query->result();
			foreach ($rows as $row) {
				$obj->Details[$row->IdRendicionDetalle] = $row;
			}
		}
		else {
			$obj->Fecha = date('Y-m-d');
		}
		return $obj;
	}
	
	protected function _show_footer() {
		$this->data_parts['js_files'] []= 'rendicion.js';
		$this->data_parts['js_files'] []= 'jquery.bootstrap-grid.js';
		$this->data_parts['typeahead'] = array(
				'bodega' => $this->load->model('bodega_model',NULL,TRUE)->get_autocomplete(),
				'articulo' => $this->load->model('articulo_model',NULL,TRUE)->get_autocomplete(),
			);
		$colmodels = config_item('detail_col_model');
		$this->data_parts['js_scripts'] []= "
		jQuery(function($) {
			$('#grid_".str_replace('/','_',$this->module_name)."').bootstrapGrid({
				colModel: ".json_encode($colmodels['rendicion']).",
				modelUrl: '".site_url($this->module_name)."',
				crudUrl: '".site_url('crud')."',
				currencySymbol: '{$this->season->IdMoneda}',
				defaultCurrencyId: '{$this->season->IdMoneda}',
				getAutocompleteData: function() { return taheadInfo; }
			});
		});
		";
		parent::_show_footer();
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