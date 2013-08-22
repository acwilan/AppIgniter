<?php

$CI =& get_instance();
$CI->load->model('system/period_model');
$period = $CI->session->userdata('current_period');
if (empty($period)) {
	$period = $CI->period_model->get_current();
} elseif (!empty($period->id_periodo)) {
	$period = $CI->period_model->get($period->id_periodo);
}

$config['reports'] = (object)array(

	'filters'=>array(
		'general_stock'=>array(
			(object)array(
				'name'=>'IdBodega',
				'title'=>'Bodega',
				'type'=>'autocomplete',
				'autocomplete'=>(object)array(
					'text_field'=>'bodega',
					'role'=>'bodega',
				),
			),
			(object)array(
				'name'=>'IdArticulo',
				'title'=>'Articulo',
				'type'=>'autocomplete',
				'autocomplete'=>(object)array(
					'text_field'=>'articulo',
					'role'=>'articulo',
				),
			),
			(object)array(
				'name'=>'SortField',
				'title'=>'-- Ordenado por --',
				'type'=>'dropdown',
				'options'=>array(
					''=>'-- Ordenado por --',
					'a.nombre'=>'Articulo',
					'a.codigo_articulo'=>'Codigo',
					'Stock desc'=>'Stock',
				),
			),
			(object)array(
				'name'=>'Show',
				'title'=>'-- Mostrar --',
				'type'=>'dropdown',
				'options'=>array(
					''=>'-- Mostrar Todos --',
					'zero'=>'Art. sin stock',
					'min+0'=>'Art. con stock minimo (incl. stock 0)',
					'min-0'=>'Art. con stock minimo (excl. stock 0)',
				),
			),
			(object)array(
				'name'=>'ViewType',
				'title'=>'-- Mostrar --',
				'type'=>'dropdown',
				'options'=>array(
					'ab'=>'Articulo->Bodega',
					'ba'=>'Bodega->Articulo',
					'm'=>'Matriz',
				),
			),
		),
		'general_movements'=>array(
			(object)array(
				'name'=>'IdBodega',
				'title'=>'Bodega',
				'type'=>'autocomplete',
				'autocomplete'=>(object)array(
					'text_field'=>'bodega',
					'role'=>'bodega',
				),
			),
			(object)array(
				'name'=>'IdArticulo',
				'title'=>'Articulo',
				'type'=>'autocomplete',
				'autocomplete'=>(object)array(
					'text_field'=>'articulo',
					'role'=>'articulo',
				),
			),
		),
		'general_sales'=>array(
			(object)array(
				'name'=>'fecha',
				'title'=>'Fecha',
				'type'=>'date',
				'format'=>'M j, Y',
				'jsformat'=>'M d, yy',
				'default'=>date('M j, Y', strtotime($period->fecha_inicio)),
				'minDate'=>date('M j, Y', strtotime($period->fecha_inicio)),
				'maxDate'=>date('M j, Y', strtotime('+6 days', strtotime($period->fecha_inicio))),
			),
		),
		'general_expenses'=>array(
			(object)array(
				'name'=>'fecha',
				'title'=>'Fecha',
				'type'=>'date',
				'format'=>'M j, Y',
				'jsformat'=>'M d, yy',
				'default'=>date('M j, Y', strtotime($period->fecha_inicio)),
				'minDate'=>date('M j, Y', strtotime($period->fecha_inicio)),
				'maxDate'=>date('M j, Y', strtotime('+6 days', strtotime($period->fecha_inicio))),
			),
		),
	),
	
);