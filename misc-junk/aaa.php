function fnGetNewEvents() { 
	$fnGNESQL = "SELECT * FROM `tblthresholdevents` WHERE ((booIsNew=1))";
	echo "<br>fntEVSQL: " . $fnGEESQL;
	$linkgne = mysqli_connect('localhost', 'root', '', 'climatecontroldata');  
  	$NewEvents = mysqli_query($linkgne , $fnGNESQL);
  	$rsNewEvents = mysqli_fetch_array($NewEvents, MYSQL_BOTH );
  	$rsNewRows = mysqli_num_rows($NewEvents);
	$gnedata[]=$rsNewEvents;
	echo mysqli_error($linkgne);


	if (isset($rsNewRows) AND $rsNewRows >= 1) { 
		#$booExistingEvents = 1; }
		echo "num of new records found:  " . $rsNewRows;
		foreach($rsNewEvents as $gnedata) {
			if (!empty($data)) {
				echo "<br>detected and dispatched intThresholdEventID=" . $intThresholdEventID;
				$intThresholdEventID = $datagne['intThresholdEventID'];
				$retVal1 = fnFixClimate($intThresholdEventID);
			}
		 	else { 
				#$booExistingEvents = 0; }
				echo "No new events";
			}
	
		}
}