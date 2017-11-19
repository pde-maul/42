<?php
	header('content-type: text/html');

	// $str = base64_encode( file_get_contents("../img/42.png"));

	// echo ("$str\n");
	$user = $_SERVER['PHP_AUTH_USER'];
	$pass = $_SERVER['PHP_AUTH_PW'];
	if($user == "zaz" && $pass == "jaimelespetitsponeys")
	{
		header('Content-Type: text/html');

?>
<html><body>
Bonjour Zaz<br />
<img src='data:img/png;base64,<?php echo base64_encode( file_get_contents("../img/42.png")) ?>' </br>
</body></html>
<?php
	}
	else
	{
		header('HTTP/1.0 401 unauthorized');
		header('WWW-Authenticate: Basic realm=‘’Espace membres’‘');
		header('Connection: close');
?>
<html><body>Cette zone est accessible uniquement aux membres du site</body></html>
<?php
	}
 ?>
