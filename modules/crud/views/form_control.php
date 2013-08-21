<?php 
	$fldhdprop = "hide_on_{$options->operation}";
	
	if (!isset($field->key) || !$field->key) :
		if (isset($field->hidden) && $field->hidden && $field->type != 'related') : 
			echo form_hidden($field->name, set_value($field->name, $options->operation == 'add' ? (isset($field->default)?$field->default:NULL) : $obj->{$field->name})); 
		endif;
		if (!isset($field->{$fldhdprop}) || !$field->{$fldhdprop}) : 
			if ($enclose && $field->type != 'hidden') : ?>
				<div class="control-group<?php echo isset($field->error) && !empty($field->error) ? ' error' : ''; ?>">
					<label for="form_field_<?php echo $field->name; ?>" class="control-label"><?php echo $field->title; ?></label>
					<div class="controls"><?php
			endif;
			
			// se crea un array asociativo con los campos estandar del control, id, valor y nombre
			$fldArr = array(
					'id'=>"form_field_{$field->name}",
					//'value'=>set_value("data[{$field->name}]",$options->operation == 'add' ? (isset($field->default)?$field->default:NULL) : $obj->{$field->name}),
					'name'=>isset($field->default_name) ? $field->default_name : "data[{$field->name}]",
					'title'=>$field->title,
				);
			if ($field->type != 'relation' && $field->type != 'user_perms' && $field->type != 'reference') {
				$fldArr['value'] = set_value("data[{$field->name}]",$options->operation == 'add' ? (isset($field->default)?$field->default:(isset($field->defaultValue)?$field->defaultValue:NULL)) : $obj->{$field->name});
			} elseif ($field->type == 'reference') {
				$fldArr['value'] = $obj->{$field->name};
			}
			// si es solo lectura, se establece el atributo disabled
			if ($options->readonly) $fldArr['disabled'] = 'disabled';
			$prop = "disable_on_{$options->operation}";
			if ((isset($field->{$prop}) && $field->{$prop}) || (isset($field->disabled) && $field->disabled))
				$fldArr['disabled'] = 'disabled';
			
			// si es un tipo bool o checkbox, se establecera el checkbox como chequeado
			if ($options->operation == 'edit' && ($field->type == 'checkbox' || $field->type == 'bool') && $obj->{$field->name} == 1)
				$fldArr['checked'] = 'checked';
			// clase CSS del control
			if (isset($field->class)) $fldArr['class'] = $field->class;
			
			if (isset($field->onchange_callback)) $fldArr['onchange'] = "return {$field->onchange_callback}(this);";
			
			$controls = config_item('crud_controls');
			if (isset($controls[$field->type])) {
				$view = $controls[$field->type];
				$this->load->view("controls/{$view}", array(
					'field'=>$field,
					'fldArr'=>$fldArr,
					'obj'=>$obj,
					'options'=>$options,
					'meta'=>$meta,
					'enclose'=>$enclose,
				));
			}
			else {
				$this->load->view('controls/default', array(
					'field'=>$field,
					'fldArr'=>$fldArr,
					'obj'=>$obj,
					'options'=>$options,
					'meta'=>$meta,
					'enclose'=>$enclose,
				));
			}
			
			if ($enclose && $field->type != 'hidden') :
			// Esta parte genera un cuadro de ayuda
				if ((!isset($field->hidden) || !$field->hidden) && (isset($field->help) && !empty($field->help)) || (isset($field->error) && !empty($field->error))) { ?>
	<span class="help-block"><?php echo isset($field->error) && !empty($field->error) ? $field->error : $field->help; ?></span><?php
				} 
				// Aqui se genera un * para señalizar que es campo requerido
				elseif ((!isset($field->hidden) || !$field->hidden) && ((isset($field->required) && $field->required) || (isset($field->rules) && (isset($field->rules) && !empty($field->rules) && strpos('required',$field->rules) !== FALSE)))) { ?>
	<span class="help-text" style="color:red"> *</span> <?php
				}
			
			?>
					</div>
				</div><?php
			endif;
		endif;
	endif;