<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="favicon.ico">

    <title>Trade Visualization</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <script src="js/jquery-2.1.4.min.js"></script>
    <script src="js/jquery-dateFormat.min.js"></script>
    <script src="https://www.google.com/jsapi"></script>
    <script src="js/show.js"></script>
</head>

<body>

<div class="container">
    <!-- Main component for a primary marketing message or call to action -->
    <div class="jumbotron">
        <h1>Tradeprocessor Visualization</h1>
        <p>Sumbit test messages to consumer endpoint</p>
        <button class="btn btn-success btn-lg" id="start">Send messages!</button>

        <pre id="log" style="display: none"></pre>

        <h3 id="msgPerSec"></h3>
        <div id="map_div" style="width: 100%; height: 500px; margin-bottom: 20px"></div>
        <br><br>
        <div id="barchart_material" style="width: 100%; height: 500px; margin-bottom: 20px"></div>
        <br><br>
        <div id="regions_div" style="width: 100%; height: 500px;"></div>
    </div>
</div> <!-- /container -->


<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="js/bootstrap.min.js"></script>
</body>
</html>
