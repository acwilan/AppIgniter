<?php

class Gasto extends CRUD_Controller {

	function __construct() {
		parent::__construct('crud/gasto','gasto','g.IdGasto');
		$this->load->model('Gasto_model','gasto_model',TRUE);
	}
	
	protected function _set_command() {
		$this->db->select("g.*, t.Nombre AS Temporada, p.Nombre AS Proveedor, tg.Nombre AS TipoGasto, b.Nombre AS Bodega, a.Nombre AS Articulo, CONCAT(m.IdMoneda,' ',g.Monto) AS CantidadMoneda",FALSE)
			->from('gasto AS g')
			->join('temporada AS t','g.IdTemporada = t.IdTemporada','inner')
			->join('pais AS pa','t.IdPais = pa.IdPais','inner')
			->join('moneda AS m','pa.IdMoneda = m.IdMoneda','inner')
			->join('proveedor AS p','g.IdProveedor = p.IdProveedor','left')
			->join('tipo_gasto AS tg','g.IdTipoGasto = tg.IdTipoGasto','inner')
			->join('bodega AS b','g.IdBodega = b.IdBodega','left')
			->join('articulo AS a','g.IdArticulo = a.IdArticulo','left');
	}
	
	function reporte() {
		$data = $this->input->post('data');
		$show = TRUE;
		if (!empty($data)) {
			$this->data_parts['report'] = $this->gasto_model->reporte_gasto($this->season, $data);
			$this->data_parts['css_files'] []= 'reports.css';
			$show = FALSE;
		}
		
		$this->crud->form_view = 'reporte_gastos';
		
		$this->_show_header($show);
		
		$this->crud->data_form(NULL, NULL,
			array(
				'operation'=>'reporte_gasto',
				'legend'=>'Reporte de gastos',
			),
			array(
				'module_name'=>$this->module_name,
				'table_name'=>$this->table_name,
				'primary_key'=>$this->primary_key,
				'catalog_state'=>$this->catalog_state,
			),
			$this->data_parts);
		
		$this->_show_footer($show);
	}
	
	protected function _insert($data) {
		$data['IdTemporada'] = $this->season->IdTemporada;
		return parent::_insert($data);
	}
}