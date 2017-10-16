$(document).ready(function () {

    getNext();

})

function scrollToLine(line) {

    var source_div = $('.source-viewer');
    var line_item = $('.source-line[data-line="' + line + '"]');
    var top = source_div.scrollTop() + line_item.position().top - source_div.height()*1.5 + line_item.height()/2;
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
    $('#tdpayment').text(response.tdpayment);
    $('#fileSqaleIndex').text(response.fileSqaleIndexRank + '%');

    var fileModRankText = (response.fileModificationsRank) ? response.fileModificationsRank + '%' : '0%';
    var fileCorRankText = (response.fileCorrectionsRank) ? response.fileCorrectionsRank + '%' : '0%';
    $('#fileModificationsRank').text(fileModRankText);
    $('#fileCorrectionsRank').text(fileCorRankText);

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
    var author = $('#author').val();
    var project = $('#project').val();
    var token = $('#token').val();

    $.ajax({
        url: host + "/violations/next?a=" + author + '&p=' + project + '&t=' + token,
        type: 'GET',
        dataType: 'json',
        async: false,
        success: function (response) {

            populateFieds(response);

        },
        error: function () {



        },
        complete: function () {

        }
    });


}