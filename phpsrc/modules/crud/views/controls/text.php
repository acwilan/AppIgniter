<?php
$fldArr['rows'] = !isset($field->rows) ? 5 : $field->rows; 
$fldArr['cols'] = !isset($field->cols) ? 20 : $field->cols;
if (isset($field->style)) $fldArr['style'] = $field->style;
echo form_textarea($fldArr);
