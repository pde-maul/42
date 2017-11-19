#!/usr/bin/php
<?php

function ft_split ($str)
{
	$exp = explode(' ', $str);
	$tab = array_filter($exp);
	sort($tab);
	return($tab);
}

	// print_r(ft_split($argv[1]));

 ?>
