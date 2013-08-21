<?php if (empty($report)) : ?>
<h1>No hay datos para este reporte con los parametros especificados</h1>
<?php else : ?>
<div class="row">
	<div class="span12 last report">
		<h2>Resumen de Ventas por Puesto de Ventas - Lobby Bar</h2>
		<h3>Temporada: <?= $report->Temporada ?> (<?= $report->TemporadaInicio ?> al <?= $report->TemporadaFin ?>)
		<table class="detail span11">
			<thead>
				<tr>
					<th colspan="2">Puesto de Venta</th>
					<th>Venta Bruta (<?= $report->Moneda ?>)</th>
					<th>Comision (<?= $report->Moneda ?>)</th>
					<th>Viaticos Vendedores (<?= $report->Moneda ?>)</th>
					<th>Venta Neta (<?= $report->Moneda ?>)</th>
				</tr>
			</thead>
			<tbody>
			<?php 
			$totales = array(0.0,0.0,0.0,0.0);
			foreach ($report->Details as $detail) : ?>
				<tr>
					<td><?= $detail->IdBodega ?></td>
					<td><?= $detail->Bodega ?></td>
					<td><?= $detail->VentaBruta ?></td>
					<td><?= $detail->Comision ?></td>
					<td><?= $detail->PagoVendedores ?></td>
					<td><?= $detail->VentaNeta ?></td>
			<?php 
				$totales[0] += $detail->VentaBruta;
				$totales[1] += $detail->Comision;
				$totales[2] += $detail->PagoVendedores;
				$totales[3] += $detail->VentaNeta;
			endforeach; ?>
			</tbody>
			<tfoot>
				<tr>
					<td colspan="2"><strong>Totales</strong></td>
					<?php foreach ($totales as $total) : ?>
					<td><strong><?= $total ?></strong></td>
				</tr>
			</tfoot>
		</table>
	</div>
</div>
<?php endif; 