<?php

class Bodega_model extends CRUD_Model {

	function __construct() {
		parent::__construct('bodega','IdBodega');
	}
	
	function get_autocomplete() {
		$query = $this->db->select('IdBodega AS id, Nombre AS value')
			->get($this->table_name);
		return $query->result();
	}
	
	function get_puntos_venta() {
		$query = $this->db->where('IdTipoBodega',2)->order_by('Nombre')->get('bodega');
		return $query->result();
	}
	
	function reporte_existencias($season, $data) {
		if ($data['AgruparPor'] == 'Articulo') {
			$this->db->select("
					T.Nombre NombreTemporada,
					AB.IdArticulo,
					A.Nombre NombreArticulo,
					C.IdCategoriaArticulo,
					C.Nombre NombreCategoria,
					CS.IdCategoriaArticulo IdCategoriaSuperior,
					CS.Nombre NombreCategoriaSuperior,
					A.CodigoArticulo,
					ROUND(A.Costo * TC.TipoCambioDolar,2) Costo,
					ROUND(A.Precio * TC.TipoCambioDolar,2) Precio,
					TC.IdMoneda IdMonedaLocal,	
					E.Nombre NombreEstado,
					A.PorcentajeComisionVenta PorcentajeComisionVenta,
					A.ManejaStock,
					AB.IdBodega,
					B.Nombre NombreBodega,
					B.IdBodega CodigoBodega,
					AB.Existencia,
					AB.UtilizaPorcentajeComisionVentaPropio,
					AB.PorcentajeComisionVenta PorcentajeComisionVentaPropio
				",FALSE)
				->from('tipo_cambio AS TC')
				->join('temporada AS T','TC.IdTemporada = T.IdTemporada','inner')
				->from('articulo_bodega AS AB')
				->join('articulo A','AB.IdArticulo = A.IdArticulo','inner')
				->join('bodega B','AB.IdBodega = B.IdBodega','inner')
				->join('categoria_articulo C','A.IdCategoriaArticulo = C.IdCategoriaArticulo','left')
				->join('categoria_articulo CS','C.IdCategoriaSuperior = CS.IdCategoriaArticulo','left')
				->join('estado_articulo E','A.IdEstadoArticulo = E.IdEstadoArticulo','inner')
				->where('TC.IdTemporada',$season->IdTemporada)
				->order_by('CS.IdCategoriaArticulo,C.IdCategoriaArticulo,A.IdArticulo,B.IdBodega');
			if (!empty($data['IdArticulo']))
				$this->db->where('A.IdArticulo',$data['IdArticulo']);
					
			$query = $this->db->get();
			$rows = $query->result();
			$resultset = array();
			$idcs = $idc = $ida = $idb = NULL;
			
			foreach ($rows as $row) {
				if (empty($row->IdCategoriaSuperior)) {
					if ($row->IdArticulo != $ida) {
						$ida = $row->IdArticulo;
						$id = NULL;
						$resultset[0]->Articulos[$ida] = (object)array(
							'Id'=>$ida,'Nombre'=>$row->NombreArticulo,'Bodegas'=>array(),'Codigo'=>$row->CodigoArticulo,
							'Costo'=>$row->Costo, 'Precio'=>$row->Precio, 'PctComision'=>$row->PorcentajeComisionVenta,
							'ManejaStock'=>$row->ManejaStock == 1, 'Estado'=>$row->NombreEstado
						);
					}
					$idb = $row->IdBodega;
					$resultset[0]->Articulos[$ida]->Bodegas[$idb] = (object)array(
						'Id'=>$idb, 'Nombre'=>$row->NombreBodega, 'PctComisionPropio'=>$row->PorcentajeComisionVentaPropio,
						'UsaPctComisionPropio'=>$row->UtilizaPorcentajeComisionVentaPropio == 1, 'Codigo'=>$row->CodigoBodega,
						'PctComision'=>$row->PorcentajeComisionVenta, 'Stock'=>$row->Existencia
					);
				}
				else {
					if ($row->IdCategoriaSuperior != $idcs) {
						$idcs = $row->IdCategoriaSuperior;
						$idc = $ida = $idb = NULL;
						$resultset[$idcs] = (object)array('Id'=>$idcs,'Nombre'=>$row->NombreCategoriaSuperior,'Subcategorias'=>array());
					}
					if ($row->IdCategoriaArticulo != $idc) {
						$idc = $row->IdCategoriaArticulo;
						$ida = $idb = NULL;
						$resultset[$idcs]->Subcategorias[$idc] = (object)array('Id'=>$idc,'Nombre'=>$row->NombreCategoria,'Articulos'=>array());
					}
					if ($row->IdArticulo != $ida) {
						$ida = $row->IdArticulo;
						$idb = NULL;
						$resultset[$idcs]->Subcategorias[$idc]->Articulos[$ida] = (object)array(
							'Id'=>$ida,'Nombre'=>$row->NombreArticulo,'Bodegas'=>array(),'Codigo'=>$row->CodigoArticulo,
							'Costo'=>$row->Costo, 'Precio'=>$row->Precio, 'PctComision'=>$row->PorcentajeComisionVenta,
							'ManejaStock'=>$row->ManejaStock == 1, 'Estado'=>$row->NombreEstado
						);
					}
					$idb = $row->IdBodega;
					$resultset[$idcs]->Subcategorias[$idc]->Articulos[$ida]->Bodegas[$idb] = (object)array(
						'Id'=>$idb, 'Nombre'=>$row->NombreBodega, 'PctComisionPropio'=>$row->PorcentajeComisionVentaPropio,
						'UsaPctComisionPropio'=>$row->UtilizaPorcentajeComisionVentaPropio == 1, 'Codigo'=>$row->CodigoBodega,
						'PctComision'=>$row->PorcentajeComisionVenta, 'Stock'=>$row->Existencia
					);
				}
			}
			
			return $resultset;
		}
	}
	
	function reporte_desecho($season, $data) {
	}
	
	function get_articulo_por_bodega($id_articulo, $id_bodega) {
		$query = $this->db->where('b.IdBodega',$id_bodega)
			->from('bodega AS b')->join('tipo_bodega AS tb','b.IdTipoBodega = tb.IdTipoBodega','inner')
			->get();
		if ($query->num_rows() == 0) return FALSE;
		
		$bodega = $query->row();
		$query = $this->db->select('*')
			->from('articulo_bodega AS ab')
			->join('articulo AS a','ab.IdArticulo = a.IdArticulo','inner')
			->where('ab.IdBodega',$id_bodega)
			->where('ab.IdArticulo',$id_articulo)->get();
		$bodega->articulos = $query->result();
		
		return $bodega;
	}
	
	function add_articulo($id_bodega, $data) {
		if (!isset($data['UtilizaPorcentajeComisionVentaPropio']) || empty($data['UtilizaPorcentajeComisionVentaPropio']))
			$data['UtilizaPorcentajeComisionVentaPropio'] = 0;
		else 
			$data['UtilizaPorcentajeComisionVentaPropio'] = 1;
		$data['IdBodega'] = $id_bodega;
		$this->db->insert('articulo_bodega',$data);
		return $this->db->insert_id();
	}
	
	function update_articulo($id_bodega, $id, $data) {
		if (!isset($data['UtilizaPorcentajeComisionVentaPropio']) || empty($data['UtilizaPorcentajeComisionVentaPropio']))
			$data['UtilizaPorcentajeComisionVentaPropio'] = 0;
		else 
			$data['UtilizaPorcentajeComisionVentaPropio'] = 1;
		$data['IdBodega'] = $id_bodega;
		$this->db->update('articulo_bodega',$data, array('IdArticuloBodega'=>$id));
		return $this->db->insert_id();
	}
	
}