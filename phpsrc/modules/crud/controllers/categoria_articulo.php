<?php

class Categoria_articulo extends CRUD_Controller {

	function __construct() {
		parent::__construct('crud/categoria_articulo','categoria_articulo','ca.IdCategoriaArticulo');
	}
	
	protected function _set_command() {
		$this->db->select('ca.*, cas.Nombre AS CategoriaSuperior')
			->from('categoria_articulo AS ca')
			->join('categoria_articulo AS cas','ca.IdCategoriaSuperior = cas.IdCategoriaArticulo','left');
	}
}