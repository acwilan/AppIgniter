<?php
if ($options->operation != 'add' && !empty($obj->{$field->name})) { ?>
<a href="<?= $obj->{$field->name} ?>" target="_blank" class="btn" title="Haga click para ver en una nueva ventana"><i class="icon-search"></i> Ver</a><br/><?php
}
$fldArr['type'] = 'file';
echo form_input($fldArr);
