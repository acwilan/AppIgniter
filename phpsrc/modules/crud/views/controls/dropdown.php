<select name="<?= $fldArr['name'] ?>" id="<?= $fldArr['id'] ?>">
<?php foreach ($field->options as $key=>$option) : ?>
<?php	if (is_object($option)) : ?>
	<option value="<?= $option->value ?>" <?= $fldArr['value'] == $option->value ? ' selected="selected"' : '' ?> ><?= $option->text ?></option>
<?php 	else : ?>
	<option value="<?= $key ?>" <?= $fldArr['value'] == $key ? ' selected="selected"' : '' ?> ><?= $option ?></option>
<?php 	endif; ?>
<?php endforeach; ?>
</select>
<?php
//echo form_dropdown($fldArr['name'], $field->options, $fldArr['value']);
