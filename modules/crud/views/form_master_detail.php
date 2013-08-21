<?php 
$detField = NULL;
echo form_open_multipart(current_url(), array('class'=>'form-horizontal')); 
?>
<div class="row">
	<div class="span8 offset2"><?php 
echo form_hidden('return_url',@$_GET['return_url']);
echo form_fieldset(!empty($options->legend) ? $options->legend : ($options->operation == 'add' ? 'Agregar nuevo registro' : 'Editar registro')); 
			
foreach ($field_data as $field) {
	if ($field->type == 'details') {
		$detField = $field;
		continue;
	}
	
	$this->load->view('form_control',array('field'=>$field,'options'=>$options,'enclose'=>TRUE));
}
	
echo form_fieldset_close(); ?>
	</div>
</div>
<?php if (!empty($detField)) : ?>
<div class="row">
	<div class="<?php echo isset($detField->class) ? $detField->class : 'span8 offset2'; ?>">
		<div class="row">
			<div class="<?php echo isset($detField->class) ? $detField->class : 'span8'; ?>">
				<?=form_fieldset(!empty($detField->title)?$detField->title:'Detalle').form_fieldset_close()?>
			</div>
		</div>
		<div class="row">
			<div class="<?php echo isset($detField->class) ? $detField->class : 'span8'; ?>">
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
			<div class="<?php echo isset($detField->class) ? $detField->class : 'span8'; ?>">
				<table class="table table-striped table-hover table-bordered table-sortable" id="grid_details_<?php echo str_replace('/','_',$meta->module_name); ?>" id="grid-form">
					<thead>
						<tr>
							<th>&nbsp;</th>
							<?php foreach ($detField->columns as $column) : ?>
							<th<?= isset($column->align) ? ' style="text-align:'.$column->align.'"' : '' ?>><?= $column->title ?></th>
							<?php endforeach; ?>
						</tr>
					</thead>
					<tbody>
					<?php 
					$totals = array();
					if (is_array($obj->{$detField->name})) {
						foreach ($obj->{$detField->name} as $id=>$detail) : ?>
						<tr<?= $id <= 0 ? ' class="new-row"' : '' ?>>
							<td class="key">
							<?php if ($id > 0) : ?>
								<input type="hidden" name="data[<?= $options->detail_index ?>][<?=$id?>][is_new]" value="0" />
								<input type="hidden" name="data[<?= $options->detail_index ?>][<?=$id?>][<?= $options->detail_pk ?>]" value="<?=$id?>" />
								<input type="checkbox" id="form_details_<?=$id?>" class="check-remove" />
							<?php else : ?>
								<a href="#" title="Quitar detalle" class="btn btn-mini" onclick="removeDetailFunc(this);return false;"><i class="icon-minus"></i></a>
								<input type="hidden" name="data[<?= $options->detail_index ?>][<?=$id?>][is_new]" value="1">
							<?php endif; ?>
							</td>
					<?php 	
							foreach ($detField->columns as &$column) :
								$column->default_name = "data[{$options->detail_index}][$id][{$column->name}]";
					?>
							<td><?php $this->load->view('form_control',array('field'=>$column,'options'=>$options,'enclose'=>FALSE,'obj'=>(object)$detail)); ?></td>
					<?php	
							endforeach; ?>
						</tr>
					<?php 
						endforeach; 
					} ?>
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
	</div>
</div>
<div class="row">
	<div class="span8 offset2">
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
<?php 
endif; 
echo form_close(); ?>