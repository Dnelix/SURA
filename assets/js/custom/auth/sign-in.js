"use strict";

// Class definition
var KTSigninGeneral = function() {
    // Elements
    var form;
    var submitButton;
    var validator;
    var showHide;
    // specify form action URL
    var formActionURL = 'controllers/sessions.php';

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
					},
                    'password': {
                        validators: {
                            notEmpty: {
                                message: 'The password is required'
                            },
                            callback: {
                                message: 'Please enter valid password',
                                callback: function(input) {
                                    if (input.value.length > 0) {
                                        return validatePassword();
                                    }
                                }
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
        var keepSignedIn = form.querySelector('[name="keep"]');
        var passwordField = form.querySelector('[name="password"]');
        var passwordIconOpen = form.querySelector('.bi-eye');
        var passwordIconClose = form.querySelector('.bi-eye-slash');
        

        // Handle form submit
        submitButton.addEventListener('click', function (e) {
            // Prevent button default action
            e.preventDefault();

            // Validate form
            validator.validate().then(function (status) {
                if (status == 'Valid') {
                    
                    submitButton.setAttribute('data-kt-indicator', 'on'); // Show loading indication
                    submitButton.disabled = true;
                    
                    var params = {
                        username: emailField.value,
                        password: passwordField.value,
                        keep: keepSignedIn.checked
                    };

                    // Ajax Request
                    $.ajax({
                        url: formActionURL,
                        type: 'POST',
                        dataType: 'JSON',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        data: JSON.stringify(params),
                        success: function(response){
                            submitButton.removeAttribute('data-kt-indicator'); // Hide loading indication
                            submitButton.disabled = false; // Enable button

                            // handle response
                            if(response['statusCode'] !== 201 && response['success'] !== true){
                                var responseMessage = "ERROR: "+response.messages[0];
                                console.log ("ERROR: "+JSON.stringify(response)); // remove for prod

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
                                    text: "You have successfully logged in. Click to proceed",
                                    icon: "success",
                                    buttonsStyling: false,
                                    confirmButtonText: "Proceed to dashboard",
                                    customClass: {
                                        confirmButton: "btn btn-primary"
                                    },
                                    timer: "1000"
                                }).then(function () {
                                        form.reset();  // reset form  
                                                                    
                                        // form.submit(); // submit form
                                        var redirectUrl = form.getAttribute('data-kt-redirect-url');
                                        if (redirectUrl) {
                                            location.href = redirectUrl;
                                        }
                                });
                            }
                        }
                    });

                    /* Ajax Request
                    FormValidation.utils.fetch(formActionURL, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        params: JSON.stringify(params)
                        
                    }).then(function(response) {
                        
                    });*/
					
                } else {
                    // Show error popup. For more info check the plugin's official documentation: https://sweetalert2.github.io/
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

        // Show/Hide password function
        showHide.addEventListener('click', function (e) {
            if(passwordField.type === "password"){
                passwordField.type = "text";
                passwordIconClose.classList.add("d-none");
                passwordIconOpen.classList.remove("d-none");
            } else {
                passwordField.type = "password";
                passwordIconOpen.classList.add("d-none");
                passwordIconClose.classList.remove("d-none");
            }
        });
    }

    // Public functions
    return {
        // Initialization
        init: function() {
            form = document.querySelector('#kt_sign_in_form');
            submitButton = document.querySelector('#kt_sign_in_submit');
            showHide = document.querySelector('#showHide');
            
            handleForm();
        }
    };

}();

// On document ready
KTUtil.onDOMContentLoaded(function() {
    KTSigninGeneral.init();
});

