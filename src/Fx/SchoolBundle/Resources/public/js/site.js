function defaultFor(arg, val) {
    return typeof arg !== 'undefined' ? arg : val;
}

function showAlert(type, message) {
    type = defaultFor(type, 'error');
    message = defaultFor(message, "Error en el servidor, consulte al administrador del sistema.");

    new PNotify({
        text: message,
        type: type,
        shadow: false,
        nonblock: {
            nonblock: true,
            nonblock_opacity: .2
        }
    });
}

$(function () {
    $('.datetimepicker').datetimepicker({
        pickTime: false
    });

    $('.fx-ghost-button').on('click', function (e) {
        e.preventDefault();
        $(this).hide();
    });

    $('#sidebar-wrapper').find('li.active').find('ul.submenu').collapse('show');

    PNotify.prototype.options.styling = "fontawesome";
});
