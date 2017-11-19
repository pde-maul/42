#!/usr/bin/php
<?php

// epur_str
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
$str3 = NULL;
while ($i < count($res))
{
	if($i != 0)
		$str2 = $str3 . " ";
		$str3 = $str2 . "$res[$i]";
	$i++;
}
$str2 = $str3;

// split
$exp = explode(' ', $str2);

// strlen
$len = 0;
while($exp[$len]){
	$len++;
}

// rostring
$tmp = $exp[0];
$i = 1;
while($exp[$i]){
	$exp[$i - 1] = $exp[$i];
	$i++;
}
$exp[$len - 1] = $tmp;

//merge tab into sring
$str = implode (" ", $exp);

print_r ($str);

 ?>
