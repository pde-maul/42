#!/usr/bin/php
<?php
function do_op($nb1, $op, $nb2)
{
	$op = trim($op);
	$nb1 = trim($nb1);
	$nb2 = trim($nb2);
	$res = 0;
	if (is_numeric($nb1) == FALSE or is_numeric($nb2) == FALSE)
		return (print("error\n"));

	if ($op == "+")
	{
		$res = $nb1 + $nb2;
		echo "$res\n";
	}
	else if ($op == "-")
	{
		$res = $nb1 - $nb2;
		echo "$res\n";
	}
	else if ($op == "*")
	{
		$res = $nb1 * $nb2;
		echo "$res\n";
	}
	else if ($op == "/")
	{
		if ($nb2 == 0)
			return (print("error\n"));
		else
		$res = $nb1 / $nb2;
		echo "$res\n";
	}
	else if ($op == "%")
	{
		$res = $nb1 % $nb2;
		echo "$res\n";
	}
	else
		return (print("error\n"));
}

	if ($argc != 4)
		return (print "Incorrect Paremeters");
	else
		do_op($argv[1], $argv[2], $argv[3]);
?>
