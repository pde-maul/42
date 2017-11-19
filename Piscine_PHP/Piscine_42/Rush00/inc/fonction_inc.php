<?php

//**********************************************************************************************************
//                         		FONCTION EXECUTE_REQUETE
//**********************************************************************************************************
		/* Ici on crée la fonction requête pour ne pas avoir à la répéter à chaque fois.
		Cette fonction appelera un argument : la requête ($req)
		Ensuite pour créer la fonction on a besoin d'appeler $mysqli de Global*/
function execute_requete($req)
{
	global $mysqli; // On récupère l'objet mysqli et la met dans une autre fonction.
	$resultat = mysqli_query($mysqli, $req);
	if(!$resultat)  // Si le resultat est FALSE alors on écrit erreur de requête
	{
		die("Erreur de requête. <br /> La requête : $req <br /> Erreur : ". mysqli_error($mysqli));
	}
	return $resultat; // Pas besoin de mettre un ELSE car le DIE arrete tout ce qui vient derrière donc pas besoin.
}

//**********************************************************************************************************
//                         		FONCTION UTILISATEUR_EST_CONNECTE
//**********************************************************************************************************
		/*Cette fonction indique si l'utilisateur est connecté */
function utilisateur_est_connecte()
{
	if(isset($_SESSION['utilisateur']))
		{
			return true; // Si je rentre dans le IF le reste n'est pas executé. Le return fonctionne comme un break. Il s'arrête là.
		}
		return false; // Si je ne rentre pas dans le IF, on passe directement à return false
}

//**********************************************************************************************************
//                         		FONCTION UTILISATEUR_EST_CONNECTE_ET_ADMIN
//**********************************************************************************************************
		/* Cette fonction nous permet de contrôler le statut des personnes qui se connecte. Ici si on a un statut 1 on lui donne des droits d'administrateur. Il pourra donc aller modififier la base de données s'il est connecté.*/
function utilisateur_est_connecte_et_admin()
{
	if (utilisateur_est_connecte() && $_SESSION['utilisateur']['statut'] == 1)
	{
		return true;
	}
	return false;
}

//**********************************************************************************************************
//                         		  	FONCTION DEBUG - PRINT_R
//**********************************************************************************************************
		/* Fonction qui permet de voir le print_r*/
function debug($arg)
{
	echo "<pre>";print_r($arg); echo "</pre>";
}

//**********************************************************************************************************
//                         		   FONCTION CREATION_DU_PANIER
//**********************************************************************************************************
		/* Fonction qui de mettre à jour le panier. On prépare les boites dans lesquelles on va enregistrer les ajouts au panier de l'internaute. Boites qui vont accepter les informations */
function creation_du_panier()
{
	if(!isset($_SESSION['panier']))
	{
		$_SESSION['panier'] = array();
		$_SESSION['panier']['id_produit'] = array();
		$_SESSION['panier']['date_commande'] = array();
		$_SESSION['panier']['date_estimation'] = array();
		$_SESSION['panier']['date_livraison'] = array();
		$_SESSION['panier']['id_promo'] = array();
		$_SESSION['panier']['prix'] = array();
		$_SESSION['panier']['prix_promo'] = array();
		$_SESSION['panier']['quantite'] = array();
		$_SESSION['panier']['pays'] = array();
		$_SESSION['panier']['ville'] = array();
		$_SESSION['panier']['adresse'] = array();
		$_SESSION['panier']['cp'] = array();
		$_SESSION['panier']['titre'] = array();
		$_SESSION['panier']['description'] = array();
		$_SESSION['panier']['photo'] = array();
		$_SESSION['panier']['categorie'] = array();
		$_SESSION['panier']['reduction'] = array();
		$_SESSION['panier']['code_promo'] = array();
	}
}

//**********************************************************************************************************
//                         		   FONCTION AJOUT_ARTICLE_DANS_PANIER
//**********************************************************************************************************
/*Cette fonction aura 4 arguments
ATTENTION ici, si on rentre 2 fois le même article, on veut éviter qu'il y ait 2 lignes différentes donc on met une condition pour vérifier que l'ID_article n'existe pas déjà dans notre fonction. On utilise donc :
array_search(1,2) -> argument 1 = Qu'est ce que je recherche ?, argument 2 = Dans quel array je le recherche ?
array_search permet d'aller chercher une valeur dans un tableau. Il va donner la position de la valeur dans le tableau array
La réponse sera donc entre 0 et nombre d'articles ajoutés. Il détecte la ligne. */
function ajout_produit_au_panier($titre, $id_produit, $photo, $descriptif, $quantite, $categorie, $prix, $prix_promo, $id_promo, $code_promo, $reduction)
{
	$position_article = array_search($id_produit ,$_SESSION['panier']['id_produit']);
	if($position_article !== false)
	// S'il trouve la position donc si le produit en train d'être ajouté au panier est déjà présent dans le panier.
	{
		$_SESSION['panier']['quantite'][$position_article] += $quantite;
		// Ici on incremente le tableau quantité de l'article. Donc si une nouvelle quantité est ajouté à un article déjà ajouté au panier alors on ajoutera simplement la nouvelle quantité à l'ancienne. On ne modifie pas les autres lignes.
		// Le += permet d'incrementer une valeur, donc de l'ajouter à la valeur d'avant.
		/*  $a = 10;
			$a += 10 // $a = $a + 10 donc $a = 20 */
	}
	else // S'il ne trouve pas la position alors on ajoute une nouvelle ligne dans le panier :
	{
	$_SESSION['panier']['id_produit'][] = $id_produit ;
    $_SESSION['panier']['id_promo'][] = $id_promo ;
    $_SESSION['panier']['prix'][] = $prix ;
    $_SESSION['panier']['prix_promo'][] = $prix_promo ;
    $_SESSION['panier']['quantite'][] = $quantite ;
    $_SESSION['panier']['titre'][] = $titre ;
    $_SESSION['panier']['descriptif'][] = $descriptif ;
    $_SESSION['panier']['photo'][] = $photo ;
    $_SESSION['panier']['categorie'][] = $categorie ;
    $_SESSION['panier']['reduction'][] = $reduction ;
    $_SESSION['panier']['code_promo'][] = $code_promo ;
	}
}

//**********************************************************************************************************
//                         	FONCTION RUPTURE DE STOCK - RETIRER ARTICLE DU PANIER
//**********************************************************************************************************
/*Cette fonction va retirer l'article du tableau array panier s'il y a rupture de stock
Pour cela on utilise la position de l'article à supprimer

Array_splice permet de vider une partie d'un tableau array. Ca fonctionne un peu comme unset mais avec plus de fonctions
Il comprend 3 arguments :
- le tableau dans lequel on veut supprimer la (ou les) ligne(s),
- l'indice du tableau où l'on doit se positionner (ex : 3)
- la longueur de la coupe : c'est à dire combien de lignes du tableau on veut supprimer. Cette valeur peut être négative (pour supprimer les lignes au-dessus)
		-> (ex pour : 2) on supprime 2 lignes en dessous de la ligne de l'indice n°3 (comprise)
		-> (ex pour : -3) on supprime 3 lignes : 1 ligne de l'indice 3 et 2 autres au dessus de celle-ci

Ici, on prend le tableau $_SESSION['panier']['titre'], on va déterminer où on se positionne avec l'argument $position et on ne supprime que cette ligne puisque la longueur de coupe est 1

L'avantage de array_splice c'est qu'il renomme les indices lorsqu'il en a supprimé 1 donc il ne faudra pas oublier de décrémenter pour stopper l'action de suppression si on est dans un FOR (voir panier.php)*/

function retirer_produit_du_panier ($position)
{
	array_splice($_SESSION['panier']['id_produit'], $position, 1);
    array_splice($_SESSION['panier']['date_commande'], $position, 1);
    array_splice($_SESSION['panier']['date_estimation'], $position, 1);
    array_splice($_SESSION['panier']['date_livraison'], $position, 1);
    array_splice($_SESSION['panier']['id_promo'], $position, 1);
    array_splice($_SESSION['panier']['prix'], $position, 1);
    array_splice($_SESSION['panier']['prix_promo'], $position, 1);
    array_splice($_SESSION['panier']['quantite'], $position, 1);
    array_splice($_SESSION['panier']['pays'], $position, 1);
    array_splice($_SESSION['panier']['ville'], $position, 1);
    array_splice($_SESSION['panier']['adresse'], $position, 1);
    array_splice($_SESSION['panier']['cp'], $position, 1);
    array_splice($_SESSION['panier']['titre'], $position, 1);
    array_splice($_SESSION['panier']['descriptif'], $position, 1);
    array_splice($_SESSION['panier']['photo'], $position, 1);
    array_splice($_SESSION['panier']['categorie'], $position, 1);
    array_splice($_SESSION['panier']['reduction'], $position, 1);
    array_splice($_SESSION['panier']['code_promo'], $position, 1);
}

//**********************************************************************************************************
//                            			 	FONCTION MONTANT
//**********************************************************************************************************
function montant ()
{
	$total = 0;
	if(isset($_SESSION['panier']['id_produit'])){
	for($i= 0; $i < count($_SESSION['panier']['id_produit']); $i++) // J'execute autant de fois que j'ai d'article
		{
			if(!empty($_SESSION['panier']['prix_promo'][$i]))
				$total += $_SESSION['panier']['quantite'][$i]*$_SESSION['panier']['prix_promo'][$i] ;
			else
				$total += $_SESSION['panier']['quantite'][$i]*$_SESSION['panier']['prix'][$i] ;
		// Ici on indique tout de suite la TVA (mais dans la réalité le prix sera donné dès le départ en TTC)
		}
	}
	return round($total,2); // Ne pas oublier de retourner le resultat souhaité en dehors de la boucle.
}

function remise ()
{
	$remise = 0;
	if(isset($_SESSION['panier']['id_produit']))
	{
		for($i= 0; $i < count($_SESSION['panier']['id_produit']); $i++) // J'execute autant de fois que j'ai d'article
		{
			$remise += $ttc = $_SESSION['panier']['prix'][$i]*1.2*$_SESSION['panier']['reduction'][$i]/100;
		}
	};
	return round($remise,2); // Ne pas oublier de retourner le resultat souhaité en dehors de la boucle.
}

//**********************************************************************************************************
//                            			 	GENERER MOT DE PASSE ALEATOIRE
//**********************************************************************************************************
function generer_mot_de_passe($nb_caractere = 12)
{
    $mot_de_passe = "";
    $chaine = "abcdefghjkmnopqrstuvwxyzABCDEFGHJKLMNOPQRSTUVWXYZ023456789+@!$%?&";
    $longeur_chaine = strlen($chaine);
    for($i = 1; $i <= $nb_caractere; $i++)
    {
        $place_aleatoire = mt_rand(0,($longeur_chaine-1));
        $mot_de_passe .= $chaine[$place_aleatoire];
    }
    return $mot_de_passe;
}

//**********************************************************************************************************
//                            			 	DATE EN FRANCAIS
//**********************************************************************************************************
 function dateConvertFrEn($dt, $sep="-")
 {
 // si les 4 premiers caractères sont des chiffres, la date n'est pas au format "français"
	 if (is_numeric(substr($dt, 0, 4)))
	 {
	     // on retourne le résultat au format 'français'
	     return substr($dt,8,2).$sep.substr($dt,5,2).$sep.substr($dt,0,4);
	 }
	 else
	 {
	     // on retourne le résultat au format 'anglais'
	     return substr($dt,6,4).$sep.substr($dt,3,2).$sep.substr($dt,0,2);
	 }
 }

//**********************************************************************************************************
//                      	PREMIERE FONCTION : 	AGE A PARTIR DE LA DATE DE NAISSANCE
//**********************************************************************************************************
  function age2($date)
 {
	$age = (time() - strtotime($date)) / 3600 / 24 / 365;
	 return $age;
 }

 //**********************************************************************************************************
//                           DEUXIEME FONCTION : 	AGE A PARTIR DE LA DATE DE NAISSANCE
//**********************************************************************************************************
function age($NAISSANCE)
 {
 //On définit le jour, mois et année actuels avec la fonction date() pour chacun d'eux.
		$jour = date('d');
		$mois = date('m');
		$annee = date('Y');
		$annee_bis = date('L');//Pour savoir si l'année actuelle est bissextile ou non. 1 si oui, 0 si non.
		$jour_bis = 29;
		$mois_bis = 02;

		$date_membre = date_parse($NAISSANCE);
		$jour_membre = $date_membre['day'];
		$mois_membre = $date_membre['month'];
		$annee_membre = $date_membre['year'];

		//On définit le jour, mois et année de naissance de la personne, obtenus à l'aide de la requête
		$membre_jour_naiss = intval($jour_membre);
		$membre_mois_naiss = intval($mois_membre);
		$membre_annee_naiss = intval($annee_membre);

		//Si l'année est bissextile, pour ceux qui sont nés un 29 février et où l'anniversaire est passé.
		if (($membre_annee_naiss < $annee) && ($annee_bis == 1) && (($mois > $membre_mois_naiss)
		|| ($mois == $membre_mois_naiss && $jour >= $membre_jour_naiss)))
		{
			$age1 = $annee - $membre_annee_naiss;
			$result_age = 'Âge : '.$age1.' ans.';
		}
		//Si l'année est bissextile, pour ceux qui sont nés un 29 février et où l'anniversaire n'est pas passé.
		elseif (($membre_annee_naiss < $annee) && ($annee_bis == 1) && (($mois < $membre_mois_naiss)
		|| ($mois == $membre_mois_naiss && $jour < $membre_jour_naiss)))
		{
			$age2 = $annee - (++$membre_annee_naiss);
			$result_age = 'Âge : '.$age2.' ans.';
		}
		//Si l'année n'est pas bissextile, pour ceux qui sont nés un 29 février et où l'anniversaire est passé.
		elseif (($membre_annee_naiss < $annee) && ($annee_bis == 0) && ($membre_jour_naiss == $jour_bis) && ($jour == 28) && ($membre_jour_naiss > $jour)
		&& ($membre_mois_naiss == $mois_bis) && ($mois >= $mois_bis) && ($membre_mois_naiss <= $mois))
		{
			$age1 = $annee - $membre_annee_naiss;
			$result_age = 'Âge : '.$age1.' ans.';
		}
		//Si l'année n'est pas bissextile, pour ceux qui sont nés un 29 février et où l'anniversaire n'est pas passé.
		elseif (($membre_annee_naiss < $annee) && ($annee_bis == 0) && ($membre_jour_naiss == $jour_bis) && ($jour < 28) && ($membre_jour_naiss < $jour)
		&& ($membre_mois_naiss <= $mois_bis) && ($mois <= $mois_bis) && ($membre_mois_naiss <= $mois))
		{
			$age2 = $annee - (++$membre_annee_naiss);
			$result_age = 'Âge : '.$age2.' ans.';
		}
		//On a le cas ici, où l'anniversaire est passé..
		elseif ($membre_annee_naiss < $annee && (($mois > $membre_mois_naiss)
		|| ($jour < $membre_jour_naiss && $mois == $membre_mois_naiss )
		|| ($mois == $membre_mois_naiss && $jour == $membre_jour_naiss)))
		{

			$age1 = $annee - $membre_annee_naiss;
			$result_age = 'Âge : '.$age1.' ans.';
		}
		else //Sinon, Si l'anniversaire n'est pas passé.
		{
			$age2 = $annee - (++$membre_annee_naiss);
			$result_age = 'Âge : '.$age2.' ans.';
		}

		if($mois == $membre_mois_naiss && $jour == $membre_jour_naiss){
			$result_age .= "JOYEUX ANNIVERSAIRE !";

		}
		return $result_age;
 }

//**********************************************************************************************************
//                      	FIL D'ARIANE
//**********************************************************************************************************
function get_fil_ariane($array_fil)
{
	$fil = '<nav id="breadcrumbs"><!-- début menu -->
				<a href="'. RACINE_SITE .'index.php">
					<img id="home_small" src="'. RACINE_SITE .'image/home_small.png" alt="Icone maison home" />
				</a>
					<img src="'. RACINE_SITE .'image/fleche_small.png" alt="fleche" />
				<a href="'. RACINE_SITE .'index.php">Accueil</a>';

	foreach($array_fil as $url => $lien)
	{
	    $fil .= ' <img src="'. RACINE_SITE .'image/fleche_small.png" alt="fleche" /> ';
	    if($url == 'final')
		{
	        $fil .= '<a href="#">' . $lien . '</a>';
	        break;
	    }
	    $fil .= '<a href="' . $url . '">' . $lien . '</a>';
    }
		$fil .= '</nav>';
        echo $fil;
}

//**********************************************************************************************************
//                         		   FONCTION CREATION_PROMOTION
//**********************************************************************************************************
	/* Fonction qui de mettre à jour le panier. On prépare les boites dans lesquelles on va enregistrer les ajouts au panier de l'internaute. Boites qui vont accepter les informations */
function creation_promotion()
{
	if(!isset($_SESSION['promotion']))
	{
		$_SESSION['promotion'] = array();
		$_SESSION['promotion']['code_promo'] = array();
		$_SESSION['promotion']['total_remise'] = array();
		$_SESSION['promotion']['montant_total_remise'] = array();
		$_SESSION['promotion']['TT'] = array();
	}
};

//**********************************************************************************************************
//                         		   FONCTION AJOUT_ARTICLE_DANS_PANIER
//**********************************************************************************************************
/*Cette fonction aura 4 arguments
ATTENTION ici, si on rentre 2 fois le même article, on veut éviter qu'il y ait 2 lignes différentes donc on met une condition pour vérifier que l'ID_article n'existe pas déjà dans notre fonction. On utilise donc :
array_search(1,2) -> argument 1 = Qu'est ce que je recherche ?, argument 2 = Dans quel array je le recherche ?
array_search permet d'aller chercher une valeur dans un tableau. Il va donner la position de la valeur dans le tableau array
La réponse sera donc entre 0 et nombre d'articles ajoutés. Il détecte la ligne. */
function ajout_code_promo($code_promo, $total_remise, $montant_total_remise, $TT)
{
		$_SESSION['promotion']['code_promo'][] = $code_promo ;
		$_SESSION['promotion']['total_remise'][] = $total_remise ;
		$_SESSION['promotion']['montant_total_remise'][] = $montant_total_remise ;
		$_SESSION['promotion']['TT'][] = $TT ;
};

//**********************************************************************************************************
//                         	FONCTION RUPTURE DE STOCK - RETIRER ARTICLE DU PANIER
//**********************************************************************************************************

/*Cette fonction va retirer l'article du tableau array panier s'il y a rupture de stock
Pour cela on utilise la position de l'article à supprimer

Array_splice permet de vider une partie d'un tableau array. Ca fonctionne un peu comme unset mais avec plus de fonctions
Il comprend 3 arguments :
- le tableau dans lequel on veut supprimer la (ou les) ligne(s),
- l'indice du tableau où l'on doit se positionner (ex : 3)
- la longueur de la coupe : c'est à dire combien de lignes du tableau on veut supprimer. Cette valeur peut être négative (pour supprimer les lignes au-dessus)
		-> (ex pour : 2) on supprime 2 lignes en dessous de la ligne de l'indice n°3 (comprise)
		-> (ex pour : -3) on supprime 3 lignes : 1 ligne de l'indice 3 et 2 autres au dessus de celle-ci

Ici, on prend le tableau $_SESSION['panier']['titre'], on va déterminer où on se positionne avec l'argument $position et on ne supprime que cette ligne puisque la longueur de coupe est 1

L'avantage de array_splice c'est qu'il renomme les indices lorsqu'il en a supprimé 1 donc il ne faudra pas oublier de décrémenter pour stopper l'action de suppression si on est dans un FOR (voir panier.php)*/

function retirer_promo($position)
{
		array_splice($_SESSION['promotion']['code_promo'], $position, 1);
		array_splice($_SESSION['promotion']['total_remise'], $position, 1);
		array_splice($_SESSION['promotion']['montant_total_remise'], $position, 1);
		array_splice($_SESSION['promotion']['TT'], $position, 1);
}
