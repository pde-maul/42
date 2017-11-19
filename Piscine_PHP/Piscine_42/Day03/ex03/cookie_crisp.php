<?php
	$action = $_GET['action'];
	// $name = $_GET['name'];
	// $value = $_GET['value'];

	switch ($action) {
		case "set":
			// setcookie($name, $value, (time() + 360));
			// setcookie("TestCookie", $value, time()+3600);
			if($_GET["name"] && $_GET["value"])
				setcookie($_GET["name"], $_GET["value"], time() + 3600);
			else
				echo "bad action/name/value\n";
			break;

		case "get":
			if (isset($_COOKIE[$_GET["name"]]))
				echo $_COOKIE[$_GET["name"]];
			else
				echo "bad cookie\n";
			break;

		case "del":
			setcookie($_GET["name"], NULL, (time() - 3600));
			break;
	}

?>
