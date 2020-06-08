  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' /* optional */
    });
    //Timepicker
    $('.timepicker').timepicker({
      // showInputs: false,
      showMeridian: false,
      format: 'H:i'     

    });
    //Date picker
    $('.datepicker').datepicker({
      autoclose: true,
      format: 'dd-mm-yyyy',
      startDate: "+0d"
    });
    // $('.datepicker').datepicker('setDate', 'now');
  });


// NOTIFICATIONS START //
function ShowWarning(Messages) {
	if($.isPlainObject(Messages))
	{
		var MsgList = '<ui>';
		$.each(Messages, function( index, value ) {
			MsgList += '<li>'+value[0]+'</li>';
		});
		MsgList += '</ui>';
		toastr.warning(MsgList);
	}else{toastr.warning(Messages);}
}
function ShowSuccess(Messages) {
	if($.isPlainObject(Messages))
	{
		var MsgList = '<ui>';
		$.each(Messages, function( index, value ) {
			MsgList += '<li>'+value[0]+'</li>';
		});
		MsgList += '</ui>';
		toastr.success(MsgList);
	}else{toastr.success(Messages);}
}
function ShowInfo(Messages) {
	if($.isPlainObject(Messages))
	{
		var MsgList = '<ui>';
		$.each(Messages, function( index, value ) {
			MsgList += '<li>'+value[0]+'</li>';
		});
		MsgList += '</ui>';
		toastr.info(MsgList);
	}else{toastr.info(Messages);}
}
function ShowError(Messages) {
	if($.isPlainObject(Messages))
	{
		var MsgList = '<ui>';
		$.each(Messages, function( index, value ) {
			MsgList += '<li>'+value[0]+'</li>';
		});
		MsgList += '</ui>';
		toastr.error(MsgList);
	}else{toastr.error(Messages);}
	
}

//REDIRECT PAGE
function RedirectUrl(pageName,TimeToRedirect = 100) {
	setTimeout(function(){ 	
		window.location.href = BASE_URL+'/'+pageName; 
	}, TimeToRedirect);

}
//REDIRECT PAGE END

// FORM VALIDATION
function resetValidation(formElement){
    $(formElement).validate().resetForm();
    $(formElement).find('div').removeClass("has-error");
}

$(".FormValidate").validate({
	highlight: function( label ) {
		$(label).closest('.form-group').removeClass('has-success').addClass('has-error');
	},
	success: function( label ) {
		$(label).closest('.form-group').removeClass('has-error');
		label.remove();
	},
	errorPlacement: function( error, element ) {
		var placement = element.closest('.input-group');
		if (!placement.get(0)) {
			placement = element;
		}
		if (error.text() !== '') {
			placement.after(error);
		}
	}
});
// FORM VALIDATION END


// ONLY NUMBER INPUT
// $('.OnlyDecimal').keypress(function(event) {
	$(document).on('keypress', '.OnlyDecimal', function(event) {
	if (((event.which != 46 || (event.which == 46 && $(this).val() == '')) ||
		$(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
		event.preventDefault();
}

});

// $(".OnlyNumber").keydown(function (event) {
$(document).on('keypress', '.OnlyNumber', function(event) {
     //if the letter is not digit then display error and don't type anything
     if (event.which != 8 && event.which != 0 && (event.which < 48 || event.which > 57)) {
     	
        return false;
    }
});
// ONLY NUMBER INPUT END
