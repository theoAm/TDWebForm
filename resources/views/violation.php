<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <script type="text/javascript" src="js/jquery.min.js"></script>
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/source.css">
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

        <input id="ajax_host" type="hidden" value="<?php echo $ajax_host; ?>">
        <input id="author" type="hidden" value="Seldaek">
        <input id="project" type="hidden" value="composer">
        <input id="token" type="hidden" value="773593604e2401da05a76d9c7480fa05">
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

    <div><u>Maintenance rate of file</u>: <span id="fileModificationsRank"></span></div>
    <div><u>Corrective maintenance rate of file</u>: <span id="fileCorrectionsRank"></span></div>
    <div class="pull-left"><u>Sqale index rank of file</u>: <span id="fileSqaleIndex"></span></div>
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
            <select class="form-control" style="width: 120px; margin: auto;" name="ranking" id="ranking">
                <option value="0">Select</option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
            </select>
            <div style="margin-top: 5px;">
                <a href="javascript:void(0);" class="btn btn-lg btn-success">
                    Evaluate
                </a>
            </div>
            <div class="small" style="margin-top: 5px;">
                <a href="javascript:void(0);" onclick="location.reload();">
                    Skip this and evaluate another TD item
                </a>
            </div>
        </div>
    </div>

    <div class="form-group right" style="margin-top: 10px;">
        <button class="btn btn-default" onclick="location.reload();" style="height: auto;" disabled>
            Evaluate one more Technical Debt Item &raquo;
        </button>
    </div>


</div>

</body>
</html>