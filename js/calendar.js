$(document).ready(function() {

    // page is now ready, initialize the calendar...

    $('#calendar').fullCalendar({
        header: false,/*{
            left: 'prev,next today',
            center: 'title',
            right: 'month,agendaWeek,agendaDay,listWeek'
        }*/
        //defaultDate: '2016-09-12',
        allDaySlot: false,
        defaultView: 'agendaWeek',
        hiddenDays: [0],
        columnFormat: 'dddd',
        firstDay: '1', //Monday
        navLinks: true, // can click day/week names to navigate views
        editable: true,
        eventLimit: true, // allow "more" link when too many events
        minTime: '06:30:00',
        events: [
            {
                title: 'Long Event',
                start: '2016-09-07',
                end: '2016-09-10',

            },
            {
                id: 999,
                title: 'Repeating Event',
                start: '2016-09-09T16:00:00'
            },
            {
                id: 999,
                title: 'Repeating Event',
                start: '2016-09-16T16:00:00'
            },


            {
                title: 'Other Meeting',
                start: '2016-11-09T10:30:00',
                end: '2016-11-09T12:30:00',
                backgroundColor: '#FF33F6'
            },
            {
                title: 'Meeting',
                start: '2016-11-09T10:30:00',
                end: '2016-11-09T12:30:00'
            },
            {
                title: 'Other Meeting',
                start: '2016-11-09T10:30:00',
                end: '2016-11-09T12:30:00'
            },
            {
                title: 'Meeting',
                start: '2016-11-09T10:30:00',
                end: '2016-11-09T12:30:00'
            },
            {
                title: 'Other Meeting',
                start: '2016-11-09T10:30:00',
                end: '2016-11-09T12:30:00'
            },
            {
                title: 'Meeting',
                start: '2016-11-09T10:30:00',
                end: '2016-11-09T12:30:00'
            },
            {
                title: 'Lunch',
                start: '2016-09-12T12:00:00'
            },
            {
                title: 'Meeting',
                start: '2016-09-12T14:30:00'
            },
            {
                title: 'Happy Hour',
                start: '2016-09-12T17:30:00'
            },
            {
                title: 'Dinner',
                start: '2016-09-12T20:00:00'
            },
            {
                title: 'Birthday Party',
                start: '2016-09-13T07:00:00'
            },
            {
                title: 'Click for Google',
                url: 'http://google.com/',
                start: '2016-09-28'
            }
        ]
    });

});