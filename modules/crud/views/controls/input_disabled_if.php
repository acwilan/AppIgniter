<?php
if (isset($field->placeholder)) {
	$fldArr['placeholder'] = $field->placeholder;
}
if (isset($field->tooltip) && $field->tooltip) {
	$fldArr['rel'] = 'tooltip';
	$fldArr['data-title'] = is_string($field->tooltip) ? $field->tooltip : $field->title;
	unset($fldArr['title']);
}
if (isset($field->disabled_test) && $obj->{$field->disabled_test} == 1) {
	$fldArr['disabled'] = 'disabled';
}
echo form_input($fldArr);
