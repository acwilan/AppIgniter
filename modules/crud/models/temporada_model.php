<?php

class Temporada_model extends CRUD_Model {

	function __construct() {
		parent::__construct('temporada','IdTemporada');
	}
	
	function get_current() {
		$query = $this->db->select('t.*, tc.IdMoneda, tc.TipoCambioDolar')
			->from("{$this->table_name} AS t")
			->join('tipo_cambio AS tc','t.IdTemporada = tc.IdTemporada','left')
			->where('t.EsActiva',1)
			->group_by('t.IdTemporada')->limit(1)
			->get($this->table_name);
		if ($query->num_rows() > 0) {
			return $query->row();
		}
		else {
			$query = $this->db->select('t.*, tc.IdMoneda, tc.TipoCambioDolar')
				->from("{$this->table_name} AS t")
				->join('tipo_cambio AS tc','t.IdTemporada = tc.IdTemporada','left')
				->where("NOW() > t.FechaInicio AND NOW() < t.FechaFin")
				->group_by('t.IdTemporada')->limit(1)
				->get($this->table_name);
			return $query->num_rows() > 1 ? $query->row() : NULL;
		}
	}
	
	function get_first_active() {
		$query = $this->db->select('t.*, tc.IdMoneda, tc.TipoCambioDolar')
			->from("{$this->table_name} AS t")
			->join('tipo_cambio AS tc','t.IdTemporada = tc.IdTemporada','left')
			->where('t.EsActiva',1)
			->order_by('t.FechaInicio','desc')->group_by('t.IdTemporada')->limit(1)
			->get($this->table_name);
		return $query->num_rows() > 0 ? $query->row() : NULL;
	}
	
	function get($id) {
		$query = $this->db->select('t.*, tc.IdMoneda, tc.TipoCambioDolar')
			->from('temporada AS t')
			->join('tipo_cambio AS tc','t.IdTemporada = tc.IdTemporada','left')
			->where('t.IdTemporada',$id)
			->group_by('t.IdTemporada')->get();
		return $query->num_rows() > 0 ? $query->row() : NULL;
	}
	
	function set_current($id) {
		$this->db->update($this->table_name,array('EsActiva'=>0));
		$this->db->update($this->table_name,array('EsActiva'=>1),array($this->primary_key=>$id));
	}
	
}