<?php $oper = isset($_POST['data'][$field->name]['oper']) ? $_POST['data'][$field->name]['oper'] : NULL; ?>
<?php $val = isset($_POST['data'][$field->name]['val']) ? $_POST['data'][$field->name]['val']  : NULL; ?>
<?php //var_dump($_POST['data']); ?>
<div class="input-prepend">
<select name="<?= $fldArr['name'] ?>[oper]" id="<?= $fldArr['id'] ?>_oper" class="input-small add-on" style="height:30px;">
<?php foreach ($field->options as $opt) : ?>
	<option <?= $oper == $opt ? ' selected="selected"' : '' ?> ><?= $opt ?></option>
<?php endforeach; ?>
</select>
<input type="text" name="<?= $fldArr['name'] ?>[val]" id="<?= $fldArr['id'] ?>_val" class="input-mini" value="<?= $val ?>" />
</div>