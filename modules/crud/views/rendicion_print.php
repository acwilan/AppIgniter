<?php if (!isset($report) || empty($report)) : ?>
<div class="row">
	<div class="span8 offset2">
		<?=form_open(current_url(), array('class'=>'form-horizontal','target'=>'_blank'))?>
			<?=form_fieldset(!empty($options->legend) ? $options->legend : ($options->operation == 'add' ? 'Agregar nuevo registro' : 'Editar registro'))?>
				<div class="control-group">
					<label for="form_field_id_bodega" class="control-label">Bodega a imprimir:</label>
					<div class="controls">
						<select name="data[IdBodega]">
							<option value="0" <?=set_select('data[IdBodega]',0,TRUE)?>>-- TODAS --</option>
						<?php foreach ($bodegas as $bodega) : ?>
							<option value="<?=$bodega->IdBodega?>" <?=set_select('data[IdBodega]',$bodega->IdBodega)?>><?=$bodega->Nombre?></option>
						<?php endforeach; ?>
						</select>
					</div>
				</div>
			<?=form_fieldset_close()?>
			<div class="form-actions">
				<button type="submit" class="btn btn-primary" name="action" value="print">Imprimir</button>
				<a href="<?=site_url($meta->module_name)?>" class="btn btn-link" id="btn-cancel">Cancelar</a>
			</div>
		<?=form_close()?>
	</div>
</div>
<?php else : ?>
<div class="row">
	<div class="span12 last report">
		<h2>Rendicion - Lobby Bar</h2>
		<?php foreach ($report as $id_bodega=>$record) : ?>
		<table class="header span11">
			<tr class="strong">
				<td width="20%">Punto de Venta:</td>
				<td width="55%"><?=$record->IdBodega?> - <?=$record->Nombre?></td>
				<td width="25%">Fecha: <?= (!isset($record->Fecha)||empty($record->Fecha)) ? date('d/m/Y l') : $record->Fecha ?></td>
			</tr>
			<tr>
				<td>Temporada</td>
				<td><?= (!isset($record->Temporada)||empty($record->Temporada)) ? $season->Nombre : $record->Temporada ?></td>
				<td>Semana (del <?= (!isset($record->Temporada)||empty($record->Temporada)) ? 
					date('d/m/Y',strtotime($season->FechaInicio)).' al '.date('d/m/Y',strtotime($season->FechaFin)) :
					$record->TemporadaFechaInicio.' al '.$record->TemporadaFechaFin ?>)</td>
			</tr>
		</table>
		<table class="detail span11">
			<thead>
				<tr class="desc">
					<th class="blank" colspan="5">&nbsp;</th>
					<th colspan="3">Ventas</th>
					<th colspan="4">Ventas PROMO</th>
					<th class="blank">&nbsp;</th>
					<th colspan="2">Comisi&oacute;n</th>
					<th class="blank" colspan="2">&nbsp;</th>
				</tr>
				<tr>
					<th>Stock<br/>Inicial</th>
					<th>#Rep.</th><th>#Ajus.</th><th>#Dev.</th><th>Total</th>
					<th>#Vent.</th><th>Precio<br/>(<?=$season->IdMoneda?>)</th><th>SubTotal</th>
					<th>#Ven.</th><th>% Desc</th><th>Precio<br/>(<?=$season->IdMoneda?>)</th>
					<th>SubTotal</th><th>SubTotal<br/>(<?=$season->IdMoneda?>)</th><th>%</th>
					<th>Total<br/>(<?=$season->IdMoneda?>)</th><th>Neto<br/>(<?=$season->IdMoneda?>)</th>
					<th>Stock<br/></th>
				</tr>
			</thead>
			<tbody>
			<?php foreach ($record->Articulos as $articulo) : ?>
				<tr class="product">
					<td class="ctr"><?=$articulo->CodigoArticulo?></td>
					<td colspan="16"><?=$articulo->NombreArticulo?></td>
				<tr>
				<tr>
					<td class="ctr"><?=$articulo->Existencia?></td>
					<td><?= isset($articulo->Reposiciones) ? $articulo->Reposiciones : '' ?></td>
					<td><?= isset($articulo->Ajustes) ? $articulo->Ajustes : '' ?></td>
					<td><?= isset($articulo->Devoluciones) ? $articulo->Devoluciones : '' ?></td>
					<td><?= isset($articulo->Total) ? $articulo->Total : '' ?></td>
					<td><?= isset($articulo->Ventas) ? $articulo->Ventas : '' ?></td>
					<td class="rt"><?=$articulo->PrecioMonedaLocal?></td>
					<td><?= isset($articulo->Ventas) ? number_format($articulo->Ventas * $articulo->PrecioMonedaLocal, 2) : '' ?></td>
					<td class="disabled"><?= isset($articulo->VentasPromo) ? $articulo->VentasPromo : '' ?></td>
					<td class="disabled"><?= isset($articulo->PorcentajeDescuentoAplicado) ? $articulo->PorcentajeDescuentoAplicado : '' ?></td>
					<td class="disabled"><?= isset($articulo->PrecioPromoMonedaLocal) ? $articulo->PrecioPromoMonedaLocal : '' ?></td>
					<td class="disabled"><?= isset($articulo->VentasPromo) ? number_format($articulo->VentasPromo * $articulo->PrecioPromoMonedaLocal, 2) : '' ?></td>
					<td><?= isset($articulo->VentasPromo) && isset($articulo->Ventas) ? number_format($articulo->Ventas * $articulo->PrecioMonedaLocal + $articulo->VentasPromo * $articulo->PrecioPromoMonedaLocal, 2) : '' ?></td>
					<td class="rt"><?=$articulo->PorcentajeComisionVenta?></td>
					<td><?= isset($articulo->VentasPromo) && isset($articulo->Ventas) ? number_format($articulo->PorcentajeComisionVenta * ($articulo->Ventas * $articulo->PrecioMonedaLocal + $articulo->VentasPromo * $articulo->PrecioPromoMonedaLocal) / 100, 2) : '' ?></td>
					<td><?= isset($articulo->TotalNeto) ? number_format($articulo->TotalNeto,2) : '' ?></td>
					<td><?= isset($articulo->StockFinal) ? $articulo->StockFinal : '' ?></td>
				</tr>
			<?php endforeach; ?>
			</tbody>
			<tfoot>
				<tr><td colspan="17">&nbsp;</td></tr>
				<tr>
					<td colspan="3" class="rt">OBSERVACIONES:</td>
					<td colspan="14" class="underline">&nbsp;</td>
				<tr>
				<tr>
					<td colspan="3">&nbsp;</td>
					<td colspan="14" class="underline">&nbsp;</td>
				<tr>
				<tr><td colspan="17">&nbsp;</td></tr>
				<tr><td colspan="17">&nbsp;</td></tr>
				<tr><td colspan="17">&nbsp;</td></tr>
				<tr>
					<td colspan="2">&nbsp;</td>
					<td colspan="5" class="underline">&nbsp;</td>
					<td colspan="4">&nbsp;</td>
					<td colspan="4" class="underline">&nbsp;</td>
					<td colspan="2">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="2">&nbsp;</td>
					<td colspan="5" class="ctr">FIRMA PUNTO DE VENTA</td>
					<td colspan="4">&nbsp;</td>
					<td colspan="4" class="ctr">FIRMA ADMINISTRADOR</td>
					<td colspan="2">&nbsp;</td>
				</tr>
				<tr><td colspan="17">&nbsp;</td></tr>
				<tr><td colspan="17">&nbsp;</td></tr>
				<tr><td colspan="17">&nbsp;</td></tr>
			</tfoot>
		</table>
		<?php endforeach; ?>
	</div>
</div>
<?php endif; ?>