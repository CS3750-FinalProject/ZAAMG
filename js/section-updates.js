var SectionUpdates = function() {
  var toggleHideClass = function() {
    $('.action-edit, .action-save').click(function() {
      sectionID = $(this).attr('id').split('_').pop();
      $('[id$=' + sectionID + ']').toggleClass('hide');
      console.log(sectionID);
    });
  }

  toggleHideClass();
}

$(document).ready( function() {
  SectionUpdates();
});
