<div class="row">
	<div class="span12 last">
		<?=form_open(current_url(), array('class'=>'form-horizontal','onsubmit'=>'return checkForm();'))?>
		<div class="row">
			<div class="span12 last">
				<?=form_fieldset(!empty($options->legend) ? $options->legend : ($options->operation == 'add' ? 'Agregar nuevo registro' : 'Editar registro'))?>
					<div class="control-group">
					<label for="form_field_fecha_creacion" class="control-label">Fecha Creacion:</label>
					<div class="controls">
						<input class="datepicker" type="text" name="data[FechaCreacion]" id="form_field_fecha_creacion" value="<?=set_value("data[FechaCreacion]",$obj->FechaCreacion)?>" />
					</div>
					<div class="control-group">
					<label for="form_field_es_activo" class="control-label">Activo:</label>
					<div class="controls">
						<input type="checkbox" name="data[EsActivo]" id="form_field_es_activo" value="1" <?=set_checkbox('data[EsActivo]',$obj->EsActivo,$obj->EsActivo==1)?> />
					</div>
				<?=form_fieldset_close()?>
			</div>
		</div>
		<div class="row">
			<div class="span12">
				<?=form_fieldset('Detalle de Esquema').form_fieldset_close()?>
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
							<th>Empleado</th>
							<th>Pct. Comisi&oacute;n</th>
						</tr>
					</thead>
					<tbody>
					<?php $total = 0.00; ?>
					<?php foreach ($obj->Details as $id=>$detail) : ?>
						<tr>
							<td class="key">
						<?php if ($detail->IsNew) : ?>
								<a href="#" title="Quitar detalle" class="btn btn-mini"><i class="icon-minus"></i></a>
								<input type="hidden" name="data[Details][<?=$id?>][IsNew]" value="1" />
						<?php else : ?>
								<input type="hidden" name="data[Details][<?=$id?>][IsNew]" value="0" />
								<input type="hidden" name="data[Details][<?=$id?>][IdEsquemaUtilidadDetalle]" value="<?=$id?>" />
								<input type="checkbox" id="form_details_<?=$id?>" class="check-remove" />
						<?php endif; ?>
							</td>
							<td class="regular">
								<input type="hidden" name="data[Details][<?=$id?>][IdEmpleado]" value="<?=set_value("data[Details][$id][IdEmpleado]",$detail->IdEmpleado)?>" id="form_field_<?=$id?>_id_empleado" />
								<input type="text" class="autocomplete" value="<?=set_value("data[Details][$id][Empleado]",$detail->Empleado)?>" id="form_field_<?=$id?>_id_empleado_name" role="empleado" data-id="form_field_<?=$id?>_id_empleado" placeholder="Empleado" title="Empleado" />
							</td>
							<td class="regular">
								<div class="input-prepend">
									<span class="add-on">%</span>
									<input type="text" role="pct-utilidad" class="input-mini" name="data[Details][<?=$id?>][PorcentajeUtilidad]" placeholder="Pct. Utilidad" title="Pct. Utilidad" value="<?=number_format(set_value("data[Details][$id][PorcentajeUtilidad]",$detail->PorcentajeUtilidad),2)?>" />
								</div>
							</td>
						</tr>
					<?php 	$total += $detail->PorcentajeUtilidad; ?>
					<?php endforeach; ?>
					</tbody>
					<tfoot>
						<tr>
							<td colspan="2" style="text-align:right"><strong>TOTAL</strong></td>
							<td>
								<div class="input-prepend">
									<span class="add-on">%</span>
									<input type="text" role="total" class="input-mini" value="<?=number_format($total,2)?>" disabled="disabled" />
								</div>
							</td>
						</tr>
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