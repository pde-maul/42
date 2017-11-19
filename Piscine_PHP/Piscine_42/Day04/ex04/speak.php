<?php
session_start();

if ($_SESSION['loggued_on_user'] == "")
	return(FALSE);
if ($_POST['submit'] == "ENVOYER")
{
	if( file_exists("../private/chat") == FALSE)
	{
		$subarray = ['login'] = $_SESSION['loggued_on_user'];
		$subarray = ['time'] = time();
		$subarray = ['message'] = $_POST['message'];
		$chat[] = $subarray;
		file_put_contents("../private/chat", serialize($chat));
	}
	else
	{
		$fd = fopen("../private/chat", "c+");
		flock($fd, LOCK_EX | LOCK_SH);

	}
}

 ?>

<html>
<head></head>
<body>
	<form method="POST" action="speak.php">
		<input type="text" name="msg" value ="" />
		<input type="submit" name="submit" value="ENVOYER">
	</form>

</body>
</html>
