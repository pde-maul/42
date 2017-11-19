<?php
$link = mysqli_connect("localhost", "root", "root");

$sql = 'CREATE DATABASE ru';

if (mysqli_query($link, $sql))
{
$mysqli =  @new Mysqli("localhost", "root", "root", "ru");

// On se connecte et on crée un objet mysqli
// Ici l'@ nous permet de gérer nous même l'erreur s'il y en a une.
mysqli_query($mysqli , "SET NAMES UTF8");
 /* Modification du jeu de résultats en utf8 */
 if (!mysqli_set_charset($mysqli , "utf8")) {
    printf("Erreur lors du chargement du jeu de caractères utf8 : %s\n", mysqli_error($mysqli));
}

if(mysqli_connect_error())
{
		die('Un problème est survenu lors de la tentative de connexion à la BDD');
}

$link = mysqli_connect("localhost", "root", "root", "ru");
$test = dirname(__FILE__);
preg_match_all("/MyWebSite\/(.*?)\/inc/", $test, $tab);
$file = $tab[1][0];
$file = "http://localhost:8080/".$file."/";
$query = file_get_contents($file."rush00_23h00.sql");

    $array = explode(";\n", $query);
    for ($i=0; $i < count($array) ; $i++) {
        $str = $array[$i];
        if ($str != '') {
             $str .= ';';
              mysqli_query($link, $str);
        }
    }
}
else {

	$mysqli =  @new Mysqli("localhost", "root", "root", "ru");

	// On se connecte et on crée un objet mysqli
	// Ici l'@ nous permet de gérer nous même l'erreur s'il y en a une.
	mysqli_query($mysqli , "SET NAMES UTF8");
	 /* Modification du jeu de résultats en utf8 */
	 if (!mysqli_set_charset($mysqli , "utf8")) {
	    printf("Erreur lors du chargement du jeu de caractères utf8 : %s\n", mysqli_error($mysqli));
	}

	if(mysqli_connect_error())
	{
			die('Un problème est survenu lors de la tentative de connexion à la BDD');
	}
}
// Avec le DIE, on lui demande de ne rien afficher excepté la phrase donnée ci-dessus.
