<?php

$config['grid_columns'] = array(
    'onyxx'=>array(
        (object)array(
            'name'=>'id',
            'title'=>'ID',
            'type'=>'string',
            'key'=>TRUE,
        ),
        (object)array(
            'name'=>'name',
            'title'=>'Name',
            'type'=>'string',
        ),
    )
);

$config['form_fields'] = array(
    'onyxx'=>array(
        (object)array(
            'name'=>'path',
            'title'=>'Output Path',
            'type'=>'string',
            'class'=>'span5',
            'default'=>realpath(dirname(__FILE__).DIRECTORY_SEPARATOR.'..'),
            'help'=>'Enter the path of the output files. Can be absolute or relative.',
        ),
        (object)array(
            'name'=>'config',
            'title'=>'Generate Config',
            'type'=>'bool',
            'default'=>FALSE,
            'help'=>'Check if you want Onyxx to generate also the crud_[table].php config file.',
        ),
        (object)array(
            'name'=>'module',
            'title'=>'Module Prefix',
            'type'=>'string',
            'default'=>'onyxx',
            'help'=>'Set the default module that the controller will be held in.',
        ),
        (object)array(
            'name'=>'mast_detail',
            'title'=>'Is master/detail?',
            'type'=>'bool',
            'default'=>FALSE,
            'help'=>'Check if the table has a master/detail structure.',
        ),
    ),
);

$config['form_actions'] = array(
    'onyxx'=>array(
        'generate'=>(object)array(
            'label'=>'Generate',
            'link'=>FALSE,
            'primary'=>TRUE,
            'hidden'=>FALSE,
        ),
        'btn-cancel'=>(object)array(
            'label'=>'Cancelar',
            'link'=>TRUE,
            'primary'=>FALSE,
            'hidden'=>FALSE,
        ),
    ),
);

$config['custom_actions'] = array(
    'onyxx'=>array(
        (object)array(
            'url_format'=>'generator/%s',
            'icon'=>'refresh',
            'label'=>'Generate'
        ),
    ),
);