<?php

class Temporada extends CRUD_Controller {

	function __construct() {
		parent::__construct('crud/temporada','temporada','IdTemporada');
	}
	
	protected function _set_command() {
		$this->db->select('t.*, pa.Nombre AS Pais')
			->from('temporada AS t')
			->join('pais AS pa','t.IdPais = pa.IdPais','inner');
	}
	
	function change($id) {
		$season = $this->season_model->get($id);
		if (!empty($season)) {
			$this->season_model->set_current($id);
			$this->_set_season($season);
			redirect();
		}
		else show_404('Season');
	}
}