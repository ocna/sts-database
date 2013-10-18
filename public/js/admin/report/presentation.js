$(document).ready(function() {
    $("#startDate").datepicker();
    $("#startDateButton").click(function(){
        $("#startDate").datepicker("show");
    })

    $("#endDate" ).datepicker();
    $("#endDateButton").click(function(){
        $("#endDate").datepicker("show");
    });

    $('select.chosen').chosen();

    if (0 < $('#reportCSVForm').length) {
        $('#reportCSVForm').submit(function() {
            var $form = $(this);

            if (!$form.find('input[type="checkbox"]').is(':checked')){
                alert("Please check at least one variable to download.");
                return false;
            }

            return true;
        })
    }
})