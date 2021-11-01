function displayToastr(title, msg, displayType, onClickFunction) {

    toastr.options = {
        closeButton: true,
        positionClass: 'toast-top-right',
        showDuration: 1000,
        hideDuration: 1000,
        timeOut: 8000,
        extendedTimeOut: 2500,
        progressBar: true,
        showEasing: 'swing',
        hideEasing: 'linear',
        showMethod: 'fadeIn',
        hideMethod: 'fadeOut',
        onclick: onClickFunction
    };

    toastr[displayType](msg, title);
}

function clearToastr() {
    toastr.clear();
}