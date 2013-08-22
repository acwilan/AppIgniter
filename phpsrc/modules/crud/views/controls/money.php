<div class="input-prepend">
	<span class="add-on"><?= $field->symbol ?></span>
<?php 
if (isset($fldArr['value']) && !empty($fldArr['value'])) {
	$fldArr['value'] = number_format($fldArr['value'],2,'.','');
}
$fldArr['class'] = 'span2';
echo form_input($fldArr);
?>
</div>