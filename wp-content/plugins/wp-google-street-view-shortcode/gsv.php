<?php
 $key = filter_var ( $_GET["key"], FILTER_SANITIZE_EMAIL);
?>
<html>
<head>
	<meta content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no" name="viewport">
	<meta content="chrome=1" http-equiv="X-UA-Compatible">
    <title>Google Street View</title>
    <style type="text/css">
        html, body { margin: 0; padding: 0; width: 100%; height: 100%; background: #000; }
        #gsv { width: 100%; height: 100%; }
    </style>
</head>
<body>
    <div id="gsv"></div>
    <script type="text/javascript" src="//maps.google.com/maps/api/js?key=<?php echo $key ?>&sensor=false"></script>
    <script type='text/javascript'>
      var myLatlng = new google.maps.LatLng('<?php echo floatval($_GET["lat"]) ?>','<?php echo floatval($_GET["lng"]) ?>');
      var panoramaOptions = {
        position: myLatlng, 
        addressControl: false,
        pov: {
          <?php echo 'heading: ' . floatval($_GET["heading"]) . ','; ?>
          pitch: <?php echo floatval($_GET["pitch"]); ?>,
          zoom: <?php echo floatval($_GET["zoom"]); ?>
        }
      }
      var gsv = new google.maps.StreetViewPanorama(document.getElementById('gsv'), panoramaOptions);
    </script>
</body>
</html>