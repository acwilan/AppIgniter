<?= '<' ?>?php

$config['grid_columns'] = array(
<?php foreach ($fields as $field) : ?>
    (object)array(
        'name'=>'<?= $field->name ?>',
        'type'=>'<?= infer_type($field->type) ?>',
    <?php if ($field->primary_key == 1) : ?>
        'title'=>'&nbsp;',
        'key'=>TRUE,
    <?php else : ?>
        'title'=>'<?= humanize($field->name) ?>',
    <?php endif; ?>
    ),
<?php endforeach; ?>
);

$config['form_fields'] = array(
<?php foreach ($fields as $field) : ?>
    <?php if ($field->primary_key == 1) continue; ?>
    (object)array(
        'name'=>'<?= $field->name ?>',
        'title'=>'<?= humanize($field->name) ?>',
        'type'=>'<?= infer_type($field->type) ?>',
    ),
<?php endforeach; ?>
);