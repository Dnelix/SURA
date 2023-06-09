"use strict";

function _(x){
	return document.getElementById(x);
}

function goTo(here){
    window.location.href=here;
}

function reloadPage(){
    window.location.reload();
}

//Sweetalert pop-ups
function swal_Popup(status, responseMessage, btn_text){
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
        icon: "warning",
        buttonsStyling: false,
        showDenyButton: true,
        confirmButtonText: btn_yes,
        denyButtonText: btn_no,
        customClass: {
            confirmButton: "btn btn-light-primary",
            denyButton: "btn btn-danger"
        }
    })
}

// AJAX Calls
function AJAXcall(formID, submitButton=null, type, url, formData=null){
    var responseMessage;
    var responseType; // error or success
    if(submitButton !== null){
        submitButton.setAttribute('data-kt-indicator', 'on'); //loading
        submitButton.disabled = true;
    }

    // Handle form serialization
    if (formData === null){
        var formData = {};
        $.each($(formID).serializeArray(), function(_, field) {
            formData[field.name] = field.value;
        });
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
                if(submitButton !== null){
                    submitButton.disabled = false;
                    submitButton.setAttribute('data-kt-indicator', 'off');
                }
            } else {
                //responseMessage = response.messages[0];
                responseMessage = "SUCCESS: "+JSON.stringify(response);
                responseType = 'success';
                swal_Popup(responseType, responseMessage, 'Okay. Got it!');
                if(submitButton !== null){
                    submitButton.disabled = false;
                    submitButton.setAttribute('data-kt-indicator', 'off');
                }
            }
        }
    });

    return responseType;
}

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

