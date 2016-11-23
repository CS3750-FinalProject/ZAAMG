/**
 * Created by Gisela on 11/18/2016.
 */

$(document).ready(function() {
    InlineEditing();

    $(window).resize(function() {
        // without rerendering, the event blocks get jacked when the window changes size
        $('#classroomOverviewSchedule').fullCalendar('rerenderEvents');
    });
});



/*******************************************************************************************/

/*  function for handling show/hide of an individual classroom schedule
 *
 */
function on_roomRowClick(roomRowId, sectionObjects){
    $('tr#' + 'calRow_room'+ roomRowId).toggleClass('hide');
    var currentDate = 6; //why not

    /* function 'load_indRoomRowEvents()' is defined in
     * this file ('classroomCalendar.js').  It takes an array of JSON sectionObjects
     * and returns an events array for fullCalendar to read.
     */
    var theEvents = load_indRoomRowEvents(sectionObjects);


    //this is what initializes and creates the individual calendar.
    //it's defined in this file (classroomCalendar.js)
    displayCalendar_Room(roomRowId, theEvents);


    //here the table row containing the calendar is shown or hidden:
    if ($('span#' + 'seeCal_room' + roomRowId).attr('class').includes("menu-up")){
        $('div#' + 'cal_room'+ roomRowId).hide();
    }else{
        $('div#'+'cal_room'+ roomRowId).show();
        currentDate = $('#classroomOverviewSchedule').fullCalendar('getDate');
    }
    $('span#' + 'seeCal_room' + roomRowId).toggleClass('glyphicon-menu-down glyphicon-menu-up');

}

/*******************************************************************************************/

/*
 * takes array of section JSON objects and returns array of events to be passed to
 * fullCalendar for display.
 */
function load_indRoomRowEvents(sectionObjects){
    var events = [];
    sectionObjects.forEach(function(section, i){
        addNewEvent_Room(section.title, section.start, section.end, section.location, section.classroom, section.professor,
            section.online ? '#137c33' : '#583372', section.online, events)
    })

    return events;
}

/*******************************************************************************************/


/*  takes a string like '2016-11-07T08:00 AM' and returns
 *  '2016-11-07T08:00:00' because fullCalendar likes it like that.
 *
 *  If the string is like '07:00:00', that's what gets returned.
 *
 *  This function is called in addNewEvent()
 */
function formatTime_fullCalendar(time){
    var timePattern = /[0-9-]{10}T[0-9:]{5} [APM]{2}/;
    if (timePattern.test(time)){
        var timePart = time.substr(time.indexOf('T')+1);
        var hourPart = timePart.substr(0, 2);
        var hour = parseInt(hourPart);
        var minutePart = timePart.substr(timePart.indexOf(':')+1, 2);
        if (timePart.indexOf('PM') != -1){
            if (hour < 12){
                timePart = (hour + 12) + ':' + minutePart + ':00';
            }else{ //hourPart is 12 and it's PM
                timePart = hourPart + ':' + minutePart + ':00';
            }
        }else { // time is AM
            timePart = hourPart + ':' + minutePart + ':00';
        }
        return time.substr(0, time.indexOf('T')+1) + timePart;
    }else{
        return time;
    }

}

/*******************************************************************************************/

/*
 * pushes an event to the events array read by fullCalendar for an individual classroom
 * figures out minimum course start time, so that the online courses can be lined up to the side
 *
 * fullCalendar works well with times formatted like '2016-11-14T07:30:00'
 */
function addNewEvent_Room(title, eventstart, eventend, location, classroom, professor, color, isOnline, eventsArray) {
    var timedEvents = [];
    //var numOnline = 0;
    eventsArray.forEach(function(event, i){
        if (!event.online){
            timedEvents.push(event);
        }
    });
    var minCourseTime = getMinTime(timedEvents, 0);
    var minHour = moment(minCourseTime, 'hh:mm:ss').hour();
    if (!isOnline){
        eventsArray.push({
            title: title,
            start: formatTime_fullCalendar(eventstart),
            end: formatTime_fullCalendar(eventend),
            color: color,
            location: location,
            classroom: classroom,
            professor: professor,
            online: isOnline
        });
    }
}


/*******************************************************************************************/
//used for scrolling a calendar to just below the earliest section

function getMinTime(eventsArray, hourChange){  //times come in looking like 2016-11-08T09:30 AM
    var minTime = '01/01/2017 23:59:00';
    eventsArray.forEach(function(event, i){
        var eventTime = '01/01/2017 ' + event.start.substr(event.start.indexOf('T')+1);
        if (Date.parse(eventTime) < Date.parse(minTime) )
            minTime = eventTime;
    });
    if (minTime == '01/01/2017 23:59:00') minTime = '01/01/2017 07:30:00'
    var hour = parseInt(minTime.substr(minTime.indexOf(' ')+1,2)) + hourChange;
    if (hour < 10) hour = '0' + hour;
    return hour + minTime.substr(minTime.indexOf(':'));
}

/*******************************************************************************************/

// display an individual classroom's schedule
var displayCalendar_Room = function(roomRowId, eventsArray){
    $('#' + 'cal_room' + roomRowId).fullCalendar({
        height: 250,
        header: false,
        defaultDate: '2016-11-07',  // 11/7/16 is a Monday
        allDaySlot: false,  // online courses can show here
        defaultView: 'agendaWeek',
        dayNames: [ '','Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
        columnFormat: 'dddd',
        firstDay: '1', //Monday
        navLinks: true, // can click day/week names to navigate views
        editable: false,
        eventLimit: true, // allow "more" link when too many events
        minTime: '06:30:00',
        slotLabelFormat: 'h(:mm) a',
        slotLabelInterval: '00:30',
        slotDuration: '00:60:00',
        events: eventsArray,
        scrollTime: formatTime_fullCalendar(getMinTime(eventsArray, -1)), //defined in this file (calendar.js)
        eventRender: function (event, element) {
            var timeString = event.online ? "" :
                (moment(event.start).format('h:mm A') + ' - ' + moment(event.end).format('h:mm A'));
            var room = event.classroom != undefined ? event.classroom : "";
            element.popover(
                {
                    html: true,
                    title: "<strong>" + event.title + "</strong>",  //some html has to be in the title or it won't work
                    content: event.professor + "<br>" + event.location + " " + room + "<br>" + timeString,
                    trigger: 'hover click',
                    placement: "right",
                    selector: event,
                    container: 'body'  //  THIS NEEDS TO BE HERE SO tooltip is on top of everything
                }
            );
        },
    });

}







/*  This function takes the "set" of classrooms and their section objects, and forms them into
 *  event blocks to be displayed by fullCalendar.  The events have starting and ending times which
 *  do NOT correlate to class time -- these times are for positioning the classrooms vertically in
 *  the first column.
 *
 *  To add to a moment object, call .clone() and then .add or .subtract (number, 'option').
 *  Ex:  momentObject.clone().add(5,'m') adds 5 minutes.  'd' is for days.  'h' is for hours.
 *
 *  *  Adding days moves an event to a different column.  Adding minutes moves it down to lower rows.
 */
function createClassroomEventsSet(classroomSet){
    var singleRow = 5;   //row "height" is 5 minutes
    var doubleRow = 10;  //two rows is 10 minutes
    var events = [];
    var rowZeroColumnZero = moment({ years:2016, months:10, date:6, hours:6, minutes:00}); //11/7/16, 6 AM
    var prevDividerStart = rowZeroColumnZero;

    classroomSet.forEach(function(classroom, i){
        var classroomName = classroom.name;
        var classroomId = 'classroom_' + classroom.id;
        //var mwId = 'mw_' + classroom.id;
        var theStart = i == 0 ? rowZeroColumnZero : prevDividerStart.clone().add(5, 'm');
        var theEnd = theStart.clone().add(10, 'm');
        events.push(
            {
                title: classroomName,
                start: theStart.toString().slice(16,24),
                end: theEnd.toString().slice(16,24),
                dow: [0],                           //dow means day of week  -- this is a recurring event
                className: 'classroomName',
                order_by: 'A',
                color: '#194d96'
            },
            {
                title: 'MW',
                start: theStart.toString().slice(16,24),
                end: theEnd.toString().slice(16,24),
                dow: [0],
                className: 'days',
                order_by: 'B'
            },
            {
                title: " ",
                start: theStart.clone().add(10, 'm').toString().slice(16,24),
                end: theEnd.clone().add(10,'m').toString().slice(16,24),
                dow: [0],
                className: 'event_placeholder',
                order_by: 'A'
            },
            {
                title: 'TTH',
                start: theStart.clone().add(10, 'm').toString().slice(16,24),
                end: theEnd.clone().add(10,'m').toString().slice(16,24),
                dow: [0],
                className: 'days',
                order_by: 'B'
            }
        );
        var theCourseTitle;
        var theCourseStart;
        classroom.timedCourses.forEach(function(course, j){
            theCourseTitle = course.courseTitle;
            theCourseStart = momentGenerator_test(course.startTime, course.courseDays, theStart.clone());
            var courseDuration = moment.duration(
                moment(course.endTime,'h:mm A').diff(moment(course.startTime, 'h:mm A'))
            ).asMinutes();
            var overPageBreak = isOverPageBreak(course.startTime, courseDuration);
            if (!overPageBreak){
                events.push(
                    {
                        title: theCourseTitle + '\n' +
                        moment(course.startTime, 'h:mm A').format('h:mm A') + ' - ' +
                        moment(course.endTime, 'h:mm A').format('h:mm A'),
                        start: theCourseStart,
                        end: theCourseStart.clone().add(9.8,'m'),   //just shy of 10 puts a little gap between blocks
                        className: 'classEvent',
                        overPageBreak: overPageBreak,
                        duration: courseDuration
                    }
                );
            }else{
                var minOverPageBreak = getMinutesOverPageBreak(course.startTime, courseDuration);
                events.push(
                    {
                        title: theCourseTitle + '\n' +
                        moment(course.startTime, 'h:mm A').format('h:mm A') + ' - ' +
                        moment(course.endTime, 'h:mm A').format('h:mm A'),
                        start: theCourseStart,
                        end: theCourseStart.clone().add(9.8,'m'),   //just shy of 10 puts a little gap between blocks
                        className: 'classEvent',
                        overPageBreak: overPageBreak,
                        relativeToBreak: 'before',
                        duration: courseDuration
                    },
                    {
                        title: theCourseTitle + ' (cont.)\n' +
                        moment(course.startTime, 'h:mm A').format('h:mm A') + ' - ' +
                        moment(course.endTime, 'h:mm A').format('h:mm A'),
                        start: theCourseStart.clone().add(2, 'd'),
                        end: theCourseStart.clone().add(2,'d').add(9.8,'m'),
                        className: 'classEvent',
                        overPageBreak: overPageBreak,
                        relativeToBreak: 'after',
                        minOverBreak: minOverPageBreak,
                        duration: courseDuration
                    }
                );
            }

        });

        classroom.nonStandardCourses.forEach(function(course, m){
            theCourseStart = momentGenerator_test(course.startTime, course.courseDays, theStart.clone());
            var courseDuration = moment.duration(
                moment(course.endTime,'h:mm A').diff(moment(course.startTime, 'h:mm A'))
            ).asMinutes();
            var overPageBreak = isOverPageBreak(course.startTime, courseDuration);
            if (!overPageBreak){
                events.push(
                    {
                        title: course.courseTitle + '\n' +
                        moment(course.startTime, 'h:mm A').format('h:mm A') + ' - ' +
                        moment(course.endTime, 'h:mm A').format('h:mm A'),
                        start: theCourseStart,
                        end: theCourseStart.clone().add(9.8, 'm'),
                        duration: courseDuration,
                        overPageBreak: overPageBreak,
                        className: 'nonStandard'
                    }
                );
            }else{
                var minOverPageBreak = getMinutesOverPageBreak(course.startTime, courseDuration);
                events.push(
                    {
                        title: course.courseTitle + '\n' +
                        moment(course.startTime, 'h:mm A').format('h:mm A') + ' - ' +
                        moment(course.endTime, 'h:mm A').format('h:mm A'),
                        start: theCourseStart,
                        end: theCourseStart.clone().add(9.8,'m'),   //just shy of 10 puts a little gap between blocks
                        className: 'nonStandard',
                        overPageBreak: overPageBreak,
                        relativeToBreak: 'before',
                        duration: courseDuration
                    },
                    {
                        title: course.courseTitle + ' (cont.)\n' +
                        moment(course.startTime, 'h:mm A').format('h:mm A') + ' - ' +
                        moment(course.endTime, 'h:mm A').format('h:mm A'),
                        start: theCourseStart.clone().add(2, 'd'),
                        end: theCourseStart.clone().add(2,'d').add(9.8,'m'),
                        className: 'nonStandard',
                        overPageBreak: overPageBreak,
                        relativeToBreak: 'after',
                        minOverBreak: minOverPageBreak,
                        duration: courseDuration
                    }
                );
            }

        });
        prevDividerStart = theStart.clone().add((10 * 2), 'm');

        for (i=0; i < 7; i++) {
            events.push(
                {
                    title: "",
                    start: prevDividerStart.clone().add(i, 'd').toString().slice(16,24),
                    end: prevDividerStart.clone()
                        .add(5, 'm')
                        .add(i,'d').toString().slice(16,24),
                    dow: [i],
                    className: 'classroomDivider'
                });
        }
    });
    return events;
};



/*******************************************************************************************/

function displayClassroomSchedule(theClassroomSet) {
    var theEvents = createClassroomEventsSet(theClassroomSet);
    $('#classroomOverviewSchedule').fullCalendar('destroy');
    $('#classroomOverviewSchedule').fullCalendar({
        header: {
            left:   '',
            center: '',
            right:  'prev,next'
        },
        //titleFormat: '[7:30 AM - 12:30 PM]',
        defaultView: 'agendaWeek',
        navLinks: true, // can click day/week names to navigate views
        editable: false,
        eventLimit: true, // allow "more" link when too many events
        dayNames: ['Classroom', '7:30 AM', '8:30 AM', '9:30 AM', '10:30 AM', '11:30 AM', '12:30 PM'],
        columnFormat: 'dddd',
        allDaySlot: false,
        defaultDate: '2016-11-06',  // 11/7/16 is a Monday
        firstDay: '0', //Monday
        slotLabelFormat: ' ', //the space makes the slots blank.  First time is 6 AM.
        slotDuration: '00:5:00',
        minTime: '06:00:00',
        eventOrder: 'order_by',
        eventAfterRender: function (event, element, view) {
            if ($(element).hasClass("classroomName")) {
                $(element).css('background-color', '#194d96');
                $(element).css('border-color', '#194d96');
            }
            if ($(element).hasClass("days")) {
                $(element).css('width', $(element).width() /2 + 'px');
                $(element).css('margin-left', '25%');
                $(element).css('background-color', '#137c33');
                $(element).css('border-color', '#137c33');
            }
            if ($(element).hasClass("classEvent")) {
                if (!event.overPageBreak){
                    var newWidth = getWidthMultiplier(event.duration * $(element).width());
                    $(element).css('width', newWidth + 'px');
                }else{
                    if (event.relativeToBreak == 'after'){
                        var newWidth =
                            getWidthMultiplier(event.minOverBreak * $(element).width());
                        $(element).css('width', newWidth + 'px');
                    }

                }
                $(element).css('margin-right', '-50%');
                $(element).css('background-color', '#583372');
                $(element).css('border-color', '#583372');
            }
            if ($(element).hasClass("event_placeholder")) {
                $(element).css('margin-top', '5%');
                $(element).css('margin-bottom', '5%');
                $(element).css('background-color', '#fff');
                $(element).css('border', 'none');
            }
            if ($(element).hasClass("classroomDivider")) {
                $(element).css('background-color', '#e5deea');//  e5deea light purple-gray
                $(element).css('border-color', '#e5deea');
            }
            if ($(element).hasClass("nonStandard")) {
                if (!event.overPageBreak){
                    var newWidth = getWidthMultiplier(event.duration * $(element).width());
                    $(element).css('width', newWidth + 'px');
                    $(element).css('margin-left', '50%');
                }else{
                    if (event.relativeToBreak == 'after'){
                        var newWidth =
                            getWidthMultiplier(event.minOverBreak * $(element).width());
                        $(element).css('width', newWidth + 'px');
                    }else{
                        $(element).css('margin-left', '50%');
                    }
                }
                $(element).css('margin-right', '-50%'); //I think this helps more text to show
                $(element).css('background-color', '#840b38');
                $(element).css('border-color', '#840b38');
            }

        },
        eventAfterAllRender: function(event, element, view){
            fixHeaders_classroom(); //this function changes the innerHTML of the weekday headers when we switch weeks
            //no need for button listeners
        },
        events: theEvents
    });

}



/*******************************************************************************************/

/*  disables prev/next buttons when there's no further to go in
 *  that direction.
 */
function fixButtons_classroom(){

    var currentDate = $('#classroomOverviewSchedule').fullCalendar('getDate').date();

    if(currentDate == 13){
        if ($("#classroomOverviewSchedule .fc-prev-button").hasClass('fc-state-disabled')){
            $("#classroomOverviewSchedule .fc-prev-button").removeClass('fc-state-disabled');
        }
        if ($("#classroomOverviewSchedule .fc-next-button").hasClass('fc-state-disabled')){
            $("#classroomOverviewSchedule .fc-next-button").removeClass('fc-state-disabled');
        }
    }else if (currentDate == 20){
        $("#classroomOverviewSchedule .fc-next-button").addClass('fc-state-disabled');
    }else if (currentDate == 6){
        $("#classroomOverviewSchedule .fc-prev-button").addClass('fc-state-disabled');
        if ($("#classroomOverviewSchedule .fc-next-button").hasClass('fc-state-disabled')){
            $("#classroomOverviewSchedule .fc-next-button").removeClass('fc-state-disabled');
        }
    }
}



/*******************************************************************************************/


var fixHeaders_classroom = function(){
    fixButtons_classroom();
    var dayHeaders = [ $('#classroomOverviewSchedule .fc-mon')[0], $('#classroomOverviewSchedule .fc-tue')[0], $('#classroomOverviewSchedule .fc-wed')[0],
        $('#classroomOverviewSchedule .fc-thu')[0], $('#classroomOverviewSchedule .fc-fri')[0], $('#classroomOverviewSchedule .fc-sat')[0],
        $('#classroomOverviewSchedule .fc-sun')[0] ];



    dayHeaders.forEach(function(header, i){
        header.getElementsByTagName('a')[0].setAttribute('id', 'header_' + i);
        header.getElementsByTagName('a')[0].setAttribute('style', 'float: left; text-decoration: none');
        header.setAttribute('style', 'padding-left: 4px');
    });

    switch ($('#classroomOverviewSchedule').fullCalendar('getDate').date()){  // "current date" is the visible Sunday
        case 6:
            dayHeaders[0].getElementsByTagName('a')[0].innerHTML = '7:30 AM';
            dayHeaders[1].getElementsByTagName('a')[0].innerHTML = '8:30 AM';
            dayHeaders[2].getElementsByTagName('a')[0].innerHTML = '9:30 AM';
            dayHeaders[3].getElementsByTagName('a')[0].innerHTML = '10:30 AM';
            dayHeaders[4].getElementsByTagName('a')[0].innerHTML = '11:30 AM';
            dayHeaders[5].getElementsByTagName('a')[0].innerHTML = '12:30 PM';
            break;
        case 13:
            dayHeaders[0].getElementsByTagName('a')[0].innerHTML = '1:30 PM';
            dayHeaders[1].getElementsByTagName('a')[0].innerHTML = '2:30 PM';
            dayHeaders[2].getElementsByTagName('a')[0].innerHTML = '3:30 PM';
            dayHeaders[3].getElementsByTagName('a')[0].innerHTML = '4:30 PM';
            dayHeaders[4].getElementsByTagName('a')[0].innerHTML = '5:30 PM';
            dayHeaders[5].getElementsByTagName('a')[0].innerHTML = '6:30 PM';
            break;
        case 20:
            dayHeaders[0].getElementsByTagName('a')[0].innerHTML = '7:30 PM';
            dayHeaders[1].getElementsByTagName('a')[0].innerHTML = '8:30 PM';
            dayHeaders[2].getElementsByTagName('a')[0].innerHTML = '9:30 PM';
            dayHeaders[3].getElementsByTagName('a')[0].innerHTML = '10:30 PM';
            dayHeaders[4].getElementsByTagName('a')[0].innerHTML = '11:30 PM';
            dayHeaders[5].getElementsByTagName('a')[0].innerHTML = '';
            break;
    }

}