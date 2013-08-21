<?php

class MY_Form_validation extends CI_Form_validation {

	function error_exists($field) {
		if (!empty($this->_field_data[$field]['error'])) {
			return TRUE;
		}
		return FALSE;
	}
	
	function get_error($field) {
		return $this->_field_data[$field]['error'];
	}
	
	function set_error($field, $message) {
		if (!isset($this->_field_data[$field])) {
			$this->_field_data[$field] = array(
				'field'	=> $field,
				'label' => $field,
				'rules' => NULL,
				'is_array' => FALSE,
				'keys' => NULL,
				'postdata' => NULL,
				'error' => $message
			);
		}
		else {
			$this->_field_data[$field]['error'] = $message;
		}
	}
	
	public function valid_email($str) {
		return empty($str) || parent::valid_email($str);
	}
	
	public function alpha($str) {
		return empty($str) || parent::alpha($str);
	}
	
	public function alpha_numeric($str) {
		return empty($str) || parent::alpha_numeric($str);
	}
	
	public function alpha_dash($str) {
		return empty($str) || parent::alpha_dash($str);
	}
	
	public function numeric($str) {
		return empty($str) || parent::numeric($str);
	}
	
	public function integer($str) {
		return empty($str) || parent::integer($str);
	}
	
	public function decimal($str) {
		return empty($str) || parent::decimal($str);
	}
	
	public function greater_than($str, $min) {
		return empty($str) || parent::greater_than($str, $min);
	}
	
	public function less_than($str, $max) {
		return empty($str) || parent::less_than($str, $max);
	}
	
	public function is_natural($str) {
		return empty($str) || parent::is_natural($str);
	}
	
	public function is_natural_no_zero($str) {
		return empty($str) || parent::is_natural_no_zero($str);
	}
	
	public function valid_date($str, $fmt = 'Y-m-d') {
		if (empty($str)) {
			return TRUE;
		}
		$date = date_create_from_format($fmt, $str);
		$errors = DateTime::getLastErrors();
		if ($date === FALSE || $errors['warning_count'] > 0) {
			return FALSE;
		}
		return checkdate($date->format('m'), $date->format('d'), $date->format('Y'));
	}
	
}