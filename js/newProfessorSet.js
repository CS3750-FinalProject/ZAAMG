
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
        theMoment = theMoment.clone().add(10, 'm');
    return theMoment;
};
