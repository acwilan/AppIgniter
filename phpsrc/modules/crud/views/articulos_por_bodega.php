<div class="row">
	<div class="span12 last">
		<?=form_open(current_url())?>
		<div class="row">
			<div class="span12">
				<?=form_fieldset('Articulos de bodega "<em>'.$bodega->Nombre.'</em>"').form_fieldset_close()?>
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
							<th style="text-align:center">Existencia</th>
							<th style="text-align:center">%Com. Propio</th>
							<th style="text-align:center">%Com. Venta</th>
							<th style="text-align:center">%Com. Venta Xtra</th>
							<th style="text-align:center">% Descuento</th>
						</tr>
					</thead>
					<tbody>
					<?php if (isset($obj->Details))
					foreach ($obj->Details as $id=>$detail) :
					?>
						<tr>
							<td class="key">
						<?php if ($detail->IsNew) : ?>
								<a href="#" title="Quitar detalle" class="btn btn-mini"><i class="icon-minus"></i></a>
								<input type="hidden" name="data[Details][<?=$id?>][IsNew]" value="1" />
						<?php else : ?>
								<input type="hidden" name="data[Details][<?=$id?>][IsNew]" value="0" />
								<input type="hidden" name="data[Details][<?=$id?>][IdArticuloBodega]" value="<?=$id?>" />
								<input type="checkbox" id="form_details_<?=$id?>" class="check-remove" />
						<?php endif; ?>
							</td>
							<td class="regular">
								<select name="data[Details][<?=$id?>][IdArticulo]" value="<?=set_value("data[Details][$id][IdArticulo]",$detail->IdArticulo)?>" id="form_field_id_articulo">
								<?php foreach ($articulos as $articulo) : ?>
									<option value="<?= $articulo->id ?>"<?= $articulo->id == $detail->IdArticulo ? ' selected="selected"' : '' ?>><?= $articulo->value ?></option>
								<?php endforeach; ?>
								</select>
							</td>
							<td class="regular" style="text-align:center">
								<input type="text" class="input-mini" name="data[Details][<?=$id?>][Existencia]" placeholder="Existencia" title="Existencia" value="<?=set_value("data[Details][$id][Existencia]",$detail->Existencia)?>" />
							</td>
							<td class="regular" style="text-align:center">
								<input type="checkbox" name="data[Details][<?=$id?>][UtilizaPorcentajeComisionVentaPropio]" placeholder="Usa %Com. Propio" title="Usa %Com. Propio" <?= $detail->UtilizaPorcentajeComisionVentaPropio == 1 ? ' checked="checked"' : '' ?> />
							</td>
							<td class="regular" style="text-align:center">
								<input type="text" class="input-mini" name="data[Details][<?=$id?>][PorcentajeComisionVenta]" placeholder="%Com. Venta" title="%Com. Venta" value="<?=set_value("data[Details][$id][PorcentajeComisionVenta]",$detail->PorcentajeComisionVenta)?>" />
							</td>
							<td class="regular" style="text-align:center">
								<input type="text" class="input-mini" name="data[Details][<?=$id?>][PorcentajeComisionVentaExtra]" placeholder="%Com. Venta Xtra" title="%Com. Venta Xtra" value="<?=set_value("data[Details][$id][PorcentajeComisionVentaExtra]",$detail->PorcentajeComisionVentaExtra)?>" />
							</td>
							<td class="regular" style="text-align:center">
								<input type="text" class="input-mini" name="data[Details][<?=$id?>][PorcentajeDescuento]" placeholder="% Descuento" title="% Descuento" value="<?=set_value("data[Details][$id][PorcentajeDescuento]",$detail->PorcentajeDescuento)?>" />
							</td>
						</tr>
					<?php endforeach; ?>
					</tbody>
					<tfoot>
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