<?php

$config['form_actions'] = array(
	'save-close'=>(object)array(
		'label'=>'Guardar y cerrar',
		'link'=>FALSE,
		'primary'=>TRUE,
		'hidden'=>FALSE,
	),
	'save-only'=>(object)array(
		'label'=>'Guardar',
		'link'=>FALSE,
		'primary'=>FALSE,
		'hidden'=>FALSE,
	),
	'btn-cancel'=>(object)array(
		'label'=>'Cancelar',
		'link'=>TRUE,
		'primary'=>FALSE,
		'hidden'=>FALSE,
	),
);
$config['crud_controls'] = array(
	'password'=>'password',
	'text'=>'text',
	'dropdown'=>'dropdown',
	'checkbox'=>'checkbox', 
	'bool'=>'checkbox',
	'radio'=>'radiobutton',
	'related'=>'relation',
	'date'=>'date',
	'hidden'=>'hidden',
	'file'=>'file',
	'autocomplete'=>'autocomplete',
	'datetime'=>'datetime',
	'money'=>'money',
	'nested_dropdown'=>'nested_dropdown',
	'user_perms'=>'user_perms',
	'range'=>'range',
	'reference'=>'reference',
	'input-disabled-if'=>'input_disabled_if',
);