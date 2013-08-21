<?php

function crud_data_grid($column_data, $table_data, $options, $meta_data) {
	$CI =& get_instance();
	$CI->crud->data_grid($column_data, $table_data, $options, $meta_data);
}

function crud_data_form($field_data, $obj, $options, $meta_data) {
	$CI =& get_instance();
	$CI->crud->data_form($field_data, $obj, $options, $meta_data);
}