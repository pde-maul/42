#!/usr/bin/php
<?php

function ft_split ($str)
{
	$exp = explode(' ', $str);
	$tab = array_filter($exp);
	sort($tab);
	return($tab);
}

$fused = array();
$i = 1;
while ($i < $argc)
{
	$split = ft_split($argv[$i]);
	$fused = array_merge($fused, $split);
	$i++;
}
asort($fused);
foreach ($fused as $element)
{
	echo $element."\n";
}

 ?>
