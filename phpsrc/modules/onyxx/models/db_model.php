<?php

class Db_model extends CI_Model {
    
    function get_tables() {
        return $this->db->list_tables();
    }
    
    function get_fields($table) {
        return $this->db->field_data($table);
    }
}