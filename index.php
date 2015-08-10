<?php
function get_data($endpoint) {
	$session = curl_init($endpoint);
	curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
	$data = curl_exec($session);
	curl_close($session);
	return json_decode($data);
}

?>


<!DOCTYPE html>
<html lang="en-US">
<head>
	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
	<link rel="icon" href="favicon.ico" type="image/x-icon">
	
	<title>Heldeep Banger Finder</title>
	
	<link rel="stylesheet" href="font-awesome/css/font-awesome.min.css">
	<link rel="stylesheet" href="css/tooltipster.css" />
	<link rel="stylesheet" href="css/style.css">
	<meta charset="UTF-8">
	<meta name="description" content="An easy way to find bangers from all the Heldeeps.">
	<meta name="author" content="Vincent Bello">
	<script>
	  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
	  
	  ga('create', 'UA-60237438-1', 'auto');
	  ga('send', 'pageview');
	</script>
</head>
<body>

<div id="container">

<?php

$clientID = "20c0a4e42940721a64391ac4814cc8c7";
$endpoint = "http://api.soundcloud.com/users/heldeepradio/tracks.json?client_id=$clientID&limit=200";

$heldeeps = get_data($endpoint);

//print_r($tracks);

echo "<h1 id='top'>Heldeep Tracklists</h1>";
echo "Heldeep # ";
for($i = 1; $i <= sizeof($heldeeps); $i++) {
	$number = sprintf('%03d', $i);
	echo "<a href='#heldeep-$number'>$number</a> ";
}
echo "<h1>Track search</h1>";
echo "<input type='text' id='track-search' placeholder='Search for banger here'>";

echo "<div id='results-container'>";
echo "<h3>Search results <span id='results-count'></span></h3>";
echo "<div id='search-results'></div></div>";
?>
<h1>iOS App</h1>

<div style="width: 100%; overflow: hidden">
	<div style="float: left; margin-right: 10px">
		<img src="logo.png" width="70" style="border-radius: 15px">
	</div>
	<div style="float: left">
		<a href="https://geo.itunes.apple.com/us/app/heldeep-radio-track-finder/id1008322889?mt=8&uo=6" target="itunes_store" style="display:inline-block;overflow:hidden;background:url(http://linkmaker.itunes.apple.com/images/badges/en-us/badge_appstore-lrg.png) no-repeat;width:165px;height:40px;@media only screen{background-image:url(http://linkmaker.itunes.apple.com/images/badges/en-us/badge_appstore-lrg.svg);}"></a>
		<p style="margin-top: 0">
			Download the iOS <i>Heldeep Radio Track Finder</i> on the App Store!
		</p>
	</div>
</div>

<?php
echo "<h1>All tracklists</h1>";

foreach(array_reverse($heldeeps) as $heldeep) {

	$heldeepDescription = $heldeep->description;
	$heldeepTitle = $heldeep->title;
	$heldeepURL = $heldeep->permalink_url;
	$trackID = $heldeep->id;
		$matches = array();
		preg_match("/[0-9]{3}/", $heldeepTitle, $matches);
	$heldeepNumber = $matches[0];

	echo "<h2 class='heldeep-header' id='heldeep-$heldeepNumber' data-trackid='$trackID'>$heldeepTitle <i class='fa fa-play'></i></h2>";
	
	$bolded = array('Heldeep Radio Cooldown', 'Heldeep Cooldown', 'Heldeep Radio Classic', 'Heldeep Classic', 'Heldeep Radio Halfbeat', 'Heldeep Halfbeat', 'Guestmix by Sander van Doorn');
	
	foreach($bolded as $b) {
		$heldeepDescription = str_replace($b, "<h4>$b</<h4>", $heldeepDescription);
	}

	if ($heldeepNumber == "010") {
		$pos = strrpos($heldeepDescription, "Guestmix");
		$heldeepDescription = substr($heldeepDescription, 0, $pos) . preg_replace("/([0-9]{1,2})/", '${1})', substr($heldeepDescription, $pos));
	}

	//echo $heldeepDescription;

	$tracklist = preg_split('/[^0-9][0-9]{1,2}[.)] /', $heldeepDescription);
	array_shift($tracklist);
	
	echo "<ol id='tracklist-$heldeepNumber'>";
	foreach ($tracklist as $track) {
		echo "<li>$track</li>";
	}
	echo "</ol>";

}

?>

<a id="return-to-top" class="tooltip" title="Top" href="#top">
	<span class="fa-stack fa-lg">
  		<i class="fa fa-circle-thin fa-stack-2x"></i>
  		<i class="fa fa-angle-up fa-stack-1x"></i>
	</span>
</a>

</div>

<script src="js/jquery.js"></script>
<script src="js/jquery.tooltipster.min.js"></script>
<script src="js/search.js"></script>
</body>
</html>