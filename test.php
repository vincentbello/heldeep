<?php

$clientID = "20c0a4e42940721a64391ac4814cc8c7";
$endpoint = "http://api.soundcloud.com/users/heldeepradio/tracks.json?client_id=$clientID";

$session = curl_init($endpoint);
curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
$data = curl_exec($session);
curl_close($session);
$heldeeps = json_decode($data);

//print_r($heldeeps);

$count = 0;

foreach(array_reverse($heldeeps) as $heldeep) {
	$count++;

}
echo $count;
?>