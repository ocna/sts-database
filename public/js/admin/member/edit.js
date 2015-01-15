$(document).ready(function() {
    $('input.presentsFor').tagedit({
        autocompleteURL : "/search/area",
        checkNewEntriesCaseSensitive: true,
        allowEdit: false,
        allowAdd: false,
        addedPostfix: ''
    });

    $('input.facilitatesFor').tagedit({
        autocompleteURL : "/search/area",
        checkNewEntriesCaseSensitive: true,
        allowEdit: false,
        allowAdd: false,
        addedPostfix: ''
    });

    $('input.coordinatesFor').tagedit({
        autocompleteURL : "/search/region",
        checkNewEntriesCaseSensitive: true,
        allowEdit: false,
        allowAdd: false,
        addedPostfix: ''
    });

    registerTaggerHelpPopover()

    $( "#dateTrained" ).datepicker();
    $("#dateTrainedButton").click(function(){
        $( "#dateTrained" ).datepicker( "show" );
    })

    $( "#diagnosisDate" ).datepicker();
    $("#diagnosisDateButton").click(function(){
        $( "#diagnosisDate" ).datepicker( "show" );
    })

    showRightFormPartsForStatus($('#memberStatus').val())

    $('#memberStatus').change(function(e){
        showRightFormPartsForStatus(this.value);
    })

    showRightFormPartsForRole($('#role').val())

    $('#role').change(function(e){
        showRightFormPartsForRole(this.value)
    })

});

function showRightFormPartsForStatus(status){
    if(status == 'STATUS_ACTIVE'){
        systemUser('on');
    }else{
        systemUser('off');
    }
}

function showRightFormPartsForRole(role) {
    if(role == 'ROLE_ADMIN'){
        userDetails('on');
        presenters('off');
        facilitators('off');
        coordinators('off');
    }else if (role == 'ROLE_COORDINATOR'){
        userDetails('on')
        presenters('off')
        facilitators('off')
        coordinators('on')
    }else if (role == 'ROLE_FACILITATOR'){
        userDetails('on')
        presenters('off')
        facilitators('on')
        coordinators('off')
    }else{
        userDetails('off')
        presenters('on')
        facilitators('off')
        coordinators('off')
    }

}

function systemUser(flip){
    if(flip == 'on'){
        $('#fieldset-systemUser').show();
    }else if (flip == 'off'){
        $('#fieldset-systemUser').hide();
    }
}

function userDetails(flip){
    if(flip == 'on'){
        $('#systemUsername').attr('required', 'required').parent().parent().show();
        $('#tempPassword').attr('required', 'required').parent().parent().show();
        $('#tempPasswordConfirm').attr('required', 'required').parent().parent().show();
    }else if (flip == 'off'){
        $('#systemUsername').attr('required', false).parent().parent().hide();
        $('#tempPassword').attr('required', false).parent().parent().hide();
        $('#tempPasswordConfirm').attr('required', false).parent().parent().hide();
    }
}

function presenters(flip){
    if(flip == 'on'){
        $('[name="presentsFor[]"]').attr('required', 'required').parent().parent().parent().parent().show();
    }else if (flip == 'off'){
        $('[name="presentsFor[]"]').attr('required', false).parent().parent().parent().parent().hide();
    }
}
function facilitators(flip){
    if(flip == 'on'){
        $('[name="facilitatesFor[]"]').attr('required', 'required').parent().parent().parent().parent().show();
    }else if (flip == 'off'){
        $('[name="facilitatesFor[]"]').attr('required', false).parent().parent().parent().parent().hide();
    }
}
function coordinators(flip){
    if(flip == 'on'){
        $('[name="coordinatesFor[]"]').attr('required', 'required').parent().parent().parent().parent().show();
    }else if (flip == 'off'){
        $('[name="coordinatesFor[]"]').attr('required', false).parent().parent().parent().parent().hide();
    }
}