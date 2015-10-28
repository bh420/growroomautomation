<?php
include ('base.php');

$intSensorID = $_GET["sid"];
$sensorValue = $_GET["value"];

#Lookup Sensor w/ SensorID passed
#Verify if individual threshhold is set for sensor, if not look at sensor type threshhold
#Record threshold min/max values into memory then make sure the $sensorValue falls between them.
# 10/25/15 - 

$booExistingEvents =  fnGetExistingEvents($intSensorID, $sensorValue);
if ($booExistingEvents == 1) {
	echo "in sensor.php with InProcess Threshold Event for SensorID";
	fnLogClimateData($intSensorID, $sensorValue);
	#We know Event is already dispatched and in process
	#Now we need to monitor its progres and terminate repair if we are by threshold<br>
	#Think we will be better determining whether sensor value was greater than threshold or below... then we know when we pass the median in which direction we are good.
	$intMinValTH = fnChkSensorTH($intSensorID,0);
	$intMaxValTH = fnChkSensorTH($intSensorID,1);
	$intMedValTH = (round((($intMaxValTH - $intMinValTH) / 2),2) + $intMinValTH);
	if ($sensorValue <= (($intMedValTH * .1) + $intMedValTH)) {
		if ($sensorValue >= ($intMedValTH - ($intMedValTH * .1))) {
				echo "Reached Operational Value";
				$intThresholdEventID = fnGetExistingEventID($intSensorID);
				fnFlagEventInProccess($intThresholdEventID, 1);
		}
	}
	echo "<br><br>!SensorVal: " . $sensorValue . " TopVal: " . (($intMedValTH * .1) + $intMedValTH) . " LowVal: " . ($intMedValTH - ($intMedValTH * .1));
		# Trying to be within 10% above or below median value

	
} else {
  if (!isset($sensorValue)) {
	  echo "<br>No Sensor Value Detected!";
  }
  else {
	  fnChkSensorValue($intSensorID, $sensorValue);
	  echo "<br>SensorID = " . $intSensorID . "<br>Sensor Value = " .$sensorValue;
  }
}
?>