$(function() {
	$('#usernameInput').on('keyup paste focus blur',function() {
		var val = $(this).val();


		if(val=='') {
			$('#username_success').hide();
			$('#username_error').hide();
		} else {

			$.get('/check/username/'+btoa(val), function(result) {
				if(result=='') {
					$('#username_success').show();
					$('#username_error').hide();
				} else {
					$('#username_success').hide();
					$('#username_error').show();
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

    		$.get('/check/email/'+ btoa(val), function(result) {
    			if(result=='') {
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