jQuery(document).ready(function($){
    $('.btn-listing-expand').click(function(e){
        e.preventDefault();
        $(this).hide();
        $(this).parent().siblings('.listing-hide').find('.btn-listing-hide').show();
        $(this).parent().siblings('.listing-desc').removeAttr('style');
    });
    /*$('.btn-listing-hide').click(function(e){
        e.preventDefault();
        $(this).hide();
        $(this).parent().siblings('.listing-more').find('.btn-listing-expand').show();
        $(this).parent().siblings('.listing-desc').css('height','45px');
    });*/
    $('.listing-buttons').find('[type=image]').hide();
    $('.listing-buttons').find('form').append('<button type="submit" class="btn-block btn-business-claim greenyellow">Business Claim</button>');
    /*$('.btn-business-claim').on('click',function(){
        alert(0);
    });*/
    $('.radio-inline').find('label').append('<div class="check"></div>');

    
    $( "#form-dialog" ).dialog({
        autoOpen: false, 
        modal: true,
        buttons: {
        OK: function() {$(this).dialog("close");}
        },
    });
    /*$( ".btn-request" ).click(function(e) {
        e.preventDefault();
        let post_id = $(this).data('id');
        let title = $(this).data('title');  
        console.log(title);
        $(".ui-dialog-title").html('Get Quote from '+title);        
        $("#form-dialog").dialog( "open" );
    });*/
});


jQuery(document).ready(function($){
    let tstep = $('.step').length;
    let step = 1;
    $(".button-next,.button-submit").click(function(e) {
        e.preventDefault();
        let error = 0;  
        let next = $(this).data('next');
        $('.current-step').find('.required-checkbox').each(function() {
            if ($(this).find('input:checkbox:checked').length == 0) {
                $(this).closest('.form-group').addClass('has-error');
                $(this).find('.help-block').show();
                // alert('radio');
                error++;
                // return false;
            }
        });

        $('.current-step').find('.required-radio').each(function() {
            if ($(this).find('input:radio:checked').length == 0) { 
                $(this).closest('.form-group').addClass('has-error');                   
                $(this).find('.help-block').show();
                // alert('radio');
                error++;
                // return false;
            }
        });

        $('.current-step').find('.required-input').each(function() {
            if ($(this).find('input').val() == '') {  
                $(this).closest('.form-group').addClass('has-error');                  
                $(this).find('.help-block').show();
                // alert('radio');
                error++;
                // return false;
            }
        });

        if (!error){
            if (next) step = next;
            else step++;
            if (step <= tstep){
                $('.step-' + step).addClass('current-step'); 
                $('#step-' + step).parent().addClass('active');
                $('html,body').animate({scrollTop: $('#step-' + step).parent().offset().top},'slow');           
                //$('.step-nav-' + step).addClass('current-step-nav');  
            }
        }
        /*if (step>1){
            $('.button-back').show();                 
        }
        if (step == tstep){
            $('.button-next').hide();
            $('.button-submit').show();
        }*/
        if (step > tstep){
            $('#solar-form').submit();
        }
        console.log(step + ' ' + tstep)
    });
    $('.button-back').click(function(){
        let back = $(this).data('back');
        if (step>1){
            $('.step-' + step).removeClass('current-step');
            //$('.step-nav-' + step).removeClass('current-step-nav');
            if (back) step = back;
            else step--;
            $('.step-' + step).addClass('current-step');               
            $('html,body').animate({scrollTop: $('#step-' + step).parent().offset().top},'slow'); 
            //$('.step-nav-' + step).addClass('current-step-nav');
        }  
        /*if (step == 1){                
            $('.button-back').hide(); 
        }
        $('.button-next').show();
        $('.button-submit').hide();*/
    });
    $('.required-checkbox').find('input[type=checkbox]').click(function(){
        if ($(this).closest('.required-checkbox').find('input:checkbox:checked').length == 0) {                
            $(this).closest('.form-group').addClass('has-error');
            $(this).closest('.required-checkbox').find('.help-block').show();
        } else {            
            $(this).closest('.form-group').removeClass('has-error');              
            $(this).closest('.required-checkbox').find('.help-block').hide();
        }
    });
    $('.required-radio').find('input[type=radio]').click(function(){
        if ($(this).closest('.required-radio').find('input:radio:checked').length == 0) {          
            $(this).closest('.form-group').addClass('has-error');
            $(this).closest('.required-radio').find('.help-block').show();
        } else {     
            $(this).closest('.form-group').removeClass('has-error');            
            $(this).closest('.required-radio').find('.help-block').hide();
        }
    });
    $('input[type=radio][name=ownershipType]').change(function() {
        //alert($(this).val());
        let ownershipType = $(this).val();
        if (ownershipType == 'Homeowner' || ownershipType == 'Purchasing'){
            $('.owner-data').show();
        } else {
            $('.owner-data').hide();                
        }
        if (ownershipType == 'Renter'){
            $('.renter-data').show();
        } else {
            $('.renter-data').hide();                
        }
        if (ownershipType == 'Building'){
            $('.building-data').show();
        } else {
            $('.building-data').hide();                
        }
        if (ownershipType == 'Purchasing'){
            $('.purchasing-data').show();
        } else {
            $('.purchasing-data').hide();               
        }
    });
    $('#alt-phone-trigger').click(function(){
        if ($(this).is(':checked'))
            $('#alt-phone').show();
        else {
            $('#alt-phone').hide();
        }
    });

});
let autocomplete;
function initAutocomplete(){
    autocomplete = new google.maps.place.Autocomplete(
        document.getElementById('autocomplete'),
        {
            type: ['establishment'],
            componentRestrictions : {'country': ['AU']},
            fields: ['place_id','geometry','name']
        }
    );
}