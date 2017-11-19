#!/usr/bin/php
<?php

	function ft_is_alpha($str)
	{
			$ascii = ord($str[0]);
			if (($ascii >= 65 and $ascii <= 90) or ($ascii >= 97 and $ascii <= 122))
				$ret = $ret + 0;
			else
				$ret = $ret + 1;
		if ($ret == 0)
			return(1);
		else
			return (0);
	}

	function epur ($str)
	{
		$str = trim($str);
		while ($str != str_replace('  ', ' ', $str))
			$str = str_replace('  ', ' ', $str);
		return $str;
	}

	if ($argc != 1)
	{
		foreach (array_slice($argv, 1) as $elem)
		{
			$tab = epur($elem);
			if ($tab2)
				$tab2 = $tab2.' '.$tab;
			else
				$tab2 = $tab;
		}

	$tab2 = explode(' ', $tab2);
	$tab2 = array_filter($tab2);

		foreach ($tab2 as $elem)
		{
			if (ft_is_alpha($elem))
				$alpha[] = $elem;
			else if (is_numeric(($elem)))
				$num[] = $elem;
			else
				$spe[] = $elem;
		}
		natcasesort($alpha);
		sort($num, SORT_STRING);
		natcasesort($spe);

		foreach ($alpha as $line)
			print "$line\n";
		foreach ($num as $line)
			print "$line\n";
		foreach ($spe as $line)
			print "$line\n";
	}

?>
