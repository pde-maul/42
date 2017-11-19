<?php
header('Location: http://e2r3p6.42.fr:8080/Day04/ex04/index.html');
if ($_POST['login'] == "" || $_POST['oldpw'] == "" || $_POST['newpw'] == "" || $_POST['submit'] != "OK")
{
	echo "ERROR\n";
	return(FALSE);
}
$log = $_POST['login'];
$newpw = hash('whirlpool', $_POST['newpw']);
$oldpw = hash('whirlpool', $_POST['oldpw']);

if (file_exists("../private/passwd"))
{
	$private = unserialize(file_get_contents("../private/passwd"));
	foreach ($private as &$elem)
	{
		if($elem['login'] == $log)
		{
			if($elem['passwd'] == $oldpw)
			{
				$elem['passwd'] = $newpw;
				file_put_contents("../private/passwd", serialize($private));
				echo "OK\n";
				return(TRUE);
			}
			else
			{
				echo "ERROR\n";
				return(FALSE);
			}
		}
	}
}
echo "ERROR\n";
return(FALSE);
?>
