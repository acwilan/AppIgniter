<?php

// Clase para manejar formularios maestro-detalle
class Crud_master_detail_Controller extends Crud_Controller {
	
	// Nombre de la tabla de detalles
	protected $detail_table;
	// Llave primaria de la tabla detalles
	protected $detail_pk;
	// Indice a utilizar en los formularios para los detalles, p.ej, "data[details]" -> para data[details][0][quantity], data[details][1][quantity], data[details][0][name], etc.
	protected $detail_index;
	// Llave foranea en la tabla detalles que enlaca con la tabla maestra
	protected $detail_fk;
	// indice del detalle que determina si es un nuevo registro
	protected $isNew_key;
	
	function __construct($modname, $table, $pk, $dettable, $detpk, $detfk = FALSE, $detindex = 'details', $isnk = 'is_new') {
		parent::__construct($modname, $table, $pk);
		$this->form_view = 'crud/form_master_detail';
		$this->detail_table = $dettable;
		$this->detail_pk = $detpk;
		$this->detail_index = $detindex;
		$this->detail_fk = !$detfk ? $pk : $detfk;
		$this->isNew_key = $isnk;
		
		$this->crud_options['detail_index'] = $this->detail_index;
		$this->crud_options['detail_pk'] = $this->detail_pk;
	}
	
	// Eliminar un detalle de orden ya ingresado. Se envia como parametro el ID del detalle
	public function delete_detail_ajax($id) {
		$success = TRUE;
		$msg = NULL;
		if (!$this->CI->db->where($this->detail_pk, $id)->delete($this->detail_table)) {
			$success = FALSE;
			$msg = 'No se pudo eliminar el registro';
		}
		$this->output->set_content_type('application/json')
			->set_output(json_encode((object)array(
			'success'=>$success,
			'message'=>$msg,
		)));
	}
	
	protected function _insert($data) {
		if (isset($data[$this->detail_index])) {
			$details = $data[$this->detail_index];
			unset($data[$this->detail_index]);
			
			$this->CI->db->trans_start();
			$mastid = parent::_insert($data);
			if (!empty($mastid)) {
				$data[$this->primary_key] = $mastid;
				$this->_update_details($mastid, $data, $details, TRUE);
			}
			$this->CI->db->trans_complete();
			return $mastid;
		}
		else {
			return parent::_insert($data);
		}
	}
	
	protected function _update($id, $data) {
		$details = $data[$this->detail_index];
		unset($data[$this->detail_index]);
		
		$this->CI->db->trans_start();
		$upd = parent::_update($id, $data);
		if ($upd) {
			$data[$this->primary_key] = $id;
			$this->_update_details($id, $data, $details, FALSE);
		}
		$this->CI->db->trans_complete();
		return $upd;
	}
	
	protected function _update_details($mastid, &$master, $details, $isNew) {
		foreach ($details as $detail) {
			if (FALSE === $this->_preprocess_detail($detail, $master)) {
				continue;
			}
			$is_new = $detail[$this->isNew_key];
			unset($detail[$this->isNew_key]);
			if ($is_new) {
				$detail[$this->detail_fk] = $mastid;
				$this->CI->db->insert($this->detail_table, $detail);
				$detail[$this->detail_pk] = $detid = $this->CI->db->insert_id();
				$this->_after_detail_update($detid, $master, $detail, $isNew, TRUE);
			}
			else {
				$detid = $detail[$this->detail_pk];
				unset($detail[$this->detail_pk]);
				$this->CI->db->update($this->detail_table, $detail, array($this->detail_pk => $detid));
				$detail[$this->detail_pk] = $detid;
				$this->_after_detail_update($detid, $master, $detail, $isNew, FALSE);
			}
		}
	}
	
	protected function _after_detail_update($detid, $master, $detail, $mastNew, $is_new) { }
	protected function _preprocess_detail(&$detail, $master) {
		return TRUE;
	}
	
	protected function _get_data($id = NULL) {
		$obj = parent::_get_data($id);
		if (!empty($id)) {
			$obj->{$this->detail_index} = !empty($id) ? $this->_get_details($id) : array();
		}
		return $obj;
	}
	
	protected function _get_details($id) {
		$query = $this->CI->db->where($this->detail_fk,$id)->get($this->detail_table);
		$rows = $query->num_rows() > 0 ? $query->result() : array();
		$details = array();
		foreach ($rows as $row) {
			$details[$row->{$this->detail_pk}] = $row;
		}
		return $details;
	}
	
	protected function _show_footer() {
		$this->data_parts['js_files'] []= 'jquery.bootstrap-grid.js?v=4';
			
		$detCol = FALSE;
		$cols = $this->_get_form_columns();
		foreach ($cols as $col) if ($col->type=='details') {
			$detCol = $col;
			break;
		}
		
		$afterAddCallback = ($this->module_name == 'point_of_sale/sales')
					? ', afterAddDetailCallback: newDetailFunc,'
					: '';
		
		$this->data_parts['js_scripts'] []= "
		jQuery(function($) {
			$('#grid_details_".str_replace('/','_',$this->module_name)."').bootstrapGrid({
				colModel: ".json_encode(!$detCol ? array() : $detCol->columns).",
				moduleUrl: '".site_url($this->module_name)."',
				crudUrl: '".site_url($this->module_name)."',
				getAutocompleteData: function() { return autocompleteData; },
				detailsIndex: '{$this->detail_index}'
				$afterAddCallback
			});
		});
		";
		parent::_show_footer();
	}
	
	protected function _get_form_columns() {
		$cols = parent::_get_form_columns();
		foreach ($cols as $col) {
			if ($col->type == 'details') {
				foreach ($col->columns as $field) {
					if ($field->type == 'related') {
						if (isset($field->relation->query) && !empty($field->relation->query)) {
							$rows = $this->CI->db->query($field->relation->query)->result();
						}
						else {
							$this->CI->db->select("{$field->relation->field} AS id");
							if (!isset($field->relation->displayfnc) || empty($field->relation->displayfnc))
								$field->relation->displayfnc = $field->relation->display;
							if (isset($field->relation->filter) && !empty($field->relation->filter))
								$this->CI->db->where($field->relation->filter, NULL, FALSE);
							if (isset($field->relation->order) && !empty($field->relation->order))
								$this->CI->db->order_by($field->relation->order);
							else
								$this->CI->db->order_by($field->relation->display);
							$this->CI->db->select("{$field->relation->displayfnc} AS title",FALSE);
							if (isset($field->relation->type_field))
								$this->CI->db->select("{$field->relation->type_field} AS type");
							if (isset($field->relation->limit))
								$this->CI->db->limit($field->relation->limit);
							$rows = $this->CI->db->get($field->relation->table)->result(); 
						}
						$field->type = 'dropdown';
						unset($field->relation);
						$field->options = array();
						foreach ($rows as $row) {
							$field->options[] = (object)array('value'=>$row->id,'text'=>$row->title);
						}
					}
					$field = $field;
				}
				//die(json_encode($col->columns));
			}
		}
		return $cols;
	}
	
	/*protected function _get_object_model($id = NULL, $data = array()) {
		$obj = parent::_get_object_model($id, $data);
		
		if (!isset($obj->{$this->detail_index})) {
			$obj->{$this->detail_index} = array();
		}
		var_dump($obj);die();
		if (is_array($data) && !empty($data) && isset($data[$this->detail_index])) {
			$cols = $this->_get_form_columns();
			foreach ($cols as &$col) {
				if ($col->type == 'details' && is_array($col->columns)) {
					foreach ($data[$this->detail_index] as $index=>$detail) {
						if (!isset($obj->{$this->detail_index}[$index])) {
							$obj->{$this->detail_index}[$index] = new stdClass;
						}							
						foreach ($detail as $field=>$value) {
							$obj->{$this->detail_index}[$index]->{$field} = $value;
						}
					}
				}
			}
		}
		
		return $obj;
	}*/
}