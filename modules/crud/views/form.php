<div class="row">
	<div class="span8 offset2"><?php 
		echo form_open_multipart(current_url(), array('class'=>'form-horizontal')); 
			echo form_hidden('return_url',@$_GET['return_url']);
			echo form_fieldset(!empty($options->legend) ? $options->legend : ($options->operation == 'add' ? 'Agregar nuevo registro' : 'Editar registro')); 
			
foreach ($field_data as $field) : 
	$this->load->view('form_control',array('field'=>$field,'obj'=>$obj,'options'=>$options,'enclose'=>TRUE));
endforeach; 
			echo form_fieldset_close(); ?>
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
			</div><?
		echo form_close(); ?>
	</div>
</div>