$(document).ready(function() {
    //createEventsSet(profSet);

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


    //displayProfOverviewSchedule();

    $(window).resize(function() {
        $('#profOverviewSchedule').fullCalendar('rerenderEvents');
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
        slotLabelFormat: 'h(:mm) a',
        slotLabelInterval: '00:30',
        slotDuration: '00:60:00',
        events: [
            {
                title: 'CS 1410 Online',
                start: '2016-11-07',
                backgroundColor: '#583372'
            },
            {
                title: 'CS 3230 Online',
                start: '2016-11-07',
                backgroundColor: '#137c33'
            },
            {
                title: 'CS 1410',
                start: '2016-11-07T09:30:00',
                end: '2016-11-07T11:20:00',
                backgroundColor: '#583372'
            },
            {
                title: 'CS 1410',
                start: '2016-11-09T09:30:00',
                end: '2016-11-09T11:20:00',
                backgroundColor: '#583372'
            },
            {
                title: 'CS 1410',
                start: '2016-11-08T11:30:00',
                end: '2016-11-08T13:20:00',
                backgroundColor: '#583372'
            },
            {
                title: 'CS 1410',
                start: '2016-11-10T11:30:00',
                end: '2016-11-10T13:20:00',
                backgroundColor: '#583372'
            }
        ]
    });

}


function createEventsSet(theSet){
    var events = [];
    var rowZeroColumnZero = moment({ years:2016, months:10, date:6, hours:6, minutes:00}); //11/7/16, 6 AM
    var prevDividerStart = rowZeroColumnZero;
    theSet.forEach(function(prof, i){
        var theTitle = prof.name;
        var prevNumCourses;
        if (i == 0)
            prevNumCourses = 0;
        else
            prevNumCourses = theSet[i-1].onlineCourses.length;

        var theStart = prevDividerStart.clone().add(5, 'm');
        var theEnd = theStart.clone().add(10, 'm');

        events.push(
            {
                title: theTitle,
                start: theStart,
                end: theEnd,
                className: 'profName'
            },
            {
                title: 'MW',
                start: theStart,
                end: theEnd,
                className: 'days'
            },
            {
                title: " ",
                start: theStart.clone().add(10, 'm'),
                end: theEnd.clone().add(10,'m'),
                className: 'event_placeholder'
            },
            {
                title: 'TTH',
                start: theStart.clone().add(10, 'm'),
                end: theEnd.clone().add(10,'m'),
                className: 'days'
            }
        );
        var theCourseTitle, theCourseStart;
        prof.timedCourses.forEach(function(course, j){
            theCourseTitle = course.courseTitle;
            theCourseStart = momentGenerator(course.courseTime, course.courseDays, theStart);
            events.push(
                {
                    title: theCourseTitle,
                    start: theCourseStart,
                    end: theCourseStart.clone().add(10,'m'),
                    className: 'classEvent'
                }
            );
        });
        prof.onlineCourses.forEach(function(course, k){
            events.push(
                {
                    title: course.courseTitle + ' [Online]',
                    start: theStart.clone().add((10*2)+(5*k),'m'),
                    end: theStart.clone().add((10*2)+(5*k)+5,'m'),
                    className: 'classEvent'
                });
        });
        prevDividerStart = theStart.clone()
            .add((10 * 2) + (5 * prof.onlineCourses.length), 'm');

        for (i=0; i < 7; i++) {
            events.push(
                {
                    title: "",
                    start: prevDividerStart.clone().add(i, 'd'),
                    end: prevDividerStart.clone()
                        .add(5, 'm')
                        .add(i,'d'),
                    className: 'profDivider'
                });
        }
    });
    return events;
};



function displayProfOverviewSchedule() {
    var theProfSet = createProfSet();
    var theEvents = createEventsSet(theProfSet);

    $('#profOverviewSchedule').fullCalendar({
        header: false,
        defaultView: 'agendaWeek',
        navLinks: true, // can click day/week names to navigate views
        editable: true,
        eventLimit: true, // allow "more" link when too many events
        dayNames: ['Professor', '7:30 AM', '9:30 AM', '11:30 AM', '1:30 PM', '5:30 PM', '7:30 PM'],
        columnFormat: 'dddd',
        allDaySlot: false,
        defaultDate: '2016-11-07',  // 11/7/16 is a Monday
        firstDay: '0', //Monday
        slotLabelFormat: ' ', //the space makes the slots blank.  First time is 6 AM.
        slotDuration: '00:5:00',
        minTime: '06:00:00',
        eventAfterRender: function (event, element, view) {
            var width = $(element).width();

            // Check which class the event has so you know whether it's half or quarter width
            if ($(element).hasClass("days")) {
                width = width / 2;
                $(element).css('margin-left', '25%');
                $(element).css('background-color', '#137c33');
                $(element).css('border-color', '#137c33');
            }
            if ($(element).hasClass("classEvent")) {
                $(element).css('background-color', '#583372');
                $(element).css('border-color', '#583372');
            }
            if ($(element).hasClass("profName")) {
                width = width * .90;
                $(element).css('background-color', '#194d96');
                $(element).css('border-color', '#194d96');
                $(element).css('width', width + 'px');
            }
            if ($(element).hasClass("event_placeholder")) {
                $(element).css('margin-top', '5%');
                $(element).css('margin-bottom', '5%');
                $(element).css('background-color', '#fff');
                $(element).css('border', 'none');
            }
            if ($(element).hasClass("profDivider")) {
                $(element).css('background-color', '#e5deea');//  e5deea light purple-gray
                $(element).css('border-color', '#e5deea');
            }

            // Set the new width
            $(element).css('width', width + 'px');

        },
        events: theEvents/*[
            {
                title: 'Brinkerhoff, Delroy',
                start: rowZeroColumnZero,  // date 2016-11-06 is the first column
                end: rowZeroColumnZero.clone().add(10, 'm'),
                className: 'profName'
            },
            {
                title: 'MW',
                start: '2016-11-06T06:00:00',  // date 2016-11-06 is the first column
                end: '2016-11-06T06:10:00',
                className: 'days'
            },
            {
                title: '',
                start: '2016-11-06T06:10:00',  // date 2016-11-06 is the first column
                end: '2016-11-06T06:20:00',
                className: 'event_placeholder'
            },
            {
                title: 'TTH',
                start: '2016-11-06T06:10:00',  // date 2016-11-06 is the first column
                end: '2016-11-06T06:20:00',
                className: 'days'
            },
            {
                title: 'CS 1410',
                start: '2016-11-09T06:00:00', // date 2016-11-09 is 11:30 AM
                end: '2016-11-09T06:10:00',
                className: 'classEvent'
            },
            {
                title: 'CS 1410',
                start: '2016-11-08T06:10:00', // date 2016-11-08 is 9:30 AM
                end: '2016-11-08T06:20:00',
                className: 'classEvent'
            },
            {
                title: 'CS 1410 [Online]',
                start: '2016-11-06T06:20:00',  // date 2016-11-06 is the first column
                end: '2016-11-06T06:25:00',
                className: 'classEvent'
            },
            {
                title: 'CS 3230 [Online]',
                start: '2016-11-06T06:25:00',  // date 2016-11-06 is the first column
                end: '2016-11-06T06:30:00',
                className: 'classEvent'
            },
            {
                title: "",
                start: '06:30', // a start time (10am in this example)
                end: '06:35', // an end time (2pm in this example)
                dow: [0, 1, 2, 3, 4, 5, 6],// Repeat monday and thursday
                color: '#e5deea'  //light purple-gray
            },
            {
                title: 'Ball, Bob',
                start: '2016-11-06T06:35:00',  // date 2016-11-06 is the first column
                end: '2016-11-06T06:45:00',
                className: 'profName'
            },
            {
                title: 'MW',
                start: '2016-11-06T06:35:00',  // date 2016-11-06 is the first column
                end: '2016-11-06T06:45:00',
                className: 'days'
            },
            {
                title: '',
                start: '2016-11-06T06:45:00',  // date 2016-11-06 is the first column
                end: '2016-11-06T06:55:00',
                className: 'event_placeholder'
            },
            {
                title: 'TTH',
                start: '2016-11-06T06:45:00',  // date 2016-11-06 is the first column
                end: '2016-11-06T06:55:00',
                className: 'days'
            },
            {
                title: 'CS 3100',
                start: '2016-11-07T06:45:00',  // date 2016-11-07 is 7:30 AM
                end: '2016-11-07T06:55:00',
                className: 'classEvent'
            },
            {
                title: 'CS 1400',
                start: '2016-11-08T06:35:00',  // date 2016-11-08 is 9:30 AM
                end: '2016-11-08T06:45:00',
                className: 'classEvent'
            },
            {
                title: 'CS 2350',
                start: '2016-11-09T06:35:00',  // date 2016-11-09 is11:30 AM
                end: '2016-11-09T06:45:00',
                className: 'classEvent'
            },
            {
                title: 'CS 1400 [Online]',
                start: '2016-11-06T06:55:00',  // date 2016-11-06 is the first column
                end: '2016-11-06T07:00:00',
                className: 'classEvent'
            },
            {
                title: "",
                start: '07:00', // a start time (10am in this example)
                end: '07:05', // an end time (2pm in this example)
                dow: [0, 1, 2, 3, 4, 5, 6],// Repeat monday and thursday
                color: '#e5deea'  //light purple-gray
            },
            {
                title: 'Cowan, Ted',
                start: '2016-11-06T07:05:00',  // date 2016-11-06 is the first column
                end: '2016-11-06T07:15:00',
                className: 'profName'
            },
            {
                title: 'MW',
                start: '2016-11-06T07:05:00',  // date 2016-11-06 is the first column
                end: '2016-11-06T07:15:00',
                className: 'days'
            },
            {
                title: '',
                start: '2016-11-06T07:15:00',  // date 2016-11-06 is the first column
                end: '2016-11-06T07:25:00',
                className: 'event_placeholder'
            },
            {
                title: 'TTH',
                start: '2016-11-06T07:15:00',  // date 2016-11-06 is the first column
                end: '2016-11-06T07:25:00',
                className: 'days'
            },
            {
                title: 'CS 4750',
                start: '2016-11-11T07:05:00',  // date 2016-11-11 is 5:30 PM
                end: '2016-11-11T07:15:00',
                className: 'classEvent'
            },
            {
                title: 'CS 3100',
                start: '2016-11-12T07:05:00',  // date 2016-11-12 is 7:30 PM
                end: '2016-11-12T07:15:00',
                className: 'classEvent'
            },
            {
                title: 'CS 3030 [Online]',
                start: '2016-11-06T07:25:00',  // date 2016-11-06 is the first column
                end: '2016-11-06T07:30:00',
                className: 'classEvent'
            },
            {
                title: "",
                start: '07:30', // a start time (10am in this example)
                end: '07:35', // an end time (2pm in this example)
                dow: [0, 1, 2, 3, 4, 5, 6],// Repeat monday and thursday
                color: '#e5deea'  //light purple-gray
            }

        ]*/
    });
}