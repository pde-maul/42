#!/usr/bin/php
<?php

	$str = $argv[1];
	$rtrim = rtrim($str);
	$str = trim($rtrim);
	$split = explode(" ", $str);
	$res = [];
	foreach($split as $elem)
	{
		if ($elem)
			array_push($res, $elem);
	}
	$i = 0;
	while ($i < count($res))
	{
		if($i != 0)
			print(" ");
		print("$res[$i]");
		$i++;
	}
	print("\n");
 ?>
