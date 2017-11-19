<?php
	include("inc/install.php");

	$comptage = execute_requete("SELECT DISTINCT *
					FROM produit p
					WHERE p.stock > 0
					ORDER BY  p.id_produit DESC LIMIT 0,9");


//**************************************************************************************************************
//                          ETAPE 1 :::  TRANSFERT ENTRE LA FICHE_produit et LE PANIER
//***************************************************************************************************************


	creation_du_panier ();

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

					ajout_produit_au_panier($produit['titre'], $produit['id_produit'], $produit['photo'], $produit['descriptif'],$_POST['quantite'], $produit['fidelite'], $produit['categorie'], $produit['prix'], 0,0,0,0 );

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

	};




	//include("inc/haut_de_site_inc.php");
	//include("inc/top_menu_inc.php");
	//include("inc/menu_inc.php");

		if($_GET["action"] == "delete")
		{
			$supp = $_GET['pseudo'];
			if ($_SESSION['utilisateur']['pseudo'] === $supp)
			{
				header("location:connexion.php");
				echo "Votre compte a bien etait supprimer\n";
				echo $supp;
				execute_requete("DELETE FROM membre WHERE pseudo LIKE '$supp'");
				session_destroy();
			}
		}

		include("inc/haut_de_site_inc.php");
		include("inc/top_menu_inc.php");
		include("inc/menu_inc.php");
//**************** FIL D'ARIANE ************************* -->


   get_fil_ariane(array());


?>
<!-- **************** MESSAGE *************************
<?php
					echo $msg;
?>

<!-- **************************************************************************************** -->
<!--  									DEUXIEME COLONNE									  -->
<!-- **************************************************************************************** -->


				<div class="colonne2"> <!-- début colonne 2-->

					<div id="index" class="titre_h2 largeur_article"><h2>NOS NOUVEAUTES</h2></div>

					<div class="englobe_produit">


					 <!-- PRODUIT -->
<?php
			while($produit = mysqli_fetch_assoc($comptage)){
?>

					<div class="produit produit_index">


						<div class="contenu_produit">

<?php
			if(!empty($produit['prix_promo'])) {
				echo '<div class="discount">PROMO</div>';
			}
?>

							<div class="contenu_produit_image">
								<a  class="image_contenu_produit"
								href="<?php echo RACINE_SITE ?>fiche-produit.php?id=<?php echo $produit['id_produit'] ?>">
								<img style="max-width: 193px; max-height: 146px;" src="<?php echo RACINE_SITE ?><?php echo $produit['photo'] ?>" alt="<?php echo $produit['titre'] ?>" />
								</a>
							</div>

							<h3><?php echo substr($produit['titre'],0, 20) ?></h3>

							<?php
			if(!empty($produit['prix_promo'])) {
				echo '<p class="prix clignotant">'.$produit['prix_promo'].'€  ';
				echo '<span class="prix prix_barre">'.$produit['prix'].'€</span></p>';
			}else {

				echo '<p class="prix">'.$produit['prix'].'€</p>';
			}
?>




						</div>

						<div class="liens_produit">
							<div>
							<img src="<?php echo RACINE_SITE ?>image/icone_plus.gif" alt="Icone PLUS" />
							<p><a href="<?php echo RACINE_SITE ?>fiche-produit.php?id=<?php echo $produit['id_produit'] ?>">En savoir plus</a></p>
							</div>

							<div>
							<!-- <img src="<?php echo RACINE_SITE ?>image/icone_bag.gif" alt="Icone sac de course" /> -->

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
			echo "<input class='presentation_produit' type='submit' name='ajout_panier' value='Ajout au panier'/>";
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

					</div> <!-- Fin bloc produit -->
				</div> <!-- fin COLONNE 2 ......................... -->


			</div><!-- Fin de principale............................ -->
