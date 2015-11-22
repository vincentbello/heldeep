<?php

	require 'init.php';
	use Parse\ParseQuery;

	$query = new ParseQuery("Episode");
	$query->descending("epId");

	$heldeeps = $query->find();

	$count = count($heldeeps);
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
	<h1 id="top">Heldeep Tracklists</h1>
	<?php
	echo "Heldeep # ";
	for ($i = 0; $i < $count; $i++) {

		$heldeep = $heldeeps[$count - $i - 1];
		$number = $heldeep->get('epId');
		$formattedNumber = sprintf('%03d', $number);

		echo "<a href='#heldeep-$number'>$formattedNumber</a> ";
	}
	?>
	<h1>Track search</h1>
	<input type="text" id="track-search" placeholder="Search for banger here">

	<div id="results-container">
		<h3>Search results <span id="results-count"></span></h3>
		<div id="search-results"></div>
	</div>

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

	for ($i = 0; $i < count($heldeeps); $i++) {

		$heldeep = $heldeeps[$i];

		?>

		<h2 class="heldeep-header" id="heldeep-<?php echo $heldeep->get("epId"); ?>" data-trackid="<?php echo $heldeep->get("scId"); ?>">
			<?php echo $heldeep->get("title"); ?> <i class="fa fa-play"></i>
		</h2>
		<ol id="tracklist-<?php echo sprintf('%03d', $heldeep->get("epId")); ?>">
			<?php

			if ($i < FIRST_SHOWN_LIMIT) {

				$query = new ParseQuery("Track");
				$query->equalTo("episode", $heldeep);
				$tracks = $query->find();

				for ($j = 0; $j < count($tracks); $j++) {
					$track = $tracks[$j];
					if ($special = $track->get("type")) {
						echo "<h4>{$special}</h4>";
					}
					echo "<li" . (($special) ? " class='special'" : "") . ">" . $track->get("title");
						echo "<a target='_blank' href='https://soundcloud.com/search?q=" . urlencode($track->get("title")) . "'><i class='fa fa-search'></i></a>";
					echo "</li>";
				}

			} else {
				echo "<i class='fa fa-spinner fa-pulse'></i>";
			}

			?>
		</ol>
		<?php
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