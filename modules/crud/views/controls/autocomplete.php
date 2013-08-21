<input type="hidden" name="<?= $fldArr['name'] ?>" value="<?= isset($obj->{$field->name}) ? $obj->{$field->name} : '' ?>" id="<?= $fldArr['id'] ?>" /><?php
	$fldArr['name'] = str_replace($field->name, $field->autocomplete->text_field, $fldArr['name']);
	$fldArr['data-id'] = "#{$fldArr['id']}";
	unset($fldArr['id']);
	$fldArr['role'] = $field->autocomplete->role;
	$fldArr['class'] = 'autocomplete';
$fldArr['type'] = 'text'; ?>
<input type="text" name="<?= $fldArr['name'] ?>" value="<?= isset($obj->{$field->autocomplete->text_field}) ? $obj->{$field->autocomplete->text_field} : '' ?>" 
placeholder="<?= $field->title ?>" title="<?= $field->title ?>" data-id="<?= $fldArr['data-id'] ?>" role="<?= $fldArr['role'] ?>"  <?= isset($field->autocomplete->onchange_callback) ? ' data-onchange="'.$field->autocomplete->onchange_callback.'"' : '' ?>
class="autocomplete" />
