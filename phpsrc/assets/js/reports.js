var periodos = [];
jQuery(function($) {
	
	$('.movement-detail').click(function(e) {
		e.preventDefault();
		e.stopPropagation();
		
		var id = $(this).attr('data-id');
		
		$(this).parent().parent().siblings('.mdetail').hide();
		$(this).parent().parent().siblings('[rel='+id+']').show();
	});
	
	$('.filter-collapse').click(function(e) {
		e.preventDefault();
		$(this).attr('disabled','disabled');
		$('.report-filters .filters').slideToggle(400,function() {
			$('.filter-collapse').removeAttr('disabled');
			$('.filter-collapse i').toggleClass('icon-minus icon-plus');
		});
	});
	
	/*$('#form_field_FechaInicial').datepicker({
		changeMonth: true,
		numberOfMonths: 3,
		onClose: function(selectedDate) {
			$('#form_field_FechaFinal').datepicker('option','minDate',selectedDate);
		},
		dateFormat:'dd.mm.y'
	});
	$('#form_field_FechaFinal').datepicker({
		changeMonth: true,
		numberOfMonths: 3,
		onClose: function(selectedDate) {
			$('#form_field_FechaInicial').datepicker('option','maxDate',selectedDate);
		},
		dateFormat:'dd.mm.y'
	});*/
	$('.datepicker').each(function() {
		var format = $(this).data('format'),
			minDate = $(this).data('minDate'),
			maxDate = $(this).data('maxDate'),
			options = {};
		options.dateFormat = format ? format : 'M d, yy';
		if (minDate) {
			options.minDate = minDate;
		}
		if (maxDate) {
			options.maxDate = maxDate;
		}
		console.dir(options);
		$(this).datepicker(options);
	});
	$('.datepicker-trigger').click(function(e) {
		e.preventDefault();
		var id = $(this).attr('rel');
		$(id).datepicker('show');
	});
	
	$('#lnk-print').click(function(e) {
		if (document.forms.length > 0) {
			e.preventDefault();
			var form = $(document.forms[0]);
			var action = form.attr('action');
			form.attr('action',action+'/printr');
			form.attr('target','_blank');
			document.forms[0].submit();
			form.removeAttr('target');
			form.attr('action',action);
		}
		else {
			$(this).attr('href', window.location.href+'/printr');
			$(this).attr('target', '_blank');
		}
	});
	
	periodos = $('#form_field_id_periodo option');
	$('#form_field_id_ciudad').change(function(e) {
		var type = $(this).val();
		$('#form_field_id_periodo option[value!=0]').remove();
		for (var i = 0; i < periodos.length; i++) {
			var typeId = $(periodos[i]).attr('data-type');
			if ((typeId != 0 && type == 0) || typeId == type) {
				$('#form_field_id_periodo').append($(periodos[i]));
			}
		}
	});
	$('#form_field_id_ciudad').trigger('change');
	
});

function setNestedDropdown(elem, elid) {
	var id = $(elem).attr('data-id');
	$('#'+elid).val(id);
	$('#'+elid+'_title').html($(elem).html());
}