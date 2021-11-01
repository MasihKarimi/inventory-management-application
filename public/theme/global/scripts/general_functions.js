// General function used in most places

function handleReports(form, callback)
{
    form.submit(function (event) {
        event.preventDefault();
        $.ajax({
            type: 'POST',
            url: form.attr('action'),
            data: form.serialize(),
            beforeSend: function () {
                $('[type="submit"]').attr('disabled', 'disabled');
            },
            success: function (response, status, xhr, $form) {
                $('[type="submit"]').removeAttr('disabled');
                callback(response, xhr);
            },
            error: function (xhr, status, error) {
                $('[type="submit"]').removeAttr('disabled');
                ajaxErrorHandler(JSON.parse(xhr.responseText));
            }
        });
    });
}

function ajaxErrorHandler(response) {
    let errors = '';
    $.each(response.errors, function(index, error) {
        errors += error + '<br/>';
    });
    displayToastr(null, errors, 'error', null);
}

$('.submit_form_response').submit(function (event) {
    event.preventDefault();
    let form = $(this);
    $.ajax({
        type: 'POST',
        url: form.attr('action'),
        data: form.serialize(),
        beforeSend: function () {
            $('[type="submit"]').attr('disabled', 'disabled');
        },
        success: function (response, status, xhr, $form) {
            $('[type="submit"]').removeAttr('disabled');
            if ("message" in response)
                displayToastr(null, response['message'], response['type'], null);
            if ("form_clean" in response)
                form.trigger('reset');
            if ("script" in response)
                eval(response['script']);
            if ("refresh" in response)
                setTimeout(function () {
                    location.reload();
                }, 3000);
            if ("modal" in response)
                $("#" + response['modal']).modal('toggle');
            if ("redirect" in response) {
                form.trigger('reset');
                setTimeout(function () {
                    location.href = response['redirect']
                }, 3000);
            }
        },
        error: function (xhr, status, error) {
            $('[type="submit"]').removeAttr('disabled');
            ajaxErrorHandler(JSON.parse(xhr.responseText));
        }
    });
});

function printSection(sectionId, cssLinks) {
    let printContents =  document.getElementById(sectionId).innerHTML;
    let printWindow = window.open("", "MsgWindow", );
    printWindow.document.body.innerHTML = printContents;
    printWindow.document.head.innerHTML = cssLinks;
    let script = document.createElement('script');
    script.innerHTML = "document.getElementById('print-button').removeAttribute('hidden')";
    printWindow.document.head.appendChild(script);
}
