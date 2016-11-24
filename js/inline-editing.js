var InlineEditing = function() {
    var toggleHideClass = function() {
        $('.action-edit, .action-save, .action-delete').click(function() {
            theID = $(this).attr('id').split('_').pop();
            theNum = theID.replace(/\D/g,'');
            console.log("theID: " + theID);
            $('[id$=' + theID + ']').toggleClass('hide');
            $('[id^=' + 'record_' +'][id$=' + theNum + ']').toggleClass('inline_editing_record');
            $('[id^=' + 'edit_' +'][id$=' + theNum + ']').toggleClass('inline_editing_edit_box');

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