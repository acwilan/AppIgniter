<?php

class Rendicion_model extends CRUD_Model {

	function __construct() {
		parent::__construct('rendicion','IdRendicion');
	}
	
	function process_details($data, &$details) {
		foreach ($details as $detail) {
			$articulo = $this->get_articulo($detail['IdArticulo'],$data['IdPuestoVenta']);
			if ($articulo->ManejaStock) {
				$detail['StockInicial'] = $articulo->Existencia;
				$detail['StockFinal'] = $articulo->Existencia - ($detail['NumeroAjustes']+$detail['NumeroDevoluciones']+$detail['NumeroVentas']+$detail['NumeroVentasPromo']) + $detail['NumeroReposiciones'];
				$articulo->Existencia = $detail['StockFinal'];
				$this->update_articulo($articulo,$data);
				
				$art_parent = $this->get_parent_articulo($articulo->IdArticulo,$data['IdPuestoVenta']);
				if (!empty($art_parent)) {
					$art_parent->Existencia = $art_parent->Existencia + $detail['NumeroDevoluciones'] - $detail['NumeroReposiciones'];
					$this->update_articulo($art_parent,$data);
				}
			}
			else {
				$detail['StockInicial'] = $detail['StockFinal'] = 0;
			}
			$detail['PrecioAplicado'] = $articulo->Precio;
			$detail['PorcentajeComisionVentaAplicado'] = $this->articulo_pct_venta_aplicable($detail['IdArticulo'],$data['IdPuestoVenta']);
			$detail['PorcentajeDescuentoAplicado'] = 0;
			$detail['DescuentoAplicado'] = 0;
			$detail['PrecioAplicadoPromo'] = 0;
			$detail['IdRendicion'] = $data['IdRendicion'];
			$detail['IdTemporada'] = $data['IdTemporada'];
			unset($detail['Articulo']);
			if ($detail['IsNew'] == 1) {
				unset($detail['IsNew']);
				$this->db->insert('rendicion_detalle',$detail);
				$detail['IdRendicionDetalle'] = $this->db->insert_id();
			}
			else {
				$id = $detail['IdRendicionDetalle'];
				unset($detail['IsNew']);
				unset($detail['IdRendicionDetalle']);
				$this->db->update('rendicion_detalle',$detail,array('IdRendicionDetalle'=>$id));
			}
		}
	}
	
	function get_articulo($id_articulo,$id_bodega) {
		$query = $this->db->select('IFNULL(ab.Existencia,0) AS Existencia, a.IdArticulo, a.ManejaStock, a.Precio, ab.IdBodega',FALSE)
			->from('articulo AS a')
			->join('articulo_bodega AS ab',"a.IdArticulo = ab.IdArticulo AND ab.IdBodega=$id_bodega",'left')
			->where('a.IdArticulo',$id_articulo)
			->limit(1)->get();
		return $query->num_rows() > 0 ? $query->row() : NULL;
	}
	
	function get_parent_articulo($id_articulo, $id_bodega) {
		$query = $this->db->select('ab.*')
			->from('bodega AS b')
			->join('bodega AS bd','b.IdBodegaDespacho = bd.IdBodega','inner')
			->join('articulo_bodega AS ab','bd.id_bodega = ab.id_bodega','inner')
			->where('ab.id_articulo',$id_articulo)
			->where('b.id_bodega',$id_bodega)
			->limit(1)->get();
		return $query->num_rows() > 0 ? $query->row() : NULL;
	}
	
	function update_articulo(&$articulo_bodega, $data) {
		if (empty($articulo_bodega->IdBodega)) {
			$this->db->insert('articulo_bodega',array(
				'IdArticulo'=>$articulo_bodega->IdArticulo,
				'IdBodega'=>$data['IdPuntoVenta'],
				'Existencia'=>$articulo_bodega->Existencia,
				'UtilizaPorcentajeComisionVentaPropio'=>0,
			));
			$articulo_bodega->IdBodega = $data['IdPuntoVenta'];
		}
		else {
			$this->db->update('articulo_bodega',array(
				'Existencia'=>$articulo_bodega->Existencia,
			),array('IdArticulo'=>$articulo_bodega->id_articulo,'IdBodega'=>$articulo_bodega->id_bodega));
		}
	}
	
	function articulo_pct_venta_aplicable($id_articulo, $id_bodega) {
		$query = $this->db->select('IF(IFNULL(ab.UtilizaPorcentajeComisionVentaPropio,0) = 1,ab.PorcentajeComisionVenta,a.PorcentajeComisionVenta) AS PctComision',FALSE)
			->from('articulo AS a')
			->join('articulo_bodega AS ab',"ab.IdArticulo = a.IdArticulo AND ab.IdBodega = $id_bodega",'left')
			->where('ab.IdArticulo',$id_articulo)
			->limit(1)->group_by('a.IdArticulo')->get();
		$row = $query->num_rows() > 0 ? $query->row() : NULL;
		return !empty($row) ? $row->PctComision : 0;
	}
	
	function imprimir($id_temporada, $id_bodega = NULL) {
		$resultset = array();
		$ids = array();
		if (!empty($id_bodega) && $id_bodega > 0) {
			$query = $this->db->where('IdBodega',$id_bodega)->get('bodega');
			if ($query->num_rows() > 0)
				$resultset[$id_bodega] = $query->row();
		}
		else {
			$query = $this->db->order_by('Nombre')->get('bodega');
			foreach ($query->result() as $row)
				$resultset[$row->IdBodega] = $row;
		}
		
		foreach ($resultset as $bodega) {
			$this->db->select("
					AB.IdArticulo,
					A.CodigoArticulo,
					A.Nombre AS NombreArticulo,		
					A.ManejaStock,
					AB.Existencia,
					
					CASE AB.UtilizaPorcentajeComisionVentaPropio 
						WHEN 1 THEN AB.PorcentajeComisionVenta 
						ELSE A.PorcentajeComisionVenta 
					END AS PorcentajeComisionVenta,
					
					(SELECT ROUND(A.Precio * TC.TipoCambioDolar, 2)
						FROM tipo_cambio TC
						WHERE TC.IdTemporada = $id_temporada) AS PrecioMonedaLocal
				",FALSE)
				->from('articulo_bodega AS AB')
				->join('articulo AS A','AB.IdArticulo = A.IdArticulo','inner')
				->join('bodega AS B','AB.IdBodega = B.IdBodega','inner')
				->where('B.IdTipoBodega',2)
				->where('B.IdBodega',$bodega->IdBodega)
				->order_by('AB.IdBodega, A.Nombre');
			$query = $this->db->get();
			$bodega->Articulos = $query->result();
		}
		return $resultset;
	}
	
	function imprimir_single($id_temporada, $id_rendicion) {
		$resultset = array();
		$ids = array();
		
		$this->db->select("
            R.IdRendicion,
            R.Fecha,
            T.Nombre as NombreTemporada,	
            R.IdPuestoVenta AS IdBodega,
            PV.Nombre AS NombreBodega,
            R.Observaciones,
            R.IdRendicion,
            RD.IdArticulo,
            A.CodigoArticulo,
            A.Nombre AS NombreArticulo,	
            RD.NumeroReposiciones,
            RD.NumeroAjustes,
            RD.NumeroDevoluciones,
            RD.NumeroVentas,	
		    TC.IdMoneda AS IdMonedaLocal,	
		    ROUND(RD.PrecioAplicado * TC.TipoCambioDolar, 2) AS PrecioMonedaLocal,			
		
		    (ROUND(RD.PrecioAplicado * TC.TipoCambioDolar, 2) * RD.NumeroVentas) + (ROUND(RD.PrecioAplicadoPromo * TC.TipoCambioDolar, 2) * RD.NumeroVentasPromo) AS SubTotal,
		    RD.PorcentajeComisionVentaAplicado AS PorcentajeComisionVenta,	
		    ((ROUND(RD.PrecioAplicado * TC.TipoCambioDolar, 2) * RD.NumeroVentas) + (ROUND(RD.PrecioAplicadoPromo * TC.TipoCambioDolar, 2) * RD.NumeroVentasPromo))		
		        * RD.PorcentajeComisionVentaAplicado / 100    AS TotalComision,
		    ((ROUND(RD.PrecioAplicado * TC.TipoCambioDolar, 2) * RD.NumeroVentas) + (ROUND(RD.PrecioAplicadoPromo * TC.TipoCambioDolar, 2) * RD.NumeroVentasPromo))
		        * (100-RD.PorcentajeComisionVentaAplicado) / 100 AS TotalNeto,
		               RD.StockInicial,
		               RD.StockFinal,
		               PV.Encargado,
		               PV.IdBodega,
		               T.FechaInicio TemporadaFechaInicio,
		               T.FechaFin TemporadaFechaFin,
		               P.Nombre NombrePais,
		
		               RD.NumeroVentasPromo,
		                ROUND(RD.PrecioAplicadoPromo * TC.TipoCambioDolar, 2) AS PrecioPromoMonedaLocal,
		              RD.PorcentajeDescuentoAplicado
		",FALSE)
			->from('rendicion_detalle AS RD')
			->join('rendicion AS R','R.IdRendicion = RD.IdRendicion','inner')
			->join('bodega AS PV','R.IdPuestoVenta = PV.IdBodega','inner')
			->join('articulo AS A','RD.IdArticulo = A.IdArticulo','inner')
			->join('temporada AS T','R.IdTemporada = T.IdTemporada','inner')
			->join('pais AS P','T.IdPais = P.IdPais','inner')
			->join('tipo_cambio AS TC','T.IdTemporada = TC.IdTemporada','inner')
			->where('R.IdRendicion',$id_rendicion);
		$query = $this->db->get();
		
		if ($query->num_rows() > 0) {
			$rows = $query->result();
			
			$firstRow = $rows[0];
			$bodega = (object)array('IdBodega'=>$firstRow->IdBodega,'Nombre'=>$firstRow->NombreBodega,'Articulos'=>array(),
				'Temporada'=>$firstRow->NombreTemporada,'Fecha'=>$firstRow->Fecha,'TemporadaFechaInicio'=>date('d/m/Y',strtotime($firstRow->TemporadaFechaInicio)),
				'TemporadaFechaFin'=>date('d/m/Y',strtotime($firstRow->TemporadaFechaFin)));
			
			foreach ($rows as $row) {
				$bodega->Articulos[$row->IdArticulo] = (object)array(
					'IdArticulo'=>$row->IdArticulo,'NombreArticulo'=>$row->NombreArticulo,'CodigoArticulo'=>$row->CodigoArticulo,
					'Existencia'=>$row->StockInicial,'PrecioMonedaLocal'=>$row->PrecioMonedaLocal,'PorcentajeComisionVenta'=>$row->PorcentajeComisionVenta,
					'Reposiciones'=>$row->NumeroReposiciones,'Ajustes'=>$row->NumeroAjustes,'Devoluciones'=>$row->NumeroDevoluciones,
					'Ventas'=>$row->NumeroVentas,'VentasPromo'=>$row->NumeroVentasPromo,'PorcentajeDescuentoAplicado'=>$row->PorcentajeDescuentoAplicado,
					'PrecioPromoMonedaLocal'=>$row->PrecioPromoMonedaLocal,'TotalNeto'=>$row->TotalNeto,'StockFinal'=>$row->StockFinal,			
				);
			}
			
			$resultset[$firstRow->IdBodega] = $bodega;
		}
		return $resultset;
	}
	
}