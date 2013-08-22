<?php if ($report === FALSE) : ?>
<div class="row">
	<div class="span8 offset2">
		<?=form_open(current_url(), array('class'=>'form-horizontal','target'=>'_blank'))?>
			<?=form_fieldset(!empty($options->legend) ? $options->legend : ($options->operation == 'add' ? 'Agregar nuevo registro' : 'Editar registro'))?>
				<div class="control-group">
					<label for="form_field_fecha_inicio" class="control-label">Fecha de inicio:</label>
					<div class="controls">
						<input type="text" name="data[FechaInicio]" id="form_field_fecha_inicio" class="datepicker" value="<?=set_value('data[FechaInicio]',date('Y-m-d'))?>">
					</div>
				</div>
				<div class="control-group">
					<label for="form_field_fecha_fin" class="control-label">Fecha final:</label>
					<div class="controls">
						<input type="text" name="data[FechaFin]" id="form_field_fecha_fin" class="datepicker" value="<?=set_value('data[FechaFin]',date('Y-m-d'))?>">
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
<?php elseif (empty($report)) : ?>
<p>No hay datos para este reporte con los parametros especificados</p>
<?php else : ?>
<div class="row">
	<div class="span12 last report">
		<h2>Rendicion - Lobby Bar</h2>
		<?php foreach ($report as $id_bodega=>$record) : ?>
		<table class="header span11">
			<tr class="strong">
				<td width="20%">Punto de Venta:</td>
				<td width="55%"><?=$record->IdBodega?> - <?=$record->Nombre?></td>
				<td width="25%">Fecha: <?=date('d/m/Y l')?></td>
			</tr>
			<tr>
				<td>Temporada</td>
				<td><?=$season->Nombre?></td>
				<td>Semana (del <?=date('d/m/Y',strtotime($season->FechaInicio))?> al <?=date('d/m/Y',strtotime($season->FechaFin))?>)</td>
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
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td class="rt"><?=$articulo->PrecioMonedaLocal?></td>
					<td>&nbsp;</td>
					<td class="disabled">&nbsp;</td>
					<td class="disabled">&nbsp;</td>
					<td class="disabled">&nbsp;</td>
					<td class="disabled">&nbsp;</td>
					<td>&nbsp;</td>
					<td class="rt"><?=$articulo->PorcentajeComisionVenta?></td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
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