function scrollToLine(line) {

    var source_div = $('.source-viewer');
    var line_item = $('.source-line[data-line="' + line + '"]');
    var top = source_div.scrollTop() + line_item.position().top - source_div.height()*1.5 + line_item.height()/2;
    source_div.scrollTop(top);

}

function populateFieds(response) {

    $('#violation').val(response.violation);
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
        url: "/violations/next?a=" + author + '&p=' + project + '&t=' + token,
        type: 'GET',
        dataType: 'json',
        success: function (response) {

            if(response.violation) {

                populateFieds(response);

            } else {

                alertify.error('No more TD items found for evaluation');

            }

        },
        error: function (jqXHR) {

            alertify.error('An error occurred');

        },
        complete: function () {

        }
    });


}

function evaluateTdItem() {

    var obj = $('#ranking');

    obj.parents('.form-group').removeClass('has-error');

    var value = obj.val();
    if(value < 0) {
        alertify.error('Select your answer');
        obj.parents('.form-group').addClass('has-error');
        return false;
    }

    var comment = $('#comment').val();

    var v = $('#violation').val();
    var host = $('#ajax_host').val();
    var author = $('#author').val();
    var token = $('#token').val();

    var data = {
        e: value,
        v: v,
        c: comment
    };

    $.ajax({
        url: "/violations/evaluate?a=" + author + "&t=" + token,
        data: data,
        type: 'POST',
        dataType: 'json',
        success: function (response) {

            alertify.success('Your answer was saved');
            $('#skip_item').hide();
            $('#more_items').show();
            document.querySelector('#more_items').scrollIntoView({
                behavior: 'smooth'
            });

        },
        error: function () {

            alertify.error('An error occurred');

        },
        complete: function () {

        }
    });

}

$(document).ready(function () {

    getNext();

});