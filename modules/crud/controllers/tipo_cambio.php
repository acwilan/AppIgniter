<?php

class Tipo_cambio extends CRUD_Controller {

	function __construct() {
		parent::__construct('crud/tipo_cambio','tipo_cambio','IdTemporada');
		$this->load->model('tipo_cambio_model','tc_model',TRUE);
	}
	
	protected function _get_form_columns() {
		$symbol = $this->tc_model->get_currency($this->item_id);
		return array(
				(object)array(
						'name'=>'TipoCambioDolar',
						'title'=>'Tipo de Cambio',
						'type'=>'money',
						'key'=>FALSE,
						'hidden'=>FALSE,
						'rules'=>'trim|required|numeric',
						'symbol'=>$symbol->IdMoneda,
					),
			);
	}
	
	protected function _update($id, $data) {
		$tc = $this->tc_model->get($id);
		if (empty($tc)) {
			$data['IdTemporada'] = $id;
			$data['IdMoneda'] = $this->tc_model->get_currency($id)->IdMoneda;
			parent::_insert($data);
			return TRUE;
		}
		else
			return parent::_update($id,$data);
	}
	
	protected function _after_save($action, $id, $data, $oper) {
		if (!empty($this->season) && $this->season->IdTemporada == $id) {
			$this->season = $this->season_model->get($id);
			$this->_set_season($this->season);
		}
		parent::_after_save($action, $id, $data, $oper);
	}
	
}