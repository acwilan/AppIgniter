<?php

class Crud_Model extends CI_Model {
	
	protected $table_name;
	protected $primary_key;
	
	function __construct($table_name, $primary_key) {
		$this->table_name = $table_name;
		$this->primary_key = $primary_key;
	}
	
	function get($id) {
		$query = $this->db->where($this->primary_key, $id)->get($this->table_name);
		return $query->num_rows() > 0 ? $query->row() : NULL;
	}
	
}