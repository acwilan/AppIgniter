<div class="input-append">
<?php
$fldArr['class'] = isset($fldArr['class']) && !empty($fldArr['class']) ? $fldArr['class'].' datepicker input-small' : 'datepicker input-small';
$field->format = isset($field->format) ? $field->format : 'Y-m-d';

if (!empty($fldArr['value']))
	$fldArr['value'] = date($field->format, strtotime($fldArr['value']));
	
if (!empty($field->jsformat)) {
	$fldArr['data-format'] = $field->jsformat;
}

if (isset($field->minDate) && !empty($field->minDate)) {
	$fldArr['data-min-date'] = $field->minDate;
}

if (isset($field->maxDate) && !empty($field->maxDate)) {
	$fldArr['data-max-date'] = $field->maxDate;
}
	
echo form_input($fldArr); ?><a href="#" class="btn add-on datepicker-trigger" id="<?= $fldArr['id'].'_sel' ?>" rel="#<?= $fldArr['id'] ?>"><i class="icon-calendar"></i></a><?php
?>
</div>