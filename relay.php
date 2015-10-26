<?php
include ('base.php');

#relay.php
#activate outputs based on logged and parsed database information (thresholdevents).
#first guess is to run a bash script which triggers aurduino run appliances, check every 5 minutes on sensor condition  events.  If we really wanted to get technical we could measure change in 5 minutes to predict time it will take (or recalc on the fly) to hit target value (middle number between min and max thresholds).
#takes relayID info, metric at time of activation, re-detect 5 minutes later and measure change.  If no change run again until there is a change but send a panic email on fail of 2 re-detection predictions.  If the number changes at all a prediction can be made so for no prediction to occur would mean data is missing.  
# 10/25/15 - LAF (Late as Fuck!)

# Determine auotmatically based on sensorID which relay needs activation and monitoring.<br>
# This may require an additional field on the sensor table that contains a hardwareID for the relay (lspci/lsusb grepped for ID# and then hardwareID extracted<br>

#takes one parameter - the thresholdeventID#

if (!isset($_GET["evtid"]) OR $_GET["evtid"] <= 0) {
	echo "<br>No EventID passed for review.";
	die();
}

	$intThresholdEventID = $_GET["evtid"];
	
#Code below clears new flag from eventthreshold table	
	echo "<br>intThreholdEventID = " . $intThresholdEventID . "<br>Date/Time = " . dtStamp();
	$flagsCleared = fnClearNewFlag($intThresholdEventID);
	echo "<br> Attempting to Clear EventID booIsNew Flag on EventID" . $intThresholdEventID . "<br>" . $flagsCleared . " flags were reported cleared.";
	
	
	$strFixStatus = fnFixClimate($intThresholdEventID);



?>