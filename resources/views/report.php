<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Technical Debt Report</title>
    <script type="text/javascript" src="js/jquery.min.js"></script>
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/source.css">

    <script src="https://www.amcharts.com/lib/3/amcharts.js"></script>
    <script src="https://www.amcharts.com/lib/3/pie.js"></script>
    <script src="https://www.amcharts.com/lib/3/plugins/export/export.min.js"></script>
    <link rel="stylesheet" href="https://www.amcharts.com/lib/3/plugins/export/export.css" type="text/css" media="all" />
    <script src="https://www.amcharts.com/lib/3/themes/light.js"></script>
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
        <p>Technical Debt Report</p>
    </h1>

    <input id="ajax_host" type="hidden" value="<?php echo $ajax_host; ?>">
    <input id="author" type="hidden" value="<?php echo $author; ?>">
    <input id="project" type="hidden" value="<?php echo $project; ?>">
    <input id="token" type="hidden" value="<?php echo $token; ?>">

    <div style="height: 20px;"></div>

    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-md-7">

                <div class="pull-left" style="margin-right: 10px;">
                    <img src="/images/avatar.png" alt="avatar" height="110">
                </div>
                <div class="pull-left" style="padding-top: 15px;">
                    <div><u>Author</u>: <?php echo $author; ?></div>
                    <div><u>Project</u>: <?php echo $project; ?></div>
                    <div><u>Commit density</u>: <?php echo round($dev_commits/$total_commits*100, 2); ?>% (of total commits)</div>
                    <div><u>Technical Debt added</u>: <?php echo round($dev_td_added/$total_td_added*100, 2); ?>% (of totally added TD)</div>
                </div>
                <div style="clear: both;"></div>

                <div id="top-violations" style="margin-bottom: 30px;">

                    <h2 style="font-size: 20px;"><u>Most frequent TD Items added</u></h2>

                    <?php foreach ($top_violations as $key => $top_violation): ?>

                        <?php $i = $key +1; ?>

                        <div class="violation" style="margin-bottom: 15px;">

                            <span><?php echo $i . ') '; ?></span>
                            <span><?php echo $top_violation->name; ?></span>
                            <a href="javascript:void(0);"
                               onclick="$(this).siblings('.details').slideToggle();">
                                details
                            </a>

                            <div class="details"
                                 style="display: none;
                                 margin-top: 10px;
                                 padding: 10px;
                                 background-color: #efefef;">

                                <?php echo $top_violation->description; ?>

                            </div>

                        </div>

                    <?php endforeach; ?>

                </div>

            </div>
            <div class="col-xs-12 col-md-5">

                <div id="chartdiv" style="margin: auto; width: 100%; height: 300px;"></div>

            </div>
        </div>
    </div>


</div>

<script>
    var chart = AmCharts.makeChart("chartdiv", {
        "type": "pie",
        "theme": "light",
        "titles": [
            {
                "text": "TD added per commit",
                "size": 18
            },
            {
                "text": "(relatively to other developers)",
                "size": 14
            }
        ],
        "dataProvider": [
            {
                "developer": "Others",
                "td": <?php echo $other_td_added/$other_commits; ?>,
                "color": "#d3d3d3"
            },
            {
                "developer": "<?php echo $author; ?>",
                "td": <?php echo $dev_td_added/$dev_commits; ?>,
                "color": "#b73130"
            }
        ],
        "valueField": "td",
        "titleField": "developer",
        "balloon":{
            "fixedPosition":true
        },
        "export": {
            "enabled": false
        },
        "colorField": "color",
        "marginTop": 0,
        "marginBottom": 0,
        "marginLeft": 0,
        "marginRight": 0,
        "pullOutRadius": 15
    } );
</script>

</body>
</html>