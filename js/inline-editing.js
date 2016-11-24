var InlineEditing = function() {
    var toggleHideClass = function() {
        $('.action-edit, .action-save, .action-delete').click(function() {
            theRecordID = $(this).parent().parent().attr('id').split('_').pop();
            theID = $(this).attr('id').split('_').pop();
            theNum = theID.replace(/\D/g,'');

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