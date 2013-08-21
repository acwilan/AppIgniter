<?php
$login = array(
	'name'	=> 'data[clave]',
	'id'	=> 'clave',
	'value' => set_value('data[clave]', $data['clave']),
	'maxlength'	=> 50,
	'size'	=> 30,
	'placeholder'	=>	'Clave',
);
?>
<div class="row">
	<div class="span2">&nbsp;</div>
	<div class="span5">
		<?php echo form_open($this->uri->uri_string(), array('class'=>'form-horizontal')); ?>
		<?php echo form_hidden('key', $key); ?>
			<fieldset>
				<legend>Cambiar contrase&ntilde;a</legend>
				<div class="control-group<?php echo has_errors('data[clave]') ? ' error' : ''; ?>">
					<?php echo form_label('Clave', 'clave', array('class'=>'control-label')); ?>
					<div class="controls">
						<?php echo form_input($login); ?>
						<span class="help-inline"><?php echo form_error('data[clave]'); ?></span>
						<span class="help-block">Ingrese la clave que se envio en el correo</span>
					</div>
				</div>
				<div class="control-group<?php echo has_errors('data[password]') ? ' error' : ''; ?>">
					<?php echo form_label('Nueva Contrase&ntilde;a', 'password', array('class'=>'control-label')); ?>
					<div class="controls">
						<?php echo form_password(array('name'=>'data[password]','id'=>'password','placeholder'=>'')); ?>
						<span class="help-inline"><?php echo form_error('data[password]'); ?></span>
					</div>
				</div>
				<div class="control-group<?php echo has_errors('data[passconf]') ? ' error' : ''; ?>">
					<?php echo form_label('Confirmar', 'passconf', array('class'=>'control-label')); ?>
					<div class="controls">
						<?php echo form_password(array('name'=>'data[passconf]','id'=>'passconf','placeholder'=>'')); ?>
						<span class="help-inline"><?php echo form_error('data[passconf]'); ?></span>
					</div>
				</div>
				<div class="form-actions">
					<?php echo form_button(array(
								'name'=>'login_btn', 
								'content'=>'Cambiar contrase&ntilde;a', 
								'class'=>"btn btn-primary",
								'type'=>"submit")); ?>
				</div>
			</fieldset>
		<?php echo form_close(); ?>
	</div>
	<div class="span2">&nbsp;</div>
</div>