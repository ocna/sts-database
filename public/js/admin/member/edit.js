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
        userDetails('on')
        presenters('off')
        facilitators('off')
        coordinators('off')
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
        $('#systemUsernameControlGroup').show();
        $('#tempPasswordControlGroup').show();
        $('#tempPasswordConfirmControlGroup').show();
    }else if (flip == 'off'){
        $('#systemUsernameControlGroup').hide();
        $('#tempPasswordControlGroup').hide();
        $('#tempPasswordConfirmControlGroup').hide();
    }
}

function presenters(flip){
    if(flip == 'on'){
        $('#presentsForControlGroup').show();
    }else if (flip == 'off'){
        $('#presentsForControlGroup').hide();
    }
}
function facilitators(flip){
    if(flip == 'on'){
        $('#facilitatesForControlGroup').show();
    }else if (flip == 'off'){
        $('#facilitatesForControlGroup').hide();
    }
}
function coordinators(flip){
    if(flip == 'on'){
        $('#coordinatesForControlGroup').show();
    }else if (flip == 'off'){
        $('#coordinatesForControlGroup').hide();
    }
}