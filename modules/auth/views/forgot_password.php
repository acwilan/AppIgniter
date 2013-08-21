<?php
$login = array(
	'name'	=> 'username',
	'id'	=> 'login',
	'value' => set_value('username'),
	'maxlength'	=> 50,
	'size'	=> 30,
	'placeholder'	=>	'Usuario',
);
?>
<div class="row">
	<div class="span2">&nbsp;</div>
	<div class="span5">
		<?php echo form_open($this->uri->uri_string(), array('class'=>'form-horizontal')); ?>
			<fieldset>
				<legend>Recuperaci&oacute;n de contrase&ntilde;a</legend>
				<div class="control-group<?php echo has_errors($login['name']) ? ' error' : ''; ?>">
					<?php echo form_label('Usuario', $login['id'], array('class'=>'control-label')); ?>
					<div class="controls">
						<?php echo form_input($login); ?>
						<span class="help-inline"><?php echo form_error($login['name']); ?></span>
					</div>
				</div>
				<div class="form-actions">
					<?php echo form_button(array(
								'name'=>'forgot_btn', 
								'content'=>'Recuperar', 
								'class'=>"btn btn-primary",
								'type'=>"submit")); ?>
				</div>
			</fieldset>
		<?php echo form_close(); ?>
	</div>
	<div class="span2">&nbsp;</div>
</div>