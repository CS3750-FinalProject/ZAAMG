var InlineEditing = function() {
    var toggleHideClass = function() {
        $('.action-edit, .action-save, .action-delete').click(function() {
            theRecordID = $(this).parent().parent().attr('id').split('_').pop();
            theID = $(this).attr('id').split('_').pop();
            theNum = theID.replace(/\D/g,'');  //strip out just the id number

            theRecordIDwChar = theRecordID.substr(theRecordID.indexOf(theNum)-1);

            /*console.log("parentID: " + theRecordID);
            console.log("theID: " + theID);
            console.log("theRecordIDwChar: " + theRecordIDwChar);
            console.log("theNum: " + theNum);*/

            $('[id$=' + theID + ']').toggleClass('hide');
            $('[id^=' + 'record_' +'][id$=' + theRecordIDwChar + ']').toggleClass('inline_editing_record');
            $('[id^=' + 'edit_' +'][id$=' + theID + ']').toggleClass('inline_editing_edit_box');
        });
    }

    toggleHideClass();

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

        console.log(
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
                "online: " + online);

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
                    "&sectionSemester="     + semester,
            success: function(msg) {
                console.log("message from updateSection: " + msg);
                loadPhpPage("section_page.php");
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