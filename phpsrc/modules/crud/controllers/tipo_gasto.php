<?php

class Tipo_gasto extends CRUD_Controller {

	function __construct() {
		parent::__construct('crud/tipo_gasto','tipo_gasto','tg.IdTipoGasto');
	}
	
	protected function _set_command() {
		$this->db->select('tg.*, tgs.Nombre AS TipoGastoSuperior, b.Nombre AS Bodega')
			->from('tipo_gasto AS tg')
			->join('tipo_gasto AS tgs','tg.IdTipoGastoSuperior = tgs.IdTipoGasto','left')
			->join('bodega AS b','tg.IdBodega = b.IdBodega','left');
	}
}