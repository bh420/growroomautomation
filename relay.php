<?php
include ('base.php');

#relay.php<
#thinking about having this file be automatically ran through cron or task scheduler... it can quickly query tblThresholdEvents for booIsNew flagged events.  Activate appropriate relay identified through mapping, then unflag it as new and reflag as booInProcess.  Then it is up to the data sensors to close it out when it gets back in parameter.
#activate outputs based on logged and parsed database information (thresholdevents).
#first guess is to run a bash script which triggers aurduino run appliances, check every 5 minutes on sensor condition  events.  If we really wanted to get technical we could measure change in 5 minutes to predict time it will take (or recalc on the fly) to hit target value (middle number between min and max thresholds).
#takes relayID info, metric at time of activation, re-detect 5 minutes later and measure change.  If no change run again until there is a change but send a panic email on fail of 2 re-detection predictions.  If the number changes at all a prediction can be made so for no prediction to occur would mean data is missing.  
# 10/25/15 

# Determine auotmatically based on sensorID which relay needs activation and monitoring.<br>
# This may require an additional field on the sensor table that contains a hardwareID for the relay (lspci/lsusb grepped for ID# and then hardwareID extracted<br>

#takes one parameter - the thresholdeventID#
	#echo "<br>booExistingEvents=" . $booExistingEvents; echo "<br>";
function fnGetNewEvents() 
{ 
	$GetNewEventsSQL = "SELECT `intThresholdEventID` FROM `tblthresholdevents` WHERE ((booIsNew>=1));";
	#$fnGNESQL = "SELECT `intThresholdEventID` FROM `tblthresholdevents` WHERE ((booIsNew>=1))";
	echo "<br>GetNewEventsSQL: " . $GetNewEventsSQL;
	$linkgnea = mysqli_connect('localhost', 'root', '', 'climatecontroldata');  
  	$GetNewEvents = mysqli_query($linkgnea , $GetNewEventsSQL);
	$rsNewRows = mysqli_num_rows($GetNewEvents);
  	$rsNewEvents = mysqli_fetch_assoc($GetNewEvents);
	#$rsNewEvents = mysqli_fetch_array($NewEvents, MYSQL_ASSOC);
 
	echo "num of new records found:  " . $rsNewRows;
	# printf ("%s (%s)\n", $rsNewRows["intThresholdEventID"]);
	#$gnedata[]=$rsNewEvents;
	echo mysqli_error($linkgnea);
	#die();
	#if (isset($rsNewRows) AND $rsNewRows >= 1) 
	#{
			echo "in dispatch loop";
				#$booExistingEvents = 1; 
		echo "num of new records found:  " . $rsNewRows;
		#foreach($GetNewEvents as $rsNewRows) 
		#$i = 0;
	#while ($i <= $rsNewRows) {
	#	if ($i < $rsNewRows) 
	#for($i=0; $i < $rsNewRows; $i=$i+1) {
			#looping like a bitch, *sigh*
			#$rsfnGED = mysqli_fetch_assoc($EventData);
				#$GNEkeys = @array_keys($rsNewEvents['intThresholdEventID']);
				#
				#Fuck it, not cycling to the next record
				# Will either spam out and go ham looping forever
				# or it wont get any data  i think the issue is we have multi columned data coming thorugh
				# with multiple rows
				# i mean technically even if it only updates one at a time -- it will probably be running every `10-15 seconds.
				# okay got it back running twice to update 1 record?!
				# will need to look into this more later, moving on for now.
				#10/26/15 
				for($i=0; $i <= 1; $i++) {
					echo "<b><b>i is" . $i;
				#f ($i > $rsNewRows) { die(); }
				$GNEretVal = $rsNewEvents['intThresholdEventID'];	
				#$GNEretVal = $rsNewEvents[$i];	
				#$retVal2 = $rsNewEvents[$i];
				echo "<br>KKK i= " . $i;
				echo "<br>GNEretVal = " . $GNEretVal;
				#$i = $i + 1;
				echo "<br>detected and dispatched $rsNewEvents=" . $GNEretVal;
				$booClearFlagTrue = fnClearNewFlag($GNEretVal);
				$intThresholdEventID = $GNEretVal;
				#$intThresholdEventID = $GNEretVal;
				fnFlagEventInProccess($intThresholdEventID, 0);
				echo "<br> fnFixClimate(" . $intThresholdEventID . ");";
				echo "<br>fnFlagEventInProccess(" . $intThresholdEventID . ",0)";
				$retVal1 = fnFixClimate($intThresholdEventID);
				#$i++;
				echo "<br> " . $intThresholdEventID;
				}
				#mysqli_next_result($linkgnea);
		
					echo "OIUAUHSDSIUADHADSD";
	
				echo "KKKKKKKKKKKKKOOOOOOOOOOO";
			
		#AAA111
	
}
	
	
if (!isset($_GET["evtid"]) OR $_GET["evtid"] <= 0) 
{
	fnGetNewEvents();
}
else 
{	
#Code below clears new flag from eventthreshold table	
	echo "<br>intThreholdEventID = " . $intThresholdEventID . "<br>Date/Time = " . dtStamp();
	$flagsCleared = fnClearNewFlag($intThresholdEventID);
	echo "<br> Attempting to Clear EventID booIsNew Flag on EventID" . $intThresholdEventID . "<br>" . $flagsCleared . " flags were reported cleared.";
	
	
	# $strFixStatus = fnFixClimate($intThresholdEventID);
}


?>