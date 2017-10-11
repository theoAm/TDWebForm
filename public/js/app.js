$(document).ready(function () {

    getNext();

})

function scrollToLine(line) {

    var source_div = $('.source-viewer');
    var line_item = $('.source-line[data-line="' + line + '"]');
    var top = source_div.scrollTop() + line_item.position().top - source_div.height()*1.25 + line_item.height()/2;
    source_div.scrollTop(top);

}

function populateFieds(response) {

    $('#title').text(response.rule_name);
    $('#severity').text(response.severity);
    $('#tags').text(response.tags);
    $('#message').text(response.message);
    $('#filename').text(response.filename);
    $('#line').text(response.line);
    $('#revision').text(response.revision);

    var source_html = '';
    for (var i in response.source) {

        var l = response.source[i];
        var line = l[0];
        var code = l[1];
        var has_issues = (line == response.line) ? 'has-issues' : '';

        source_html += '<tr class="source-line ' + has_issues + '" data-line="' + line + '">' +
            '<td class="source-meta source-line-number">' + line + '</td>' +
            '<td class="source-line-code code">' +
            '<div class="source-line-code-inner">' +
            '<pre>' + code + '</pre>' +
            '</div>' +
            '</td>' +
            '</tr>';

    }

    $('#source').html(source_html);

    if(response.line) {
        $('#scrollToLine').attr('onclick', 'scrollToLine(' + response.line + ');')
        scrollToLine(response.line);
    }

}

function getNext() {

    var host = $('#ajax_host').val();

    $.ajax({
        url: host + "/violations/next/kkkkk",
        type: 'GET',
        dataType: 'json',
        async: false,
        success: function (response) {

            populateFieds(response);

        },
        error: function () {

            resetFields();

        },
        complete: function () {

        }
    });


}