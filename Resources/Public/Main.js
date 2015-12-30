$(document).ready(function(){
	$('.uk-nestable .uk-nestable-panel').click(function() {
		$(this).closest('.uk-nestable-item').find('.uk-nestable').first().toggleClass('uk-nestable-collapsed');
	});

	var updating = false;
	$('.uk-nestable').on('stop.uk.nestable', function(e) {
		if (updating == true) {
			return;
		}
		updating = true;
		setTimeout(function(){
			var positions = {};
			$('.uk-nestable-item').each(function(i) {
				var parent = 0;
				if ($(this).parents('.uk-nestable-item').first().attr('data-uid')) {
					parent = $(this).parents('.uk-nestable-item').first().attr('data-uid');
				}
				positions[$(this).attr('data-uid')] = {
					sorting: i * 10,
					parent: parent
				}
			});

			var uri = $('.items').attr('data-position-update-uri');

			$.ajax({
				url: uri,
				type: 'post',
				cache: false,
				data: {
					tx_mia3categories_web_mia3categoriestxmia3categoriesmod1: {
						positions: positions
					}
				},
				dataType: 'html',

				success: function(data, textStatus, jqXHR) {
					updating = false;
				},
				error: function(response) {
					updating = false;
				}
			});
		}, 200);
	});
});
