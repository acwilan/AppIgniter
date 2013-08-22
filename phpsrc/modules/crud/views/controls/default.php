<?php
if (isset($field->placeholder)) {
	$fldArr['placeholder'] = $field->placeholder;
}
if (isset($field->tooltip) && $field->tooltip) {
	$fldArr['rel'] = 'tooltip';
	$fldArr['data-title'] = is_string($field->tooltip) ? $field->tooltip : $field->title;
	unset($fldArr['title']);
}
echo form_input($fldArr);
