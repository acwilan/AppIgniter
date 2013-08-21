<?php

class Articulo_bodega extends CRUD_Controller {

	function __construct() {
		parent::__construct('crud/articulo_bodega','articulo_bodega','IdArticuloBodega');
	}
	
	protected function _set_command() {
		$this->db->select('ab.*, a.Nombre AS Articulo, b.Nombre AS Bodega')
			->from('articulo_bodega AS ab')
			->join('articulo AS a','ab.IdArticulo = a.IdArticulo','inner')
			->join('bodega AS b','ab.IdBodega = b.IdBodega','inner');
	}
}