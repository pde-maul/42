<?php
header('Location: http://e2r3p6.42.fr:8080/Day04/ex04/index.html');
	if (($_POST['login'] == "" || $_POST['passwd'] == "") || $_POST['submit'] != "OK")
	{
		echo "ERROR\n";
		return(FALSE);
	}
	$log = $_POST['login'];
	$password = hash('whirlpool', $_POST['passwd']);
	if (file_exists("../private") == FALSE)
		mkdir("../private", 0777);
	if (file_exists("../private/passwd"))
	{
		$private = unserialize(file_get_contents("../private/passwd"));
		foreach ($private as $elem)
		{
			if($elem['login'] == $log)
			{
				echo "ERROR\n";
				return(FALSE);
			}
		}
	}
	$subtab['login'] = $log;
	$subtab['passwd'] = $password;
	$private[] = $subtab;
	file_put_contents("../private/passwd", serialize($private));
	echo "OK\n";
	return(TRUE);
?>
