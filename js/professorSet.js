//var theProfSet = [];  //array of professor/course objects for making events in fullCalendar

function add_toProfSet(profFirst, profLast, timedCourseObjects, onlineCourseObjects){
    //alert(profFirst);
    var profName = profLast + ", " + profFirst;

    var t_courseObjects = [];
    var o_courseObjects = [];
    var nonStandard_courseObjects = [];

    timedCourseObjects.forEach(function(course, i){
        var courseTitle = course.pref + " " + course.num;

        var startTimeMoment = moment(course.startTime, "hh:mm A");
        var endTimeMoment = moment(course.endTime, "hh:mm A");
        console.log(startTimeMoment.hour());



        var formatted_startTime =
            startTimeMoment.hour() > 12 ?  startTimeMoment.hour() - 12 : startTimeMoment.hour();
        formatted_startTime += ":"  + startTimeMoment.minute() + " "
            + course.startTime.substr(course.startTime.length - 2, course.startTime.length);
        var formatted_endTime =
            endTimeMoment.hour() > 12 ?  endTimeMoment.hour() - 12 : endTimeMoment.hour();
        formatted_endTime += ":"  + endTimeMoment.minute() + " "
            + course.endTime.substr(course.endTime.length - 2, course.endTime.length);
        console.log(formatted_startTime);
        console.log(formatted_endTime);

        var standardTimes = ['7:30 AM', '9:30 AM', '11:30 AM', '1:30 PM', '5:30 PM', '7:30 PM'];

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
            timedCourses: t_courseObjects,
            onlineCourses: o_courseObjects,
            nonStandardCourses: nonStandard_courseObjects
        }
    );

}

function momentGenerator(time, days, startMoment){
    var newTime = time;
    console.log("time" + time);
    /*var theHour = parseInt(newTime.substr(0, newTime.indexOf(':')));
    if (theHour > 12){
        theHour = theHour-12;
        newTime = theHour + newTime.substr(newTime.indexOf(':') );
    }*/
    var standardTimes = ['7:30 AM', '9:30 AM', '11:30 AM', '1:30 PM', '5:30 PM', '7:30 PM'];

    if (standardTimes.indexOf(newTime) == -1){
        var oddHour = parseInt(newTime.substr(0, newTime.indexOf(':')));
        while(standardTimes.indexOf(newTime) == -1){
            oddHour --;
            newTime = oddHour + newTime.substr(newTime.indexOf(':'));
        }
        //var newHour = parseInt(newTime.substr(0, newTime.indexOf(':'))) - 1;
    }
    var theMoment;
    switch(newTime) {
        case "7:30 AM":
            theMoment = startMoment.clone().add(1, 'd');
            break;
        case "9:30 AM":
            theMoment = startMoment.clone().add(2, 'd');
            break;
        case "11:30 AM":
            theMoment = startMoment.clone().add(3, 'd');
            break;
        case "1:30 PM":
            theMoment = startMoment.clone().add(4, 'd');
            break;
        case "5:30 PM":
            theMoment = startMoment.clone().add(5, 'd');
            break;
        case "7:30 PM":
            theMoment = startMoment.clone().add(6, 'd');
            break;
    }
    if (days.toUpperCase() == "TTH")
        theMoment = theMoment.clone().add(10, 'm');
    return theMoment;
};














/*



 function createProfSet(){
 var profSet = [
 {
 name: "Brinkerhoff, Delroy",
 timedCourses: [
 {
 courseTitle: "CS 1410",
 courseDays: "MW",
 courseTime: "11:30 AM",
 },
 {
 courseTitle: "CS 1410",
 courseDays: "TTH",
 courseTime: "9:30 AM",
 }
 ],
 onlineCourses: [
 {
 courseTitle: "CS 1410"
 },
 {
 courseTitle: "CS 3230"
 }
 ]
 },
 {
 name: "Ball, Bob",
 timedCourses: [
 {
 courseTitle: "CS 1400",
 courseDays: "MW",
 courseTime: "9:30 AM",
 },
 {
 courseTitle: "CS 2350",
 courseDays: "MW",
 courseTime: "11:30 AM",
 },
 {
 courseTitle: "CS 3100",
 courseDays: "TTH",
 courseTime: "7:30 AM",
 }
 ],
 onlineCourses: [
 {
 courseTitle: "CS 1400"
 }
 ]
 },
 {
 name: "Cowan, Ted",
 timedCourses: [
 {
 courseTitle: "CS 4750",
 courseDays: "MW",
 courseTime: "5:30 PM",
 },
 {
 courseTitle: "CS 3100",
 courseDays: "MW",
 courseTime: "7:30 PM",
 }
 ],
 onlineCourses: [
 {
 courseTitle: "CS 3030"
 }
 ]
 },
 {
 name: "Hilton, Rob",
 timedCourses: [
 {
 courseTitle: "CS 2550",
 courseDays: "MW",
 courseTime: "9:30 AM",
 },
 {
 courseTitle: "CS 4790",
 courseDays: "MW",
 courseTime: "11:30 AM",
 }
 ],
 onlineCourses: [
 {
 courseTitle: "CS 2550"
 },
 {
 courseTitle: "CS 3270"
 }
 ]
 }
 ]
 return profSet;
 };

 */