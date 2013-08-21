<?php

class Esquema_utilidad extends CRUD_Controller {

	function __construct() {
		parent::__construct('crud/esquema_utilidad','esquema_utilidad','IdEsquemaUtilidad');
		$this->load->helper('text');
		$this->load->model('esquema_utilidad_model','esquema_model',TRUE);
	}
	
	// Eliminar un detalle de orden ya ingresado. Se envia como parametro el ID del detalle
	public function delete_detail_ajax($id) {
		$success = TRUE;
		$msg = NULL;
		if (!$this->CI->db->where('IdEsquemaUtilidadDetalle', $id)->delete('esquema_utilidad')) {
			$success = FALSE;
			$msg = 'No se pudo eliminar el registro';
		}
		$this->output->set_content_type('application/json')
			->set_output(json_encode((object)array(
					'success'=>$success,
					'message'=>$msg,
				)));
	}
	
	protected function _insert($data) {
		$details = $data['Details'];
		unset($data['Details']);
		$data['IdEsquemaUtilidad'] = parent::_insert($data);
		$this->esquema_model->process_details($data, $details);
		return $data['IdRendicion'];
	}
	
	protected function _update($id, $data) {
		$details = $data['Details'];
		unset($data['Details']);
		$data['IdEsquemaUtilidad'] = $id;
		$res = parent::_update($id, $data);
		$this->esquema_model->process_details($data, $details);
		return $res;
	}
	
	protected function _process_form($id = FALSE) {
		$this->crud->form_view = 'esquema_utilidad_form';
		list($empleados,$es) = $this->_get_typeahead_data("IdEmpleado AS id, CONCAT(PrimerNombre,' ',ApellidoPaterno) AS value",'empleado');
		$this->data_parts['typeahead'] = array(
				'empleado'=>$empleados,
			);
		parent::_process_form($id);
	}
	
	protected function _get_object_model($id = NULL) {
		$obj = parent::_get_object_model($id);
		$obj->Details = array();
		if (!empty($id)) {
			$query = $this->CI->db->select("eud.*,CONCAT(e.PrimerNombre,' ',e.ApellidoPaterno) AS Empleado, 0 AS IsNew",FALSE)
				->from('esquema_utilidad_detalle AS eud')
				->join('empleado AS e','eud.IdEmpleado = e.IdEmpleado','inner')
				->where('eud.IdEsquemaUtilidad',$id)
				->get();
			$rows = $query->result();
			foreach ($rows as $row) {
				$obj->Details[$row->IdEsquemaUtilidadDetalle] = $row;
			}
		}
		else {
			$obj->FechaCreacion = date('Y-m-d');
		}
		return $obj;
	}
	
	protected function _show_footer() {
		$this->data_parts['js_files'] []= 'esquema_utilidad.js';
		$this->data_parts['js_files'] []= 'jquery.bootstrap-grid.js';
		$colmodels = config_item('detail_col_model');
		$this->data_parts['js_scripts'] []= "
		jQuery(function($) {
			$('#grid_".str_replace('/','_',$this->module_name)."').bootstrapGrid({
				colModel: ".json_encode($colmodels['esquema_utilidad']).",
				modelUrl: '".site_url($this->module_name)."',
				crudUrl: '".site_url('crud')."',
				currencySymbol: '{$this->season->IdMoneda}',
				defaultCurrencyId: '{$this->season->IdMoneda}',
				getAutocompleteData: function() { return taheadInfo; },
				afterAddDetailCallback: function(grid, row, e) { changeDetail(grid, null, e); },
				afterRemoveDetailCallback: changeDetail
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