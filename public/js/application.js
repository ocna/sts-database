$(document).ready(function() {

  $('.controls > p.help-block').hide();

  $('.controls:has(p.help-block) > :input').popover({
    trigger : 'focus',
    placement : 'right',
    title : function() {
      return '<div style="font-size: 16px;"><i class="icon-info-sign"></i> More Information</div>'
    },

    // <div style="font-size: 24px;" />

    // '<i class="icon-info-sign"></i>
    // Help!',
    content : function() {
      console.log(this)
      return '<small>' + $(this).siblings('p.help-block').html() + '</small>'
    }
  });
  
  //($'.ui-autocomplete-input').cl
})