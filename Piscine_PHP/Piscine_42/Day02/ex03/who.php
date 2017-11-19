#!/usr/bin/php
<?php

date_default_timezone_set('Europe/paris');
$usr = get_current_user();
$file = file_get_contents("/var/run/utmpx");
$sub = substr($file, 628);
$arr = array();

while ($sub != NULL)
{
	$array = unpack('a256user/a4id/a32line/ipid/itype/I2time/a256host/i16pad', $sub);

	if (strcmp(trim($array[user]), $usr) == 0 && $array[type] == 7)
	{
		$date = date("M j H:i", $array["time1"]);
		$line = trim($array[line]);
		$line = $line . "  ";
		$usrr = trim($array[user]);
		$usrr = $usrr . " ";
		$tab = array($usrr.$line.$date);
		$arr = array_merge($arr, $tab);
	}
	$sub = substr($sub, 628);
}
sort($arr);

foreach ($arr as $elem) {
	echo $elem;
	echo "\n";
}

?>
