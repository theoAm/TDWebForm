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
            <img src="images/pamak-front-eng-header.jpg" alt="University of Macedonia" width="210">
            &nbsp;
        </div>
        <div class="pull-left">
            <h1>University of Macedonia</h1>
            <h2>Department of Applied Informatics</h2>
            <h4>Thessaloniki, Greece</h4>
        </div>
        <div style="clear: both;"></div>
    </div>

    <h1 class="center">
        <p>Technical Debt Item Evaluation</p>
    </h1>

    <div class="alert alert-warning" style="padding: 20px;margin: 20px 0px 20px 0px; font-size: 18px;">

        <input id="ajax_host" type="hidden" value="<?php echo $ajax_host; ?>">
        <div>
            <div class="pull-left">
                <span>&#9888;&nbsp;</span>
            </div>
            <div class="pull-left">
                <div><u>Technical Debt Item</u>: <span id="title"></span></div>
                <div>
                    <u>Suggestion</u>: <span id="message"></span>
                    <a id="scrollToLine" href="#!" onclick="">(Line: <span id="line"></span>)</a>
                </div>
            </div>
            <div style="clear: both;"></div>
        </div>

        <br>

        <div class="small">
            <div class="pull-left">Tags: <span id="tags"></span></div>
            <div class="pull-right">
                Severity: <span id="severity"></span>
            </div>
            <div style="clear: both;"></div>
        </div>

    </div>

    <div class="pull-left">File: <span id="filename"></span></div>
    <div class="pull-right">Revision: <span id="revision"></span></div>
    <div style="clear: both;"></div>

    <div class="source-viewer" style="max-height: 450px; overflow: scroll;">
        <table class="source-table">
            <tbody id="source">

            </tbody>
        </table>
    </div>


</div>

</body>
</html>