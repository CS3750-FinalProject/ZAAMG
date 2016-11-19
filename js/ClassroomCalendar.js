/**
 * Created by Gisela on 11/18/2016.
 */

$(document).ready(function() {

    $(window).resize(function() {
        // without rerendering, the event blocks get jacked when the window changes size
        $('#classroomOverviewSchedule').fullCalendar('rerenderEvents');
    });
});



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