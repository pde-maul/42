<?php
session_start();
if ($_SESSION['loggued_on_user'] != NULL)
{
	if ($_SESSION['loggued_on_user'] === "")
		echo "ERROR\n";
	else
		{
			echo $_SESSION['loggued_on_user'];
			echo "\n";
		}
}
else
	echo "ERROR\n";
?>
