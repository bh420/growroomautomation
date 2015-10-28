<?php

include ('db112345b.php');
include ('base2.php');
date_default_timezone_set('EST');

function fnFlagEventInProccess($intThresholdEventID, $booFlagorUnflag) {
	echo "<br>UUU<br>xx<br> in FlagEventInProcess function";
# Depending on val of booFlagorUnflag, we will either set either to true or false
	if ($booFlagorUnflag == 0) {
		$sqlInProcess = "UPDATE `tblthresholdevents` SET `booInProcess`=1 WHERE intThresholdEventID=" . $intThresholdEventID;
	} else {
		$sqlInProcess = "UPDATE `tblthresholdevents` SET `booInProcess`=0 WHERE intThresholdEventID=" . $intThresholdEventID;
	}
	
	$linkevtinproc = mysqli_connect('localhost', 'root', '', 'climatecontroldata');
  	$EventInProccess = mysqli_query($linkevtinproc , $sqlInProcess, MYSQLI_USE_RESULT);
	echo "<br>" . mysqli_error($linkevtinproc);	
	$rsEventInProcess = @mysqli_fetch_assoc($ThreshEvtIns);
	$rsNumIPRows = @mysqli_affected_rows($EventInProcess);
	$booFlagSuccess = 0;
	
	if ($rsNumIPRows > 0) {
		$booFlagSuccess = 1;
	}
	
	mysqli_close($linkevtinproc);
	return $booFlagSuccess;
}

function fnGetEventData($intThresholdEventID, $intColIndex) {
	#$intSensorID = fnGet
	#if (!Is_Null($intSensorID)) {
	#$fnGEDSQL = "SELECT * FROM `tblthresholdevents` WHERE ((intThresholdEventID=" . $intThresholdEventID . ") AND booInProcess=1) OR ((intThresholdEventID=" . $intThresholdEventID . ") AND booIsNew=1)";
	$fnGEDSQL = "SELECT * FROM `tblthresholdevents` WHERE ((intThresholdEventID=" . $intThresholdEventID . "))";
# AND booInProcess=1) OR ((intSensorID=" . $intSensorID . ") AND booIsNew=1)";
echo "<br>!?!?!? fnGEDSQL: " . $fnGEDSQL;
	$linkged = mysqli_connect('localhost', 'root', '', 'climatecontroldata');  
  	$EventData = mysqli_query($linkged , $fnGEDSQL);
  	#$rsEventData = mysqli_fetch_assoc($EventData);
  	$intResCount = @mysqli_num_rows($EventData);
	$intCols = mysqli_field_count($linkged);
	
	if ($intColIndex <= $intCols) {
		# echo "<br>Total Cols: " . $intCols , "<br>Total Results: " . $intResCount;
		# $rsfnGEDarr = mysqli_fetch_array($EventData);
		# $GEDretVal = $rsfnGEDarr[1][1];
		$rsfnGED = mysqli_fetch_array($EventData, MYSQL_ASSOC);
		
		#printf("%s (%s)\n", $rsfnGED[0],$rsfnGED[4]);
		#$row = mysqli_fetch_row($EventData); 
		$keys = @array_keys($rsfnGED);
		$GEDretVal = $rsfnGED[$keys[$intColIndex]];		
	} else {
		echo "Requested ColumnID does not exist";
		die();
	}
	mysqli_close($linkged);
	return $GEDretVal;	
	}
#}

function fnFixClimate($intThresholdEventID) {
#Looks up Sensor info for ThresholdEvent (including Thresholds and RelayMapping)
#Looks up appropriate Relay to activate via RelayMapping table for that particular sensor
#More TBD (do we run shell script w/ parameters to activate relay off PLC board)
#	$booFlagSuccess = fnFlagEventInProccess($intThresholdEventID, 0);
	$intSensorID = fnGetEventData($intThresholdEventID, 1);
	$intRelayID = fnGetRelayNum($intSensorID);
	
	
	return @($strFixResult);
}

function fnClearNewFlag($intThresholdEventID) {
	#change booIsNew to false for provided ThreholdEventID Number
	$sqlNotNew = "UPDATE `tblthresholdevents` SET `booIsNew`=0 WHERE intThresholdEventID=" . $intThresholdEventID;
	$linkclearflag = mysqli_connect('localhost', 'root', '', 'climatecontroldata');
  	$ThreshEvtIns = mysqli_query($linkclearflag , $sqlNotNew, MYSQLI_USE_RESULT);
	echo "<br>" . mysqli_error($linkclearflag);	
	$rsThreshEvtIns = @mysqli_fetch_assoc($ThreshEvtIns);
	$rsNumEvtRows = @mysqli_num_rows($ThreshEvtIns);
echo "<br>sqlNotNew: " . $sqlNotNew;
// THE STUPID EventThresholdID is not getting its new flag cleared.  Needs troubleshooting
	 if (!isset($intThresholdEventID) OR $intThresholdEventID <= 0) {
		 echo "Missing Value to clear ThresholdEventID = " . $intThresholdEventID;
	} 
		$rsNumRowsTE = mysqli_affected_rows($linkclearflag);
		echo "affected row count is: " . $rsNumRowsTE;
	if ($rsNumRowsTE <= 0) {
		#$ThreshEvtIns = mysqli_fetch_assoc($rsThreshEvtIns);
    	# $rsNumEvtRows = mysql_num_rows($ThreshEvtIns);
		echo "No Affected Rows!";	
	}
		
	return $rsNumRowsTE;	
  	mysqli_close($linkclearflag);

}

function fnChkSensorTH($intSensorID, $booMinorMax) { 
# Function that will return the Thresholds for a specific Sensor
# If booMinorMax = 0 then we return the min, if = 1 then we return the max
	$intSensorTypeID = fnGetSensorType($intSensorID);
	$linksensTH = mysqli_connect('localhost', 'root', '', 'climatecontroldata');
 	$SensorThresholdSQL = "SELECT * FROM `tblsensorthresholds` WHERE intSensorID=" . $intSensorID;
  	$SensorThreshold = mysqli_query($linksensTH, $SensorThresholdSQL);
  	$rsSensorThreshold = mysqli_fetch_assoc($SensorThreshold);
  	$rsNumRows = mysqli_num_rows($SensorThreshold);
	echo "rsNumRows when looking up SensorThresholds by SensorID (not type) is =" . $rsNumRows;
  	if (isset($rsNumRows) AND $rsNumRows == 0)
  	{
	  	echo "IN SENSORTYPE THRESHOLD LOOKUP LOOP";

		unset($SensorThresholdSQL);
		unset($SensorThreshold);
		$SensorThresholdSQL = "SELECT * FROM `tblsensorthresholds` WHERE intSensorTypeID=" . $intSensorTypeID;
		echo "<br> " . $SensorThresholdSQL;
		$SensorThreshold = mysqli_query($linksensTH, $SensorThresholdSQL);
		$rsSensorThreshold = mysqli_fetch_assoc($SensorThreshold);
		$rsNumRows = mysqli_num_rows($SensorThreshold);
		echo "ASDJASD " . $rsNumRows;
	} 
	if ($booMinorMax == 0) {
		$retVal = $rsSensorThreshold['intMinVal'];
	
	} else {
		$retVal = $rsSensorThreshold['intMaxVal'];
	}
	mysqli_close($linksensTH);
	echo "<br>returning $retVal=" . $retVal . " From fnChkSensorTH";
	return $retVal;
}

function fnGetSensorType( $intSensorID ) {
	$stlink = mysqli_connect('localhost', 'root', '', 'climatecontroldata');
	$SensorTypeID = mysqli_query($stlink, "SELECT * FROM `tblsensors` WHERE intSensorID=" . $intSensorID,MYSQLI_USE_RESULT);
  	$rsSensorTypeID = mysqli_fetch_assoc($SensorTypeID);
  	$intSensorTypeID = $rsSensorTypeID['intSensorTypeID'];
  	mysqli_close($stlink);
  
  	return $intSensorTypeID;
}

function fnChkSensorValue( $intSensorID, $intValue) {


	
# OBTAIN SENSOR TYPE INFO

  $intSensorTypeID = fnGetSensorType($intSensorID);

# LOOK UP MIN AND MAX VALUES FOR THAT SENSOR (NOT LOOKING AT TYPE YET...)
# IF NULL IT WILL CHECK AND OBTAIN SENSOR TYPE DATAS

  $intMinVal = fnChkSensorTH($intSensorID, 0);
  $intMaxVal = fnChkSensorTH($intSensorID, 1);
echo "<br>intMinVal=" . $intMinVal . " and intMaxVal= " . $intMaxVal;	
if (($intValue >= $intMinVal) && ($intValue <= $intMaxVal)) {
#!!! Value is larger than the minimum andsmaller than the max -- ITS IN BOUNDS!
	fnLogClimateData($intSensorID, $intValue);
	} 
else {
#!!! IF OUT OF BOUNDS CREATE THRESHOLDEVENT -- IF NOT LOG DATA PER NORMAL
	fnThresholdEvt($intSensorID, $intValue);
	fnLogClimateData($intSensorID, $intValue);
	}
}

function dtStamp () {
	$curDT = date("m/d/Y ");
	$curDT = $curDT . (date("G") + 1);
	$curDT = $curDT . date(":i:s e");
	
	return $curDT;
}

function fnThresholdEvtVerify($intSensorID, $intValue) {
#This was written to verify we aren't re-detecting a previously noticed problem.  It looks at sensor in trouble and reviews ThresholdEvent for matching sensor that booInProcess = 1
echo "<br>IN fnThresholdEvtVerify() function, intSensorID=" . $intSensorID . " and value is " . $intValue;
	$fnTEVSQL = "SELECT * FROM `tblthresholdevents` WHERE ((intSensorID=" . $intSensorID . ") AND booInProcess=1) OR ((intSensorID=" . $intSensorID . ") AND booIsNew=1)";
echo "<br>fntEVSQL: " . $fnTEVSQL;
	$linkevtverify = mysqli_connect('localhost', 'root', '', 'climatecontroldata');  
  	$ThresholdEvtVerification = mysqli_query($linkevtverify, $fnTEVSQL);
  	$rsThresholdEvtVerification = mysqli_fetch_assoc($ThresholdEvtVerification);
  	$rsNumEvtVerifRows = mysqli_num_rows($ThresholdEvtVerification);
	if (isset($rsNumEvtVerifRows) AND $rsNumEvtVerifRows >= 1) { $booThresholdEvtExists = 1; }
	else { $booThresholdEvtExists = 0; }

	mysqli_close($linkevtverify);
	echo "<br>booThresholdEvtExists=" . $booThresholdEvtExists; echo "<br>";
	return $booThresholdEvtExists;
}

function fnGetExistingEvents($intSensorID, $intValue) {
#This was written to verify we aren't re-detecting a previously noticed problem.  It looks at sensor in trouble and reviews ThresholdEvent for matching sensor that booInProcess = 1
echo "<br>IN fnThresholdEvtVerify() function, intSensorID=" . $intSensorID . " and value is " . $intValue;
	$fnGEESQL = "SELECT * FROM `tblthresholdevents` WHERE ((intSensorID=" . $intSensorID . ") AND booInProcess=1)";
echo "<br>fntEVSQL: " . $fnGEESQL;
	$linkexistevt = mysqli_connect('localhost', 'root', '', 'climatecontroldata');  
  	$ExistingEvtVerification = mysqli_query($linkexistevt , $fnGEESQL);
  	$rsExistingEvtVerification = mysqli_fetch_assoc($ExistingEvtVerification);
  	$rsNumEvtVerifRows = mysqli_num_rows($ExistingEvtVerification);
	echo mysqli_error($linkexistevt);
	echo "AAA  " . $rsNumEvtVerifRows;
	if (isset($rsNumEvtVerifRows) AND $rsNumEvtVerifRows >= 1) { $booExistingEvents = 1; }
	else { $booExistingEvents = 0; }

	mysqli_close($linkexistevt) ;
	echo "<br>booExistingEvents=" . $booExistingEvents; echo "<br>";
	return $booExistingEvents;
}

function fnGetExistingEventID($intSensorID) {
	$fnGEESQL = "SELECT * FROM `tblthresholdevents` WHERE ((intSensorID=" . $intSensorID . ") AND booInProcess=1)";
echo "<br>fntEVSQL: " . $fnGEESQL;
	$linkexistevt = mysqli_connect('localhost', 'root', '', 'climatecontroldata');  
  	$ExistingEvtVerification = mysqli_query($linkexistevt , $fnGEESQL);
  	$rsExistingEvtVerification = mysqli_fetch_assoc($ExistingEvtVerification);
  	$rsNumEvtVerifRows = mysqli_num_rows($ExistingEvtVerification);
	echo mysqli_error($linkexistevt);
	echo "AAA  " . $rsNumEvtVerifRows;
	if (isset($rsNumEvtVerifRows) AND $rsNumEvtVerifRows >= 1) { $booExistingEvents = 1; }
	else { $booExistingEvents = 0; }

	mysqli_close($linkexistevt) ;
	echo "<br>booExistingEvents=" . $booExistingEvents; echo "<br>";
	$EEIDretVal = $rsExistingEvtVerification['intThresholdEventID'];
	return $EEIDretVal;
}

function fnThresholdEvt($intSensorID, $intValue) {
	# CHANGE -- Should I be inserting the ClimateData first.  Then using the SensorDataID number tying0to the threshold event?  If so will need to modify table schema allowing nulls, fill in null values, then remodify schema disabling nulls on SensorDataID field.
	

	$booThresholdEvtExists = fnThresholdEvtVerify($intSensorID, $intValue);
	echo "In fnThresholdEvt()";
	if ($booThresholdEvtExists == 0) {
#Added above variable/function 10/25/15
	echo "$booThresholdEvtExists=" . $booThresholdEvtExists . "<br> Proceed w/ Inserting as new ThresholdEvent";
		$sqlThreshEvent = "INSERT INTO tblThresholdEvents(intSensorID, intValue, strDateTime, booIsNew, booInProcess) VALUES(" . $intSensorID . ", " . $intValue . ", '" . dtStamp() . "', 1, 0)" ;
		$linkthreshevt = mysqli_connect('localhost', 'root', '', 'climatecontroldata');
  		$ThreshEventIns= mysqli_query($linkthreshevt, $sqlThreshEvent, MYSQLI_USE_RESULT);
  		mysqli_close($linkthreshevt);
	}
 
}

function fnLogClimateData($intSensorID, $intValue) {
	$sqlClimateData = "INSERT INTO tblsensordata(intSensorID, intDataValue, strDateTime) VALUES(" . $intSensorID . ", " . $intValue . ", '" . dtStamp() . "')" ;

  	$linkcd = mysqli_connect('localhost', 'root', '', 'climatecontroldata');
  	$ClimateDataIns= mysqli_query($linkcd, $sqlClimateData, MYSQLI_USE_RESULT);
	mysqli_close($linkcd);
}

function fnGetRelayNum($intSensorID) {
	$relaySQL = "SELECT * from `tblrelaysensormap` WHERE `intSensorID`=" . $intSensorID;
	$linkgrn = mysqli_connect('localhost', 'root', '', 'climatecontroldata');
	$RelayMap = mysqli_query($linkgrn, $relaySQL, MYSQLI_USE_RESULT);
	$rsRelayMap = mysqli_fetch_assoc($RelayMap);
	echo "<br>5555<br>relaySQL: " . $relaySQL;
	$grnRetVal = $rsRelayMap['intRelayOutput'];
	
	echo "fnGetRelayNum: " . $grnRetVal;
	
	return $grnRetVal;
}
?>