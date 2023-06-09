"use strict";

// Class definition
var KTPasswordResetGeneral = function() {
    // Elements
    var form;
    var submitButton;
    var validator;

    // specify form action URL
    var formActionURL = 'scripts/forgotPass.php';

    // Handle form
    var handleForm = function(e) {
        // Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
        validator = FormValidation.formValidation(
			form,
			{
				fields: {					
					'email': {
                        validators: {
							notEmpty: {
								message: 'Email address is required'
							},
                            emailAddress: {
								message: 'The value is not a valid email address'
							}
						}
					}
				},
				plugins: {
					trigger: new FormValidation.plugins.Trigger(),
					bootstrap: new FormValidation.plugins.Bootstrap5({
                        rowSelector: '.fv-row'
                    })
				}
			}
		);

        // Identify form fields
        var emailField = form.querySelector('[name="email"]');

        // Handle form submit
        submitButton.addEventListener('click', function (e) {
            e.preventDefault();

            // Validate form
            validator.validate().then(function (status) {
                if (status == 'Valid') {
                    submitButton.setAttribute('data-kt-indicator', 'on');
                    submitButton.disabled = true;
                    
                    // Ajax Request
                    FormValidation.utils.fetch(formActionURL, {
                        method: 'POST',
                        params: {
                            email: emailField.value
                        }
                    }).then(function(response) {
                        submitButton.removeAttribute('data-kt-indicator');
                        submitButton.disabled = false;

                        // handle response
                        if(response !== "success"){
                            var responseMessage = "ERROR: "+JSON.stringify(response);

                            Swal.fire({
                                text: responseMessage,
                                icon: "error",
                                buttonsStyling: false,
                                confirmButtonText: "Try Again",
                                customClass: {
                                    confirmButton: "btn btn-danger"
                                }
                            });
                        } else {
                            Swal.fire({
                                html: "We have sent a mail to <b>"+ emailField.value +"</b>. <br/>Kindly click the link in the email to reset your password. Remeber to check your spam box if you cannot find the mail",
                                icon: "success",
                                buttonsStyling: false,
                                confirmButtonText: "Proceed to login",
                                customClass: {
                                    confirmButton: "btn btn-primary"
                                }
                            }).then(function (result) {
                                if (result.isConfirmed) {  //if the confirm button is clicked
                                    form.reset(); 
                                    var redirectUrl = form.getAttribute('data-kt-redirect-url');
                                    if (redirectUrl) {
                                        location.href = redirectUrl;
                                    }
                                }
                            });
                        }
                        
                    });
					
                } else {
                    Swal.fire({
                        text: "Oops! You have some error in the form, please review and try again.",
                        icon: "error",
                        buttonsStyling: false,
                        confirmButtonText: "Ok, got it!",
                        customClass: {
                            confirmButton: "btn btn-primary"
                        }
                    });
                }
            });
		});
    }

    // Public functions
    return {
        // Initialization
        init: function() {
            form = document.querySelector('#kt_password_reset_form');
            submitButton = document.querySelector('#kt_password_reset_submit');
            
            handleForm();
        }
    };

}();

// On document ready
KTUtil.onDOMContentLoaded(function() {
    KTPasswordResetGeneral.init();
});
