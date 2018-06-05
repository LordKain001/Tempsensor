<?php

function GetAllW1SensorTemps()
{
	$w1Sensors = scandir("/sys/bus/w1/devices");
$w1Sensors = array_filter($w1Sensors, "isW1Sensor");
$temperatures = array();	


foreach ($w1Sensors as $key) 
{
	$path = "/sys/bus/w1/devices/" . $key . "/w1_slave";
	$sensorTemp = file_get_contents($path);
	$sensorTemp = substr($sensorTemp, strpos($sensorTemp, "t=") + 2);    
	$temperatures[$key]["Temp"] = $sensorTemp/ 1000;
	$temperatures[$key]["Time"] = time();
}
return $temperatures;
}



function isW1Sensor($value)
{
	$query = "28-";
	if(substr( $value, 0, strlen($query) ) === $query)
	{
		return true;
	}
}

?>