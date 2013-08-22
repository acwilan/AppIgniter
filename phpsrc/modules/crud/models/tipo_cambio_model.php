<?php

class Tipo_cambio_model extends CRUD_Model {

	function __construct() {
		parent::__construct('tipo_cambio','IdTemporada');
	}
	
	function get_currency($id_temporada) {
		$query = $this->db->select('m.*')
			->from('temporada AS t')
			->join('pais AS p','p.IdPais = t.IdPais','inner')
			->join('moneda AS m','p.IdMoneda = m.IdMoneda','inner')
			->where('t.IdTemporada',$id_temporada)
			->limit(1)
			->get();
		return $query->num_rows() > 0 ? $query->row() : NULL;
	}
	
}