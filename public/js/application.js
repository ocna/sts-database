$(document).ready(function() {

  $('.controls > p.help-block').hide();

  $('.controls:has(p.help-block) > :input').popover({
    trigger : 'focus',
    placement : 'right',
    title : function() {
      return '<div style="font-size: 16px;"><i class="icon-info-sign"></i> More Information</div>';
    },
    content : function() {
      return '<small>' + $(this).siblings('p.help-block').html() + '</small>';
    }
  });

  $('#delete-button').tooltip({
    placement: 'right',
  })
});


function registerTaggerHelpPopover(){
    $('.ui-autocomplete-input').focus(function(e){
        box = $(this).parent().parent();
        box.popover({
          trigger : 'manual',
          placement : 'right',
          title : function() {
            return '<div style="font-size: 16px;"><i class="icon-info-sign"></i> More Information</div>';
          },
          content : function() {
            return '<small>' + $(this).siblings('p.help-block').html() + '</small>';
          }
        });
      box.popover('show');
    }).blur(function(e){
        box = $(this).parent().parent();
        box.popover('destroy');
    });
}
