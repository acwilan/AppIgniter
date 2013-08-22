<?= '<' ?>?php

class <?= ucfirst($table) ?> extends Crud_Controller {
    
    function __construct() {
        $this->load->config('crud_<?= $table ?>');
        parent::__construct('<?= $module ?>/<?= $table ?>', '<?= $table ?>', '<?= $primary_key ?>');
        
        $this->data_parts['title'] = '<?= humanize($table) ?>';
        $this->load->model('<?= $table ?>_model');
    }
}