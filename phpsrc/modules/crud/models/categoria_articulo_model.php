<?php

class Categoria_articulo_model extends CRUD_Model {

	function __construct() {
		parent::__construct('categoria_articulo','IdCategoriaArticulo');
	}
	
	function get_autocomplete() {
		$query = $this->db->select('IdArticulo AS id, Nombre AS value')
			->get($this->table_name);
		return $query->result();
	}
	
}