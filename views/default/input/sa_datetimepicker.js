define(['jquery'], function ($) {
	if (!$('.elgg-input-sa-datetime').length) {
		return;
	}
	
	$('.elgg-input-sa-datetime').datetimepicker({
		dateFormat: 'yy-mm-dd',
		timeFormat: 'hh:mm',
		ampm: false,
		hideIfNoPrevNext: true,
		onSelect: function(dateText, timepicker) {
			var datepicker = timepicker.inst;
			
			if ($(this).is('.elgg-input-timestamp')) {
				// convert to unix timestamp
				var timestamp = Date.UTC(datepicker.selectedYear, datepicker.selectedMonth, datepicker.selectedDay, timepicker.hour, timepicker.minute);
				
				timestamp = timestamp / 1000;
				
				var id = $(this).attr('id');
				$('input[name="' + id + '"]').val(timestamp);
			}
		}
	});
});
