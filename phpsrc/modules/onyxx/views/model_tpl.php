<?= '<' ?>?php

class <?= ucfirst($table) ?>_model extends CI_Model {

    function get_all() {
        return $this->db->order_by('<?= $primary_key ?>','asc')->get('<?= $table ?>')->result();
    }
    
    function get($id) {
        $query = $this->db->where('<?= $primary_key ?>',$id)->limit(1)->get('<?= $table ?>');
        return $query->num_rows() > 0 ? $query->row() : NULL;
    }

}