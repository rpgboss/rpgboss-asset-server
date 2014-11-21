$(function() {
	$('#displayNameInput').on('keyup paste focus blur',function() {
		var val = $(this).val();


		if(val=='') {
				$('#name_success').hide();
				$('#name_error').hide();
		} else {

  		$.get('/check/displayname/'+val, function(result) {
  			if(result=='false') {
  				$('#name_success').show();
  				$('#name_error').hide();
  			} else {
  				$('#name_success').hide();
  				$('#name_error').show();
  			}
  		});

		}
	});

	function validateEmail(email) { 
	    var re = new RegExp("[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*\@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?")
	    return re.test(email);
	} 

	$('#emailInput').on('keyup paste focus blur',function() {
		var val = $(this).val();


		if(val=='') {
				$('#email_success').hide();
				$('#email_error').hide();
		} else {

			if(validateEmail(val)) {

    		$.get('/check/email/'+val, function(result) {
    			if(result=='false') {
    				$('#email_success').show();
    				$('#email_error').hide();
    			} else {
    				$('#email_success').hide();
    				$('#email_error').show();
    			}
    		});

  		}

		}
	});
});