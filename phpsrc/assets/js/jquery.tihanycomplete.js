(function($) {
	$.fn.tihanyComplete = function(options) {
		var settings = $.extend( {
				data : {},
				itemsInDrop : 4,
				changeCallback : null
			}, options);

		return this.each(function() {
			var control = this;
			var role = $(this).attr('role');
			var controlid = $(this).attr('data-id');
			var initTxt = $(this).val();
			var initId = $(controlid).val();
			
			$(this).autocomplete({
				source: settings.data[role],
				focus: function(e,ui) {
					$(this).val(ui.item.value);
					return false;
				},
				select: function(e,ui) {
					if (ui.item != null) {
						$(this).val(ui.item.value);
						$(controlid).val(ui.item.id);
					}
					else {
						$(controlid).val('');
					}
					if (settings.changeCallback) {
						if (typeof settings.changeCallback == 'string')
							window[settings.changeCallback].apply(this, [ui.item,control,settings.data]);
						else {
							settings.changeCallback(ui.item, control, settings.data);
						}
					}
				},
				change: function(e,ui) {
					//console.log(ui.item);
					if (ui.item == null) {
						if ($(this).val() != initTxt || $(controlid).val() != initId)
							$(controlid).val('');
					}
					if (settings.changeCallback) {
						if (typeof settings.changeCallback == 'string')
							window[settings.changeCallback].apply(this, [ui.item,control,settings.data]);
						else {
							settings.changeCallback(ui.item, control, settings.data);
						}
					}
				},
				delay: 100
			})
			.data( 'autocomplete' )._renderItem = function( ul,item ) {
				return $('<li/>')
					.data( 'item.autocomplete', item )
					.append( '<a>'+(item.label || item.value)+'</a>' )
					.appendTo( ul );
			};
			/*.change(function(e,ui) {
				console.log(ui.item);
				var role = $(this).attr('role');
				var controlid = $(this).attr('data-id');
				var txt = $(this).val().trim().toLowerCase();
				var found = false, item = null;
				if (txt.length > 0) {
					var arr = settings.data[role];
					for (var i = 0; i < arr.length && !found; i++) {
						if (arr[i].value.toLowerCase() == txt) {
							found = true;
							item = arr[i];
						}
					}
				}
				if (!found) {
					$(controlid).val('');
					var div = $(this).siblings('div');
					div.show();
					div.children('select').focus();
				}
				else {
					$(controlid).val(item.id).triggerHandler('change');
					$(this).siblings('div').hide();
					settings.foundCallback(item, this, settings);
				}
			});*/
		});
	}
})(jQuery);