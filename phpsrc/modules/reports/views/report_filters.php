<?= validation_errors() ?>
<?= form_open($this->module_name,array('class'=>'form-inline')) ?>
<div class="row report-filters">
	<div class="span12">
		<fieldset>
			<legend>Filtros <a href="#" class="btn btn-mini filter-collapse"><i class="icon-minus"></i></a></legend>
			<div class="filters row">
				<div class="span10">
<?php
foreach ($filters as $filter) { 
	$this->load->view('crud/form_control',array('field'=>$filter,'options'=>$options,'meta'=>$meta,'obj'=>$obj,'enclose'=>FALSE)); 
}
?>
				</div>
				<div class="span2 pull-right" style="text-align:right">
					<button type="submit" class="btn" type="submit"><i class="icon-filter"></i> Filtrar</button>
				</div>
			</div>
		</fieldset>
	</div>
</div>
<?= form_close() ?>