/**
 * Created by Gisela on 11/18/2016.
 */


/**
 *   FUNCTIONS IN THIS FILE:
 *
 *   add_toClassroomSet(classroomNumber, timedCourseObjects)
 *
 *
 */




function add_toClassroomSet(theSet, classroomNumber, timedCourseObjects){

    var t_courseObjects = [];
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
    theSet.push(
        {
            name: classroomNumber,
            timedCourses: t_courseObjects,
            nonStandardCourses: nonStandard_courseObjects
        }
    );

}

