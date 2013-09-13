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
})