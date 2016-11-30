var InlineEditing = function() {
    var toggleHideClass = function() {
        $('.action-edit, .action-save, .action-delete').click(function() {

            if ($(this).attr('id').indexOf('_delete') == -1){
                theID = $(this).attr('id').split('_').pop();
                theRecordID = $(this).parent().parent().attr('id').split('_').pop();
                theNum = theID.replace(/\D/g,'');  //strip out just the id number
            }

            theRecordIDwChar = theRecordID.substr(theRecordID.indexOf(theNum)-1);

            console.log("parentID: " + theRecordID);
            console.log("theID: " + theID);
            console.log("theRecordIDwChar: " + theRecordIDwChar);
            console.log("theNum: " + theNum);

            $('[id$=' + theID + ']').toggleClass('hide');
            $('[id^=' + 'record_' +'][id$=' + theRecordIDwChar + ']').toggleClass('inline_editing_record');
            $('[id^=' + 'edit_' +'][id$=' + theID + ']').toggleClass('inline_editing_edit_box');
        });
    }

    toggleHideClass();
    fill_editFields_prof();
    fill_editFields_room();
    fill_editFields_section();


    function fill_editFields_section(){
        $('[id^=' + 'pencil_sect' + ']').click(function(){
            var secId = $(this).attr('id').split('pencil_sect').pop();
            var profId;
            var courseId;
            var roomId;
            var semId;

            $.ajax({
                type: "POST",
                url: "action/action_updateSection.php",
                data: "secId=" + secId,
                dataType:  'json',
                success: function(sections) {
                    var section = {};
                    sections.forEach(function(obj){
                        if (obj.id == secId)
                            section = obj;
                    });
                    profId = section.prof;
                    courseId = section.course;
                    roomId = section.room;
                    semId = section.sem;

                    $('#' + 'inlineEdit_sectCap' + secId).val(section.cap);
                    $('#' + 'inlineEdit_sectStartTime' + secId).val();
                    $('#' + 'inlineEdit_sectEndTime' + secId).val();

                    if(section.online == 1){
                        $('#' + 'inlineEdit_sectOnline' + secId).attr('checked', 'checked');
                    }

                    $('#' + 'inlineEdit_sectDays' + secId).empty();
                    $('#' + 'inlineEdit_sectDays' + secId).append($('<option />').val("Online").text("Online"));
                    $('#' + 'inlineEdit_sectDays' + secId).append($('<option />').val("Monday").text("Monday"));
                    $('#' + 'inlineEdit_sectDays' + secId).append($('<option />').val("Tuesday").text("Tuesday"));
                    $('#' + 'inlineEdit_sectDays' + secId).append($('<option />').val("Wednesday").text("Wednesday"));
                    $('#' + 'inlineEdit_sectDays' + secId).append($('<option />').val("Thursday").text("Thursday"));
                    $('#' + 'inlineEdit_sectDays' + secId).append($('<option />').val("Friday").text("Friday"));
                    $('#' + 'inlineEdit_sectDays' + secId).append($('<option />').val("Saturday").text("Saturday"));


                    var days = section.days == "online" ? ["Online"]
                        : section.days.match(/[A-Z][a-z]+/g); //split the day string by Capitalized Day Names
                    $('#' + 'inlineEdit_sectDays' + secId +'> option').each(function() {
                        if (  jQuery.inArray(this.innerHTML, days) > -1){
                            //console.log("inArray: " + jQuery.inArray(this.innerHTML, days));
                            $('#' + 'inlineEdit_sectDays' + secId +' option[value=' + this.innerHTML + ']').attr('selected', 'true');
                        }
                    });

                    $('#' + 'inlineEdit_sectBlock' + secId).empty();
                    $('#' + 'inlineEdit_sectBlock' + secId).append($('<option />').val(0).text("Full"));
                    $('#' + 'inlineEdit_sectBlock' + secId).append($('<option />').val(1).text("First"));
                    $('#' + 'inlineEdit_sectBlock' + secId).append($('<option />').val(2).text("Second"));

                    $('#' + 'inlineEdit_sectBlock' + secId +'> option').each(function() {
                        if ( this.value == section.block){
                            $('#' + 'inlineEdit_sectDays' + secId +' option[value=' + this.value + ']').attr('selected', 'true');
                        }
                    });
                },
                error: function(msg) {
                    console.log("error: " + JSON.stringify(msg));
                }
            });
            $.ajax({
                type: "POST",
                url: "action/action_getResources.php",
                data: "resource=courses",
                dataType:  'json',
                success: function(courses) {
                    $('#' + 'inlineEdit_sectCourse' + secId).empty();
                    courses.forEach(function(obj){
                        $('#' + 'inlineEdit_sectCourse' + secId).append($('<option />').val(obj.id).text(obj.pref + " " + obj.num + " " + obj.title));
                        if (obj.id == courseId)
                            $('#' + 'inlineEdit_sectCourse' + secId +' option[value=' + courseId + ']').attr('selected','selected');
                    });
                },
                error: function(msg) {
                    console.log("error: " + JSON.stringify(msg));
                }
            });
            $.ajax({
                type: "POST",
                url: "action/action_getResources.php",
                data: "resource=professors",
                dataType:  'json',
                success: function(profs) {
                    $('#' + 'inlineEdit_sectProf' + secId).empty();
                    profs.forEach(function(obj){
                        $('#' + 'inlineEdit_sectProf' + secId).append($('<option />').val(obj.id).text(obj.last + ", " + obj.first));
                        if (obj.id == profId)
                            $('#' + 'inlineEdit_sectProf' + secId +' option[value=' + profId + ']').attr('selected','selected');
                    });
                },
                error: function(msg) {
                    console.log("error: " + JSON.stringify(msg));
                }
            });
            $.ajax({
                type: "POST",
                url: "action/action_getResources.php",
                data: "resource=rooms",
                dataType:  'json',
                success: function(rooms) {
                    $('#' + 'inlineEdit_sectRoom' + secId).empty();
                    rooms.forEach(function(obj){
                        $('#' + 'inlineEdit_sectRoom' + secId).append($('<option />').val(obj.id).text(obj.campus + ", " + obj.building + ": " + obj.number));
                        if (obj.id == roomId)
                            $('#' + 'inlineEdit_sectRoom' + secId +' option[value=' + roomId + ']').attr('selected','selected');
                    });
                },
                error: function(msg) {
                    console.log("error: " + JSON.stringify(msg));
                }
            });
            $.ajax({
                type: "POST",
                url: "action/action_getResources.php",
                data: "resource=semesters",
                dataType:  'json',
                success: function(sems) {
                    $('#' + 'inlineEdit_sectSem' + secId).empty();
                    sems.forEach(function(obj){
                        $('#' + 'inlineEdit_sectSem' + secId).append($('<option />').val(obj.id).text(obj.year + " " + obj.season));
                        if (obj.id == semId)
                            $('#' + 'inlineEdit_sectSem' + secId +' option[value=' + semId + ']').attr('selected','selected');
                    });
                },
                error: function(msg) {
                    console.log("error: " + JSON.stringify(msg));
                }
            });
        });
    }




    function fill_editFields_room(){
        $('[id^=' + 'pencil_room' + ']').click(function(){
            var roomId = $(this).attr('id').split('pencil_room').pop();
            var buildId;
            $.ajax({
                type: "POST",
                url: "action/action_updateClassroom.php",
                data: "roomId=" + roomId,
                dataType:  'json',
                success: function(rooms) {
                    var room = {};
                    rooms.forEach(function(obj){
                       if (obj.id == roomId)
                           room = obj;
                    });
                    $('#' + 'inlineEdit_roomNumber' + roomId).val(room.number);
                    $('#' + 'inlineEdit_roomCap' + roomId).val(room.cap);
                    $('#' + 'inlineEdit_roomComputers' + roomId).val(room.computers);
                    buildId = room.building;
                },
                error: function(msg) {
                    console.log("error: " + JSON.stringify(msg));
                }
            });
            $.ajax({
                type: "POST",
                url: "action/action_getResources.php",
                data: "resource=campus_buildings",
                dataType:  'json',
                success: function(buildings) {
                    $('#' + 'inlineEdit_roomBuilding' + roomId).empty();
                    buildings.forEach(function(obj){
                        $('#' + 'inlineEdit_roomBuilding' + roomId).append($('<option />').val(buildId).text(obj.campus + ": " + obj.building_name));
                        if (obj.building_id == buildId)
                            $('#' + 'inlineEdit_roomBuilding' + roomId +' option[value=' + buildId + ']').attr('selected','selected');
                    });
                },
                error: function(msg) {
                    console.log("error: " + JSON.stringify(msg));
                }
            });
        });
    }


    function fill_editFields_prof(){
        $('[id^=' + 'pencil_prof' + ']').click(function(){
            var profId = $(this).attr('id').split('pencil_prof').pop();
            var deptId;
            $.ajax({
                type: "POST",
                url: "action/action_updateProfessor.php",
                data: "profId="       + profId,
                dataType:  'json',
                success: function(profs) {
                    var prof = {};
                    profs.forEach(function(obj){
                        if (obj.id == profId)
                            prof = obj;
                    });
                    $('#' + 'inlineEdit_profFirst' + profId).val(prof.first);
                    $('#' + 'inlineEdit_profLast' + profId).val(prof.last);
                    $('#' + 'inlineEdit_profEmail' + profId).val(prof.email);
                    $('#' + 'inlineEdit_profReqHours' + profId).val(prof.req);
                    $('#' + 'inlineEdit_profRelHours' + profId).val(prof.rel);
                    deptId = prof.dept;
                },
                error: function(msg) {
                    console.log("error: " + JSON.stringify(msg));
                }
            });
            $.ajax({
                type: "POST",
                url: "action/action_getResources.php",
                data: "resource=departments",
                dataType:  'json',
                success: function(depts) {
                    $('#' + 'inlineEdit_profDept' + profId).empty();
                    depts.forEach(function(obj){
                        $('#' + 'inlineEdit_profDept' + profId).append($('<option />').val(deptId).text(obj.code + " " + obj.name));
                        if (obj.id == deptId)
                            $('#' + 'inlineEdit_profDept' + profId +' option[value=' + deptId + ']').attr('selected','selected');
                    });
                },
                error: function(msg) {
                    console.log("error: " + JSON.stringify(msg));
                }
            });
        });
    }


    $('[id^=' + 'save_sect' +']').click(function(){
        secId       = $(this).attr('id').split('save_sect').pop();
        courseId    = $('#' + 'inlineEdit_sectCourse' + secId + ' :selected').val();
        profId      = $('#' + 'inlineEdit_sectProf' + secId + ' :selected').val();
        roomId      = $('#' + 'inlineEdit_sectRoom' + secId + ' :selected').val();
        days        = $('#' + 'inlineEdit_sectDays' + secId).val().join("");
        start       = formatTime($('#' + 'inlineEdit_sectStartTime' + secId).val());
        end         = formatTime($('#' + 'inlineEdit_sectEndTime' + secId).val());
        semester    = $('#' + 'inlineEdit_sectSem' + secId + ' :selected').val();
        block       = $('#' + 'inlineEdit_sectBlock' + secId + ' :selected').val();
        cap         = $('#' + 'inlineEdit_sectCap' + secId).val();
        online      = $('#' + 'inlineEdit_sectOnline' + secId).is(':checked') ? 1 : 0;

        /*console.log(
            "secId: " + secId + "\n" +
                "courseId: " + courseId +  "\n" +
                "profId: " + profId + "\n" +
                "roomId: " + roomId +  "\n" +
                "days: " + days + "\n" +
                "start: " + start + "\n" +
                "end: " + end + "\n" +
                "semester: " + semester + "\n" +
                "block: " + block + "\n" +
                "cap: " + cap + "\n" +
                "online: " + online);*/

        $.ajax({
            type: "POST",
            url: "action/action_updateSection.php",
            data:   "sectionId="            + secId +
                    "&sectionCourse="       + courseId +
                    "&sectionProfessor="    + profId +
                    "&sectionClassroom="    + roomId +
                    "&sectionDays="         + days +
                    "&sectionStartTime="    + start +
                    "&sectionEndTime="      + end +
                    "&sectionIsOnline="     + online +
                    "&sectionBlock="        + block +
                    "&sectionCapacity="     + cap +
                    "&sectionSemester="     + semester +
                    "&action=update",
            dataType: "json",
            success: function(msg) {
                loadPhpPage("section_page.php");
            },
            error: function(msg) {
                console.log("error: " + JSON.stringify(msg));
            }
        });

    });




    $('[id^=' + 'save_prof' +']').click(function(){
        profId      = $(this).attr('id').split('save_prof').pop();
        first       = $('#' + 'inlineEdit_profFirst' + profId).val();
        last        = $('#' + 'inlineEdit_profLast' + profId).val();
        email       = $('#' + 'inlineEdit_profEmail' + profId).val();
        dept        = $('#' + 'inlineEdit_profDept' + profId + ' :selected').val();
        reqHrs      = $('#' + 'inlineEdit_profReqHours' + profId).val();
        relHrs      = $('#' + 'inlineEdit_profRelHours' + profId).val();

        /*console.log(
            "profId: " + profId + "\n" +
            "first: " + first +  "\n" +
            "last: " + last + "\n" +
            "email: " + email +  "\n" +
            "dept: " + dept + "\n" +
            "reqHrs: " + reqHrs + "\n" +
            "relHrs: " + relHrs + "\n");*/

        $.ajax({
            type: "POST",
            url: "action/action_updateProfessor.php",
            data:
            "profId="       + profId +
            "&first="       + first +
            "&last="        + last +
            "&email="       + email +
            "&dept="        + dept +
            "&reqHrs="      + reqHrs +
            "&relHrs="      + relHrs +
            "&action="      + "update",
            dataType:  'json',
            success: function(profObjects) {
                //console.log("message from updateProfessor: " + profObjects);
                loadPhpPage("prof_page.php");
            },
            error: function(msg) {
                console.log("error: " + JSON.stringify(msg));
            }
        });

    });



    $('[id^=' + 'save_room' +']').click(function(){
        roomId      = $(this).attr('id').split('save_room').pop();
        building       = $('#' + 'inlineEdit_roomBuilding' + roomId + ' :selected').val();
        number        = $('#' + 'inlineEdit_roomNumber' + roomId).val();
        cap       = $('#' + 'inlineEdit_roomCap' + roomId).val();
        computers        = $('#' + 'inlineEdit_roomComputers' + roomId).val();

        console.log(
            "roomId: "      + roomId + "\n" +
            "building: "    + building +  "\n" +
            "number: "      + number + "\n" +
            "cap: "         + cap +  "\n" +
            "computers: "   + computers);

        $.ajax({
            type: "POST",
            url: "action/action_updateClassroom.php",
            data:
            "roomId="           + roomId +
            "&building="        + building +
            "&number="          + number +
            "&cap="             + cap +
            "&computers="       + computers +
            "&action="          + 'update',
            success: function(msg) {
                console.log("message from updateClassroom: " + msg);
                loadPhpPage("classroom_page.php");
            },
            error: function(msg) {
                console.log("error: " + JSON.stringify(msg));
            }
        });

    });


    $('[id^=' + 'sect_delete' +']').click(function(){
        var secId  = $(this).attr('id').split('sect_delete').pop();
        $.ajax({
            type: "POST",
            url: "action/action_deleteSection.php",
            data:   "sectionId=" + secId +
                    "&action=delete",
            success: function(msg) {
               // console.log("message from deleteSection: " + msg);
                loadPhpPage("section_page.php");
            },
            error: function(msg) {
                console.log("error: " + JSON.stringify(msg));
            }
        });
    });


    $('[id^=' + 'prof_delete' +']').click(function(){
        var profId = $(this).attr('id').split('prof_delete').pop();
        $.ajax({
            type: "POST",
            url: "action/action_deleteProfessor.php",
            data:   "profId=" + profId +
                    "&action=" + "delete",
            dataType: 'json',
            success: function(professorObjects) {
                //console.log("message from deleteProfessor: " + professorObjects);
                //refreshModals_prof(professorObjects);
                loadPhpPage("prof_page.php");
            },
            error: function(msg) {
                console.log("error: " + JSON.stringify(msg));
            }
        });
    });



    $('[id^=' + 'room_delete' +']').click(function(){
        var roomId = $(this).attr('id').split('room_delete').pop();
        $.ajax({
            type: "POST",
            url: "action/action_deleteClassroom.php",
            data:   "roomId="    + roomId +
            "&action=" + "delete",
            success: function(msg) {
               // console.log("message from deleteClassroom: " + msg);
                loadPhpPage("classroom_page.php");
            },
            error: function(msg) {
                console.log("error: " + JSON.stringify(msg));
            }
        });
    });


    function formatTime(time){
        if (time.indexOf('AM') > -1){
            time = time.slice(0,5) + ":00";
        }
        else if (time.indexOf('PM') > -1){
            var hour = parseInt(time.slice(0,2));
            if (hour < 12){
                hour += 12;
            }
            time = hour + time.slice(2,5) + ":00";
        }
        else{
            time = time + ":00";
        }
        return time;
    };


}



















/*$('[id^=' + 'record_' +'][id$=' + theNum + ']').css('background-color', '#d0e5bd');
 $('[id^=' + 'record_' +'][id$=' + theNum + '] td').css('border-right-color', '#d0e5bd');
 $('[id^=' + 'record_' +'][id$=' + theNum + '] td:first-child').css('border-left-color','#d0e5bd');

 $('[id^=' + 'edit_' +'][id$=' + theNum + ']').css('background-color', '#edfce0');
 $('[id^=' + 'edit' +'][id$=' + theNum + '] td').css('border-right-color', '#edfce0');
 $('[id^=' + 'edit' +'][id$=' + theNum + '] td:first-child').css('border-left-color','#edfce0');

 $('[id^=' + 'record_' +'][id$=' + theNum + '] td').css('border-top', '2px solid black');
 $('[id^=' + 'record_' +'][id$=' + theNum + '] td:first-child').css('border-left', '2px solid black');
 $('[id^=' + 'record_' +'][id$=' + theNum + '] td:last-child').css('border-right', '2px solid black');
 $('[id^=' + 'edit_' +'][id$=' + theNum + '] td').css('border-bottom', '2px solid black');
 $('[id^=' + 'edit_' +'][id$=' + theNum + '] td:first-child').css('border-left', '2px solid black');
 $('[id^=' + 'edit_' +'][id$=' + theNum + '] td:last-child').css('border-right', '2px solid black');*/