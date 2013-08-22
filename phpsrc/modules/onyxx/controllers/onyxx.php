<?php

class Onyxx extends Crud_Controller {
    
    private $tables;
    
    function __construct() {
        parent::__construct('onyxx', 'tables');
        
        $this->load->helper('inflector');
        $this->load->model('db_model');
        $this->load->helper('onyxx');
        
        $this->data_parts['title'] = 'Onyxx Generator';
        
        $this->allow_edit = FALSE;
        $this->allow_add = FALSE;
        $this->allow_delete = FALSE;
        $this->allow_search = FALSE;
        $this->tables = $this->db_model->get_tables();
    }
    
    protected function _get_data($id = NULL) {
        $tableobj = array();
        foreach ($this->tables as $i=>$name) {
            $tableobj []= (object)array(
                'id'=>$name,
                'name'=>humanize($name),
            );
        }
        $table_count = count($this->tables);
        
        $pg = $this->catalog_state->pagination;
        $pg->total_records = $table_count;
        if ($table_count > 0) {
             $records = $tableobj;
             $offset = ($pg->current_page - 1) * $pg->rpp;
             $pg->total_pages = (int)($pg->total_records / $pg->rpp);
             if ($pg->total_records % $pg->rpp > 0)
                  $pg->total_pages++;
             $this->catalog_state->pagination = $pg;
             $this->_set_catalog_state();
             return array_splice($records, $offset, $this->catalog_state->pagination->rpp);
        }
        else return array();
    }
    
    protected function _set_command() {}
    
    function generator($table) {
        if (!in_array($table, $this->tables)) {
            show_404();
        } else {
            $this->data_parts['operation'] = 'generate';
            $this->crud_options['legend'] = 'Generate for table "'.humanize($table).'"';
            
            parent::_process_form($table);
        }
    }
    
    protected function _get_object_model($id = NULL, $data = array()) {
        $obj = new stdClass();
        $flds = $this->_get_form_columns();
        
        foreach ($flds as $field) {
            $obj->{$field->name} = $field->default;
        }
        return $obj;
    }
    
    // generator function
    protected function _update($table, $data) {
        
        $data['table'] = strtolower($table);
        $data['fields'] = $this->db_model->get_fields($table);
        $data['primary_key'] = "{$table}_id";
        
        foreach ($data['fields'] as $field) {
            if ($field->primary_key == 1) {
                $data['primary_key'] = $field->name;
                break;
            }
        }
        
        $controller_tpl = $this->load->view($data['mast_detail'] ? 'controller_tpl_mast_detail' : 'controller_tpl_regular', $data, TRUE);
        $controller_path = trim($data['path'], DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.'controllers'.DIRECTORY_SEPARATOR.$data['table'].".php";
        file_put_contents($controller_path, $controller_tpl);
        
        $model_tpl = $this->load->view('model_tpl', $data, TRUE);
        $model_path = trim($data['path'], DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.'models'.DIRECTORY_SEPARATOR.$data['table']."_model.php";
        file_put_contents($model_path, $model_tpl);
        
        if ($data['config']) {
            $config_tpl = $this->load->view('config_tpl', $data, TRUE);
            $config_path = trim($data['path'], DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR."crud_{$data['table']}.php";
            file_put_contents($config_path, $config_tpl);
        }
        
        return $table;
    }
    
    protected function _after_save($action, $id, $data, $oper) {
        $this->_store_errors();
        redirect($this->module_name);
    }
}