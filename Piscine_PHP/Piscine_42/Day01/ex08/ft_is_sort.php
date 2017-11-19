#!/usr/bin/php
<?php

	function ft_is_sort($tab){
		$sorted = $tab;
		sort($sorted);

		$ret = true;
		foreach ($sorted as $key=>$value){
			if ($value != $tab[$key])
				return $ret = false;
		}
		return $ret;
	}

 ?>
