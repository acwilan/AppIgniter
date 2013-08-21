<?php
$login = array(
	'name'	=> 'username',
	'id'	=> 'login',
	'value' => set_value('username'),
	'maxlength'	=> 50,
	'size'	=> 30,
	'placeholder'	=>	'Usuario',
);
$password = array(
	'name'	=> 'password',
	'id'	=> 'password',
	'size'	=> 30,
	'placeholder' => 'Contraseña'
);
?>
<div class="row">
	<div class="span2">&nbsp;</div>
	<div class="span5">
		<?php echo form_open($this->uri->uri_string(), array('class'=>'form-horizontal')); ?>
			<fieldset>
				<legend>Inicio de sesi&oacute;n</legend>
				<div class="control-group<?php echo isset($errors[$login['name']]) ? ' error' : ''; ?>">
					<?php echo form_label('Usuario', $login['id'], array('class'=>'control-label')); ?>
					<div class="controls">
						<?php echo form_input($login); ?>
						<span class="help-inline"><?php echo form_error($login['name']); ?></span>
					</div>
				</div>
				<div class="control-group<?php echo isset($errors[$password['name']]) ? ' error' : ''; ?>">
					<?php echo form_label('Contrase&ntilde;a', $password['id'], array('class'=>'control-label')); ?>
					<div class="controls">
						<?php echo form_password($password); ?>
						<span class="help-inline"><?php echo form_error($password['name']); ?></span>
					</div>
				</span>
				<?php if (isset($allow_forgot_password) && $allow_forgot_password) : ?>
				<div class="control-group" style="text-align:center"><br/><br/>
					<a href="<?php echo site_url('auth/forgot_password'); ?>">Olvido su clave?</a>
				</div>
				<?php endif; ?>
				<div class="form-actions">
					<?php echo form_button(array(
								'name'=>'login_btn', 
								'content'=>'Iniciar sesi&oacute;n', 
								'class'=>"btn btn-primary",
								'type'=>"submit")); ?>
				</div>
			</fieldset>
		<?php echo form_close(); ?>
	</div>
	<div class="span2">&nbsp;</div>
</div>