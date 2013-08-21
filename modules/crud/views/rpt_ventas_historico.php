<?php if (empty($report)) : ?>
<h1>No hay datos para este reporte con los parametros especificados</h1>
<?php else : ?>
<style type="text/css" media="print">
h2{font-size:9pt;}
h3{font-size:8pt;margin-left:1in;}
th,caption{font-size:7pt;}
td{font-size:6pt;}
.article{margin-left:2in;width:6in;}
.warehouse{margin-left:4in;width:4in;}
</style>
<style type="text/css">
h1{text-align:center;}
h2{text-align:left !important;color:#FFF;background:#888;border:1px solid #000;padding:0 10px;}
h3{color:#000;background:#CCC;border:1px solid #000;padding:0 10px;}
.article {margin-top:10px;}
.article thead th{border:1px solid #000;}
.article tbody td{border:1px solid #CCC;padding:0 3px;}
.article tbody td:first-child{border:none;text-align:right}
.warehouse {margin-top:20px;}
.warehouse caption{border:1px solid #000;text-align:center;padding:5px 0;font-weight:700;}
.warehouse thead th{border:1px solid #000;border-top:none;}
.warehouse tbody td{border:1px solid #CCC;padding:0 3px;}
.warehouse tbody td:first-child{border:none;text-align:right}
.warehouse tfoot td{border:1px solid #000;}
.warehouse tfoot td:first-child{border:none;}
</style>
<div class="row">
	<div class="span12 last report">
		<h1>Reporte de Ventas Historico</h1><hr/>
		<?php foreach ($report as $idTipo=>$tipoSuperior) : ?>
			<?php if ($idTipo > 0) : ?>
		<h2><?= $tipoSuperior->Nombre ?></h2>
			<?php endif; ?>
			<?php foreach ($idTipo->TiposGasto as $idTipo2=>$tipo) : ?>
				<?php if ($idTipo2 > 0) : ?>
		<h3><?= $tipo->Nombre ?></h3>
				<?php endif; ?>
		<div class="row">
			<table class="span8 article">
				<thead>
					<th>Fecha</th>
					<th>Proveedor</th>
					<th>Monto (<?= $report->Moneda ?>)</th>
				</thead>
				<tbody>
				<?php foreach ($tipo->Gastos as $gasto) : ?>
					<tr>
						<td><?php echo $articulo->Codigo; ?></td>
						<td><strong><?php echo strtoupper($articulo->Nombre); ?></strong></td>
						<td><?php echo $articulo->Costo; ?></td>
						<td><?php echo $articulo->Precio; ?></td>
						<td><?php echo $articulo->PctComision; ?></td>
						<td><?php echo $articulo->ManejaStock?'Si':'No'; ?></td>
						<td><?php echo $articulo->Estado; ?></td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		</div>
			<?php endforeach; ?>
		<?php endforeach; ?>
	</div>
</div>
<?php endif; 