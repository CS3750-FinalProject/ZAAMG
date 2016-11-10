$(document).ready(function() {

    $("#seeCal_1").click ( function(){
        $('#1').toggle();

        displayCalendar();
        if ($(this).attr('class').includes("menu-up")){
            $('#calendar').hide();
        }else{
            $('#calendar').show();
        }

        $(this).toggleClass('glyphicon-menu-down glyphicon-menu-up');
    });

});


var displayCalendar = function(){
    $('#calendar').fullCalendar({
        height: 250,
        header: false,
        defaultDate: '2016-11-07',  // 11/7/16 is a Monday
        //allDaySlot: false,  // online courses can show here
        defaultView: 'agendaWeek',
        hiddenDays: [0],
        columnFormat: 'dddd',
        firstDay: '1', //Monday
        navLinks: true, // can click day/week names to navigate views
        editable: true,
        eventLimit: true, // allow "more" link when too many events
        minTime: '07:30:00',
        slotLabelFormat: 'h:mm',
        slotLabelInterval: '00:30',
        slotDuration: '00:60:00',
        events: [
            {
                title: 'CS 1410 Online',
                start: '2016-11-07',
                backgroundColor: '#6526a5'
            },
            {
                title: 'CS 3230 Online',
                start: '2016-11-07',
                backgroundColor: '#34bc56'
            },
            {
                title: 'CS 1410',
                start: '2016-11-07T09:30:00',
                end: '2016-11-07T11:20:00',
                backgroundColor: '#6526a5'
            },
            {
                title: 'CS 1410',
                start: '2016-11-09T09:30:00',
                end: '2016-11-09T11:20:00',
                backgroundColor: '#6526a5'
            },
            {
                title: 'CS 1410',
                start: '2016-11-08T11:30:00',
                end: '2016-11-08T13:20:00',
                backgroundColor: '#6526a5'
            },
            {
                title: 'CS 1410',
                start: '2016-11-10T11:30:00',
                end: '2016-11-10T13:20:00',
                backgroundColor: '#6526a5'
            }
        ]
    });

}