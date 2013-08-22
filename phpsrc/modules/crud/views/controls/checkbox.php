<?php
$fldArr['class'] = "frm_checkbox";
if ($obj->{$field->name} == 1) {
	$fldArr['checked'] = 'checked';
}
echo form_checkbox($fldArr);
