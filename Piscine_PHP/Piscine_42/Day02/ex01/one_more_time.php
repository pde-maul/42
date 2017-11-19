#!/usr/bin/php
<?php

$jours = array('/[Ll]undi /', '/[Mm]ardi /', '/[Mm]ercredi /', '/[Jj]eudi /', '/[Vv]endredi /', '/[Ss]amedi /', '/[Dd]imanche /');

$mois = array('/[Jj]anvier/', '/[Ff][ée]vrier/', '/[Mm]ars/', '/[Aa]vril/', '/[Mm]ai/', '/[Jj]uin/', '/[Jj]uillet/', '/[Aa]o[uû]t/', '/[Ss]eptembre/', '/[Oo]ctobre/', '/[Nn]ovembre/', '/[Dd][eé]cembre/');

$month = array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');

if ($argv[1])
{
	$str = $argv[1];
	$str = preg_replace($mois, $month, $str);
	$str = preg_replace($jours, '', $str);
	date_default_timezone_set("Europe/Paris");
	$time = strtotime($str, time());
	if ($time)
		print $time;
	else {
		print "Wrong Format\n";
	}
}
else
	print "error2\n";
?>
