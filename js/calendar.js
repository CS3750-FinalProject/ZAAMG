$(document).ready(function(){
    InlineEditing();
    ModalEditing();

    $(window).resize(function() {
        // without rerendering, the event blocks get jacked when the window changes size
        $('#profOverviewSchedule').fullCalendar('rerenderEvents');
    });
});




/*
 * FUNCTIONS IN THIS FILE:  (no particular order)
 *
 * loadPhpPage(page)
 * changePage(anchor)
 * on_profRowClick(profRowId, sectionObjects)
 * load_indProfRowEvents(sectionObjects)
 * formatTime_fullCalendar(time)
 * addNewEvent(title, eventstart, eventend, location, classroom, professor, color, isOnline, eventsArray)
 * getMinTime(eventsArray, hourChange)
 * displayCalendar = function(profRowId, eventsArray)
 * createEventsSet_test(theSet)         ------  rename this one! -------
 * displayTest(theProfSet)              -----  rename this one! ------   It's the Overview ------
 * fixButtons()
 * fixHeaders_prof
 */

function loadPhpPage(page){
    $("#main_container").load(page);
}

/* changePage(anchor) is what changes the shading on the Navbar and also loads the correct php content
*  into the main container.
************************************************************************************************************/
function changePage(anchor){
    phpPages = {"sec":"section_page.php", "prof":"prof_page.php", "room":"room_page.php"};
    navbar_item = anchor.parentElement;


    switch (navbar_item.getAttribute('id')){
        case 'navbar_sec':
            loadPhpPage("section_page.php");
            $('#navbar_sec').addClass('active');
            $('#navbar_prof').removeClass('active');
            $('#navbar_room').removeClass('active');
            break;
        case 'navbar_prof':
            loadPhpPage("prof_page.php");
            $('#navbar_sec').removeClass('active');
            $('#navbar_prof').addClass('active');
            $('#navbar_room').removeClass('active');
            break;
        case 'navbar_room':
            loadPhpPage("classroom_page.php");
            $('#navbar_sec').removeClass('active');
            $('#navbar_prof').removeClass('active');
            $('#navbar_room').addClass('active');
            break;
    }
}





/*******************************************************************************************/

/*  function for handling show/hide of an individual professor schedule
 *
 */
function on_profRowClick(profRowId, sectionObjects){
    $('tr#' + 'calRow_prof'+ profRowId).toggleClass('hide');

    var currentDate = 6; //why not

    /* function 'load_indProfRowEvents()' is defined in
     * this file ('calendar.js').  It takes an array of JSON sectionObjects
     * and returns an events array for fullCalendar to read.
     */
    var theEvents = load_indProfRowEvents(sectionObjects);


    //this is what initializes and creates the individual calendar.
    //it's defined in this file (calendar.js)
    displayCalendar(profRowId, theEvents);


    //here the table row containing the calendar is shown or hidden:
    if ($('span#' + 'seeCal_prof' + profRowId).attr('class').includes("menu-up")){
        $('div#' + 'cal_prof'+ profRowId).hide();
    }else{
        $('div#'+'cal_prof'+ profRowId).show();
        currentDate = $('#profOverviewSchedule').fullCalendar('getDate');
    }
    $('span#' + 'seeCal_prof' + profRowId).toggleClass('glyphicon-menu-down glyphicon-menu-up');

}

/*******************************************************************************************/

/*
 * takes array of section JSON objects and returns array of events to be passed to
 * fullCalendar for display.
 */
function load_indProfRowEvents(sectionObjects){
    var events = [];
    sectionObjects.forEach(function(section, i){
        addNewEvent(section.title, section.start, section.end, section.location, section.classroom, section.professor,
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
 * pushes an event to the events array read by fullCalendar for an individual professor
 * figures out minimum course start time, so that the online courses can be lined up to the side
 *
 * fullCalendar works well with times formatted like '2016-11-14T07:30:00'
 */
function addNewEvent(title, eventstart, eventend, location, classroom, professor, color, isOnline, eventsArray) {
    var timedEvents = [];
    var numOnline = 0;
    eventsArray.forEach(function(event, i){
       if (event.online == true){
           numOnline ++;
       }else{
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
    }else{
        var adj_minHour = minHour + (numOnline);
        var starttime = '2016-11-13T';
        if (adj_minHour < 10) starttime += '0';
        starttime += adj_minHour + minCourseTime.substr(minCourseTime.indexOf(':'));

        var endtime = '2016-11-13T';
        if (adj_minHour + 1 < 10) endtime += '0';
        var minutesMinus2 = parseInt(minCourseTime.substr(minCourseTime.indexOf(':')+1, 2)) - 2;
        endtime += (adj_minHour + 1) + ':' + minutesMinus2  + minCourseTime.substr(minCourseTime.lastIndexOf(':'));

        eventsArray.push({
            title: title,
            start: formatTime_fullCalendar(starttime),
            end: formatTime_fullCalendar(endtime),
            color: color,
            location: "Online",
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

// display an individual professor's schedule
var displayCalendar = function(profRowId, eventsArray){
    $('#' + 'cal_prof' + profRowId).fullCalendar({
        height: 250,
        header: false,
        defaultDate: '2016-11-07',  // 11/7/16 is a Monday
        allDaySlot: false,  // online courses can show here
        defaultView: 'agendaWeek',
        dayNames: [ 'Online','Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
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





/*  This function takes the "set" of professors and their section objects, and forms them into
 *  event blocks to be displayed by fullCalendar.  The events have starting and ending times which
 *  do NOT correlate to class time -- these times are for positioning the professors vertically in
 *  the first column.
 *
 *  To add to a moment object, call .clone() and then .add or .subtract (number, 'option').
 *  Ex:  momentObject.clone().add(5,'m') adds 5 minutes.  'd' is for days.  'h' is for hours.
 *
 *  Adding days moves an event to a different column.  Adding minutes moves it down to lower rows.
 */
function createEventsSet_test(theSet){
    var singleRow = 5;   //row "height" is 5 minutes
    var doubleRow = 10;  //two rows is 10 minutes
    var events = [];
    var rowZeroColumnZero = moment({ years:2016, months:10, date:6, hours:6, minutes:00}); //11/7/16, 6 AM
    var prevDividerStart = rowZeroColumnZero;

    theSet.forEach(function(prof, i){
        var profName = prof.name;
        var profId = 'prof_' + prof.id;
        var mwId = 'mw_' + prof.id;
        var theStart = i == 0 ? rowZeroColumnZero : prevDividerStart.clone().add(5, 'm');
        var theEnd = theStart.clone().add(10, 'm');
        events.push(
            {
                title: profName.substr(0, profName.indexOf(',')+1) + '\n'
                + profName.substr(profName.indexOf(' ')),
                start: theStart.toString().slice(16,24),
                end: theEnd.toString().slice(16,24),
                dow: [0],                           //dow means day of week  -- this is a recurring event
                className: 'profName',
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
        prof.timedCourses.forEach(function(course, j){
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
                        title: theCourseTitle + '   >>>\n' +
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
        prof.onlineCourses.forEach(function(course, k){
            events.push(
                {
                    title: course.courseTitle + '   -- Online --',
                    start: theStart.clone().add((doubleRow * 2)+(singleRow * k),'m').toString().slice(16,24),
                    end: theStart.clone().add((doubleRow * 2)+(singleRow * k)+ 4.8,'m').toString().slice(16,24),
                    color: '#583372',
                    //className: 'online',
                    dow: [0]
                });
        });
        prof.nonStandardCourses.forEach(function(course, m){
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
                        title: course.courseTitle + '   >>>\n' +
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
        prevDividerStart = theStart.clone()
            .add((10 * 2) + (5 * prof.onlineCourses.length)  /*(10 * prof.nonStandardCourses.length)*/, 'm');

        for (i=0; i < 7; i++) {
            events.push(
                {
                    title: "",
                    start: prevDividerStart.clone().add(i, 'd').toString().slice(16,24),
                    end: prevDividerStart.clone()
                        .add(5, 'm')
                        .add(i,'d').toString().slice(16,24),
                    dow: [i],
                    className: 'profDivider'
                });
        }
    });
    return events;
};

/*******************************************************************************************/

function isOverPageBreak(startTime, duration){
    var isOver = false;
    pageBreakTimes = ['1:30 PM', '7:30 PM'];
    pageBreakTimes.forEach(function(pageBreakTime){
        var diff = moment.duration(
                            moment(pageBreakTime,'h:mm A').diff(moment(startTime, 'h:mm A'))
                            ).asMinutes();
        if (diff > 0){
            if (diff < duration){
                isOver = true;
            }
        }
    });
    return isOver;
}

/*******************************************************************************************/

function getWidthMultiplier(duration){
    return Math.floor(duration/60) + (duration / (60 * Math.ceil(duration/60)));
}
/*******************************************************************************************/

function getMinutesOverPageBreak(startTime, duration){
    var pageBreakTimes = ['1:30 PM', '7:30 PM'];
    var minutesOverPageBreak = 0;
    pageBreakTimes.forEach(function(pageBreakTime){
        var diff = moment.duration(
            moment(pageBreakTime,'h:mm A').diff(moment(startTime, 'h:mm A'))
        ).asMinutes();
        if (diff > 0){
            if (diff < duration){
                minutesOverPageBreak = duration - diff;
            }
        }
    });
    return minutesOverPageBreak;
}

/*******************************************************************************************/

function displayTest(theProfSet) {
    var theEvents = createEventsSet_test(theProfSet);

    $('#profOverviewSchedule').fullCalendar({
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
        dayNames: ['Professor', '7:30 AM', '8:30 AM', '9:30 AM', '10:30 AM', '11:30 AM', '12:30 PM'],
        columnFormat: 'dddd',
        allDaySlot: false,
        defaultDate: '2016-11-06',  // 11/7/16 is a Monday
        firstDay: '0', //Monday
        slotLabelFormat: ' ', //the space makes the slots blank.  First time is 6 AM.
        slotDuration: '00:5:00',
        minTime: '06:00:00',
        eventOrder: 'order_by',
        eventAfterRender: function (event, element, view) {
            if ($(element).hasClass("profName")) {
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
            if ($(element).hasClass("profDivider")) {
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
            fixHeaders_prof(); //this function changes the innerHTML of the weekday headers when we switch weeks
            //no need for button listeners
        },
        events: theEvents
    });

}


/*******************************************************************************************/

/*  disables prev/next buttons when there's no further to go in
 *  that direction.
 */
function fixButtons(){

    var currentDate = $('#profOverviewSchedule').fullCalendar('getDate').date();

    if(currentDate == 13){
        if ($("#profOverviewSchedule .fc-prev-button").hasClass('fc-state-disabled')){
            $("#profOverviewSchedule .fc-prev-button").removeClass('fc-state-disabled');
        }
        if ($("#profOverviewSchedule .fc-next-button").hasClass('fc-state-disabled')){
            $("#profOverviewSchedule .fc-next-button").removeClass('fc-state-disabled');
        }
    }else if (currentDate == 20){
        $("#profOverviewSchedule .fc-next-button").addClass('fc-state-disabled');
    }else if (currentDate == 6){
        $("#profOverviewSchedule .fc-prev-button").addClass('fc-state-disabled');
        if ($("#profOverviewSchedule .fc-next-button").hasClass('fc-state-disabled')){
            $("#profOverviewSchedule .fc-next-button").removeClass('fc-state-disabled');
        }
    }
}


/*******************************************************************************************/


var fixHeaders_prof = function(){
    fixButtons();
    var dayHeaders = [ $('#profOverviewSchedule .fc-mon')[0], $('#profOverviewSchedule .fc-tue')[0], $('#profOverviewSchedule .fc-wed')[0],
        $('#profOverviewSchedule .fc-thu')[0], $('#profOverviewSchedule .fc-fri')[0], $('#profOverviewSchedule .fc-sat')[0],
        $('#profOverviewSchedule .fc-sun')[0] ];



    dayHeaders.forEach(function(header, i){
        header.getElementsByTagName('a')[0].setAttribute('id', 'header_' + i);
        header.getElementsByTagName('a')[0].setAttribute('style', 'float: left; text-decoration: none');
        header.setAttribute('style', 'padding-left: 4px');
    });

    switch ($('#profOverviewSchedule').fullCalendar('getDate').date()){  // "current date" is the visible Sunday
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