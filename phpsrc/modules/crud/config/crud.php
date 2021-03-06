<?php

$config['grid_columns'] = array(
		'articulo'=>array(
				(object)array(
						'name'=>'IdArticulo',
						'title'=>'&nbsp;',
						'type'=>'int',
						'key'=>TRUE,
						'hidden'=>FALSE,
					),
				(object)array(
						'name'=>'CodigoArticulo',
						'title'=>'Codigo',
						'type'=>'string',
						'key'=>FALSE,
						'hidden'=>FALSE,
					),
				(object)array(
						'name'=>'Nombre',
						'title'=>'Nombre',
						'type'=>'string',
						'key'=>FALSE,
						'hidden'=>FALSE,
					),
				(object)array(
						'name'=>'Costo',
						'title'=>'Costo',
						'type'=>'money',
						'key'=>FALSE,
						'hidden'=>FALSE,
						'symbol'=>'$',
					),
				(object)array(
						'name'=>'Precio',
						'title'=>'Precio',
						'type'=>'money',
						'key'=>FALSE,
						'hidden'=>FALSE,
						'symbol'=>'$',
					),
				(object)array(
						'name'=>'EstadoArticulo',
						'title'=>'Estado',
						'type'=>'string',
						'key'=>FALSE,
						'hidden'=>FALSE,
					),
				(object)array(
						'name'=>'CategoriaArticulo',
						'title'=>'Categoria',
						'type'=>'string',
						'key'=>FALSE,
						'hidden'=>FALSE,
					),
				(object)array(
						'name'=>'PorcentajeComisionVenta',
						'title'=>'% Comision',
						'type'=>'string',
						'key'=>FALSE,
						'hidden'=>FALSE,
					),
				(object)array(
						'name'=>'ManejaStock',
						'title'=>'Maneja Stock',
						'type'=>'bool',
						'key'=>FALSE,
						'hidden'=>FALSE,
					),
			),
		'articulo_bodega'=>array(
				(object)array(
						'name'=>'IdArticuloBodega',
						'title'=>'&nbsp;',
						'type'=>'int',
						'key'=>TRUE,
						'hidden'=>FALSE,
					),
				(object)array(
						'name'=>'Articulo',
						'title'=>'Articulo',
						'type'=>'string',
						'key'=>FALSE,
						'hidden'=>FALSE,
						'sortable'=>TRUE,
						'search'=>(object)array('field'=>'a.Nombre'),
					),
				(object)array(
						'name'=>'Bodega',
						'title'=>'Bodega',
						'type'=>'string',
						'key'=>FALSE,
						'hidden'=>FALSE,
						'sortable'=>TRUE,
						'search'=>(object)array('field'=>'b.Nombre'),
					),
				(object)array(
						'name'=>'Existencia',
						'title'=>'Existencia',
						'type'=>'int',
						'key'=>FALSE,
						'hidden'=>FALSE,
						'sortable'=>TRUE,
						'search'=>TRUE,
					),
				(object)array(
						'name'=>'UtilizaPorcentajeComisionVentaPropio',
						'title'=>'Usa % venta propio',
						'type'=>'bool',
						'key'=>FALSE,
						'hidden'=>FALSE,
					),
			),
		'bodega'=>array(
				(object)array(
						'name'=>'IdBodega',
						'title'=>'&nbsp;',
						'type'=>'int',
						'key'=>TRUE,
						'hidden'=>FALSE,
					),
				(object)array(
						'name'=>'Nombre',
						'title'=>'Nombre',
						'type'=>'string',
						'key'=>FALSE,
						'hidden'=>FALSE,
					),
				(object)array(
						'name'=>'EstadoBodega',
						'title'=>'Estado',
						'type'=>'string',
						'key'=>FALSE,
						'hidden'=>FALSE,
					),
				(object)array(
						'name'=>'TipoBodega',
						'title'=>'Tipo',
						'type'=>'string',
						'key'=>FALSE,
						'hidden'=>FALSE,
					),
				(object)array(
						'name'=>'BodegaDespacho',
						'title'=>'Bodega Superior',
						'type'=>'string',
						'key'=>FALSE,
						'hidden'=>FALSE,
					),
			),
		'carga'=>array(
				(object)array(
						'name'=>'IdCarga',
						'title'=>'&nbsp;',
						'type'=>'int',
						'key'=>TRUE,
						'hidden'=>FALSE,
					),
				(object)array(
						'name'=>'Temporada',
						'title'=>'Temporada',
						'type'=>'string',
						'key'=>FALSE,
						'hidden'=>FALSE,
					),
				(object)array(
						'name'=>'BodegaOrigen',
						'title'=>'Bodega de Origen',
						'type'=>'string',
						'key'=>FALSE,
						'hidden'=>FALSE,
					),
				(object)array(
						'name'=>'BodegaDestino',
						'title'=>'Bodega Destino',
						'type'=>'string',
						'key'=>FALSE,
						'hidden'=>FALSE,
					),
				(object)array(
						'name'=>'Articulo',
						'title'=>'Art&iacute;culo',
						'type'=>'string',
						'key'=>FALSE,
						'hidden'=>FALSE,
					),
				(object)array(
						'name'=>'Cantidad',
						'title'=>'Cantidad',
						'type'=>'integer',
						'key'=>FALSE,
						'hidden'=>FALSE,
					),
				(object)array(
						'name'=>'Fecha',
						'title'=>'Fecha',
						'type'=>'date',
						'key'=>FALSE,
						'hidden'=>FALSE,
					),
				(object)array(
						'name'=>'TipoCarga',
						'title'=>'Tipo de Carga',
						'type'=>'string',
						'key'=>FALSE,
						'hidden'=>FALSE,
					),
			),
		'categoria_articulo'=>array(
				(object)array(
						'name'=>'IdCategoriaArticulo',
						'title'=>'&nbsp;',
						'type'=>'int',
						'key'=>TRUE,
						'hidden'=>FALSE,
					),
				(object)array(
						'name'=>'Nombre',
						'title'=>'Nombre',
						'type'=>'string',
						'key'=>FALSE,
						'hidden'=>FALSE,
					),
				(object)array(
						'name'=>'CategoriaSuperior',
						'title'=>'CategoriaSuperior',
						'type'=>'string',
						'key'=>FALSE,
						'hidden'=>FALSE,
					),
			),
		'empleado'=>array(
				(object)array(
						'name'=>'IdEmpleado',
						'title'=>'',
						'type'=>'int',
						'key'=>TRUE,
						'hidden'=>FALSE,
					),
				(object)array(
						'name'=>'NombreCompleto',
						'title'=>'Nombre Completo',
						'type'=>'string',
						'key'=>FALSE,
						'hidden'=>FALSE,
					),
			),
		'esquema_utilidad'=>array(
				(object)array(
						'name'=>'IdEsquemaUtilidad',
						'title'=>'',
						'type'=>'int',
						'key'=>TRUE,
						'hidden'=>FALSE,
					),
				(object)array(
						'name'=>'FechaCreacion',
						'title'=>'Fecha Creacion',
						'type'=>'date',
						'key'=>FALSE,
						'hidden'=>FALSE,
					),
				(object)array(
						'name'=>'EsActivo',
						'title'=>'Activo',
						'type'=>'bool',
						'key'=>FALSE,
						'hidden'=>FALSE,
					),
			),
		'fondo_fijo'=>array(
				(object)array(
						'name'=>'IdFondoFijo',
						'title'=>'&nbsp;',
						'type'=>'int',
						'key'=>TRUE,
						'hidden'=>FALSE,
					),
				(object)array(
						'name'=>'FondoFijo',
						'title'=>'Fondo Fijo',
						'type'=>'decimal',
						'key'=>FALSE,
						'hidden'=>FALSE,
						'rules'=>'required|trim|decimal',
					),
			),
		'gasto'=>array(
				(object)array(
						'name'=>'IdGasto',
						'title'=>'&nbsp;',
						'type'=>'int',
						'key'=>TRUE,
						'hidden'=>FALSE,
					),
				(object)array(
						'name'=>'Temporada',
						'title'=>'Temporada',
						'type'=>'string',
						'key'=>FALSE,
						'hidden'=>FALSE,
					),
				(object)array(
						'name'=>'Proveedor',
						'title'=>'Proveedor',
						'type'=>'string',
						'key'=>FALSE,
						'hidden'=>FALSE,
					),
				(object)array(
						'name'=>'Fecha',
						'title'=>'Fecha',
						'type'=>'date',
						'key'=>FALSE,
						'hidden'=>FALSE,
					),
				(object)array(
						'name'=>'TipoGasto',
						'title'=>'Tipo de Gasto',
						'type'=>'string',
						'key'=>FALSE,
						'hidden'=>FALSE,
					),
				(object)array(
						'name'=>'CantidadMoneda',
						'title'=>'Monto',
						'type'=>'string',
						'key'=>FALSE,
						'hidden'=>FALSE,
					),
			),
		'liquidacion_utilidad'=>array(
				(object)array(
						'name'=>'IdLiquidacionUtilidad',
						'title'=>'&nbsp;',
						'type'=>'int',
						'key'=>TRUE,
						'hidden'=>FALSE,
					),
				(object)array(
						'name'=>'FechaLiquidacion',
						'title'=>'Fecha Liq.',
						'type'=>'string',
						'key'=>FALSE,
						'hidden'=>FALSE,
						'sortable'=>TRUE,
					),
				(object)array(
						'name'=>'FechaInicioPeriodo',
						'title'=>'Inicio Periodo',
						'type'=>'string',
						'key'=>FALSE,
						'hidden'=>FALSE,
						'sortable'=>TRUE,
					),
				(object)array(
						'name'=>'FechaFinPeriodo',
						'title'=>'Fin Periodo',
						'type'=>'string',
						'key'=>FALSE,
						'hidden'=>FALSE,
						'sortable'=>TRUE,
					),
				(object)array(
						'name'=>'TotalGastos',
						'title'=>'Gastos',
						'type'=>'money',
						'key'=>FALSE,
						'hidden'=>FALSE,
						'sortable'=>TRUE,
						'symbol'=>'$',
					),
				(object)array(
						'name'=>'TotalVentasBrutas',
						'title'=>'Ventas Brutas',
						'type'=>'money',
						'key'=>FALSE,
						'hidden'=>FALSE,
						'sortable'=>TRUE,
						'symbol'=>'$',
					),
				(object)array(
						'name'=>'TotalComisiones',
						'title'=>'Comisiones',
						'type'=>'money',
						'key'=>FALSE,
						'hidden'=>FALSE,
						'sortable'=>TRUE,
						'symbol'=>'$',
					),
				(object)array(
						'name'=>'TotalVentasNetas',
						'title'=>'Ventas Netas',
						'type'=>'money',
						'key'=>FALSE,
						'hidden'=>FALSE,
						'sortable'=>TRUE,
						'symbol'=>'$',
					),
				(object)array(
						'name'=>'TotalUtilidad',
						'title'=>'Utilidad',
						'type'=>'money',
						'key'=>FALSE,
						'hidden'=>FALSE,
						'sortable'=>TRUE,
						'symbol'=>'$',
					),
			),
		'proveedor'=>array(
				(object)array(
						'name'=>'IdProveedor',
						'title'=>'&nbsp;',
						'type'=>'int',
						'key'=>TRUE,
						'hidden'=>FALSE,
					),
				(object)array(
						'name'=>'Nombre',
						'title'=>'Nombre',
						'type'=>'string',
						'key'=>FALSE,
						'hidden'=>FALSE,
					),
				(object)array(
						'name'=>'Pais',
						'title'=>'Pais',
						'type'=>'string',
						'key'=>FALSE,
						'hidden'=>FALSE,
					),
				(object)array(
						'name'=>'Ciudad',
						'title'=>'Ciudad',
						'type'=>'string',
						'key'=>FALSE,
						'hidden'=>FALSE,
					),
				(object)array(
						'name'=>'TelefonoMovil',
						'title'=>'Telefono Movil',
						'type'=>'string',
						'key'=>FALSE,
						'hidden'=>FALSE,
					),
			),
		'rendicion'=>array(
				(object)array(
						'name'=>'IdRendicion',
						'title'=>'&nbsp;',
						'type'=>'int',
						'key'=>TRUE,
						'hidden'=>FALSE,
					),
				(object)array(
						'name'=>'PuestoVenta',
						'title'=>'Puesto de venta',
						'type'=>'string',
						'key'=>FALSE,
						'hidden'=>FALSE,
						'sortable'=>TRUE,
						'search'=>(object)array('field'=>'b.Nombre'),
					),
				(object)array(
						'name'=>'Fecha',
						'title'=>'Fecha',
						'type'=>'date',
						'key'=>FALSE,
						'hidden'=>FALSE,
						'sortable'=>TRUE,
						'search'=>(object)array('field'=>'r.Fecha'),
					),
				(object)array(
						'name'=>'Observaciones',
						'title'=>'Observaciones',
						'type'=>'text',
						'key'=>FALSE,
						'hidden'=>FALSE,
					),
			),
		'temporada'=>array(
				(object)array(
						'name'=>'IdTemporada',
						'title'=>'&nbsp;',
						'type'=>'int',
						'key'=>TRUE,
						'hidden'=>FALSE,
					),
				(object)array(
						'name'=>'Pais',
						'title'=>'Pais',
						'type'=>'string',
						'key'=>FALSE,
						'hidden'=>FALSE,
					),
				(object)array(
						'name'=>'Nombre',
						'title'=>'Nombre',
						'type'=>'string',
						'key'=>FALSE,
						'hidden'=>FALSE,
					),
				(object)array(
						'name'=>'FechaInicio',
						'title'=>'Inicio',
						'type'=>'date',
						'key'=>FALSE,
						'hidden'=>FALSE,
					),
				(object)array(
						'name'=>'FechaFin',
						'title'=>'Fin',
						'type'=>'date',
						'key'=>FALSE,
						'hidden'=>FALSE,
					),
			),
		'tipo_gasto'=>array(
				(object)array(
						'name'=>'IdTipoGasto',
						'title'=>'&nbsp;',
						'type'=>'int',
						'key'=>TRUE,
						'hidden'=>FALSE,
					),
				(object)array(
						'name'=>'Nombre',
						'title'=>'Nombre',
						'type'=>'string',
						'key'=>FALSE,
						'hidden'=>FALSE,
					),
				(object)array(
						'name'=>'TipoGastoSuperior',
						'title'=>'Tipo Gasto Superior',
						'type'=>'string',
						'key'=>FALSE,
						'hidden'=>FALSE,
					),
				(object)array(
						'name'=>'Bodega',
						'title'=>'Bodega',
						'type'=>'string',
						'key'=>FALSE,
						'hidden'=>FALSE,
					),
			),
	);

$config['form_fields'] = array(
		'articulo'=>array(
				(object)array(
						'name'=>'CodigoArticulo',
						'title'=>'Codigo',
						'type'=>'string',
						'key'=>FALSE,
						'hidden'=>FALSE,
						'rules'=>'trim|required',
					),
				(object)array(
						'name'=>'Nombre',
						'title'=>'Nombre',
						'type'=>'string',
						'key'=>FALSE,
						'hidden'=>FALSE,
						'rules'=>'trim|required',
					),
				(object)array(
						'name'=>'Descripcion',
						'title'=>'Descripcion',
						'type'=>'text',
						'key'=>FALSE,
						'hidden'=>FALSE,
					),
				(object)array(
						'name'=>'MinimoInventario',
						'title'=>'Minimo Inventario',
						'type'=>'string',
						'key'=>FALSE,
						'hidden'=>FALSE,
						'rules'=>'trim|integer',
					),
				(object)array(
						'name'=>'MaximoInventario',
						'title'=>'Maximo Inventario',
						'type'=>'string',
						'key'=>FALSE,
						'hidden'=>FALSE,
						'rules'=>'trim|integer',
					),
				(object)array(
						'name'=>'IdCategoriaArticulo',
						'title'=>'Categoria',
						'type'=>'related',
						'key'=>FALSE,
						'hidden'=>FALSE,
						'relation'=>(object)array(
								'table'=>'categoria_articulo',
								'field'=>'IdCategoriaArticulo',
								'display'=>'Nombre',
								'required'=>FALSE,
							),
					),
				(object)array(
						'name'=>'IdProveedor',
						'title'=>'Proveedor',
						'type'=>'related',
						'key'=>FALSE,
						'hidden'=>FALSE,
						'relation'=>(object)array(
								'table'=>'proveedor',
								'field'=>'IdProveedor',
								'display'=>'Nombre',
								'required'=>FALSE,
							),
					),
				(object)array(
						'name'=>'CodigoArticuloProveedor',
						'title'=>'Codigo Proveedor',
						'type'=>'string',
						'key'=>FALSE,
						'hidden'=>FALSE,
						'help'=>'Codigo que maneja el proveedor',
					),
				(object)array(
						'name'=>'Costo',
						'title'=>'Costo Total',
						'type'=>'money',
						'key'=>FALSE,
						'hidden'=>FALSE,
						'rules'=>'trim|required|numeric',
						'symbol'=>'$',
					),
				(object)array(
						'name'=>'Precio',
						'title'=>'Precio',
						'type'=>'money',
						'key'=>FALSE,
						'hidden'=>FALSE,
						'rules'=>'trim|required|numeric',
						'symbol'=>'$',
					),
				(object)array(
						'name'=>'IdEstadoArticulo',
						'title'=>'Estado',
						'type'=>'related',
						'key'=>FALSE,
						'hidden'=>FALSE,
						'relation'=>(object)array(
								'table'=>'estado_articulo',
								'field'=>'IdEstadoArticulo',
								'display'=>'Nombre',
							),
					),
				(object)array(
						'name'=>'PorcentajeComisionVenta',
						'title'=>'Comision',
						'type'=>'money',
						'key'=>FALSE,
						'hidden'=>FALSE,
						'symbol'=>'%',
						'rules'=>'trim|required|numeric',
					),
				(object)array(
						'name'=>'ManejaStock',
						'title'=>'Maneja Stock',
						'type'=>'bool',
						'key'=>FALSE,
						'hidden'=>FALSE,
					),
			),
		'articulo_bodega'=>array(
				(object)array(
						'name'=>'IdArticulo',
						'title'=>'Articulo',
						'type'=>'related',
						'key'=>FALSE,
						'hidden'=>FALSE,
						'relation'=>(object)array(
								'table'=>'articulo',
								'field'=>'IdArticulo',
								'display'=>'Nombre',
							),
					),
				(object)array(
						'name'=>'IdBodega',
						'title'=>'Bodega',
						'type'=>'related',
						'key'=>FALSE,
						'hidden'=>FALSE,
						'relation'=>(object)array(
								'table'=>'bodega',
								'field'=>'IdBodega',
								'display'=>'Nombre',
							),
					),
				(object)array(
						'name'=>'Existencia',
						'title'=>'Existencia',
						'type'=>'int',
						'key'=>FALSE,
						'hidden'=>FALSE,
						'rules'=>'trim|required|integer',
					),
				(object)array(
						'name'=>'UtilizaPorcentajeComisionVentaPropio',
						'title'=>'Usa % venta propio',
						'type'=>'bool',
						'key'=>FALSE,
						'hidden'=>FALSE,
					),
				(object)array(
						'name'=>'PorcentajeComisionVenta',
						'title'=>'Comision',
						'type'=>'money',
						'key'=>FALSE,
						'hidden'=>FALSE,
						'symbol'=>'%',
						'rules'=>'trim|numeric',
					),
			),
		'bodega'=>array(
				(object)array(
						'name'=>'IdBodega',
						'title'=>'ID',
						'type'=>'int',
						'key'=>TRUE,
						'hidden'=>FALSE,
					),
				(object)array(
						'name'=>'Nombre',
						'title'=>'Nombre',
						'type'=>'string',
						'key'=>FALSE,
						'hidden'=>FALSE,
						'rules'=>'trim|required',
					),
				(object)array(
						'name'=>'IdEstadoBodega',
						'title'=>'Estado',
						'type'=>'related',
						'key'=>FALSE,
						'hidden'=>FALSE,
						'relation'=>(object)array(
								'table'=>'estado_bodega',
								'field'=>'IdEstadoBodega',
								'display'=>'Nombre',
							),
					),
				(object)array(
						'name'=>'IdTipoBodega',
						'title'=>'Tipo',
						'type'=>'related',
						'key'=>FALSE,
						'hidden'=>FALSE,
						'relation'=>(object)array(
								'table'=>'tipo_bodega',
								'field'=>'IdTipoBodega',
								'display'=>'Nombre',
							),
					),
				(object)array(
						'name'=>'IdBodegaDespacho',
						'title'=>'Bodega Despacho (Superior)',
						'type'=>'related',
						'key'=>FALSE,
						'hidden'=>FALSE,
						'relation'=>(object)array(
								'table'=>'bodega',
								'field'=>'IdBodega',
								'display'=>'Nombre',
								'required'=>FALSE,
							),
					),
			),
		'carga'=>array(
				(object)array(
						'name'=>'IdCarga',
						'title'=>'ID',
						'type'=>'int',
						'key'=>TRUE,
						'hidden'=>FALSE,
					),
				(object)array(
						'name'=>'IdArticulo',
						'title'=>'Art&iacute;culo',
						'type'=>'related',
						'key'=>FALSE,
						'hidden'=>FALSE,
						'relation'=>(object)array(
								'table'=>'articulo',
								'field'=>'IdArticulo',
								'display'=>'Nombre',
							),
					),
				(object)array(
						'name'=>'IdBodegaOrigen',
						'title'=>'Bodega Origen',
						'type'=>'related',
						'key'=>FALSE,
						'hidden'=>FALSE,
						'relation'=>(object)array(
								'table'=>'bodega',
								'field'=>'IdBodega',
								'display'=>'Nombre',
							),
					),
				(object)array(
						'name'=>'IdBodegaDestino',
						'title'=>'Bodega Destino',
						'type'=>'related',
						'key'=>FALSE,
						'hidden'=>FALSE,
						'relation'=>(object)array(
								'table'=>'bodega',
								'field'=>'IdBodega',
								'display'=>'Nombre',
							),
					),
				(object)array(
						'name'=>'Cantidad',
						'title'=>'Cantidad',
						'type'=>'int',
						'key'=>FALSE,
						'hidden'=>FALSE,
						'rules'=>'trim|required|integer',
					),
				(object)array(
						'name'=>'Fecha',
						'title'=>'Fecha',
						'type'=>'date',
						'key'=>FALSE,
						'hidden'=>FALSE,
					),
				(object)array(
						'name'=>'IdTipoCarga',
						'title'=>'Tipo de Carga',
						'type'=>'related',
						'key'=>FALSE,
						'hidden'=>FALSE,
						'relation'=>(object)array(
								'table'=>'tipo_carga',
								'field'=>'IdTipoCarga',
								'display'=>'Nombre',
								'required'=>FALSE,
							),
					),
				(object)array(
						'name'=>'Observaciones',
						'title'=>'Observaciones',
						'type'=>'text',
						'key'=>FALSE,
						'hidden'=>FALSE,
					),
			),
		'categoria_articulo'=>array(
				(object)array(
						'name'=>'IdCategoriaArticulo',
						'title'=>'',
						'type'=>'int',
						'key'=>TRUE,
						'hidden'=>FALSE,
					),
				(object)array(
						'name'=>'Nombre',
						'title'=>'Nombre',
						'type'=>'string',
						'key'=>FALSE,
						'hidden'=>FALSE,
					),
				(object)array(
						'name'=>'IdCategoriaSuperior',
						'title'=>'Categoria Superior',
						'type'=>'related',
						'key'=>FALSE,
						'hidden'=>FALSE,
						'relation'=>(object)array(
								'table'=>'categoria_articulo',
								'field'=>'IdCategoriaArticulo',
								'display'=>'Nombre',
								'required'=>FALSE,
							),
					),
			),
		'empleado'=>array(
				(object)array(
						'name'=>'IdEmpleado',
						'title'=>'',
						'type'=>'int',
						'key'=>TRUE,
						'hidden'=>FALSE,
					),
				(object)array(
						'name'=>'PrimerNombre',
						'title'=>'Primer Nombre',
						'type'=>'string',
						'key'=>FALSE,
						'hidden'=>FALSE,
					),
				(object)array(
						'name'=>'SegundoNombre',
						'title'=>'Segundo Nombre',
						'type'=>'string',
						'key'=>FALSE,
						'hidden'=>FALSE,
					),
				(object)array(
						'name'=>'ApellidoPaterno',
						'title'=>'Primer Apellido',
						'type'=>'string',
						'key'=>FALSE,
						'hidden'=>FALSE,
					),
				(object)array(
						'name'=>'ApellidoMaterno',
						'title'=>'Segundo Apellido',
						'type'=>'string',
						'key'=>FALSE,
						'hidden'=>FALSE,
					),
			),
		'esquema_utilidad'=>array(
				(object)array(
						'name'=>'IdEsquemaUtilidad',
						'title'=>'',
						'type'=>'int',
						'key'=>TRUE,
						'hidden'=>FALSE,
					),
				(object)array(
						'name'=>'FechaCreacion',
						'title'=>'Fecha Creacion',
						'type'=>'date',
						'key'=>FALSE,
						'hidden'=>FALSE,
						'default'=>date('Y-m-d'),
					),
				(object)array(
						'name'=>'EsActivo',
						'title'=>'Activo',
						'type'=>'bool',
						'key'=>FALSE,
						'hidden'=>FALSE,
					),
			),
		'gasto'=>array(
				(object)array(
						'name'=>'IdGasto',
						'title'=>'',
						'type'=>'int',
						'key'=>TRUE,
						'hidden'=>FALSE,
					),
				(object)array(
						'name'=>'IdProveedor',
						'title'=>'Proveedor',
						'type'=>'related',
						'key'=>FALSE,
						'hidden'=>FALSE,
						'relation'=>(object)array(
								'table'=>'proveedor',
								'field'=>'IdProveedor',
								'display'=>'Nombre',
								'required'=>FALSE,
							),
					),
				(object)array(
						'name'=>'Fecha',
						'title'=>'Fecha',
						'type'=>'date',
						'key'=>FALSE,
						'hidden'=>FALSE,
						'default'=>date('Y-m-d'),
						'rules'=>'trim|required',
					),
				(object)array(
						'name'=>'IdTipoGasto',
						'title'=>'Tipo de Gasto',
						'type'=>'related',
						'key'=>FALSE,
						'hidden'=>FALSE,
						'relation'=>(object)array(
								'table'=>'tipo_gasto',
								'field'=>'IdTipoGasto',
								'display'=>'Nombre',
							),
					),
				(object)array(
						'name'=>'IdBodega',
						'title'=>'Bodega',
						'type'=>'related',
						'key'=>FALSE,
						'hidden'=>FALSE,
						'relation'=>(object)array(
								'table'=>'bodega',
								'field'=>'IdBodega',
								'display'=>'Nombre',
								'required'=>FALSE,
							),
					),
				(object)array(
						'name'=>'IdArticulo',
						'title'=>'Articulo',
						'type'=>'related',
						'key'=>FALSE,
						'hidden'=>FALSE,
						'relation'=>(object)array(
								'table'=>'articulo',
								'field'=>'IdArticulo',
								'display'=>'Nombre',
								'required'=>FALSE,
							),
					),
				(object)array(
						'name'=>'Monto',
						'title'=>'Monto',
						'type'=>'money',
						'key'=>FALSE,
						'hidden'=>FALSE,
						'rules'=>'trim|required|numeric',
					),
				(object)array(
						'name'=>'Observaciones',
						'title'=>'Observaciones',
						'type'=>'text',
						'key'=>FALSE,
						'hidden'=>FALSE,
					),
			),
		'liquidacion_utilidad'=>array(
				(object)array(
						'name'=>'IdLiquidacionUtilidad',
						'title'=>'&nbsp;',
						'type'=>'int',
						'key'=>TRUE,
						'hidden'=>FALSE,
					),
				(object)array(
						'name'=>'FechaInicioPeriodo',
						'title'=>'Inicio',
						'type'=>'date',
						'key'=>FALSE,
						'hidden'=>FALSE,
						'rules'=>'trim|required',
						'disable_on_edit'=>TRUE,
					),
				(object)array(
						'name'=>'FechaFinPeriodo',
						'title'=>'Fin',
						'type'=>'date',
						'key'=>FALSE,
						'hidden'=>FALSE,
						'rules'=>'trim|required',
						'disable_on_edit'=>TRUE,
					),
				(object)array(
						'name'=>'Observaciones',
						'title'=>'Observaciones',
						'type'=>'text',
						'key'=>FALSE,
						'hidden'=>FALSE,
					),
			),
		'proveedor'=>array(
				(object)array(
						'name'=>'IdProveedor',
						'title'=>'',
						'type'=>'int',
						'key'=>TRUE,
						'hidden'=>FALSE,
					),
				(object)array(
						'name'=>'Nombre',
						'title'=>'Nombre',
						'type'=>'string',
						'key'=>FALSE,
						'hidden'=>FALSE,
						'rules'=>'trim|required',
					),
				(object)array(
						'name'=>'IdPais',
						'title'=>'Pais',
						'type'=>'related',
						'key'=>FALSE,
						'hidden'=>FALSE,
						'relation'=>(object)array(
								'table'=>'pais',
								'field'=>'IdPais',
								'display'=>'Nombre',
							),
					),
				(object)array(
						'name'=>'Ciudad',
						'title'=>'Ciudad',
						'type'=>'string',
						'key'=>FALSE,
						'hidden'=>FALSE,
					),
				(object)array(
						'name'=>'Direccion',
						'title'=>'Direccion',
						'type'=>'string',
						'key'=>FALSE,
						'hidden'=>FALSE,
					),
				(object)array(
						'name'=>'TelefonoMovil',
						'title'=>'Telefono Movil',
						'type'=>'string',
						'key'=>FALSE,
						'hidden'=>FALSE,
					),
				(object)array(
						'name'=>'TelefonoOficina',
						'title'=>'Telefono Oficina',
						'type'=>'string',
						'key'=>FALSE,
						'hidden'=>FALSE,
					),
				(object)array(
						'name'=>'Nit',
						'title'=>'NIT',
						'type'=>'string',
						'key'=>FALSE,
						'hidden'=>FALSE,
					),
			),
		'rendicion'=>array(
				(object)array(
						'name'=>'IdRendicion',
						'title'=>'',
						'type'=>'int',
						'key'=>TRUE,
						'hidden'=>FALSE,
					),
				(object)array(
						'name'=>'IdPuestoVenta',
						'title'=>'Puesto de venta',
						'type'=>'autocomplete',
						'key'=>FALSE,
						'hidden'=>FALSE,
						'autocomplete'=>(object)array(
								'text_field'=>'PuestoVenta',
								'role'=>'PuntoDeVenta',
							),
					),
				(object)array(
						'name'=>'Fecha',
						'title'=>'Fecha',
						'type'=>'date',
						'key'=>FALSE,
						'hidden'=>FALSE,
					),
				(object)array(
						'name'=>'Observaciones',
						'title'=>'Observaciones',
						'type'=>'text',
						'key'=>FALSE,
						'hidden'=>FALSE,
					),
			),
		'temporada'=>array(
				(object)array(
						'name'=>'IdTemporada',
						'title'=>'&nbsp;',
						'type'=>'int',
						'key'=>TRUE,
						'hidden'=>FALSE,
					),
				(object)array(
						'name'=>'IdPais',
						'title'=>'Pais',
						'type'=>'related',
						'key'=>FALSE,
						'hidden'=>FALSE,
						'relation'=>(object)array(
								'table'=>'pais',
								'field'=>'IdPais',
								'display'=>'Nombre',
							),
					),
				(object)array(
						'name'=>'Nombre',
						'title'=>'Nombre',
						'type'=>'string',
						'key'=>FALSE,
						'hidden'=>FALSE,
					),
				(object)array(
						'name'=>'FechaInicio',
						'title'=>'Inicio',
						'type'=>'date',
						'key'=>FALSE,
						'hidden'=>FALSE,
					),
				(object)array(
						'name'=>'FechaFin',
						'title'=>'Fin',
						'type'=>'date',
						'key'=>FALSE,
						'hidden'=>FALSE,
					),
			),
		'tipo_cambio'=>array(
				(object)array(
						'name'=>'IdMoneda',
						'title'=>'Moneda',
						'type'=>'related',
						'key'=>FALSE,
						'hidden'=>FALSE,
						'relation'=>(object)array(
								'table'=>'moneda',
								'field'=>'IdMoneda',
								'display'=>'IdMoneda',
							),
					),
				(object)array(
						'name'=>'TipoCambioDolar',
						'title'=>'Tipo de Cambio',
						'type'=>'decimal',
						'key'=>FALSE,
						'hidden'=>FALSE,
						'rules'=>'trim|required|numeric',
					),
			),
		'tipo_gasto'=>array(
				(object)array(
						'name'=>'IdTipoGasto',
						'title'=>'',
						'type'=>'int',
						'key'=>TRUE,
						'hidden'=>FALSE,
					),
				(object)array(
						'name'=>'Nombre',
						'title'=>'Nombre',
						'type'=>'string',
						'key'=>FALSE,
						'hidden'=>FALSE,
					),
				(object)array(
						'name'=>'IdTipoGastoSuperior',
						'title'=>'Tipo Gasto Superior',
						'type'=>'related',
						'key'=>FALSE,
						'hidden'=>FALSE,
						'relation'=>(object)array(
								'table'=>'tipo_gasto',
								'field'=>'IdTipoGasto',
								'display'=>'Nombre',
								'required'=>FALSE,
							),
					),
				(object)array(
						'name'=>'IdBodega',
						'title'=>'Bodega',
						'type'=>'related',
						'key'=>FALSE,
						'hidden'=>FALSE,
						'relation'=>(object)array(
								'table'=>'bodega',
								'field'=>'IdBodega',
								'display'=>'Nombre',
								'required'=>FALSE,
							),
					),
			),
	);

	$config['detail_col_model'] = array(
			'esquema_utilidad'=>array(
					(object)array(
						'name'=>'IdEsquemaUtilidadDetalle',
						'type'=>'int',
						'title'=>'ID',
						'key'=>TRUE,
						'hidden'=>FALSE,
					),
					(object)array(
						'name'=>'IdEmpleado',
						'type'=>'autocomplete',
						'title'=>'Empleado',
						'txtFieldName'=>'Empleado',
						'role'=>'empleado',
						'key'=>FALSE,
						'hidden'=>FALSE,
					),
					(object)array(
						'name'=>'PorcentajeUtilidad',
						'type'=>'money',
						'title'=>'Pct. Utilidad',
						'key'=>FALSE,
						'hidden'=>FALSE,
						'currencySymbol'=>'%',
						'defaultValue'=>'0.00',
						'role'=>'pct-utilidad',
					),
				),
			'rendicion'=>array(
					(object)array(
						'name'=>'IdRendicionDetalle',
						'type'=>'int',
						'title'=>'ID',
						'key'=>TRUE,
						'hidden'=>FALSE,
					),
					(object)array(
						'name'=>'IdArticulo',
						'type'=>'autocomplete',
						'title'=>'Articulo',
						'txtFieldName'=>'Articulo',
						'role'=>'articulo',
						'key'=>FALSE,
						'hidden'=>FALSE,
					),
					(object)array(
						'name'=>'NumeroReposiciones',
						'type'=>'int',
						'title'=>'Repos.',
						'key'=>FALSE,
						'hidden'=>FALSE,
						'class'=>'input-mini',
						'defaultValue'=>0,
					),
					(object)array(
						'name'=>'NumeroAjustes',
						'type'=>'int',
						'title'=>'Ajust.',
						'key'=>FALSE,
						'hidden'=>FALSE,
						'class'=>'input-mini',
						'defaultValue'=>0,
					),
					(object)array(
						'name'=>'NumeroDevoluciones',
						'type'=>'int',
						'title'=>'Devol.',
						'key'=>FALSE,
						'hidden'=>FALSE,
						'class'=>'input-mini',
						'defaultValue'=>0,
					),
					(object)array(
						'name'=>'NumeroVentas',
						'type'=>'int',
						'title'=>'Venta.',
						'key'=>FALSE,
						'hidden'=>FALSE,
						'class'=>'input-mini',
						'defaultValue'=>0,
					),
					(object)array(
						'name'=>'NumeroVentasPromo',
						'type'=>'int',
						'title'=>'Venta (Promo)',
						'key'=>FALSE,
						'hidden'=>FALSE,
						'class'=>'input-mini',
						'defaultValue'=>0,
					),
				),
				'articulo_bodega'=>array(
					(object)array(
						'name'=>'IdArticulo',
						'title'=>'Articulo',
						'type'=>'related',
						'key'=>FALSE,
						'hidden'=>FALSE,
						'relation'=>(object)array(
							'table'=>'articulo',
							'field'=>'IdArticulo',
							'display'=>'Nombre',
						),
					),
					(object)array(
						'name'=>'Existencia',
						'title'=>'Existencia',
						'type'=>'int',
						'key'=>FALSE,
						'hidden'=>FALSE,
						'rules'=>'trim|required|integer',
						'align'=>'center',
					),
					(object)array(
						'name'=>'UtilizaPorcentajeComisionVentaPropio',
						'title'=>'Usa % venta propio',
						'type'=>'bool',
						'key'=>FALSE,
						'hidden'=>FALSE,
						'align'=>'center',
					),
					(object)array(
						'name'=>'PorcentajeComisionVenta',
						'title'=>'Comision',
						'type'=>'decimal',
						'key'=>FALSE,
						'hidden'=>FALSE,
						'rules'=>'trim|numeric',
						'align'=>'center',
					),
					(object)array(
						'name'=>'PorcentajeComisionVentaExtra',
						'title'=>'Comision Extra',
						'type'=>'decimal',
						'key'=>FALSE,
						'hidden'=>FALSE,
						'rules'=>'trim|numeric',
						'align'=>'center',
					),
					(object)array(
						'name'=>'PorcentajeDescuento',
						'title'=>'Descuento',
						'type'=>'decimal',
						'key'=>FALSE,
						'hidden'=>FALSE,
						'rules'=>'trim|numeric',
						'align'=>'center',
					),
				),
		);
		
$config['custom_actions'] = array(
		'temporada'=>array(
				(object)array(
					'url_format'=>'change/%s',
					'icon'=>'refresh',
					'label'=>'Usar esta',
				),
			),
		'bodega'=>array(
				(object)array(
					'url_format'=>'articulos/%s',
					'icon'=>'',
					'label'=>'Articulos',
				),
			),
	);
	
$config['form_actions'] = array(
		'liquidacion_utilidad'=>array(
				'save-close'=>(object)array(
						'label'=>'Guardar y cerrar',
						'link'=>FALSE,
						'primary'=>TRUE,
						'hidden'=>FALSE,
					),
				'save-only'=>(object)array(
						'label'=>'Guardar',
						'link'=>FALSE,
						'primary'=>FALSE,
						'hidden'=>FALSE,
					),
				'save-new'=>(object)array(
						'label'=>'Guardar y nuevo',
						'link'=>FALSE,
						'primary'=>FALSE,
						'hidden'=>FALSE,
					),
				'btn-cancel'=>(object)array(
						'label'=>'Cancelar',
						'link'=>TRUE,
						'primary'=>FALSE,
						'hidden'=>FALSE,
					),
			),
	);