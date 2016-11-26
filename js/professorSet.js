/**
 *   FUNCTIONS IN THIS FILE:
 *
 *   add_toProfSet(profFirst, profLast, profId, timedCourseObjects, onlineCourseObjects)
 *   momentGenerator_test(time, days, startMoment)
 *
 */




function add_toProfSet(profFirst, profLast, profId, timedCourseObjects, onlineCourseObjects){
    var profName = profLast + ", " + profFirst;

    var t_courseObjects = [];
    var o_courseObjects = [];
    var nonStandard_courseObjects = [];

    timedCourseObjects.forEach(function(course, i){
        var courseTitle = course.pref + " " + course.num;

        var startTimeMoment = moment(course.startTime, "hh:mm A");
        var endTimeMoment = moment(course.endTime, "hh:mm A");

        var formatted_startTime =
            startTimeMoment.hour() > 12 ?  startTimeMoment.hour() - 12 : startTimeMoment.hour();
        formatted_startTime += ":";
        formatted_startTime += startTimeMoment.minute() == 0 ? '00' : startTimeMoment.minute();
        formatted_startTime += " " + course.startTime.substr(course.startTime.length - 2, course.startTime.length);

        var formatted_endTime =
            endTimeMoment.hour() > 12 ?  endTimeMoment.hour() - 12 : endTimeMoment.hour();
        formatted_endTime += ":";
        formatted_endTime += endTimeMoment.minute() == 0 ? '00' : endTimeMoment.minute();
        formatted_endTime += " " + course.endTime.substr(course.endTime.length - 2, course.endTime.length);

        var standardTimes = ['7:30 AM', '8:30 AM', '9:30 AM', '10:30 AM', '11:30 AM',
            '12:30 PM', '1:30 PM', '2:30 PM', '3:30 PM', '4:30 PM', '5:30 PM', '6:30 PM', '7:30 PM',
            '8:30 PM', '9:30 PM', '10:30 PM'
        ];


        if (standardTimes.indexOf(formatted_startTime) == -1){
            nonStandard_courseObjects.push(
                {
                    courseTitle: courseTitle,
                    courseDays: course.days,
                    startTime: formatted_startTime,
                    endTime: formatted_endTime
                }
            );
        }else{
            t_courseObjects.push(
                {
                    courseTitle: courseTitle,
                    courseDays: course.days,
                    startTime: formatted_startTime,
                    endTime: formatted_endTime
                }
            );
        }
    });
    onlineCourseObjects.forEach(function(course, k){
        var courseTitle = course.pref + " " + course.num;
        o_courseObjects.push(
            {
                courseTitle: courseTitle
            }
        );

    });
    theProfSet.push(
        {
            name: profName,
            id: profId,
            timedCourses: t_courseObjects,
            onlineCourses: o_courseObjects,
            nonStandardCourses: nonStandard_courseObjects
        }
    );

}


/**************************************************************************************************************/


/*  produces the moment time objects that place course event blocks in the correct positions
*   in profOverviewSchedule.
 */
function momentGenerator_test(time, days, startMoment){
    var newTime = time;

    var standardTimes = ['7:30 AM', '8:30 AM', '9:30 AM', '10:30 AM', '11:30 AM',
        '12:30 PM', '1:30 PM', '2:30 PM', '3:30 PM', '4:30 PM', '5:30 PM', '6:30 PM', '7:30 PM',
        '8:30 PM', '9:30 PM', '10:30 PM'
    ];

    // if it's on the hour, treat it like half an hour earlier.  for ex: treat 8:00 am like 7:30 am.
    if (standardTimes.indexOf(newTime) == -1){
        newTime = moment('2016-01-01 ' + newTime, 'YYYY-MM-DD hh:mm A').clone().subtract(30,'m').format('h:mm A');
    }
    var theMoment;
    switch(newTime) {
        case "7:30 AM":
            theMoment = startMoment.clone().add(1, 'd');
            break;
        case "8:30 AM":
            theMoment = startMoment.clone().add(2, 'd');
            break;
        case "9:30 AM":
            theMoment = startMoment.clone().add(3, 'd');
            break;
        case "10:30 AM":
            theMoment = startMoment.clone().add(4, 'd');
            break;
        case "11:30 AM":
            theMoment = startMoment.clone().add(5, 'd');
            break;
        case "12:30 PM":
            theMoment = startMoment.clone().add(6, 'd');
            break;

        case "1:30 PM":
            theMoment = startMoment.clone().add(8, 'd');
            break;
        case "2:30 PM":
            theMoment = startMoment.clone().add(9, 'd');
            break;
        case "3:30 PM":
            theMoment = startMoment.clone().add(10, 'd');
            break;
        case "4:30 PM":
            theMoment = startMoment.clone().add(11, 'd');
            break;
        case "5:30 PM":
            theMoment = startMoment.clone().add(12, 'd');
            break;
        case "6:30 PM":
            theMoment = startMoment.clone().add(13, 'd');
            break;
        case "7:30 PM":
            theMoment = startMoment.clone().add(15, 'd');
            break;
        case "8:30 PM":
            theMoment = startMoment.clone().add(16, 'd');
            break;
        case "9:30 PM":
            theMoment = startMoment.clone().add(17, 'd');
            break;
        case "10:30 PM":
            theMoment = startMoment.clone().add(18, 'd');
            break;
    }
    if (days.toUpperCase() == "TTH")
        theMoment = theMoment.clone().add(2, 'm');
    if (days.toUpperCase() == "MWF")
        theMoment = theMoment.clone().add(4, 'm');
    return theMoment;
};









