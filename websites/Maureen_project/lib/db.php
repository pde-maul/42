<?php
// connection a la base de donnees avec message d'erreur, et affichage des erreurs
	try{
		$db = new PDO('mysql:host=localhost;dbname=site', 'root', 'root');
		$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
	}catch (Exception $e){
		echo 'Impossible de se connecter à la base de données ';
		echo $e->getMessage();
		die();
	}
// fin de connection a la base de donnees
?>
