<?php

class Carga extends CRUD_Controller {

	function __construct() {
		parent::__construct('crud/carga','carga','IdCarga');
	}
	
	protected function _set_command() {
		$this->db->select('c.*, t.Nombre AS Temporada, bo.Nombre AS BodegaOrigen, bd.Nombre AS BodegaDestino, a.Nombre AS Articulo, tc.Nombre AS TipoCarga')
			->from('carga AS c')
			->join('temporada AS t','c.IdTemporada = t.IdTemporada','inner')
			->join('bodega AS bo','c.IdBodegaOrigen = bo.IdBodega','inner')
			->join('bodega AS bd','c.IdBodegaDestino = bd.IdBodega','inner')
			->join('articulo AS a','c.IdArticulo = a.IdArticulo','inner')
			->join('tipo_carga AS tc','c.IdTipoCarga = tc.IdTipoCarga','left');
	}
	
	protected function _insert($data) {
		$this->load->model('bodega_model','bodega_model',TRUE);
		
		$bodegaOrigen = $this->bodega_model->get_articulo_por_bodega($data['IdArticulo'], $data['IdBodegaOrigen']);
		if (count($bodegaOrigen->articulos) <= 0)  && $bodegaOrigen->EsExterna == 0) {
			$this->_add_error('error', 'La bodega origen no tiene asignado el articulo a descargar.');
			return FALSE;
		}
		
		$bodegaDestino = $this->bodega_model->get_articulo_por_bodega($data['IdArticulo'], $data['IdBodegaDestino']);
		if (count($bodegaDestino->articulos) <= 0) {
			$this->_add_error('error', 'La bodega destino no maneja el artículo a cargar. Debe asignar el artículo a la bodega destino antes.');
			return FALSE;
		}
		
		$data['IdTemporada'] = $this->season->IdTemporada;
		
		$id = parent::_insert($data);
		
		if ($bodegaOrigen->EsExterna == 0 && $bodegaOrigen->articulos[0]->ManejaStock == 1) {
			$this->CI->db->query('UPDATE articulo_bodega SET Existencia = Existencia - '.$data['Cantidad'].' WHERE IdArticuloBodega = '.$bodegaOrigen->articulos[0]->IdArticuloBodega);
			$this->CI->db->query('UPDATE articulo_bodega SET Existencia = Existencia + '.$data['Cantidad'].' WHERE IdArticuloBodega = '.$bodegaDestino->articulos[0]->IdArticuloBodega);
		}
		else {
			$this->CI->db->query('UPDATE articulo_bodega SET Existencia = Existencia + '.$data['Cantidad'].' WHERE IdArticuloBodega = '.$bodegaOrigen->articulos[0]->IdArticuloBodega);
			$this->CI->db->query('UPDATE articulo_bodega SET Existencia = Existencia - '.$data['Cantidad'].' WHERE IdArticuloBodega = '.$bodegaDestino->articulos[0]->IdArticuloBodega);
		}
		
		return $id;
	}
	
	protected function _update($id, $data) {
		$data['IdTemporada'] = $this->season->IdTemporada;
		return parent::_update($id, $data);
	}
	
}