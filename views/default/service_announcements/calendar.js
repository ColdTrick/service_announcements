define(['jquery', 'elgg', 'fullcalendar'], function($, elgg) {

	var init = function() {
		$('#service-announcements-calendar').fullCalendar({
			events: elgg.normalize_url('ajax/view/service_announcements/calendar'),
			header: {
				left: 'prev,next today',
				center: 'title',
				right: 'month,agendaWeek,agendaDay'
			},
			allDayDefault: true,
			timeFormat: 'H:mm',
			lang: elgg.get_language(),
			buttonText: {
				today: elgg.echo('service_announcements:calendar:today'),
				month: elgg.echo('service_announcements:calendar:month'),
				week: elgg.echo('service_announcements:calendar:week'),
				day: elgg.echo('service_announcements:calendar:day')
			}
		});
	};

	elgg.register_hook_handler('init', 'system', init);
});