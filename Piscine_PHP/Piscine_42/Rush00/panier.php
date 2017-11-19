<?php
	include("inc/install.php");


//**************************************************************************************************************
//                          ETAPE 1 :::  TRANSFERT ENTRE LA FICHE_produit et LE PANIER
//***************************************************************************************************************

	creation_du_panier ();
	creation_promotion();

	// -------------------------------------- ACTION de VIDER LE PANIER ------------------------------------
	///[cf.1]
	/*if (isset($_SESSION['panier']))
	{
					echo '<div id="msg">
						<p class="orange">Votre panier est vider</p>
						</div>';

	}*/

	if(isset($_GET['action']) && $_GET['action'] == "vider")
	{
		unset($_SESSION['panier']); // unset permet de préciser et de vider un élément : ici la session du panier et non pas la session de l'utilisateur. session_destroy () n'aurait pas convenu.
		unset($_SESSION['promotion']); // unset permet de préciser et de vider un élément : ici la session du panier et non pas la session de l'utilisateur. session_destroy () n'aurait pas convenu.
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
		retirer_promo($_GET['position']);

	}

	// -------------------------------------- AJOUT AU PANIER - PRODUIT ------------------------------------------

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
					//debug($promotion);

					ajout_produit_au_panier($produit['titre'], $produit['id_produit'], $produit['photo'], $produit['descriptif'],$_POST['quantite'], $produit['fidelite'], $produit['categorie'], $produit['prix'], $produit['prix_promo'], $produit['id_promo'], $promotion['code_promo'], $promotion['reduction'] );
				};

				// IMPORTANT
				//Ici on redirige l'internaute vers la page pour éviter que le dernier produit s'ajoute indéfiniment au moment du rafraîchissement de la page, F5.
				// Attention néanmoins à veiller à ce que le header se situe avant le HTML, et avant ECHO ou PRINT_R.
				//header("location:panier.php");



	}

	//Ensuite on vérifie que ça fonctionne en vidant nos cookies et en ajoutant 1, 2 puis 3 produits au panier pour voir le résultat dans debug($_SESSION);

	// -------------------------------------- AJOUT AU PANIER - PRODUITS ASSOCIES ------------------------------------------

	if(isset($_POST['ajout_panier_associe'])) // Est-ce que l'internaute a cliqué sur ajout panier dans la fiche produit ?
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

					ajout_produit_au_panier($produit['titre'], $produit['id_produit'], $produit['photo'], $produit['descriptif'],$_POST['quantite'], $produit['fidelite'], $produit['categorie'], $produit['prix'], $produit['prix_promo'], 0,0,0 );

				}else{

					$resultat = execute_requete("SELECT * FROM promotion
					WHERE ".$produit['id_promo']." = id_promo");
					$promotion = mysqli_fetch_assoc($resultat);
					//debug($promotion);

					ajout_produit_au_panier($produit['titre'], $produit['id_produit'], $produit['photo'], $produit['descriptif'],$_POST['quantite'], $produit['fidelite'], $produit['categorie'], $produit['prix'], $produit['prix_promo'], $produit['id_promo'], $promotion['code_promo'], $promotion['reduction'] );
				};

				// IMPORTANT
				//Ici on redirige l'internaute vers la page pour éviter que le dernier produit s'ajoute indéfiniment au moment du rafraîchissement de la page, F5.
				// Attention néanmoins à veiller à ce que le header se situe avant le HTML, et avant ECHO ou PRINT_R.
				//header("location:panier.php");

				$msg.= '<div id="msg">
						<p class="vert">Un produit a été ajouté à votre panier</p>
						</div>';

	}


// --------------------------- Création de la requête produits associes ---------------------------------------

	$comptage = execute_requete("SELECT DISTINCT *
					FROM produit p
					WHERE p.stock > 0
					ORDER BY  RAND() LIMIT 0,4");





//**********************************************************************************************************
//                             			ETAPE 4 ::	APPLIQUER REDUCTION
//**********************************************************************************************************


	//[cf.3] Payer
	//$montant_remise =  - remise();

	$total_avec_reduction_saisie = montant();
	$applique_reduction = "";
	//$code_invalide = "";
	$x = 0;
	$compte = 0;
	$remise = "";
	$remise2 = "";
	$total_remise = "";
	$montant_total_remise = "";
	$affichage = "";
	$code = "";

	//debug($_SESSION['panier']['code_promo']);


	if(isset($_POST['reduction'])) // Est-ce que l'internaute a cliqué sur appliquer la reduction ?
	{


		$applique_reduction .= "<div class='reduction'>
										<table class='tableau_admin' class='tableau_panier' summary='Gestion administrateur'>
										<caption><img src='". RACINE_SITE ."image/panier-rouge.png' alt='homme profil'/>LE CODE PROMO saisi vous donne droit à ces réductions : </caption>
										<thead>
										<tr>
										<th scope='col'>Réduction</th>
										<th scope='col'>appliquée sur ce produit :</th>
										<th scope='col'>Remise calculée</th>
										</tr>
										</thead>
										</tbody>";


		for($i= 0; $i < count($_SESSION['panier']['id_produit']); $i++) // J'execute autant de fois que j'ai d'article
		{
			if(empty($_SESSION['panier']['prix_promo'][$i])) {

						if($_POST['bonachat'] === $_SESSION['panier']['code_promo'][$i]){


						$x = $_SESSION['panier']['prix'][$i] * $_SESSION['panier']['quantite'][$i] * (100 - $_SESSION['panier']['reduction'][$i])/100;
						$y = $_SESSION['panier']['prix'][$i] * $_SESSION['panier']['quantite'][$i]  * ($_SESSION['panier']['reduction'][$i])/100;


						$applique_reduction .= "<tr><td>".$_SESSION['panier']['reduction'][$i]."% </td><td><img style='max-width: 80px; max-height: 100px;' src='".RACINE_SITE . $_SESSION['panier']['photo'][$i] ."'/> ".$_SESSION['panier']['titre'][$i]."</td><td> soit ".$y."€ TTC de remise </td></tr>";




						$remise = "(remise déduite)";
						$remise2 = "AVEC REMISE (code promo ".$_SESSION['panier']['code_promo'][$i].")";
						$compte += 1;
						$total_remise += $y;
						$montant_total_remise += $x;
						//echo $compte;



						}else{


							$montant_total_remise += $_SESSION['panier']['prix'][$i]  * $_SESSION['panier']['quantite'][$i] ;

						};

			}else {


						if($_POST['bonachat'] === $_SESSION['panier']['code_promo'][$i]){


						$x = $_SESSION['panier']['prix_promo'][$i] * $_SESSION['panier']['quantite'][$i] * (100 - $_SESSION['panier']['reduction'][$i])/100;
						$y = $_SESSION['panier']['prix_promo'][$i] * $_SESSION['panier']['quantite'][$i]  * ($_SESSION['panier']['reduction'][$i])/100;


						$applique_reduction .= "<tr><td>".$_SESSION['panier']['reduction'][$i]."% </td><td><img style='max-width: 80px; max-height: 100px;' src='".RACINE_SITE . $_SESSION['panier']['photo'][$i] ."'/> ".$_SESSION['panier']['titre'][$i]."</td><td> soit ".$y."€ TTC de remise </td></tr>";




						$remise = "(remise déduite)";
						$remise2 = "AVEC REMISE (code promo ".$_SESSION['panier']['code_promo'][$i].")";
						$compte += 1;
						$total_remise += $y;
						$montant_total_remise += $x;
						//echo $compte;



						}else{


							$montant_total_remise += $_SESSION['panier']['prix_promo'][$i]  * $_SESSION['panier']['quantite'][$i] ;

						};

			}; // Fin du IF prix-promo







		}; // FIN de la boucle FOR

		$applique_reduction .= "</tbody>
								<tfoot>
								<tr>
								<th scope='row' colspan='2'>Montant des économies réalisées : ".$total_remise." €</th>
								<td colspan='1'>sur ".$compte." produit(s)</td>
								</tr>
								</tfoot></table></div>";


		for($i= 0; $i < count($_SESSION['panier']['id_produit']); $i++) // J'execute autant de fois que j'ai d'article
		{

							if($compte == 0){

								$x = montant();
								$applique_reduction = '<p>Ce code a expiré ou ne correspond pas aux produits sélectionnés</p>';

							};


		};


		$TT = montant() - $total_remise;
		$affichage = '<div class="clear"></div>
					<div class="montant_commande"><p> Total avec réduction : '.$TT.'€ TTC</p></div>';

				//$total_avec_reduction_saisie = round($montant_total_remise,2);
				//echo $total_avec_reduction_saisie;


		ajout_code_promo($_POST['bonachat'],$total_remise, $montant_total_remise, $TT );


	};	 // Fin du POST reduction

//**********************************************************************************************************
//                             			ETAPE 3 ::: ACTIONS PAYER
//**********************************************************************************************************


	//[cf.3] Payer

	if(isset($_POST['payer'])) // Est-ce que l'internaute a cliqué sur payer ?
	{

				//******************************************************************************

			if(!utilisateur_est_connecte())
			{
						header("location:connexion.php");
			}
			else
			{
				if(empty($_SESSION['panier']['id_produit']))
				{
					header("location:panier.php");
				}
				else
				{
					header("location:livraison.php");
				}
			}



	} //-------------- FIN du $_POST['payer']


//**********************************************************************************************************


	include("inc/haut_de_site_inc.php");
	include("inc/top_menu_inc.php");
	include("inc/menu_inc.php");

//**************** FIL D'ARIANE ************************* -->


   get_fil_ariane(array(
   'final' => 'Mon panier'
   ));






//*********************** MESSAGE ************************* -->

					echo $msg;
						/*<div id="msg">
						<p class="vert">Vous avez bien rentré toutes les informations</p>
						</div>*/

?>
<!-- **************************************************************************************** -->
<!--  			ETAPE 2 :::		DEUXIEME COLONNE - TABLEAU PANIER							  -->
<!-- **************************************************************************************** -->


				<div id="colonne-unique" class="colonne2"> <!-- début colonne 2-->


					<div class="titre_h2 largeur_article titre-panier"><h2>MON PANIER</h2></div>
					<div class="bouton-ajout">
					<img src="<?php echo RACINE_SITE ?>image/chariot.png" alt="chariot"/><a href="<?php echo RACINE_SITE ?>index.php">Continuer mes achats</a>
					<img src="<?php echo RACINE_SITE ?>image/vider-panier.png" alt="Chariot barré"/><a href="?action=vider">Vider le panier</a>
					<img src="<?php echo RACINE_SITE ?>image/homme-petit.png" alt="homme silhouette de profil"/><a href="<?php echo RACINE_SITE ?>profil.php">Voir mon profil</a>
					</div>

					<div id="tableau">

					<!-- Formulaire du panier -->
					<form id="panier" class="formulaire" method="post" action="">


					<!-- Tableau du panier -->
					<table class="tableau_admin" class="tableau_panier" summary="Gestion administrateur">
					<caption><img src="<?php echo RACINE_SITE ?>image/panier-rouge.png" alt="homme profil"/>  	ARTICLES AU PANIER</caption>
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
						<td class="petit"><?php echo $_SESSION['panier']['quantite'][$i] ?></td>
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
					$montant_TTC += $total;
					//$montant_remise += $_SESSION['panier']['prix'][$i]*(100-$_SESSION['panier']['reduction'][$i])/100;

				}


					// ------------------------------- TOTAL - SUITE FORMULAIRE ------------------------------------


					$montant_HT = montant()/1.2;
					$TVA =  montant() - $montant_HT ;

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
					<td colspan="3"><?php echo count($_SESSION['panier']['id_produit']) ?> produits pour cette commande</td>
					<td colspan="2"><strong><?php echo montant() ?>€ TTC</strong></td>
					</tr>
					</tfoot>

					</table>
				<?php

			};



				?>
					<div id="reglement">

						<label for="bonchat">VALIDER UN BON D'ACHAT</label>
							<input type="text" id="bonachat" name="bonachat" placeholder=" Entrez le code promotionnel" value=""/>
							<input type='submit' name='reduction' value='Appliquer la reduction'/>
								<br />
						<?php echo $applique_reduction ?>

					</div>

						<?php echo $affichage; ?>
					<input class="submit-panier" type="submit" name="payer" value="VALIDER LA COMMANDE"/>



					</form>

					</div>
				</div> <!-- fin COLONNE 2 ......................... -->

			</div><!-- Fin de principale............................ -->
