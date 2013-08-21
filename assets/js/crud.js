jQuery(function($, undefined) {
	$('#btn-cancel').click(function(e) {
		if (!confirm('Seguro de cancelar y perder cambios?'))
			e.preventDefault();
	});
	$('.frm_checkbox').change(function() {
		$(this).val($(this).is(':checked') ? 1 : 0);
	});
	$('.datepicker').datepicker({dateFormat:'M d, yy'});
	$('.datepicker-trigger').click(function(e) {
		e.preventDefault();
		var id = $(this).attr('rel');
		$(id).datepicker('show');
	});
	$('.datetimepicker').datetimepicker({dateFormat:'M d, yy'});
	$('.datetimepicker-trigger').click(function(e) {
		e.preventDefault();
		var id = $(this).attr('rel');
		$(id).datetimepicker('show');
	});
	
	$('.autocomplete').each(function() {
		var onchg = $(this).data('onchange');
		if (onchg != undefined) {
			$(this).tihanyComplete({
				data : autocompleteData,
				changeCallback : onchg
			});
		} else {
			$(this).tihanyComplete({
				data : autocompleteData
			});
		}
	});
	
	$('.row-header').click(function() {
		$(this).parent().parent().toggleClass('info');
	});
	
	var deletefnc = function(e) {
		e.preventDefault();
		if (confirm('Esta seguro de eliminar los elementos seleccionados?')) {
			var checks = $('#grid_'+moduleName+" input[type='checkbox']:checked");
			var ids = [];
			$('.grid-overlay').show();
			checks.each(function() {
				var id = $(this).val();
				if ($(this).attr('checked'))
					ids.push(id);
				if (ids.length >= checks.length) {
					$.post(moduleUrl+'/delete_ajax', { idList: ids }, function(result) {
						if (result.success) {
							$.post(moduleUrl+'/grid_opp/refresh',{},function(html){
								refreshfnc(html);
								if (result.message != null)
									alert(result.message);
							},'html');
						} else {
							alert('No se pudo eliminar los elementos seleccionados.\n'+result.message);
							$('.grid-overlay').hide();
						}
					}, 'json');
				}
			});
		}
	};
	var togglefnc = function(e) {
		e.preventDefault();
		var parent = $(this).parent();
		var anchor = $(this).detach();
		var href = anchor.attr('href');
		parent.html('<img src="'+siteUrl+'assets/images/loading.gif" />');
		$.get(href, {}, function(data) {
			parent.html('');
			if (data.success) {
				anchor.children('i').toggleClass('icon-ok icon-remove');
			}
			else {
				alert('No se pudo realizar la accion');
			}
			parent.append(anchor);
		}, 'json');
	};
	var sortfnc = function() {
		var cname = $(this).attr('data-id');
		$('.grid-overlay').show();
		$.post(moduleUrl+'/grid_opp/sort',{field:cname},refreshfnc,'html');
	};
	var pagfnc = function(e) {
		e.preventDefault();
		if ($(this).parent().hasClass('disabled')) return;
		var newpg = $(this).attr('data-id');
		$('.grid-overlay').show();
		$.post(moduleUrl+'/grid_opp/pag',{page:newpg},refreshfnc,'html');
	}
	var searchfnc = function(e) {
		e.preventDefault();
		var term = $(this).find('.search-query').val();
		$('.grid-overlay').show();
		$.post(moduleUrl+'/grid_opp/search',{term:term},refreshfnc,'html');
	}
	var refreshfnc = function(html) {
		$('.grid-overlay').hide();
		if (html != null)
			$('#main-content').html(html);
		$('.btn-toggle').click(togglefnc);
		$('.table-sortable th.sortable').click(sortfnc);
		$('#'+moduleName+'-pagination a').click(pagfnc);
		$('.form-search').submit(searchfnc);
		$('[rel=popover]').popover({trigger:'hover'});
		$('[rel=tooltip]').tooltip();
		$('#btn-del-selected').click(deletefnc);
		$('.grid-search-form .clear').click(function(e) {
			e.preventDefault();
			$(this).siblings('input').val('');
			$(this).siblings('button').trigger('click');
		});
	};
	
	refreshfnc(null);
});

function setNestedDropdown(elem, elid) {
	var id = $(elem).attr('data-id');
	$('#'+elid).val(id);
	$('#'+elid+'_title').html($(elem).html());
}