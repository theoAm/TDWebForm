<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Technical Debt Evaluation</title>
    <script type="text/javascript" src="js/jquery.min.js"></script>
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
    <script type="text/javascript" src="js/alertify.min.js"></script>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/source.css">
    <link rel="stylesheet" href="css/alertify/alertify.css">
    <link rel="stylesheet" href="css/alertify/themes/bootstrap.css">
    <script type="text/javascript" src="js/app.js"></script>
</head>
<body>

<div class="container">

    <div id="header">
        <div class="pull-left">
            <img src="images/UOM_logo_new_ENG300_transparent.PNG"
                 alt="University of Macedonia"
                 height="80"
            >
            &nbsp;
        </div>
        <div class="pull-left">
            <h1>
                University of Macedonia<br>
                Department of Applied Informatics<br>
                Thessaloniki, Greece
            </h1>
        </div>
        <div style="clear: both;"></div>
    </div>

    <h1 class="center">
        <p>Technical Debt Evaluation</p>
    </h1>

    <div class="alert alert-warning" style="padding: 20px;margin: 20px 0px 20px 0px; font-size: 18px;">

        <input id="violation" type="hidden" value="">
        <input id="ajax_host" type="hidden" value="<?php echo $ajax_host; ?>">
        <input id="author" type="hidden" value="<?php echo $author; ?>">
        <input id="project" type="hidden" value="<?php echo $project; ?>">
        <input id="token" type="hidden" value="<?php echo $token; ?>">
        <div>
            <div class="pull-left">
                <span>&#9888;&nbsp;&nbsp;</span>
            </div>

            <div class="pull-left">
                <div><u>Technical Debt Item</u>: <span id="title"></span></div>
                <div>
                    <u>Suggestion</u>: <span id="message"></span>
                    <a id="scrollToLine" href="#!" onclick="">(Line: <span id="line"></span>)</a>
                </div>

                <br>

                <div class="small">
                    <div><u>Tags</u>: <span id="tags"></span></div>
                    <div>
                        <u>Severity</u>: <span id="severity"></span>
                    </div>
                    <div>
                        <u>Estimated time to fix</u>: <span id="tdpayment"></span>
                    </div>
                    <div style="clear: both;"></div>
                </div>
            </div>
            <div style="clear: both;"></div>
        </div>

    </div>

    <div class="center" style="font-size: 18px;"><u>File: <span id="filename"></span></u></div>
    <div style="clear: both;"></div>

    <div>
        <u>File change frequency</u>: <span id="fileModificationsRank"></span>
        <span style="cursor: pointer; color: #0096ff;" data-toggle="tooltip"
              data-placement="top"
              data-title="This indicates how often the file gets modified, relatively to other files. A percentage of 100% means that the file is the most frequently modified file.">
            (&#8505;)
        </span>
    </div>
    <div>
        <u>Issue fixing frequency</u>: <span id="fileCorrectionsRank"></span>
        <span style="cursor: pointer; color: #0096ff;" data-toggle="tooltip"
              data-placement="top"
              data-title="This indicates how often the file gets modified for issue fixing, relatively to other files. A percentage of 100% means that the file produces the most issues.">
            (&#8505;)
        </span>
    </div>
    <div class="pull-left">
        <u>Technical Debt of file</u>: <span id="fileSqaleIndex"></span>
        <span style="cursor: pointer; color: #0096ff;" data-toggle="tooltip"
              data-placement="top"
              data-title="This indicates the technical debt of the file, relatively to other files. A percentage of 100% means that the file has the highest technical debt.">
            (&#8505;)
        </span>
    </div>
    <div class="pull-right"><u>Revision</u>: <span id="revision"></span></div>
    <div style="clear: both;"></div>

    <div class="source-viewer" style="max-height: 400px; overflow: scroll;">
        <table class="source-table">
            <tbody id="source">

            </tbody>
        </table>
    </div>

    <div class="form-group center">
        <div class="center" style="padding: 20px;">

            <div class="form-group">

                <div style="margin-bottom: 10px; font-size: 18px;">
                    <i>"From scale 1 to 5 how important is it to solve the issue above?"</i>
                </div>

                <select class="form-control"
                        style="width: 120px;
                        font-size: 18px;
                        margin: auto;
                        padding: 10px 16px;
                        height: auto;
                        vertical-align: middle;
                        display: inline-block;"
                        name="ranking"
                        id="ranking"
                >
                    <option value="0">Select</option>
                    <option value="1">1 (No need to solve it)</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5 (It is urgent to solve it)</option>
                </select>

                <a href="javascript:void(0);"
                   class="btn btn-lg btn-success"
                   onclick="evaluateTdItem();"
                >
                    Evaluate
                </a>

            </div>

            <div class="row">
                <div class="col-xs-12 col-md-6 col-md-offset-3">
                    <div class="form-group-lg">
                        <input id="comment"
                               class="form-control"
                               type="text"
                               value=""
                               placeholder="Here you can write your comment if you want"
                        />
                    </div>
                </div>
            </div>

            <div id="skip_item" class="small" style="margin-top: 10px;">
                <div>
                    <a href="javascript:void(0);" onclick="location.reload();">
                        Skip this and evaluate another TD item
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div id="more_items"
         class="form-group center" style="margin-top: 10px; display: none;"
    >
        <button id='next'
                class="btn btn-info btn-lg"
                onclick="location.reload();"
                style="height: auto;"
        >
            Evaluate one more Technical Debt Item &raquo;
        </button>
        <div class="small" style="margin-top: 10px;">
            <a href="/thank-you">I do not want to evaluate more TD items</a>
        </div>
    </div>


</div>

<script>
    $(document).ready(
        function () {
            $('[data-toggle="tooltip"]').tooltip();
        }
    );
</script>

</body>
</html>