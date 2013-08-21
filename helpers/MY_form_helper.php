<?php

if (!function_exists('has_errors')) {
	
	function has_errors($field) {
		$OBJ =& _get_validation_object();
		if ($OBJ == FALSE) {
			return FALSE;
		}
		$err = $OBJ->error($field);
		return !empty($err);
	}
	
}