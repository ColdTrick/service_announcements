define(['jquery'], function ($) {
	if (!$('.elgg-input-sa-datetime').length) {
		return;
	}
	
	$('.elgg-input-sa-datetime').datetimepicker({
		dateFormat: 'yy-mm-dd',
		timeFormat: 'hh:mm',
		ampm: false,
		hideIfNoPrevNext: true,
		onClose: function(dateText, timepicker) {
			
			if ($(this).is('.elgg-input-timestamp')) {
				// convert to unix timestamp
				var timestamp = '';
				if (dateText.length) {
					timestamp = Date.parse(dateText);
					timestamp = timestamp / 1000;
				}
				
				var id = $(this).attr('id');
				$('input[name="' + id + '"]').val(timestamp);
			}
		}
	});
});
