<?php
# base2.php
# just more unorganized code but I am splitting it up in an effor to stop screwing up existing work. 
# New functions will be here as of 10/26/15
function fnGetValueChangeDir($intThresholdEventID) {
	$intOriginalEventValue = fnGetEventData($intThresholdEventID, 4); # I think 4 is the right column for captured value of event
	#tblSensorData.Value Where tblSensorData.SensorID=tblThresholdEvents.SensorID
	# SORT DESC BY tblSensorData.DataID TOP 1
	# if original value > current value, return a 0, if the original value was less than the current value then return a 1
	# if we want a true historical view on this we could look for the SensorData entry that corresponded with the same TimeStamp as the<br>
	# thresholdevent that was logged
	
	
	return booChangeDir; #0 is a negative change was needed to get back into target levels, a 1 means a positive change was needed
}
function fnRelaySwitch($intRelayOutput, $booRelayFlag) {
	#Activate or De-Activate relayoutput based on mapping
	#Method of switch activation may either be direct control through USB injected command or be posted on a webserver in a csv parsable format and a cron or timed job where PLC will request the page, grab the relay number to activate, then activate it.  Direct-Control would be preferable
	return $booRelayFlag;
}

?>