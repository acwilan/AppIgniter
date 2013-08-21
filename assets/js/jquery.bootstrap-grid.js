(function($, undefined) {

	$.fn.bootstrapGrid = function(options) {
	
		var settings = $.extend( {
				colModel: [],
				moduleUrl: '',
				crudUrl: 'crud',
				currencySymbol: '$',
				defaultCurrencyId: null,
				beforeRemoveDetailCallback: function(obj, grid,e) { return true; },
				afterRemoveDetailCallback: function(grid, e) {},
				beforeAddDetailCallback: function(grid, e) { return true; },
				afterAddDetailCallback: function(grid, row, e) {},
				rowFocusInCallback: function(grid, row) {},
				rowFocusOutCallback: function(grid, row) {},
				getAutocompleteData: function() { return null; },
				detailsIndex: 'details'
			}, options);
			
		return this.each(function() {
			var grid = $(this);
			currencySymbol = settings.currencySymbol;
			
			var removeDetailFunc = function(e) {
				e.preventDefault();
				if ((typeof settings.beforeRemoveDetailCallback != 'function' || settings.beforeRemoveDetailCallback(this, grid,e)) && confirm('Descartar cambios?')) {
					console.log(this);
					$(this).parents('tr').remove();
					if (typeof settings.afterRemoveDetailCallback == 'function')
						settings.afterRemoveDetailCallback(grid, e);
				}
			};
			
			var addNewDetailFunc = function(e) {
				e.preventDefault();
				
				if (typeof settings.beforeAddDetailCallback != 'function' || settings.beforeAddDetailCallback(grid, e)) {
					var newRow = RowBuilder.buildRow(grid);
					newRow.appendTo(grid.find('tbody'));
					
					newRow.children('td.regular').first().children(':input[type!=hidden]').first().focus().select();
					settings.afterAddDetailCallback(grid, newRow, e);
					window.scrollTo(0, document.body.scrollHeight);
				}
			};
	
			var deleteDetailFunc = function(e) {
				e.preventDefault();
				var anchor = $(this);
				var checks = grid.find('input:checked'), processed = 0;
				if (checks.length > 0) {
					$(this).unbind('click');
					$(this).html('<i class=\"icon-i\"></i> Eliminando...').addClass('disabled');
					checks.each(function() {
						var id = $(this).attr('id').split('_')[2];
						var check = $(this);
						$.post(settings.moduleUrl+'/delete_detail_ajax/'+id, function(data) {
							processed++;
							if (data.success) {
								check.parent().parent().remove();
							}
							if (processed >= checks.length) {
								anchor.html('<i class=\"icon-trash\"></i> Eliminar seleccionados').removeClass('disabled');
								anchor.bind('click', deleteDetailFunc);
								if (grid.find('input[type=checkbox]').length == 0)
									$('#btn-del').hide();
							}
						});
					});
				}
				else
					alert('Seleccione al menos un detalle de orden a eliminar');
			};
			
			var focusInRowFunc = function() {
				$(this).parent().addClass('info');
				$(this).children('.regular').first().children().first().focus();
				if (typeof settings.rowFocusInCallback == 'function')
					settings.rowFocusInCallback(grid, $(this));
			};
			var focusOutRowFunc = function() {
				$(this).parent().removeClass('info');
				if (typeof settings.rowFocusOutCallback == 'function')
					settings.rowFocusOutCallback(grid, $(this));
			};
			var escapeRowFunc = function(e) {
				var key = e.charCode || e.keyCode;
				if (key == 27) {
					removeDetailFunc(e);
				}
			};
			var lastTabRowFunc = function(e) {
				var key = e.charCode || e.keyCode;
				if (!e.shiftKey && key == 9) {
					addNewDetailFunc(e);
				}
			};
			
			grid.on('click','tbody tr.new-row .key a',removeDetailFunc);
			grid.on('keydown','tbody tr:last :input:enabled:not([readonly]):last',lastTabRowFunc);
			grid.on('focusin','tbody tr td',focusInRowFunc);
			grid.on('focusout','tbody tr td',focusOutRowFunc);
			grid.on('keydown','tbody tr.new-row td',escapeRowFunc);
			$('#btn-add').click(addNewDetailFunc);
			$('#btn-del').click(deleteDetailFunc);
			if (grid.find('input[type=checkbox]').length == 0)
				$('#btn-del').hide();
	
			RowBuilder = {
				tableData: [],
			
				buildRow: function(grid) {
					var newRowCount = grid.find('tbody tr.new-row').length;
					var newRow = $('<tr/>').attr('id','new-row-'+(++newRowCount)).addClass('new-row');
					
					$('<td/>').addClass('key').append(
						$('<a/>').attr('href','#').attr('title','Quitar detalle').append(
							$('<i/>').addClass('icon-minus')
						).addClass('btn btn-mini'),
						$('<input/>').attr({
							type: 'hidden',
							name: 'data['+settings.detailsIndex+']['+(-newRowCount)+'][is_new]',
							value: 1,
						})
					).appendTo(newRow);
					
					for (var i = 0; i < settings.colModel.length; i++) {
					
						var col = settings.colModel[i];
						
						if (col.hidden || col.key) continue;
						
						var td = $('<td/>');
						
						td.addClass('regular');
						
						var fldAttr = {
							id: 'data_details_nuevo_'+newRowCount+'_'+col.name,
							name: 'data['+settings.detailsIndex+']['+(-newRowCount)+']['+col.name+']',
							type: 'text',
							placeholder: col.title,
							title: col.title
						};
						if (col['class'])
							fldAttr['class'] = col['class'];
						if (col['disabled'])
							fldAttr['disabled'] = 'disabled';
						if (col['onchange_callback'])
							fldAttr['onchange'] = 'return '+col['onchange_callback']+'(this);';
						
						switch (col.type) {
								
							case 'text':
								td.append($('<textarea/>').attr(fldAttr).attr({rows:1,cols:20}));
								break;
								
							case 'dropdown':
								fldAttr.type = null;
								var opts = '';
								$.each(col.options,function(){
									opts+= ' <option value="'+this.value+'">'+this.text+'</option> '; 
								});
								td.append($('<select/>').attr(fldAttr).append(opts));
								break;
								
							case 'related':
								this.buildDropdown(td, fldAttr, col.relation.table, col.relation.display, col.relation.displayfnc, null);
								break;
								
							case 'city':
								fldAttr.type = 'hidden';
								td.append($('<input/>').attr(fldAttr));
								$('<input/>').attr({
									type: 'text',
									'class': 'autocomplete',
									id: fldAttr.id+'_name',
									role: 'city',
									'data-id': col.id
								}).appendTo(td);
								break;
								
							case 'currency':
								buildDropdown(td, fldAttr, 'monedas', 'simbolo', 'simbolo', settings.defaultCurrencyId);
								break;
								
							case 'exchange_rate':
								var name = fldAttr.name, id = fldAttr.id;
								
								fldAttr.type = 'hidden';
								fldAttr.name = name+'[id]';
								fldAttr.id = id+'_id';
								$('<input/>').attr(fldAttr).appendTo(td);
								
								fldAttr.name = name+'[id_moneda]';
								fldAttr.id = id+'_curr';
								buildDropdown(td, fldAttr, 'monedas', 'simbolo', 'simbolo', currencyId);
								
								fldAttr.type = 'text';
								fldAttr.name = name+'[valor]';
								fldAttr.id = id+'_value';
								fldAttr.value = exchangeRate;
								fldAttr['class'] = 'input-medium';
								$('<input/>').attr(fldAttr).appendTo(td);
								break;
								
							case 'exchange_rate_fixed':
								$('<div/>').addClass('input-prepend').append(
									$('<span/>').addClass('add-on').html(col.symbol),
									$('<input/>').attr(fldAttr)
								).appendTo(td);
								break;
								
							case 'money':
								td.addClass('money');
								if (col.defaultValue != 'undefined')
									fldAttr.value = parseFloat(col.defaultValue).toFixed(2);
								else
									fldAttr.value = '1.00';
								fldAttr.role = col.role;
								if (col.disabled)
									fldAttr.disabled = 'disabled';
								var fld = $('<input/>').attr(fldAttr).addClass('input-mini');
								if (col.onchange_callback)
									fld.bind('change',col.onchange_callback);
								$('<div/>').addClass('input-prepend').append(
									$('<span/>').addClass('add-on').html(col.currencySymbol ? col.currencySymbol : currencySymbol),
									fld
								).appendTo(td);
								break;
								
							case 'autocomplete':
								fldAttr.type = 'hidden';
								$('<input/>').attr(fldAttr).appendTo(td);
								
								fldAttr.name = fldAttr.name.replace(col.name, col.autocomplete.text_field);
								fldAttr['data-id'] = '#'+fldAttr.id;
								fldAttr.id = null;
								fldAttr.role = col.autocomplete.role;
								fldAttr.type = 'text';
								var fld = $('<input/>').attr(fldAttr);
								if (col.autocomplete.onchange_callback)
									fld.tihanyComplete({
										data: settings.getAutocompleteData(),
										changeCallback: col.autocomplete.onchange_callback
									});
								else
									fld.tihanyComplete({
										data: settings.getAutocompleteData()
									});
								fld.appendTo(td);
								break;
								
							case 'reference':
								td.addClass('ref');
								$('<span/>').addClass('ref').html(col.defaultValue).appendTo(td);;
								break;
								
							case 'hidden':
								fldAttr.type = 'hidden';
								
							case 'date':
								fldAttr['class'] = 'datepicker';
						
							case 'password':
								fldAttr.type = 'password';
								
							//case 'checkbox':
							case 'bool':
								fldAttr.type = 'checkbox';
								
							case 'decimal':
								fldAttr.value = '1.00';
								fldAttr['class'] = 'input-mini';
								
							default:
								if (col.role)
									fldAttr.role = col.role;
								if (!fldAttr.value && col.defaultValue != 'undefined')
									fldAttr.value = col.defaultValue;
								var fld = $('<input/>').attr(fldAttr);
								if (col.onchange_callback)
									fld.bind('change',col.onchange_callback);
								if (col.disabled)
									fld.attr('disabled','disabled');
								if (col.tooltip) {
									fld.attr('rel','tooltip');
									if (typeof col.tooltip != 'string') {
										fld.attr('data-title',col.title);
									} else {
										fld.attr('data-title',col.tooltip);
									}
									fld.removeAttr('title');
									fld.tooltip();
								}
								td.append(fld);
								break;
						}
						
						td.appendTo(newRow);
					
					}
					return newRow;
					
				},
				
				buildDropdown: function(td, attr, table, display, displayfnc, dflt) {
					attr.type = null;
					var data = this.tableData[table];
					if (!data) {
						$.post(settings.crudUrl+'/table_data',{table:table,display:display,displayfnc:displayfnc},function(rows) {
							data = rows;
							RowBuilder.tableData[table] = rows;
							var dd = $('<select/>');
							for (var prop in attr)
								dd.attr(prop, attr[prop]);
							for (var j = 0; j < data.length; j++) {
								var opt = $('<option/>').attr('value',data[j].id).html(data[j].value);
								if (data[j].id == dflt)
									opt.attr('selected','selected');
								opt.appendTo(dd);
							}
							dd.appendTo(td);
						},'json');
					}
					else {
						var dd = $('<select/>');
						for (var prop in attr)
							dd.attr(prop, attr[prop]);
						for (var j = 0; j < data.length; j++) {
							var opt = $('<option/>').attr('value',data[j].id).html(data[j].value);
							if (data[j].id == dflt)
								opt.attr('selected','selected');
							opt.appendTo(dd);
						}
						dd.appendTo(td);
					}
				}
				
			};
		});
	
	}
	
})(jQuery);