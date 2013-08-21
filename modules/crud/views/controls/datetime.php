<div class="input-append">
<?php
$fldArr['class'] = isset($fldArr['class']) && !empty($fldArr['class']) ? $fldArr['class'].' datetimepicker span2' : 'datetimepicker span2';
echo form_input($fldArr); ?><a href="#" class="btn add-on datetimepicker-trigger" id="<?= $fldArr['id'].'_sel' ?>" rel="#<?= $fldArr['id'] ?>"><i class="icon-calendar"></i></a><?php
?>
</div>