<?php
	include("inc/install.php");

	$nav_en_cours = 'page_boutique';
	$tri = 'id_produit';
	$ordre = 'ASC';

	if(isset($_GET['tri']) && isset($_GET['ordre'])){

		$tri = $_GET['tri'] ;
		$ordre = $_GET['ordre'];

	};

//**************************************************************************************************************
//                          ETAPE 1 :::  TRANSFERT ENTRE LA FICHE_produit et LE PANIER
//***************************************************************************************************************


	creation_du_panier ();



// --------------------------- Création de la requête affichage produits  ---------------------------------------

	$produits_promo = execute_requete("SELECT DISTINCT *
					FROM produit
					WHERE stock > 0
					AND prix_promo != 0
					GROUP BY  id_produit
					ORDER BY ".$tri." ".$ordre);

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

					ajout_produit_au_panier($produit['titre'], $produit['id_produit'], $produit['photo'], $produit['descriptif'],$_POST['quantite'], $produit['fidelite'], $produit['categorie'], $produit['prix'], $produit['prix_promo'],0,0,0 );

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
					FROM produit
					WHERE stock > 0
					AND prix_promo = 0
					ORDER BY  RAND() LIMIT 0,3");


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




	include("inc/haut_de_site_inc.php");
	include("inc/top_menu_inc.php");
	include("inc/menu_inc.php");

//**************** FIL D'ARIANE ************************* -->

	get_fil_ariane(array(
   'final' => 'Promotions'
   ));

//**************** MESSAGE ************************* -->


						echo $msg;

?>


<!-- **************************************************************************************** -->
<!--  									DEUXIEME COLONNE									  -->
<!-- **************************************************************************************** -->


				<div class="colonne2"> <!-- début colonne 2-->

					<div class="titre_h2 largeur_article boutique"><h2>NOS PROMOTIONS</h2></div>
					<div class="trier">
					<p>Trier par :
					<a href="<?php echo RACINE_SITE ?>promotions.php?tri=prix&ordre=asc">Prix croissant &#124;</a>
					<a href="<?php echo RACINE_SITE ?>promotions.php?tri=prix&ordre=desc">Prix décroissant </a>
					</p>
					</div>


					 <!-- PRODUIT -->
<?php
			while($produit = mysqli_fetch_assoc($produits_promo)){
?>


					<div class="produit_boutique">

<?php
			if(!empty($produit['prix_promo'])) {
				echo '<div class="discount">PROMO</div>';
			}
?>

						<div class="image_boutique"><a href="<?php echo RACINE_SITE ?>fiche-produit.php?id=<?php echo $produit['id_produit'] ?>"><img style="max-width: 162px; max-height: 150px;" src="<?php echo RACINE_SITE ?><?php echo $produit['photo'] ?>" alt="<?php echo $produit['titre'] ?>" /></a></div>

						<div class="contenu_produit_boutique">
							<a href="<?php echo RACINE_SITE ?>fiche-produit.php?id=<?php echo $produit['id_produit'] ?>"><h3><?php echo $produit['titre'] ?></h3></a>

							<p><?php echo substr($produit['descriptif'],0, 200) ?>... </p>

						</div>

						<div class="produit-action">

							<div class="prix-fidelite">

								<div class="prix-stock">
									<p>Prix :</p>

<?php
			if(!empty($produit['prix_promo'])) {
				echo '<p class="prix prix_barre">'.$produit['prix'].'€ TTC</p>';
				echo '<p class="prix clignotant">'.$produit['prix_promo'].'€ TTC</p>';
			}else {

				echo '<p class="prix">'.$produit['prix'].'€ TTC</p>';
			}
?>
									<p class="vert">En stock</p>
								</div>

							</div>

							<div class="liens_produit">
								<div>
								<img src="<?php echo RACINE_SITE ?>image/icone_plus_petit.png" alt="Icone PLUS" />
								<p><a href="<?php echo RACINE_SITE ?>fiche-produit.php?id=<?php echo $produit['id_produit'] ?>">En savoir plus</a></p>
								</div>



								<?php
//**********************************************************************************************************
//                         			FORMULAIRE 'ajout_panier' QUANTITE SI STOCK
//**********************************************************************************************************


if($produit['stock'] >0) // Affiche le formulaire de selection de quantité s'il y a du stock
	{
		//---- DEBUT FORMULAIRE -----------------------------------------------------------------------------

		echo "<form id='quantite' action='' method='post'>";
			echo "<input type='hidden' name='id_produit' readonly value='$produit[id_produit]'/>";
			echo "<input type='hidden' name='quantite' readonly value='1'/>";
			echo "<input class='boutique_produit' type='submit' name='ajout_panier' value='Ajout au panier'/>";
		echo "</form>";

		//---- FIN FORMULAIRE -----------------------------------------------------------------------------

		}
	else
	{
		echo "<p>Rupture de stock</p>"; // S'il n'y a pas de stock (stock = 0)
	}

?>

							</div>

						</div>

					</div>

<?php
			}
?>
					<!-- fin PRODUIT -->


				</div> <!-- fin COLONNE 2 ......................... -->

			</div><!-- Fin de principale............................ -->
