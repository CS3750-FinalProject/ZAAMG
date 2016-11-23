var InlineEditing = function() {
    var toggleHideClass = function() {
        $('.action-edit, .action-save').click(function() {
            theID = $(this).attr('id').split('_').pop();
            console.log("theID: " + theID);
            $('[id$=' + theID + ']').toggleClass('hide');
        });

    }


    toggleHideClass();
}

