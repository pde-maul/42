<?php
include ('auth.php');
session_start();

$login = $_POST['login'];
$passwd = $_POST['passwd'];
$pass = hash('whirlpool', $passwd);
if(auth($_POST['login'], $_POST['passwd']) == TRUE)
{
		$_SESSION['loggued_on_user'] = $_POST['login'];
		?><html><body>
			<iframe src="chat.php"
				width="320"
				height=550px;
				style="border:2px solid orange">
			</iframe>
			<br/>
			<iframe src="speak.php"
				width="320"
				height=50px;
				style="border:2px solid orange">
			</iframe>
			</html></body><?php
}
else
{
	$_SESSION['loggued_on_user'] = "";
	echo "ERROR\n";
}
?>
