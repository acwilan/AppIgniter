<?php

class Empleado extends CRUD_Controller {

	function __construct() {
		parent::__construct('crud/empleado','empleado','IdEmpleado');
	}
	
	protected function _set_command() {
		$this->db->select("e.*, CONCAT(e.PrimerNombre,IF(e.SegundoNombre IS NULL,'',CONCAT(' ',e.SegundoNombre)),' ',e.ApellidoPaterno,IF(e.ApellidoMaterno IS NULL,'',CONCAT(' ',e.ApellidoMaterno))) AS NombreCompleto",FALSE)
			->from('empleado AS e');
	}
	
}