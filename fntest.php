<?php
include ('base.php');
$intThresholdEventID = $_GET['evtid'];
$intSensorID = fnGetEventData($intThresholdEventID, 1);
#echo "DIE but here is intSensorID var" . $intSensorID; die();
#echo "<br>!<br>!<br>fnChkSensorTH: " . fnChkSensorTH(14,0);
$intMinValTH = fnChkSensorTH($intSensorID,0);
$intMaxValTH = fnChkSensorTH($intSensorID,1);
$intMedValTH = (round((($intMaxValTH - $intMinValTH) / 2),2) + $intMinValTH);
$intRelayID = fnGetRelayNum($intSensorID);
$intOrigValue = fnGetEventData($intThresholdEventID, 3);

echo "<br>" . $intSensorID . " - " . $intMinValTH . " - " . $intMaxValTH . " - " . $intMedValTH . " - " . $intOrigValue;
echo "<br><br>!!! - fnGetEventData($intThresholdEventID, 1): " . $intSensorID;
echo "<br>Relay " . $intRelayID;
?>