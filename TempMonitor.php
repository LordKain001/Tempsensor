<?php


include "GetAllW1SensorTemps.php";




if (!file_exists("/etc/systemd/system/TempMonitor.service"))
{
	passthru("sudo cp -v TempMonitor.service /etc/systemd/system");
}

if (!file_exists("/etc/systemd/system/multi-user.target.wants/TempMonitor.service"))
{
	passthru("sudo cp -v TempMonitor.service /etc/systemd/system/multi-user.target.wants");
}


while (1) {
	
   
	echo "\n------------------------------------------\n";
	echo "---------------alive----------------------\n";
	echo "------------------------------------------\n";

	$temperatures = GetAllW1SensorTemps();
	var_dump($temperatures);
	foreach ($temperatures as $key => $value) {
		echo "$key : ". $value["Temp"] ." °C on " . date("F j, Y, g:i a",$value["Time"]) . "\n";
	}

	SendDataToServer('home.ccs.at:8080/GetW1SensorData.php',$temperatures);


 	echo "\n------------------------------------------\n";
	echo "---------------sleep----------------------\n";
	echo "------------------------------------------\n";
	sleep(60);
}




function SendDataToServer($server = 'home.ccs.at:8080', $jsonData)
{
	$url = $server; 
	//Initiate cURL.
	$ch = curl_init($url);
	 
	 
	//Encode the array into JSON.
	$jsonDataEncoded = json_encode($jsonData);
	//echo "--------------\n" . 'Json-Data' . $jsonDataEncoded . "\n--------------";
	 
	//Tell cURL that we want to send a POST request.
	curl_setopt($ch, CURLOPT_POST, 1);
	 
	//Attach our encoded JSON string to the POST fields.
	curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);

	//Send echos to return var
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	 
	//Set the content type to application/json
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json')); 
	 
	if(curl_exec($ch) === false)
	{
		echo 'Curl-Fehler: ' . curl_error($ch);
	}
	else
	{
		echo "Server erreicht\n";		
	}
	curl_close($ch);
}


?>
