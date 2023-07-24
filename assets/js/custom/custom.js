"use strict";
//#######################//
//####### GENERIC #######//
//#######################//
function _(x){
	return document.getElementById(x);
}

function goTo(here){
    window.location.href=here;
}

function reloadPage(){
    window.location.reload();
}

//Show an element and hide others
function toggleView(elem1, elem2=null, elem3=null, elem4=null){
    $(elem1).toggleClass('d-none');
    $(elem2).toggleClass('d-none');
    $(elem3).toggleClass('d-none');
    $(elem4).toggleClass('d-none');
}

//Sweetalert pop-ups
function swal_Popup(status, responseMessage, btn_text='Ok, got it!'){
    // status = error/success
    if (status === 'error'){ var btn_type = "btn font-weight-bold btn-danger";} 
    else if (status === 'success') { var btn_type = "btn btn-primary";}
    else if (status === 'info') { var btn_type = "btn btn-info";}
    else { var btn_type = "btn btn-warning";}

    Swal.fire({
        text: responseMessage,
        icon: status,
        buttonsStyling: false,
        confirmButtonText: btn_text,
        customClass: {
            confirmButton: btn_type
        }
    });
}

//sweetalert confirm
function swal_confirm(msg, btn_yes='YES', btn_no='NO'){
    return swal.fire({
        text: msg,
        icon: "question",
        buttonsStyling: false,
        showDenyButton: true,
        confirmButtonText: btn_yes,
        denyButtonText: btn_no,
        customClass: {
            confirmButton: "btn btn-primary",
            denyButton: "btn btn-light"
        }
    })
}

// Serialize any form and convert output to JSON. The name of each field will become the JSON attribute
function serializeToJSON(formID){
    var formData = {};
    $.each($(formID).serializeArray(), function(_, field) {
        formData[field.name] = field.value;
    });
    return formData;
}

// Extract all elements from any form as JSON data
function formdataJSON(form){
    var formElements = form.elements;
    var formData = {};
    for (var i = 0; i < formElements.length; i++) {
        var element = formElements[i];
        if (element.tagName === 'INPUT' || element.tagName === 'SELECT' || element.tagName === 'TEXTAREA') {
            formData[element.name] = element.value;
        }
    }
    return formData;
}

// Handle response from ajax call
function handleResponse(responseType, action, url=null){
    if (responseType == 'success'){
        if(action == 'reload'){ reloadPage(); }
        else if(action == 'redirect'){ goTo(url); }
        else if(action == 'goback'){ window.history.back(); }
    } else {
        console.log(responseType);
    }
}

// AJAX Calls
function AJAXcall(formID, submitButton=null, type, url, formData=null, callback){
    var responseMessage;
    var responseType; // error or success
    if(submitButton !== null){
        submitButton.setAttribute('data-kt-indicator', 'on'); //loading
        submitButton.disabled = true;
    }

    // Handle form serialization
    if (formData === null){
        formData = serializeToJSON(formID);
    } else {
        formData = formData;
    }

    $.ajax({
        url: url,
        type: type,
        dataType: 'JSON',
        headers: {'Content-Type': 'application/json'},
        data: JSON.stringify(formData),
        success: function(response){
            if(response['statusCode'] !== 200 || response['success'] !== true){
                //responseMessage = "ERROR: "+response.messages[0];
                responseMessage = "ERROR: "+JSON.stringify(response);
                responseType = 'error';
                swal_Popup(responseType, responseMessage, 'Okay. Got it!');
            } else {
                responseMessage = response.messages[0];
                //responseMessage = "SUCCESS: "+JSON.stringify(response);
                responseType = 'success';
                swal_Popup(responseType, responseMessage, 'Okay. Got it!');
            }

            if(callback) callback(responseType);

            if(submitButton !== null){
                submitButton.disabled = false;
                submitButton.setAttribute('data-kt-indicator', 'off');
            }

        }
    });
}

//--clipboard
function copyToClipboard() {
    var button = document.querySelector('#copy_clipboard_btn');
    var input = document.querySelector('#copy_clipboard_input');

    var buttonCaption = button.innerHTML;
    var clipboard = new ClipboardJS(button);
    button.innerHTML = 'Double click to copy';

    clipboard.on('success', function(e) {
        input.classList.add('bg-success');
        input.classList.add('text-inverse-success');

        button.innerHTML = 'Copied!';

        setTimeout(function() {
            button.innerHTML = buttonCaption;

            // Remove bgcolor
            input.classList.remove('bg-success'); 
            input.classList.remove('text-inverse-success'); 
        }, 3000);

        e.clearSelection();
    });
}

// Randomize a set of displays
function randomizeSpans() {
    var spans = document.querySelectorAll('#showRandom span');
    var currentSpan = null;

    function showRandomSpan() {
        //alert('hey');
        if (currentSpan) {
        currentSpan.style.display = 'none';
        }
        
        // Randomly select a new span
        var randomIndex = Math.floor(Math.random() * spans.length);
        var newSpan = spans[randomIndex];
        
        // Display the new span
        newSpan.style.display = 'inline';
        currentSpan = newSpan;
    }

    showRandomSpan();
    // Switch the displayed span every 10 seconds
    setInterval(showRandomSpan, 5000);
}

// Show Toast Message
function showToastMsg(msg){
    toastr.options = {
        "closeButton": false,
        "debug": false,
        "newestOnTop": false,
        "progressBar": true,
        "positionClass": "toastr-top-center",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "1000",
        "hideDuration": "1000",
        "timeOut": 0,
        "extendedTimeOut": 0,
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut",
        "tapToDismiss": false
    };

    if (msg == "incomplete"){
        toastr.error('Please complete your business profile to continue enjoying our services<br /><br /><button type="button" class="btn btn-bg-light btn-sm" onClick="goTo(\'profile?page=business\')">Go to Profile</button>', "Complete your profile");
    }
}

//#######################//
//###### SPECIFIC #######//
//#######################//
// sendMail
function sendMail(type, subject, to_mail, to_name='', message='', sender=''){
    var params = {
        type: type,
        subject: subject,
        to_name: to_name,
        to_mail: to_mail,
        message: message,
        sender: sender
    };

    AJAXcall(null, null, 'POST', 'controllers/_email.php', params);

}

//logout
function logout(sessionid, accesstoken){
    var formActionURL = "controllers/sessions.php?sessionid="+sessionid;
    var logoutLink = document.querySelector('#logout_link');
    logoutLink.setAttribute('data-kt-indicator', 'on');

    var params = {
        accesstoken: accesstoken
    };

    $.ajax({
        url: formActionURL,
        type: 'DELETE',
        dataType: 'JSON',
        headers: {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer '.accesstoken
        },
        data: JSON.stringify(params),
        success: function(response){
            if(response['statusCode'] !== 200 || response['success'] !== true){
                var responseMessage = "ERROR: "+response.messages[0];
                //var responseMessage = "ERROR: "+JSON.stringify(response);

                swal_Popup('error', responseMessage, 'Try Again!');
            } else {
                Swal.fire({
                    text: "You have successfully logged out",
                    icon: "success",
                    buttonsStyling: false,
                    confirmButtonText: "Exit",
                    customClass: {
                        confirmButton: "btn btn-primary"
                    },
                    timer: "1000"
                }).then(function () {
                    location.href = "home";
                });
            }
        }
    });
}

//Populate States and cities based on selected country
function populateStates() {
    var countrySelect = document.getElementById("country");
    var stateSelect = document.getElementById("state");
    var citySelect = document.getElementById("city");
    // Get selected country value
    var selectedCountry = countrySelect.value;
    // Clear previous state and city options
    stateSelect.innerHTML = ""; //"<option value=''>States in "+selectedCountry+"</option>";
    citySelect.innerHTML = ""; //"<option value=''>Select a city</option>";

    // Fetch the data from JSON DB
    fetch('models/databases/city-state-country.json')
    .then(response => response.json())
    .then(data => {
        var states = [];
        data.Countries.forEach(country => {
            if (country.CountryName === selectedCountry) {
                states = country.States.map(state => state.StateName);
            }
        });
        for (var i = 0; i < states.length; i++) {
            var state = states[i];
            var option = document.createElement("option");
            option.value = state;
            option.text = state;
            stateSelect.appendChild(option);
        }
    })
    .catch(error => {
        console.log('Error:', error);
    });
}

function populateCities() {
    var stateSelect = document.getElementById("state");
    var citySelect = document.getElementById("city");
    // Get selected state values
    var selectedState = stateSelect.value;
    // Clear previous city options
    citySelect.innerHTML = ""; //"<option value=''>Cities in "+selectedState+"</option>";

    fetch('models/databases/city-state-country.json')
    .then(response => response.json())
    .then(data => {
        var cities = [];
        data.Countries.forEach(country => {
            country.States.forEach(state => {
                if (state.StateName === selectedState) {
                    cities = state.Cities;
                }
            });
        });
        for (var i = 0; i < cities.length; i++) {
            var city = cities[i];
            var option = document.createElement("option");
            option.value = city;
            option.text = city;
            citySelect.appendChild(option);
        }
    })
    .catch(error => {
        console.log('Error:', error);
    });

}
