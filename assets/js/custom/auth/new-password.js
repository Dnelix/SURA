"use strict";

// Class Definition
var KTPasswordResetNewPassword = function() {
    // Elements
    var form;
    var submitButton;
    var validator;
    var passwordMeter;

    // specify form action URL
    var formActionURL = 'scripts/forgotPass.php';

    var handleForm = function(e) {

        // Identify form fields
        var passwordField = form.querySelector('[name="password"]');
        var passwordConfirmField = form.querySelector('[name="confirm-password"]');

    
        validator = FormValidation.formValidation(
			form,
			{
				fields: {					 
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
                    },
                    'confirm-password': {
                        validators: {
                            notEmpty: {
                                message: 'The password confirmation is required'
                            },
                            identical: {
                                compare: function() {
                                    return passwordField.value;
                                },
                                message: 'The password and its confirm are not the same'
                            }
                        }
                    },
                    'toc': {
                        validators: {
                            notEmpty: {
                                message: 'You must accept the terms and conditions'
                            }
                        }
                    }
				},
				plugins: {
					trigger: new FormValidation.plugins.Trigger({
                        event: {
                            password: false
                        }  
                    }),
					bootstrap: new FormValidation.plugins.Bootstrap5({
                        rowSelector: '.fv-row',
                        eleInvalidClass: '',
                        eleValidClass: ''
                    })
				}
			}
		);


        submitButton.addEventListener('click', function (e) {
            e.preventDefault();

            validator.revalidateField('password');

            validator.validate().then(function(status) {
		        if (status == 'Valid') {
                    submitButton.setAttribute('data-kt-indicator', 'on');
                    submitButton.disabled = true;

                    // Ajax Request
                    FormValidation.utils.fetch(formActionURL, {
                        method: 'POST',
                        params: {
                            password: passwordField.value,
                            passwordConfirm: passwordConfirmField.value
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
                                html: " Yeee! You have successfully reset your password. Kindly login with the new credentials",
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

        form.querySelector('input[name="password"]').addEventListener('input', function() {
            if (this.value.length > 0) {
                validator.updateFieldStatus('password', 'NotValidated');
            }
        });
    }

    var validatePassword = function() {
        return  (passwordMeter.getScore() >= 75);
    }

    // Public Functions
    return {
        init: function() {
            form = document.querySelector('#kt_new_password_form');
            submitButton = document.querySelector('#kt_new_password_submit');
            passwordMeter = KTPasswordMeter.getInstance(form.querySelector('[data-kt-password-meter="true"]'));

            handleForm();
        }
    };
}();

// On document ready
KTUtil.onDOMContentLoaded(function() {
    KTPasswordResetNewPassword.init();
});
