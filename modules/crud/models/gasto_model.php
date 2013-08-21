<?php

class Gasto_model extends CRUD_Model {

	function __construct() {
		parent::__construct('gasto','IdGasto');
	}
	
	function reporte_gasto($season, $data) {
		return array();
	}
}