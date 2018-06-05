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
		echo "$key : ". $value["Temp"] ." °C on " . $value["Time"] . "\n";
	}

 	echo "\n------------------------------------------\n";
	echo "---------------sleep----------------------\n";
	echo "------------------------------------------\n";
	sleep(60);
}


?>
