<?php

class Esquema_utilidad_model extends CRUD_model {

	function __construct() {
		parent::__construct('esquema_utilidad','IdEsquemaUtilidad');
	}
	
	function process_details($data, $details) {
		foreach ($details as $detail) {
			$detail['IdEsquemaUtilidad'] = $data['IdEsquemaUtilidad'];
			unset($detail['Empleado']);
			if ($detail['IsNew'] == 1) {
				unset($detail['IsNew']);
				$this->db->insert('esquema_utilidad_detalle',$detail);
				$detail['IdEsquemaUtilidadDetalle'] = $this->db->insert_id();
			}
			else {
				$id = $detail['IdEsquemaUtilidadDetalle'];
				unset($detail['IsNew']);
				unset($detail['IdEsquemaUtilidadDetalle']);
				$this->db->update('esquema_utilidad_detalle',$detail,array('IdEsquemaUtilidadDetalle'=>$id));
			}
		}
	}
	
}