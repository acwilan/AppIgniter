<?= '<' ?>?php

class <?= ucfirst($table) ?> extends Crud_master_detail_Controller {

    function __construct() {
        $this->load->config('crud_<?= $table ?>');
        parent::__construct('<?= $prefix ?>/<?= $table ?>', '<?= $table ?>', '<?= $primary_key ?>', '<?= $table ?>_detail', '<?= $table ?>_detail_id', '<?= $primary_key ?>', 'details', 'is_new');
        
        $this->data_parts['title'] = '<?= humanize($table) ?>';
        $this->load->model('<?= $table ?>_model');
    }

}