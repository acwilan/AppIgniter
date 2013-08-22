<?php

class Proveedor extends CRUD_Controller {

	function __construct() {
		parent::__construct('crud/proveedor','proveedor','p.IdProveedor');
	}
	
	protected function _set_command() {
		$this->db->select('p.*, pa.Nombre AS Pais')
			->from('proveedor AS p')
			->join('pais AS pa','p.IdPais = pa.IdPais','left');
	}
}