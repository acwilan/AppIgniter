<div class="row">
	<div class="span12 last">
		<?=form_open(current_url())?>
		<div class="row">
			<div class="span12 last">
				<?=form_fieldset(!empty($options->legend) ? $options->legend : ($options->operation == 'add' ? 'Agregar nuevo registro' : 'Editar registro'))?>
					<table class="table table-condensed">
				<?php if ($options->operation != 'add') : ?>
						<tr>
							<td colspan="4" style="text-align:right;"><a href="<?php echo site_url('crud/rendicion/imprimir/'.$obj->IdRendicion); ?>" class="btn" target="_blank"><i class="icon-print"></i> Imprimir</a></td>
						</tr>
				<?php endif; ?>
						<tr>
							<td class="rt">Puesto de venta</td>
							<td class="lt">
								<input type="hidden" name="data[IdPuestoVenta]" value="<?=set_value('data[IdPuestoVenta]',$obj->IdPuestoVenta)?>" id="form_field_id_puesto_venta" />
								<input type="text" class="typeahead" name="data[PuestoVenta]" value="<?=set_value('data[PuestoVenta]',$obj->PuestoVenta)?>" id="form_field_id_puesto_venta_name" role="bodega" data-id="#form_field_id_puesto_venta" />
							</td>
							<td class="rt">Fecha</td>
							<td class="lt">
								<input type="text" name="data[Fecha]" class="datepicker" value="<?=set_value('data[Fecha]',date('Y-m-d',strtotime($obj->Fecha)))?>" id="form_field_fecha" />
							</td>
						</tr>
						<tr>
							<td class="rt">Observaciones</td>
							<td class="lt" colspan="3">
								<textarea name="data[Observaciones]" cols="30" rows="3"><?=set_value('data[Observaciones]',$obj->Observaciones)?></textarea>
							</td>
						</td>
					</table>
				<?=form_fieldset_close()?>
			</div>
		</div>
		<div class="row">
			<div class="span12">
				<?=form_fieldset('Detalle de orden').form_fieldset_close()?>
			</div>
		</div>
		<div class="row">
			<div class="span12 last">
				<div class="btn-toolbar">
					<a href="#" class="btn" id="btn-add">
						<i class="icon-plus"></i> Agregar
					</a>
					<a href="#" class="btn" id="btn-del">
						<i class="icon-trash"></i> Eliminar seleccionados
					</a>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="span12 last">
				<table class="table table-striped table-hover table-bordered table-sortable" id="grid_<?php echo str_replace('/','_',$meta->module_name); ?>" id="grid-form">
					<thead>
						<tr>
							<th>&nbsp;</th>
							<th>Art&iacute;culo</th>
							<th style="text-align:center">Repos.</th><th style="text-align:center">Ajust.</th>
							<th style="text-align:center">Devol.</th><th style="text-align:center">Venta.</th>
							<th style="text-align:center">Venta (Promo)</th>
						</tr>
					</thead>
					<tbody>
					<?php 
					$totals = array(0,0,0,0,0);
					foreach ($obj->Details as $id=>$detail) :
						$totals[0] += $detail->NumeroReposiciones;
						$totals[1] += $detail->NumeroAjustes;
						$totals[2] += $detail->NumeroDevoluciones;
						$totals[3] += $detail->NumeroVentas;
						$totals[4] += $detail->NumeroVentasPromo;
					?>
						<tr>
							<td class="key">
						<?php if ($detail->IsNew) : ?>
								<a href="#" title="Quitar detalle" class="btn btn-mini"><i class="icon-minus"></i></a>
								<input type="hidden" name="data[Details][<?=$id?>][IsNew]" value="1" />
						<?php else : ?>
								<input type="hidden" name="data[Details][<?=$id?>][IsNew]" value="0" />
								<input type="hidden" name="data[Details][<?=$id?>][IdRendicionDetalle]" value="<?=$id?>" />
								<input type="checkbox" id="form_details_<?=$id?>" class="check-remove" />
						<?php endif; ?>
							</td>
							<td class="regular">
								<input type="hidden" name="data[Details][<?=$id?>][IdArticulo]" value="<?=set_value("data[Details][$id][IdArticulo]",$detail->IdArticulo)?>" id="form_field_id_articulo" />
								<input type="text" class="autocomplete" value="<?=set_value("data[Details][$id][Articulo]",$detail->Articulo)?>" id="form_field_id_articulo_name" role="articulo" data-id="form_field_id_articulo" placeholder="Articulo" title="Articulo" />
							</td>
							<td class="regular" style="text-align:center">
								<input type="text" class="input-mini" name="data[Details][<?=$id?>][NumeroReposiciones]" placeholder="Reposiciones" title="Reposiciones" value="<?=set_value("data[Details][$id][NumeroReposiciones]",$detail->NumeroReposiciones)?>" />
							</td>
							<td class="regular" style="text-align:center">
								<input type="text" class="input-mini" name="data[Details][<?=$id?>][NumeroAjustes]" placeholder="Ajustes" title="Ajustes" value="<?=set_value("data[Details][$id][NumeroAjustes]",$detail->NumeroAjustes)?>" />
							</td>
							<td class="regular" style="text-align:center">
								<input type="text" class="input-mini" name="data[Details][<?=$id?>][NumeroDevoluciones]" placeholder="Devoluciones" title="Devoluciones" value="<?=set_value("data[Details][$id][NumeroDevoluciones]",$detail->NumeroDevoluciones)?>" />
							</td>
							<td class="regular" style="text-align:center">
								<input type="text" class="input-mini" name="data[Details][<?=$id?>][NumeroVentas]" placeholder="Ventas" title="Ventas" value="<?=set_value("data[Details][$id][NumeroVentas]",$detail->NumeroVentas)?>" />
							</td>
							<td class="regular" style="text-align:center">
								<input type="text" class="input-mini" name="data[Details][<?=$id?>][NumeroVentasPromo]" placeholder="VentasPromo" title="VentasPromo" value="<?=set_value("data[Details][$id][NumeroVentasPromo]",$detail->NumeroVentasPromo)?>" />
							</td>
						</tr>
					<?php endforeach; ?>
					</tbody>
					<tfoot>
						<!--tr>
							<td colspan="2" style="text-align:right"><strong>Totales</strong></td>
					<?php foreach ($totals as $total) : ?>
							<td style="text-align:center"><?= $total ?></td>
					<?php endforeach; ?>
						</tr-->
					</tfoot>
				</table>
			</div>
		</div>
		<div class="form-actions"><?php 
	foreach ($form_actions as $key=>$info) : 
		if ($info->hidden) continue;
		$css = 'btn';
		if ($info->primary) $css .= ' btn-primary';
		elseif (!empty($info->class)) $css .= " btn-{$info->class}";
		if (!$info->link) :
	?>
				<button type="submit" class="<?php echo $css; ?>" name="action" value="<?php echo $key; ?>"><?php echo $info->label; ?></button><?php
		else : ?>
				<a href="<?php echo site_url($meta->module_name); ?>" class="btn btn-link" id="btn-<?php echo $key; ?>"><?php echo $info->label; ?></a><?php
		endif;
	endforeach; ?>
		</div>
		<?=form_close()?>
	</div>
</div>