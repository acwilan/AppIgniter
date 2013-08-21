<?php

class Reports_Controller extends MY_Controller {
	
	protected $report_view = FALSE;
	protected $config = FALSE;
	protected $title = 'Reporte';
	protected $valid_form = TRUE;
	
	function __construct($module_name = FALSE) {
		if ($module_name === FALSE)
			$module_name = 'reports/'.url_title(get_class($this),'_',TRUE);
		parent::__construct($module_name);
		
		$this->data_parts['report'] = FALSE;

		$this->load->library('form_validation');
		$this->load->helper('form');
		$this->load->config('crud_main');
		$this->load->config('reports');
		
		$this->config = config_item('reports');
		
		$this->data_parts['js_files'] []= 'reports.js?v=4';
		$this->data_parts['css_files'] []= 'reports.css?v=4';
		
		$this->form_validation->set_error_delimiters('<div class="alert alert-error"><a href="#" class="close" data-dismiss="alert">&times;</a>', '</div>');
	}
	
	function index() {
		$this->data_parts['report_title'] = $this->title;
		$this->data_parts['print'] = FALSE;
		$filters = $this->input->post('data');
		$this->_show_header();
		
		$this->load->view('report_header',$this->data_parts);
		
		$this->_load_filters($filters);

		if ($this->report_view !== FALSE && $this->valid_form) {
			$report = $this->report_model->get_report($filters, $this->current_period);
			if (empty($report)) {
				$this->load->view('rpt_no_data');
			}
			else {
				$this->data_parts['report'] = $report;
				$this->load->view($this->report_view, $this->data_parts);
			}
		}
		
		$this->load->view('report_footer',$this->data_parts);
		$this->_show_footer();
	}
	
	function printr() {
		$this->data_parts['report_title'] = $this->title;
		$this->data_parts['print'] = TRUE;
		$filters = $this->input->post('data');
		$this->_show_header(FALSE);
		
		$this->load->view('report_header',$this->data_parts);
		
		$this->_load_filters($filters, FALSE);
		
		if ($this->report_view !== FALSE && $this->valid_form) {
			$report = $this->report_model->get_report($filters, $this->current_period);
			if (empty($report)) {
				$this->load->view('rpt_no_data');
			}
			else {
				$this->data_parts['report'] = $report;
				$this->load->view($this->report_view, $this->data_parts);
			}
		}
		
		$this->load->view('report_footer',$this->data_parts);
		$this->data_parts['js_scripts'] []= '$(document).ready(function(){window.print();});';
		$this->_show_footer(FALSE);
	}
	
	protected function _load_filters(&$post, $show_view = TRUE) {
		$cls = url_title(get_class($this),'_',TRUE);
		//var_dump($this->config);die();
		if (isset($this->config->filters[$cls]) && !empty($this->config->filters[$cls])) {
			$filters = $this->config->filters[$cls];
			
			$rules = 0;
			if (!empty($post)) {
				foreach ($filters as $filter) {
					if ($filter->type == 'date' && isset($filter->format) && !empty($post[$filter->name])) {
						$date = date_create_from_format($filter->format, $post[$filter->name]);
						if ($date !== FALSE) {
							$post[$filter->name] = date_format($date, 'Y-m-d');
						}
					}
					if (isset($filter->rules) && !empty($filter->rules)) {
						$this->form_validation->set_rules("data[{$filter->name}]", $filter->title, $filter->rules);
						$rules++;
					}
				}
			}
			
			$this->valid_form = $rules == 0 || $this->form_validation->run() !== FALSE;
			$obj = !empty($post) ? (object)$post : $this->_get_default_filters($filters);
			if ($show_view)
				$this->load->view('report_filters', array('filters'=>$filters,'options'=>(object)array(
					'operation'=>'report','readonly'=>FALSE,
				),'meta'=>NULL,'obj'=>$obj));
			
			$post = $obj;
		}
		return (object)$post;
	}
	
	protected function _get_default_filters($filters) {
		$default = new stdClass;
		foreach ($filters as $filter) {
			if (isset($filter->default))
				$default->{$filter->name} = $filter->default;
			else
				$default->{$filter->name} = NULL;
		}
		return $default;
	}
	
}