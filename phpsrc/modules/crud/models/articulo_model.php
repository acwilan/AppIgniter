<?php

class Articulo_model extends CRUD_Model {

	function __construct() {
		parent::__construct('articulo','IdArticulo');
	}
	
	function get_autocomplete() {
		$query = $this->db->select('IdArticulo AS id, Nombre AS value')
			->get($this->table_name);
		return $query->result();
	}
	
	function ventas($season, $data) {
		$this->db->select("
				'{$data['FechaInicio']}' FechaInicio,
				'{$data['FechaFin']}' FechaFin,
				A.IdArticulo,
				A.Nombre NombreArticulo,
				C.IdCategoriaArticulo,
				C.Nombre NombreCategoria,
				CS.IdCategoriaArticulo IdCategoriaArticuloSuperior,
				CS.Nombre NombreCategoriaSuperior,
				P.Nombre NombrePais,
				T.Nombre NombreTemporada,
				T.FechaInicio TemporadaFechaInicio,
				T.FechaFin TemporadaFechaFin,
				SUM(RV.NumeroReposiciones) TotalNumeroReposiciones,
				SUM(RV.NumeroAjustes) TotalNumeroAjustes,
				SUM(RV.NumeroDevoluciones) TotalNumeroDevoluciones,
				SUM(RV.NumeroVentas) TotalNumeroVentas,
				SUM(RV.NumeroVentasPromo) TotalNumeroVentasPromo,
				TC.IdMoneda IdMonedaLocal,
				SUM(RV.SubTotal) VentaBruta,
				SUM(RV.TotalComision) TotalComision,
				SUM(RV.TotalNeto) VentaNeta,
				SUM(RV.TotalCosto) TotalCosto
			",FALSE)->from("
				tipo_cambio TC
				INNER JOIN temporada T ON TC.IdTemporada = T.IdTemporada
				INNER JOIN pais P ON T.IdPais = P.IdPais,
				articulo A
				LEFT JOIN rendiciones_view RV ON A.IdArticulo = RV.IdArticulo
				AND RV.IdTemporada = {$season->IdTemporada} AND RV.Fecha >= '{$data['FechaInicio']}' AND RV.Fecha <= '{$data['FechaFin']}'
				LEFT JOIN categoria_articulo C ON A.IdCategoriaArticulo = C.IdCategoriaArticulo
				LEFT JOIN categoria_articulo CS ON C.IdCategoriaSuperior = CS.IdCategoriaArticulo
			")->where('TC.IdTemporada', $season->IdTemporada)->order_by('CS.IdCategoriaArticulo,C.IdCategoriaArticulo,A.IdArticulo');
		$query = $this->db->get();
		$results = $query->result();
		//echo $this->db->last_query().PHP_EOL;
		//var_dump($data);
		//var_dump($results);die();
		//die($this->db->last_query());
		
		$rows = array();
		
		foreach ($results as $row) {
			if (empty($data['IdCategoriaArticulo'])) {
				if ($row->VentaBruta !== NULL && intval($row->VentaBruta) >= 0) {
					$rows []= $row;
				}
			}
			else {
				if (($row->IdCategoriaArticulo == $data['IdCategoriaArticulo'] ||
					$row->IdCategoriaArticuloSuperior == $data['IdCategoriaArticulo']) &&
					($row->VentaBruta !== NULL && intval($row->VentaBruta) >= 0)) {
					$rows []= $row;
				}
			}
		}
		
		$resultset = array();
		$idcs = $idc = $ida = NULL;

 		foreach ($rows as $row) {
			if (empty($row->IdCategoriaArticuloSuperior)) {
				if ($row->IdArticulo != $ida) {
					$ida = $row->IdArticulo;
					$resultset[0]->Articulos[$ida] = (object)array(
						'Id'=>$ida,'Nombre'=>$row->NombreArticulo,
						'Ajustes'=>$row->TotalNumeroAjustes,'Devoluciones'=>$row->TotalNumeroDevoluciones,
						'Ventas'=>$row->TotalNumeroVentas,'VentasPromo'=>$row->TotalNumeroVentasPromo,
						'VentasBrutas'=>$row->VentaBruta,'Comision'=>$row->TotalComision,
						'VentasNetas'=>$row->VentaNeta,'Costo'=>$row->TotalCosto,
						'Utilidad'=>$row->VentaNeta-$row->TotalCosto,
					);
				}
			}
			else {
				if ($row->IdCategoriaArticuloSuperior != $idcs) {
					$idcs = $row->IdCategoriaArticuloSuperior;
					$idc = $ida = NULL;
					$resultset[$idcs] = (object)array('Id'=>$idcs,'Nombre'=>$row->NombreCategoriaSuperior,'Subcategorias'=>array());
				}
				if ($row->IdCategoriaArticulo != $idc) {
					$idc = $row->IdCategoriaArticulo;
					$ida = NULL;
					$resultset[$idcs]->Subcategorias[$idc] = (object)array('Id'=>$idc,'Nombre'=>$row->NombreCategoria,'Articulos'=>array());
				}
				if ($row->IdArticulo != $ida) {
					$ida = $row->IdArticulo;
					$resultset[$idcs]->Subcategorias[$idc]->Articulos[$ida] = (object)array(
						'Id'=>$ida,'Nombre'=>$row->NombreArticulo,
						'Ajustes'=>$row->TotalNumeroAjustes,'Devoluciones'=>$row->TotalNumeroDevoluciones,
						'Ventas'=>$row->TotalNumeroVentas,'VentasPromo'=>$row->TotalNumeroVentasPromo,
						'VentasBrutas'=>$row->VentaBruta,'Comision'=>$row->TotalComision,
						'VentasNetas'=>$row->VentaNeta,'Costo'=>$row->TotalCosto,
						'Utilidad'=>$row->VentaNeta-$row->TotalCosto,
					);
				}
			}
		}
		
		return $resultset;
	}
	
}