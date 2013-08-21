<?php

class Liquidacion_utilidad extends CRUD_Controller {

	function __construct() {
		parent::__construct('crud/liquidacion_utilidad','liquidacion_utilidad','IdLiquidacionUtilidad');
	}
	
	protected function _set_command() {
		$this->db->select('lu.*')
			->from('liquidacion_utilidad AS lu')
			->join('temporada AS t','lu.IdTemporada = t.IdTemporada','inner')
			->join('esquema_utilidad AS eu','lu.IdEsquemaUtilidad = eu.IdEsquemaUtilidad','inner')
			->where('t.IdTemporada',$this->season->IdTemporada);
	}
	
	protected function _get_form_columns() {
		$cols = parent::_get_form_columns();
		foreach ($cols as $col)
			if ($col->type == 'money')
				$col->symbol = $this->season->IdMoneda;
		return $cols;
	}
	
	protected function _get_grid_columns() {
		$cols = parent::_get_grid_columns();
		foreach ($cols as $col)
			if ($col->type == 'money')
				$col->symbol = $this->season->IdMoneda;
		return $cols;
	}
	
	protected function _insert($data) {
		// esquema utilidad
		$query = $this->CI->db
			->where('EsActivo',1)
			->get('esquema_utilidad');
		if ($query->num_rows() > 1) {
			$this->_add_error('error','Hay mas de un esquema de utilidad activos');
			return FALSE;
		}
		$esquema = $query->num_rows() > 0 ? $query->row() : NULL;
		if (!empty($esquema)) {
			$query = $this->CI->db
				->where('IdEsquemaUtilidad',$esquema->IdEsquemaUtilidad)
				->get('esquema_utilidad_detalle');
			$esquema->Detalles = $query->result();
		}
		else {
			$this->_add_error('error','No hay esquemas de utilidad activos');
			return FALSE;
		}
		
		// validaciones
		$errors = FALSE;
		$fip = explode('-',$data['FechaInicioPeriodo']); $ffp = explode('-',$data['FechaFinPeriodo']);
		if (count($fip) != 3 || count($ffp) != 3) {
			$errors = TRUE;
			count($fip) != 3 && $this->form_validation->set_message('FechaInicioPeriodo','La fecha de inicio es invalida');
			count($ffp) != 3 && $this->form_validation->set_message('FechaFinPeriodo','La fecha de fin es invalida');
		}
		else {
			if (!checkdate($fip[1],$fip[2],$fip[0]) || !checkdate($ffp[1],$ffp[2],$ffp[0])) {
				$errors = TRUE;
				!checkdate($fip[1],$fip[2],$fip[0]) && $this->form_validation->set_message('FechaInicioPeriodo','La fecha de inicio es invalida');
				!checkdate($ffp[1],$ffp[2],$ffp[0]) && $this->form_validation->set_message('FechaFinPeriodo','La fecha de fin es invalida');
			}
		}
		if (!$errors && strtotime($data['FechaInicioPeriodo']) > strtotime($data['FechaFinPeriodo'])) {
			$errors = TRUE;
			$this->form_validation->set_message('FechaInicioPeriodo','La fecha de inicio debe ser menor a la fecha de fin');
		}
		
		if (!$errors && strtotime($data['FechaInicioPeriodo']) < strtotime($this->season->FechaInicio) ||
			strtotime($data['FechaFinPeriodo']) > strtotime($this->season->FechaFin)) {
			$errors = TRUE;
			$this->form_validation->set_message('FechaInicioPeriodo','El rango de fechas de la liquidación se encuentra afuera del rango de la temporada actual. Verifique las fechas.');
		}
		else {
			$query = $this->CI->db->select('COUNT(lu.IdLiquidacionUtilidad) AS Cuenta',FALSE)
				->from('liquidacion_utilidad AS lu')
				->where('lu.FechaInicioPeriodo >=',$data['FechaInicioPeriodo'])
				->where('lu.FechaFinPeriodo <=',$data['FechaFinPeriodo'])
				->or_where("(lu.FechaFinPeriodo >= '{$data['FechaInicioPeriodo']}' AND lu.FechaInicioPeriodo <= '{$data['FechaFinPeriodo']}')",NULL,FALSE)
				->group_by('lu.IdLiquidacionUtilidad')
				->get();
			$row = $query->num_rows() > 0 ? $query->row() : NULL;
			$ct = !empty($row) ? $row->Cuenta : 0;
			if ($ct > 0) {
				$errors = TRUE;
				$this->form_validation->set_message('FechaInicioPeriodo','Existe un traslape de fechas con otras liquidaciones anteriores. Verifique.');
			}
		}
		if ($this->form_validation->has_errors()) return FALSE;
		
		// realizar calculos
		$totales = (object)array(
				'totalGastosMonedaLocal'=>0.00,
				'totalVentasBrutasMonedaLocal'=>0.00,
				'totalComisionesMonedaLocal'=>0.00,
				'totalVentasNetasMonedaLocal'=>0.00,
				'totalUtilidadMonedaLocal'=>0.00,
			);
		// calcular total de gastos
		$query = $this->CI->db->select("ROUND(SUM(g.Monto * tc.TipoCambioDolar)) AS TotalGastosLocal",FALSE)
			->from('gasto AS g')
			->join('temporada AS t','g.IdTemporada = t.IdTemporada','inner')
			->join('tipo_cambio AS tc','t.IdTemporada = tc.IdTemporada','inner')
			->where('g.Fecha >=',$data['FechaInicioPeriodo'])
			->where('g.Fecha <=',$data['FechaFinPeriodo'])
			->get();
		$row = $query->num_rows() > 0 ? $query->row() : NULL;
		$totales->totalGastosMonedaLocal = !empty($row) ? $row->TotalGastosLocal : 0.00;
		
		// calculo de ventas
		$query = $this->CI->db->select("
				SUM(
					(ROUND(rd.PrecioAplicado * tc.TipoCambioDolar,2) * rd.NumeroVentas) 
					+ 
					(ROUND(rd.PrecioAplicadoPromo * tc.TipoCambioDolar,2) * rd.NumeroVentasPromo)
				) AS TotalVentas,
				SUM(
					(
						(ROUND(rd.PrecioAplicado * tc.TipoCambioDolar,2) * rd.NumeroVentas)
						+ 
						(ROUND(rd.PrecioAplicadoPromo * tc.TipoCambioDolar,2) * rd.NumeroVentasPromo)
					) *
					(rd.PorcentajeComisionVentaAplicado / 100)
				) AS TotalComision
			",FALSE)
			->from('rendicion AS r')
			->join('rendicion_detalle AS rd','r.IdRendicion = rd.IdRendicion','inner')
			->join('temporada AS t','r.IdTemporada = t.IdTemporada','inner')
			->join('tipo_cambio AS tc','t.IdTemporada = tc.IdTemporada','inner')
			->where('r.Fecha >=',$data['FechaInicioPeriodo'])
			->where('r.Fecha <=',$data['FechaFinPeriodo'])
			->get();
		$row = $query->num_rows() > 0 ? $query->row() : NULL;
		$totales->totalVentasBrutasMonedaLocal = !empty($row) ? $row->TotalVentas : 0.00;
		$totales->totalComisionesMonedaLocal = !empty($row) ? $row->TotalComision : 0.00;
		$totales->totalVentasNetasMonedaLocal = $totales->totalVentasBrutasMonedaLocal - $totales->totalComisionesMonedaLocal;
		$totales->totalUtilidadMonedaLocal = $totales->totalVentasNetasMonedaLocal - $totales->totalGastosMonedaLocal;
		
		if ($totales->totalUtilidadMonedaLocal <= 0) {
			$this->_add_error('error','No hay utilidades a liquidar o los gastos superaron a las ventas netas (perdida).');
			return FALSE;
		}
		
		$liq = array(
			'TotalVentasBrutas'=>$totales->totalVentasBrutasMonedaLocal,
			'TotalComisiones'=>$totales->totalComisionesMonedaLocal,
			'TotalVentasNetas'=>$totales->totalVentasNetasMonedaLocal,
			'TotalGastos'=>$totales->totalGastosMonedaLocal,
			'TotalUtilidad'=>$totales->totalUtilidadMonedaLocal,
			'IdTemporada'=>$this->season->IdTemporada,
			'FechaInicioPeriodo'=>$data['FechaInicioPeriodo'],
			'FechaFinPeriodo'=>$data['FechaFinPeriodo'],
			'IdEsquemaUtilidad'=>$esquema->IdEsquemaUtilidad,
			'FechaLiquidacion'=>date('Y-m-d H:i:s'),
			'Observaciones'=>$data['Observaciones'],
		);
		$this->CI->db->insert('liquidacion_utilidad',$liq);
		$liq['IdLiquidacionUtilidad'] = $this->CI->db->insert_id();
		
		foreach ($esquema->Detalles as $detalle) {
			$this->CI->db->insert('liquidacion_utilidad_detalle',array(
				'IdLiquidacionUtilidad'=>$liq['IdLiquidacionUtilidad'],
				'IdEmpleado'=>$detalle->IdEmpleado,
				'PorcentajeUtilidadUtilizado'=>$detalle->PorcentajeUtilidad,
				'TotalUtilidad'=>$liq['TotalUtilidad'],
			));
		}
		
		return $liq['IdLiquidacionUtilidad'];
	}
}