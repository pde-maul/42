<?php

function auth($login, $passwd)
{
	$pass = hash('whirlpool', $passwd);
	$private = unserialize(file_get_contents("../private/passwd"));
	foreach ($private as $elem)
	{
		if ($elem['login'] == $login && $elem['passwd'] == $pass)
			return(TRUE);
	}
	return (FALSE);
}
?>
