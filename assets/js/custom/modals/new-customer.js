"use strict";

// Class definition
var ModalNewCustomer = function () {
	var submitButton;
	var cancelButton;
	var validator;
	var form;
	var modal;
	var modalEl;
	var successWindow;
	var createNewbtn;
	var addMeasurebtn;

	var formID = '#modal_new_customer_form';
	var successWinID = '#new_customer_success';

	// Handle form validation and submittion
	var handleForm = function() {
		validator = FormValidation.formValidation(
			form,
			{
				fields: {
					fullname: {
						validators: {
							notEmpty: {
								message: 'Customer name is required'
							}
						}
					},
					phone: {
						validators: {
							notEmpty: {
								message: 'It would be great to add a phone number too'
							},
                            numeric: {
                                message: 'Please enter a valid phone number'
                            }
						}
					},
				},
				plugins: {
					trigger: new FormValidation.plugins.Trigger(),
					bootstrap: new FormValidation.plugins.Bootstrap5({
						rowSelector: '.fv-row',
                        eleInvalidClass: '',
                        eleValidClass: ''
					})
				}
			}
		);

		// Action buttons
		submitButton.addEventListener('click', function (e) {
			e.preventDefault();

			if (validator) {
				validator.validate().then(function (status) {
					//console.log('validated!');

					if (status == 'Valid') {
						var responseMessage;
						submitButton.setAttribute('data-kt-indicator', 'on');
						submitButton.disabled = true;

						$.ajax({
							url: 'controllers/customers.php',
							type: 'POST',
							dataType: 'JSON',
							headers: {'Content-Type': 'application/json'},
							data: JSON.stringify(serializeToJSON(form)),
							success: function(response){
								if(response['success'] !== true){
									responseMessage = "ERROR: "+JSON.stringify(response);
									swal_Popup('error', responseMessage, 'Okay. Got it!');
									submitButton.disabled = false;
									submitButton.setAttribute('data-kt-indicator', 'off');
								} else {
									responseMessage = "SUCCESS: "+JSON.stringify(response);
									submitButton.disabled = false;
									submitButton.setAttribute('data-kt-indicator', 'off');
									form.reset();
									$(formID).toggleClass('d-none');
									$(successWinID).toggleClass('d-none');
								}
							}
						}); 						
					} else {
						//swal_Popup('error', 'Some required items are missing');
					}
				});
			}
		});

		cancelButton.addEventListener('click', function (e) {
			e.preventDefault();

			swal_confirm("Are you sure you would like to cancel?", "Yes, cancel and exit", "No, return")
			.then(function (result) {
				if (result.value) {
					form.reset(); // Reset form	
					modal.hide(); // Hide modal				
				} else if (result.dismiss === 'cancel') {
					swal_Popup('success', 'Not cancelled. Continue creating customer');
				}
			});
		});

		createNewbtn.addEventListener('click', function (e) {
			e.preventDefault();
			form.reset();
			$(formID).toggleClass('d-none');
			$(successWinID).toggleClass('d-none');
		});

		addMeasurebtn.addEventListener('click', function (e) {
			e.preventDefault();
		});
	}

	return {
		// Public functions
		init: function () {
			modalEl = document.querySelector('#modal_new_customer');

			if (!modalEl) {
				return;
			}

			modal = new bootstrap.Modal(modalEl);

			form = document.querySelector(formID);
			submitButton = document.getElementById('modal_new_customer_submit');
			cancelButton = document.getElementById('modal_new_customer_cancel');

			successWindow = document.querySelector(successWinID);
			createNewbtn  = document.querySelector('#create_another');
			addMeasurebtn = document.querySelector('#add_measurement');

			handleForm();
		}
	};
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
	ModalNewCustomer.init();
});