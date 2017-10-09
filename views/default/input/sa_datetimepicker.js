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
					// use datepicker function because of IE issues
					var date = $.datepicker.parseDateTime('yy-mm-dd', 'hh:mm', dateText);
					timestamp = Date.parse(date.toString());
					timestamp = timestamp / 1000;
					
					// apply timezone offset to GMT
					timestamp = timestamp - (date.getTimezoneOffset() * 60);
				}
				
				var id = $(this).attr('id');
				$('input[name="' + id + '"]').val(timestamp);
			}
		}
	});
});
