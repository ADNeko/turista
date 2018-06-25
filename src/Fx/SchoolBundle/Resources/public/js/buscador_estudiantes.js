$(function () {

    var estudiantes;

    estudiantes = new Bloodhound({
        datumTokenizer: function (d) {
            return [d.nombres, d.apellidoPaterno, d.apellidoMaterno, d.codigo];
        },
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        prefetch: '/estudiantes.json'
    });

    estudiantes.initialize();

    $('#fx-buscador-estudiantes').typeahead({
            hint: true,
            highlight: true,
            minLength: 2
        },
        {
            name: 'estudiantes',
            displayKey: function (d) {
                return d.codigo + ' - ' + d.nombres + ' ' + d.apellidoPaterno + ' ' + d.apellidoMaterno;
            },
            source: estudiantes.ttAdapter()
        });

    $('#fx-buscador-estudiantes').bind('typeahead:selected', function (obj, datum, name) {
        window.location.href = Routing.generate('fx_school.estudiante.show', {id: datum.id});
    });
});
