$(document).ready(function() {
    //createEventsSet(profSet);

    $("#seeCal_1").click ( function(){
        $('#profRow_1').toggle();

        addNewEvent('addNewEvent(1)', '2016-11-11T07:30:00',
            '2016-11-11T09:20:00', 'Main 103C', 'Valle, Hugo','#583372', false);
        addNewEvent('addNewEvent(2)', '2016-11-11T09:30:00',
            '2016-11-11T11:20:00', 'Main 103C', 'Fry, Richard','#583372', false);
        addNewEvent('OnlineEvent(1)', '2016-11-11T00:00:00',
            '2016-11-11T00:00:00', '', 'Fry, Richard','#137c33', true);
        addNewEvent('OnlineEvent(2)', '2016-11-11T00:00:00',
            '2016-11-11T00:00:00', '', 'Rague, Brian','#137c33', true);
        console.log(getMinTime(hardcoded_events));
        displayCalendar();
        if ($(this).attr('class').includes("menu-up")){
            $('#profCalendar_1').hide();
        }else{
            $('#profCalendar_1').show();
        }

        $(this).toggleClass('glyphicon-menu-down glyphicon-menu-up');
    });

    //displayProfOverviewSchedule();

    $(window).resize(function() {
        $('#profOverviewSchedule').fullCalendar('rerenderEvents');
    });




});


function addNewEvent(title, eventstart, eventend, location, professor, color, isOnline) {
    var numOnline = 0;
    hardcoded_events.forEach(function(event, i){
       if (event.online == true){
           numOnline ++;
       }
    });
    console.log("numOnline: " + numOnline);
    var minCourseTime = getMinTime(hardcoded_events);
    var minHour = parseInt(minCourseTime.substr(0, minCourseTime.indexOf(':')));
    console.log("minHour: " + minHour);
    if (!isOnline){
        hardcoded_events.push({
            title: title,
            start: eventstart,
            end: eventend,
            color: color,
            location: location,
            professor: professor,
            online: isOnline
        });
    }else{
        var adj_minHour = minHour + (numOnline);
        var starttime = '2016-11-13T';
        if (adj_minHour < 10) starttime += '0';
        starttime += adj_minHour + minCourseTime.substr(minCourseTime.indexOf(':'));
        console.log("starttime:", starttime);

        var endtime = '2016-11-13T';
        if (adj_minHour + 1 < 10) endtime += '0';
        endtime += (adj_minHour + 1) + minCourseTime.substr(minCourseTime.indexOf(':'));
        console.log("endtime:", endtime);

        hardcoded_events.push({
            title: title,
            start: starttime,
            end: endtime,
            color: color,
            location: location,
            professor: professor,
            online: isOnline
        });
    }
}



function getMinTime(eventsArray){
    var minTime = '01/01/2017 23:59:00';
    eventsArray.forEach(function(event, i){
        var eventTime = '01/01/2017 ' + event.start.substr(event.start.indexOf('T')+1);
        if (Date.parse(eventTime) < Date.parse(minTime) )
            minTime = eventTime;
    });
    return minTime.substr(minTime.indexOf(' ')+1);
}


var hardcoded_events = [
    /*{
        title: 'CS 1410 Online',
        start: '2016-11-07',
        color: '#583372',
        online: true
    },
    {
        title: 'CS 3230 Online',
        start: '2016-11-07',
        color: '#137c33',
        online: true
    },*/
    {
        title: 'CS 1410',
        start: '2016-11-07T09:30:00',
        end: '2016-11-07T11:20:00',
        color: '#583372'
    },
    {
        title: 'CS 1410',
        start: '2016-11-09T09:30:00',
        end: '2016-11-09T11:20:00',
        color: '#583372'
    },
    {
        title: 'CS 1410',
        start: '2016-11-08T11:30:00',
        end: '2016-11-08T13:20:00',
        color: '#583372'
    },
    {
        title: 'CS 1410',
        start: '2016-11-10T11:30:00',
        end: '2016-11-10T13:20:00',
        color: '#583372'
    }
];




var displayCalendar = function(){
    $('#profCalendar_1').fullCalendar({
        height: 250,
        header: false,
        defaultDate: '2016-11-07',  // 11/7/16 is a Monday
        //allDaySlot: false,  // online courses can show here
        defaultView: 'agendaWeek',
        dayNames: [ 'Online','Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
        columnFormat: 'dddd',
        firstDay: '1', //Monday
        navLinks: true, // can click day/week names to navigate views
        editable: true,
        eventLimit: true, // allow "more" link when too many events
        minTime: '07:30:00',
        slotLabelFormat: 'h(:mm) a',
        slotLabelInterval: '00:30',
        slotDuration: '00:60:00',
        events: hardcoded_events,
        scrollTime: getMinTime(hardcoded_events),
        eventRender: function (event, element) {
            element.popover(
                {
                    title: event.title,
                    content: event.location + "\n" + event.professor,
                    trigger: 'hover click',
                    placement: "right",
                    selector: event,
                    container: 'body'  //  THIS NEEDS TO BE HERE SO tooltip is on top of everything
                }
            );
            //element.attr('title', event.professor);
        },
    });

}


function createEventsSet(theSet){
        console.log(theSet);
    var events = [];
    var rowZeroColumnZero = moment({ years:2016, months:10, date:6, hours:6, minutes:00}); //11/7/16, 6 AM
    var prevDividerStart = rowZeroColumnZero;
    theSet.forEach(function(prof, i){
        var profName = prof.name;
        console.log("prof.name: " + profName);
        var theStart = i == 0 ? rowZeroColumnZero : prevDividerStart.clone().add(5, 'm');
        var theEnd = theStart.clone().add(10, 'm');
        //document.getElementsByClassName('profName')[1]
        events.push(
            {
                title: profName,
                start: theStart,
                end: theEnd,
                className: 'profName',
                order_by: 'A',
                color: '#194d96'
            },
            {
                title: 'MW',
                start: theStart,
                end: theEnd,
                className: 'days',
                order_by: 'B'
            },
            {
                title: " ",
                start: theStart.clone().add(10, 'm'),
                end: theEnd.clone().add(10,'m'),
                className: 'event_placeholder',
                order_by: 'A'
            },
            {
                title: 'TTH',
                start: theStart.clone().add(10, 'm'),
                end: theEnd.clone().add(10,'m'),
                className: 'days',
                order_by: 'B'
            }
        );
        var theCourseTitle;
        var theCourseStart;
        prof.timedCourses.forEach(function(course, j){
            theCourseTitle = course.courseTitle;
            theCourseStart = momentGenerator(course.startTime, course.courseDays, theStart.clone());

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
                    title: course.courseTitle + '   -- Online --',
                    start: theStart.clone().add((10*2)+(5*k),'m'),
                    end: theStart.clone().add((10*2)+(5*k)+5,'m'),
                    className: 'classEvent'
                });
        });
        prof.nonStandardCourses.forEach(function(course, m){
            theCourseStart = momentGenerator(course.startTime, course.courseDays, theStart.clone());
            events.push(
                {
                    title: course.courseTitle + '\n' +
                        course.startTime.substring(0,course.startTime.indexOf(' ')) +
                        '-' + course.endTime.substring(0,course.endTime.indexOf(' ')),
                    start: theCourseStart,
                    end: theCourseStart.clone().add(10, 'm'),
                    color: '#840b38'
                }
                /*{
                    title: course.courseTitle + '  --  ' + course.courseDays.toUpperCase() + '   --  ' +
                    '\n' + course.startTime + ' - ' + course.endTime,
                    start: theStart.clone().add((10*2)+(5*prof.onlineCourses.length) + (10*m),'m'),
                    end: theStart.clone().add((10*2)+(5*prof.onlineCourses.length) + (10*m) + 10,'m'),
                    color: '#840b38'
                }*/
            );
        });
        prevDividerStart = theStart.clone()
            .add((10 * 2) + (5 * prof.onlineCourses.length)  /*(10 * prof.nonStandardCourses.length)*/, 'm');

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



function displayProfOverviewSchedule(theProfSet) {
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
        eventOrder: 'order_by',
        eventAfterRender: function (event, element, view) {
            if ($(element).hasClass("profName")) {
                $(element).css('width', $(element).width() *.90 + 'px');
                $(element).css('background-color', '#194d96');
                $(element).css('border-color', '#194d96');
            }
            if ($(element).hasClass("days")) {
                $(element).css('width', $(element).width() / 2 + 'px');
                $(element).css('margin-left', '25%');
                $(element).css('background-color', '#137c33');
                $(element).css('border-color', '#137c33');
            }
            if ($(element).hasClass("classEvent")) {
                $(element).css('background-color', '#583372');
                $(element).css('border-color', '#583372');
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



        },
        events: theEvents
    });
}
























/*[
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