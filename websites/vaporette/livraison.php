<?php
include("inc/install.php");


//**************************************************************************************************************
//                          ETAPE 1 :::  TRANSFERT ENTRE LA FICHE_produit et LE PANIER
//***************************************************************************************************************

$j = count($_SESSION['promotion']['code_promo']) - 1;
creation_du_panier ();

// -------------------------------------- ACTION de VIDER LE PANIER ------------------------------------
///[cf.1]
if(isset($_GET['action']) && $_GET['action'] == "vider")
{
	unset($_SESSION['panier']); // unset permet de préciser et de vider un élément : ici la session du panier et non pas la session de l'utilisateur. session_destroy () n'aurait pas convenu.
}

/// [cf.2] Ensuite on va rentrer les informations en HTML dans le formulaire

//debug($_SESSION);

	/* $_SESSION['panier']['titre'][] = "Super Pull";
		$_SESSION['panier']['id_produit'][] = 123;
		$_SESSION['panier']['quantite'][] = 3 ;
$_SESSION['panier']['prix'][] = "50€";*/

	// -------------------------------------- RETIRER le produit DU PANIER ------------------------------

	if(isset($_GET['action']) && $_GET['action'] == "retirer")
	{

		retirer_produit_du_panier($_GET['position']);

	}

// -------------------------------------- AJOUT AU PANIER ------------------------------------------

if(isset($_POST['ajout_panier'])) // Est-ce que l'internaute a cliqué sur ajout panier dans la fiche produit ?
{
	// Execution d'une requête de selection pour aller chercher les infos venant de $_POST['id_produit'] du formulaire de la fiche produit.
	//echo $_POST['id_produit'];
	$resultat = execute_requete("SELECT *
		FROM produit p
		WHERE p.id_produit = $_POST[id_produit]
		GROUP BY  p.id_produit");
	$produit = mysqli_fetch_assoc($resultat);

	//debug($produit);

	// LA REQUETE avec 4 arguments (cf. fonction_inc.php). On ajoute le produit dans la SESSION panier.
	if($produit['id_promo'] == ""){

		ajout_produit_au_panier($produit['titre'], $produit['id_produit'], $produit['photo'], $produit['descriptif'],$_POST['quantite'], $produit['fidelite'], $produit['categorie'], $produit['prix'], 0,0,0 );

	}else{

		$resultat = execute_requete("SELECT * FROM promotion
			WHERE ".$produit['id_promo']." = id_promo");
		$promotion = mysqli_fetch_assoc($resultat);

		ajout_produit_au_panier($produit['titre'], $produit['id_produit'], $produit['photo'], $produit['descriptif'],$_POST['quantite'], $produit['fidelite'], $produit['categorie'], $produit['prix'], $produit['id_promo'], $promotion['code_promo'], $promotion['reduction'] );
	};

	// IMPORTANT
	//Ici on redirige l'internaute vers la page pour éviter que le dernier produit s'ajoute indéfiniment au moment du rafraîchissement de la page, F5.
	// Attention néanmoins à veiller à ce que le header se situe avant le HTML, et avant ECHO ou PRINT_R.
	//header("location:panier.php");



}

//Ensuite on vérifie que ça fonctionne en vidant nos cookies et en ajoutant 1, 2 puis 3 produits au panier pour voir le résultat dans debug($_SESSION);


// --------------------------- Création de la requête produits associes ---------------------------------------

$comptage = execute_requete("SELECT DISTINCT *
	FROM produit p
	WHERE p.stock > 0
	ORDER BY  p.id_produit DESC LIMIT 0,3");




//**********************************************************************************************************
//                             			ETAPE 3 ::: ACTIONS PAYER
//**********************************************************************************************************


//[cf.3] Payer

if(isset($_POST['payer'])) // Est-ce que l'internaute a cliqué sur payer ?
{

	//******************************************************************************

	if(!utilisateur_est_connecte())
	{
		header("location:inscription.php");
	}
	else if (!empty($_SESSION['panier']['id_produit']))
	{
	if(isset($_POST['conditions_generales'])){

	for($i= 0; $i < count ($_SESSION['panier']['id_produit']); $i++) // J'execute autant de fois que j'ai de produit
	{

		//On vérifie la quantité en stock avec une requête de selection pour chaque produit avec l'id_produit de la session
		// $i correspond à l'indice du tableau array $_SESSION['panier']['id_produit'] pour aller chercher chaque ligne du produit

		$resultat = execute_requete("SELECT * FROM produit WHERE id_produit = '".$_SESSION['panier']['id_produit'][$i]."'");
		$produit = mysqli_fetch_assoc($resultat);


		if($produit['stock'] < $_SESSION['panier']['quantite'][$i])
			// Est-ce que stock réel (en base) est inférieur à la quantité demandée dans le panier ?
		{
			// ----------------- RUPTURE DE STOCK -------------

			// Rupture de stock = On retire le produit du panier
			/*L'avantage de cette fonction, ou désavantage  (array_splice) :-), c'est qu'il RENOMME les indices lorsqu'il en a supprimé 1 donc il ne faudra pas oublier de décrémenter pour stopper l'action de suppression si on est dans un FOR (voir panier.php)*/


			$msg .= "<div class='erreur'>Nous sommes désolés, le produit ".$_SESSION['panier']['id_produit'][$i]." n'est plus disponible. Il a été retiré de votre panier car un autre membre vient de le réserver. Veuillez s'il vous plait vérifier et modifier votre commande</div>";
			retirer_produit_du_panier($i);
			$i--; // $i = $i-1




		} //------ FIN du stock réel inférieur au stock demandé

	} //---------- FIN du FOR

	$verif_caractere_code_postal = preg_match('#^[0-9]+$#' , $_POST['cp']);
	if(!$verif_caractere_code_postal && strlen($_POST['cp'])!=5)
	{
		$msg .=  '<div id="msg">
			<p class="orange">Veuillez rentrer un code postal valide</p>
			</div>';
	}
	if (empty($_POST['ville']))
	{
		$msg .= '<div id="msg">
			<p class="orange">Veuillez remplir le champs ville</p>
			</div>';
	}
	if (empty($_POST['adresse']))
	{
		$msg .= '<div id="msg">
			<p class="orange">Veuillez remplir le champs adresse</p>
			</div>';
	}

	//**********************************************************************************************************

	// ----------------------- INSERTION TABLES COMMANDES --------------------------------------------


	// S'il n'y a aucune erreur alors on crée une requête d'insertion pour rentrer les données dans la table commande.
	// Il faut cependant calculer le montant total de la commande. Pour cela nous allons écrire une fonction

	if(empty($msg))
	{
		foreach ($_POST as $key => $value)  // SECURITE / Ici on sécurise les données pour ne pas rentrer des caractères HTML et on empêche le navigateur d'intrepeter du code à la place du texte. On nettoie/purge toutes les entrées.
		{
			$_POST[$key] = htmlentities($value, ENT_QUOTES);
			// ex: 1er tour de boucle = $_POST[$pseudo] = htmlentities('TOTO', ENT_QUOTES); toto est filtré.
			// Pour le MDP, on peut crypter le mot de passe avec MD5 au lieu de htmlentities
		}

		//--------------------------- 1ere requete insertion dans commande
		if(isset($_SESSION['promotion']['montant_total_remise'][$j])){


			execute_requete("INSERT INTO commande (id_membre, montant, date_commande, date_estimation, adresse, cp , ville, statut) VALUES ('".$_SESSION['utilisateur']['id_membre']."', ".$_SESSION['promotion']['montant_total_remise'][$j].", now(), now()+5, '".$_POST['adresse']."','".$_POST['cp']."','".$_POST['ville']."', 'En cours de traitement')");


		}else{

			execute_requete("INSERT INTO commande (id_membre, montant, date_commande, date_estimation, adresse, cp , ville, statut) VALUES ('".$_SESSION['utilisateur']['id_membre']."', '".montant()."', now(), now()+5, '".$_POST['adresse']."','".$_POST['cp']."','".$_POST['ville']."', 'En cours de traitement')");

		};

		// Ici il va falloir aller chercher id de la dernière commande enregistrée dans la table commande
		$id_commande = mysqli_insert_id ($mysqli);



		for($i = 0; $i < count($_SESSION['panier']['id_produit']); $i++)
		{

			//--------------------------- Insertion du code PROMO servant à la réduction
			if(isset($_SESSION['promotion']['code_promo'][$j]) && $_SESSION['promotion']['code_promo'][$j] === $_SESSION['panier']['code_promo'][$i]){



				if(!empty($_SESSION['panier']['prix_promo'][$i])){

					execute_requete
						("INSERT INTO details_commande (id_commande, id_produit, quantite, prix_promo, code_promo, reduction)
						VALUES ($id_commande, ".$_SESSION['panier']['id_produit'][$i].", ".$_SESSION['panier']['quantite'][$i].", ".$_SESSION['panier']['prix_promo'][$i].", ".$_SESSION['panier']['code_promo'][$i].", ".$_SESSION['panier']['reduction'][$i].")");

				}else{

					//--------------------------- Insertion du prix promo

					execute_requete
						("INSERT INTO details_commande (id_commande, id_produit, quantite, prix_promo, code_promo, reduction)
						VALUES ($id_commande, ".$_SESSION['panier']['id_produit'][$i].", ".$_SESSION['panier']['quantite'][$i].", ".$_SESSION['panier']['prix_promo'][$i].", ".$_SESSION['panier']['code_promo'][$i].", ".$_SESSION['panier']['reduction'][$i].")");


				}

			}else{

				//--------------------------- Insertion dans detais_commande avec un FOR puisqu'il va correspondre à chaque produit

				execute_requete
					("INSERT INTO details_commande (id_commande, id_produit, quantite, prix_promo, code_promo, reduction)
					VALUES ($id_commande, ".$_SESSION['panier']['id_produit'][$i].", ".$_SESSION['panier']['quantite'][$i].", 0,0,0) 	");

			}

			//-------------------------3ème requete update dans produit pour mettre à jour le stock
			execute_requete
				("UPDATE produit
				SET stock = stock - ".$_SESSION['panier']['quantite'][$i]."
				WHERE id_produit = ".$_SESSION['panier']['id_produit'][$i]);

		} // Fin du FOR


		//**********************************************************************************************************
		//                         		  GENERER UN EMAIL DE CONFIRMATION
		//**********************************************************************************************************



		//--- TABLEAU HTML ---------------------------------

		$resultat = execute_requete("SELECT *
			FROM commande c, details_commande d, membre m, produit p
			WHERE c.id_commande = '$id_commande'
			AND c.id_commande = d.id_commande
			AND m.id_membre = c.id_membre
			AND p.id_produit = d.id_produit
			GROUP BY id_details_commande");

		//debug($resultat);

		if(mysqli_num_rows($resultat)== 0){
			echo "<p class='CA'>Pas de détail pour cette commande</p>";
		}else{


			// Ici on aura pu donner un autre nom à cette variable ex : $modif et dire que $_POST = $modif mais on a raccourci l'opération en écrivant directement $_POST. Il faut se souvenir que la superglobal $_POST fonctionne pour l'ajout d'un commande mais pas pour la modification car on ne soumet pas de formulaire, donc va lui dire quoi aller chercher pour remplir $_POST.

			// 2ème ligne de tableau et suivantes **********************

			while($ligne = mysqli_fetch_assoc($resultat))
			{

				$contenu.= "<p class='CA'>Détail de la commande n°".$ligne['id_commande']." : </p>";
				$contenu.= "<p class='CA'>Montant de votre commande : ".$ligne['montant']."€ TTC</p>";
				// 1ère ligne de tableau****************************
				$contenu.= "<table border = 4px>";

				$contenu.= "<tr>";
				$contenu.= "<th>Id Détails Commande</th>";
				$contenu.= "<th>Id Membre</th>";
				$contenu.= "<th>Pseudo</th>";
				$contenu.= "<th>Id Produit</th>";
				$contenu.= "<th>Produit</th>";
				$contenu.= "<th>Date de votre commande</th>";
				$contenu.= "<th>Date de livraison estimée</th>";
				$contenu.= "<th>Prix</th>";
				$contenu.= "</tr>";

				//debug($ligne);

				$contenu.= "<tr>";
				$contenu.= "<td>".$ligne['id_details_commande']."</td>";
				$contenu.= "<td>".$ligne['id_membre']."</td>";
				$contenu.= "<td>".$ligne['pseudo']."</td>";
				$contenu.= "<td>".$ligne['id_produit']."</td>";
				$contenu.= "<td>".$ligne['titre']."</td>";
				$contenu.= "<td>".$ligne['date_commande']."</td>";
				$contenu.= "<td>".$ligne['date_estimation']."</td>";
				$contenu.= "<td>".$ligne['prix']."€ HT</td>";
				$contenu.= "</tr>";

			}


			// ********************************************************

			$contenu.= "</table>";

			//--- FIN TABLEAU HTML -------------------------------


		};



		//--------------------------VIDER LE PANIER
		unset($_SESSION['panier']);


		//--------------------------MESSAGE DE VALIDATION
		$msg .= '<div id="msg">
			<p class="vert">Votre commande est validée.</p>
			</div>';


	} // FIN s'il n'y a pas de message d'erreur.


}else{	//-------------- FIN du $_POST['conditions_generales']

	$msg .= '<div id="msg">
		<p class="orange">Vous devez accepter les conditions générales de vente</p>
		<p class="vert">Si vous avez saisi un code promo, merci de le saisir à nouveau.</p>
		</div>';

}

	} //-------------- FIN de utilisateur connecté
} //-------------- FIN du $_POST['payer']


include("inc/haut_de_site_inc.php");
include("inc/top_menu_inc.php");
include("inc/menu_inc.php");


//**************** FIL D'ARIANE ************************* -->


get_fil_ariane(array(
	'panier.php' => 'Mon panier',
	'enregistrement.php' => 'S\'enregistrer',
	'final' => 'Valider votre commande'
));





//*********************** MESSAGE **************************/:w

echo $msg;

?>
<!-- **************************************************************************************** -->
<!--  			ETAPE 2 :::		DEUXIEME COLONNE - TABLEAU PANIER							  -->
<!-- **************************************************************************************** -->


				<div id="colonne-unique" class="colonne2"> <!-- début colonne 2-->


					<div class="titre_h2 largeur_article titre-panier"><h2>RECAPITULATIF</h2></div>


					<div id="tableau">

					<!-- Formulaire du panier -->
					<form id="panier" class="formulaire" method="post" action="">


					<!-- Tableau du panier -->
					<table class="tableau_admin" class="tableau_panier" summary="Gestion administrateur">
					<caption><img src="<?php echo RACINE_SITE ?>image/panier-rouge.png" alt="homme profil"/>  	PRODUITS DE VOTRE COMMANDE</caption>
					<thead>
					<tr>
					<th scope="col">Produit</th>
					<th scope="col">Photo</th>
					<th scope="col">Descriptif</th>
					<th scope="col">Prix unitaire</th>
					<th scope="col" class="petit">Qtité</th>
					<th scope="col">Prix total</th>
					<th scope="col">Actions</th>
					</tr>

					</thead>

					<tbody>
<?php
// -------------------------------------- SI PANIER VIDE ---------------------------------------------
/* Dans le cas où le panier est plein */

if(empty($_SESSION['panier']['id_produit'])) // Si le panier est vide alors,
{

	echo "<tr><td colspan=10>Votre panier est vide</td></tr>";
?>
					</tbody>

					<tfoot>
					<tr>
					<th scope="row" colspan="2">Total</th>
					<td colspan="3">TVA 20% : 0 €</td>
					<td colspan="2">0 € HT</td>
					</tr>

					<tr>
					<th scope="row" colspan="2">MONTANT TOTAL</th>
					<td colspan="3">0 produits pour cette commande</td>
					<td colspan="2"><strong>0 € TTC</strong></td>
					</tr>
					</tfoot>

					</table>

<?php

}
else  // Si le panier est plein, alors paiement.
{
	$total = 0;
	$montant_TTC = 0;
	$montant_HT = 0;
	$TVA = 0;


	for($i=0; $i <count($_SESSION['panier']['id_produit']); $i++)
		// Ici on ne met pas count($_SESSION['panier']['id_produit']) dans une variable car on aurait des soucis à la fin
	{

		//debug($_SESSION['promotion']['code_promo'][$i]);
		//debug($_SESSION['promotion']['total_remise'][$i]);
		//debug($_SESSION['panier']['code_promo'][$i]);
		//$compte = 0;



		if(isset($_SESSION['promotion']['code_promo'][$j]) && $_SESSION['promotion']['code_promo'][$j] === $_SESSION['panier']['code_promo'][$i]){

?>

						<tr>
							<td><?php echo $_SESSION['panier']['titre'][$i] ?></td>
							<td><a href="<?php echo RACINE_SITE ?>fiche-produit.php?id=<?php echo $_SESSION['panier']['id_produit'][$i] ?>"><img style="max-width: 80px; max-height: 100px;" src="<?php echo RACINE_SITE ?><?php echo $_SESSION['panier']['photo'][$i] ?>"/></a></td>
							<td><?php echo substr($_SESSION['panier']['descriptif'][$i],0, 350) ?> [...]</td>



<?php
			if(!empty($_SESSION['panier']['prix_promo'][$i])) {


				$prix = $_SESSION['panier']['prix_promo'][$i] - ($_SESSION['panier']['prix_promo'][$i] * $_SESSION['panier']['quantite'][$i]  * ($_SESSION['panier']['reduction'][$i]/100));

				?><td><span class="prix_barre"><?php echo $_SESSION['panier']['prix'][$i] ?>€ TTC</span>
					<span class="prix_barre"><?php echo $_SESSION['panier']['prix_promo'][$i] ?>€ TTC</span>
					<br /><?php echo $prix ?>€ TTC</td><?php

			}else {

				$prix = $_SESSION['panier']['prix'][$i] - ($_SESSION['panier']['prix'][$i] * $_SESSION['panier']['quantite'][$i]  * ($_SESSION['panier']['reduction'][$i]/100));

				?><td><span class="prix_barre"><?php echo $_SESSION['panier']['prix'][$i] ?>€ TTC</span>
					<br /><?php echo $prix ?>€ TTC</td><?php

			}
?>


							<td class="petit"><input type="text" id="quantite" name="quantite"   maxlength="3" value="<?php echo $_SESSION['panier']['quantite'][$i] ?>"/></td>
							<?php $total = $_SESSION['panier']['quantite'][$i]*$prix ?>

							<td><?php echo $total ?>€ TTC</td>
							<td><a href="?action=retirer&position=<?php echo $i ?>"><img src="<?php echo RACINE_SITE ?>image/poubelle.gif"/></a></td>
						</tr>


<?php
			//$compte +=1;


		}else{




?>

						<tr>
							<td><?php echo $_SESSION['panier']['titre'][$i] ?></td>
							<td><a href="<?php echo RACINE_SITE ?>fiche-produit.php?id=<?php echo $_SESSION['panier']['id_produit'][$i] ?>"><img style="max-width: 80px; max-height: 100px;" src="<?php echo RACINE_SITE ?><?php echo $_SESSION['panier']['photo'][$i] ?>"/></a></td>
							<td><?php echo substr($_SESSION['panier']['descriptif'][$i],0, 350) ?> [...]</td>

<?php
			if(!empty($_SESSION['panier']['prix_promo'][$i])) {

				echo "<td ><span class='prix_barre'>".$_SESSION['panier']['prix'][$i]."€</span>-".$_SESSION['panier']['prix_promo'][$i]."€  </td>	";

			}else {

				echo '<td>'.$_SESSION['panier']['prix'][$i].'€ </td>	';
			}
?>

							<td class="petit"><input type="text" id="quantite" name="quantite"   maxlength="3" value="<?php echo $_SESSION['panier']['quantite'][$i] ?>"/></td>

<?php
			if(!empty($_SESSION['panier']['prix_promo'][$i])) {

				$total = $_SESSION['panier']['quantite'][$i]*$_SESSION['panier']['prix_promo'][$i] ;

			}else{

				$total = $_SESSION['panier']['quantite'][$i]*$_SESSION['panier']['prix'][$i] ;
			}
?>

							<td><?php echo $total ?>€ TTC</td>
							<td><a href="?action=retirer&position=<?php echo $i ?>"><img src="<?php echo RACINE_SITE ?>image/poubelle.gif"/></a></td>
						</tr>


<?php

		};
		$montant_TTC += $total;
		//echo $compte;
		//$montant_remise += $_SESSION['panier']['prix'][$i]*(100-$_SESSION['panier']['reduction'][$i])/100;

	};


	// ------------------------------- TOTAL - SUITE FORMULAIRE ------------------------------------

	//debug($_SESSION['promotion']['code_promo']);
	//debug($_SESSION['promotion']['total_remise']);
	//debug($_SESSION['promotion']['montant_total_remise']);
	//debug($_SESSION['promotion']['TT']);


	$montant_HT = $montant_TTC/1.2;
	$TVA =  $montant_TTC - $montant_HT ;

?>
					</tbody>

					<tfoot>
					<tr>
					<th scope="row" colspan="2">Total</th>
					<td colspan="3">TVA 20% : <?php echo round($TVA,2) ?>€</td>
					<td colspan="2"><?php echo round($montant_HT,2) ?>€ HT</td>
					</tr>

					<tr>
					<th scope="row" colspan="2">MONTANT TOTAL</th>
					<td colspan="1"><?php echo count($_SESSION['panier']['id_produit']) ?> produits pour cette commande</td>
					<td colspan="4"><strong><?php
	if(isset( $_SESSION['promotion']['montant_total_remise'][$j])){
		echo $_SESSION['promotion']['montant_total_remise'][$j]."€ TTC (Remise supplémentaire de ".$_SESSION['promotion']['total_remise'][$j]."€ avec le code".$_SESSION['promotion']['code_promo'][$j].")";
	}else{
		echo montant()."€ TTC";
	};
?></strong>
					</td>

					</tr>
					</tfoot>

					</table>
<?php

};



?>

					<!-- Modes de paiement -->

					<div id="reglement">

						<!-- Adresse de livraison -->
<?php

$membre = execute_requete("SELECT * FROM membre WHERE id_membre = '".$_SESSION['utilisateur']['id_membre']."'");
$membre_connecte = mysqli_fetch_assoc($membre);
?>


							<label for="ville">Adresse de livraison (*modifier si différente)</label><br />
								<input type="text" id="cp" name="cp" placeholder=" Votre code postal" value="<?php echo $membre_connecte['cp']?>"/><br />
								<input type="text" id="ville" name="ville" placeholder=" Votre ville" value="<?php echo $membre_connecte['ville']?>" /><br />
								<textarea id="adresse" name="adresse" placeholder=" Votre adresse et autres détails (Bat, Etage, Résidence...)"><?php echo $membre_connecte['adresse']?></textarea>
								<br />





							<label for="conditions_generales">J'accepte les conditions générales de vente (<a href=#>voir</a>)</label>
							<input type="checkbox" name="conditions_generales"><br />


						<label id="mode-reglement" for="reglement">MODE DE REGLEMENT</label>
							<input class="radio" type="radio" name="reglement" value="carte-bleue" /><img src="<?php echo RACINE_SITE ?>image/carte-bleue.jpg" alt="icone carte bleue"/>
							<input class="radio" type="radio" name="reglement" value="visa" /><img src="<?php echo RACINE_SITE ?>image/visa.jpg" alt="icone visa"/>
							<input class="radio" type="radio" name="reglement" value="mastercard" /><img src="<?php echo RACINE_SITE ?>image/mastercard.jpg" alt="icone mastercard"/>
							<input class="radio" type="radio" name="reglement" value="amex" /><img src="<?php echo RACINE_SITE ?>image/amex.jpg" alt="icone american express"/>
							<input class="radio" type="radio" name="reglement" value="paypal" /><img src="<?php echo RACINE_SITE ?>image/paypal.jpg" alt="icone paypal"/>
							<br />
						<input class="submit-panier" type="submit" name="payer" value="PAYER"/>



						<!-- Fin Adresse de livraison -->




					</div>



					</form>

					</div>


<!-- **************************************************************************************** -->
<!--  									PRODUITS ASSOCIES									  -->
<!-- **************************************************************************************** -->
<h4>Découvrez les produits associés</h4>

<?php
while($produit_assoc = mysqli_fetch_assoc($comptage)){
?>



					<div class="produit">
						<div class="contenu_produit">
							<a class="image_contenu_produit" href="<?php echo RACINE_SITE ?>fiche-produit.php?id=<?php echo $produit_assoc['id_produit'] ?>">
							<img style="max-width: 193px; max-height: 146px;" src="<?php echo RACINE_SITE ?><?php echo $produit_assoc['photo'] ?>" alt="<?php echo $produit_assoc['titre'] ?>" /></a>
							<h3><?php echo $produit_assoc['titre'] ?></h3>
							<p class="prix"><?php echo $produit_assoc['prix'] ?> €</p>
						</div>

						<div class="liens_produit">
							<div>
							<img src="image/icone_plus.gif" alt="Icone PLUS" />
							<p><a href="<?php echo RACINE_SITE ?>fiche-produit.php?id=<?php echo $produit_assoc['id_produit'] ?>">En savoir plus</a></p>
							</div>


					</div>

					</div>

<?php
}
?>

				</div> <!-- fin COLONNE 2 ......................... -->

			</div><!-- Fin de principale............................ -->
