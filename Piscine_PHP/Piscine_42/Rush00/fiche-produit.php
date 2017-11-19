<?php
	include("inc/install.php");

	$nav_en_cours = 'page_fiche_produit';
//**********************************************************************************************************
//                         		AFFICHAGE DES INFORMATIONS DU PRODUIT
//**********************************************************************************************************


	//echo $_GET['id'];

// --------------------------- Création de la requête fiche produit ---------------------------------------

	if(isset($_GET['id'])) // Est-ce qu'il y a bien un ID renseigné dans l'URL
	{

		$fiche_produit = execute_requete("SELECT *
			FROM produit p, promotion c
			WHERE p.id_produit = ".$_GET['id']."
			AND	(c.id_promo = p.id_promo
			OR p.id_promo IS NULL)
			GROUP BY  p.id_produit");

			// On fait une requête de selection pour aller chercher les infos dU produit en fonction de l'ID de l'URL

	//debug($fiche_produit);
	}




// --------------------------- Vérification produit existant ---------------------------------


			$nb_produit_page = 3;




	if(mysqli_num_rows($fiche_produit)== 0) //Est-ce que le produit cliqué n'existe plus en base ? (il peut avoir été supprimé)
	{
		header("location:boutique.php"); // On redirige vers boutique

	}elseif(!isset($_GET['page'])){

			 header("location:?id=".$_GET['id']."&page=1");;

	}else{
			$limite = ($_GET['page'] - 1)*$nb_produit_page;
	}




// ---------------------------- Exploitation de la requête -------------------------------------


	$produit = mysqli_fetch_assoc($fiche_produit);
			//debug($produit);

			//$produit['id_produit'];



	// --------------------------- Création de la requête produits associes ---------------------------------------

	$categorie = $produit['categorie'];


	$comptage = execute_requete("SELECT DISTINCT *
					FROM produit p
					WHERE p.stock > 0
					AND p.categorie != '".$categorie."'
					ORDER BY  RAND() LIMIT 0,3");




//**************************************************************************************************************
//                          ETAPE 1 :::  TRANSFERT ENTRE LA FICHE_produit et LE PANIER
//***************************************************************************************************************


	creation_du_panier ();

	// -------------------------------------- AJOUT AU PANIER ------------------------------------------

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



// ----------------------------------------------------------------------------------------------
// --											BODY		   								   --
// ----------------------------------------------------------------------------------------------

	include("inc/haut_de_site_inc.php");
	include("inc/top_menu_inc.php");
	include("inc/menu_inc.php");

	//**************** FIL D'ARIANE ************************* -->

if($produit['categorie'] == 'E-cigarettes'){

	get_fil_ariane(array(
	'boutique.php?cat=E-cigarettes' => 'Boutique E-cigarettes',
	'final' => 'Fiche produit : '.$produit['titre']. ''
   ));
}

if($produit['categorie'] == 'E-liquides'){

	get_fil_ariane(array(
	'boutique.php?cat=E-liquides' => 'Boutique E-liquides',
	'final' => 'Fiche produit : '.$produit['titre']. ''
   ));
}

if($produit['categorie'] == 'Accessoires'){

	get_fil_ariane(array(
	'boutique.php?cat=Accessoires' => 'Boutique Accessoires',
	'final' => 'Fiche produit : '.$produit['titre']. ''
   ));
}



?>





<!-- **************** MESSAGE ************************* -->


					<?php echo $msg; ?>




<!-- **************************************************************************************** -->
<!--  									DEUXIEME COLONNE									  -->
<!-- **************************************************************************************** -->


				<div class="colonne2"> <!-- début colonne 2-->

					<div class="titre_h2 largeur_article boutique"><h2>FICHE PRODUIT</h2></div>

					<div id="fiche_produit">
					<?php
			if(!empty($produit['prix_promo'])) {
				echo '<div class="discount">PROMO</div>';
			}
?>


<!-- **************************************************************************************** -->
<!--  								PRESENTATION PRODUIT									  -->
<!-- **************************************************************************************** -->


						<div class="image_fiche_produit">

								<?php
			if(!empty($produit['prix_promo'])) {
				echo '<h3 class="deplacement">'.$produit['titre'].'</h3>';
			} else {

				echo '<h3>'.$produit['titre'].'</h3>';
			}
?>
						<a class="image-popup-no-margins" href="<?php echo $produit['photo'] ?>">
						<img  style="max-width: 395px; max-height: 297px" src="<?php echo $produit['photo'] ?>" title="<?php echo $produit['titre'] ?>" alt="<?php echo $produit['titre'] ?>" />
						</a>




						</div>  <!-- Fin image_fiche_produit -->


						<div class="fiche-produit-action">


								<!-- PRIX STOCK ------------------------------------------------------------>

								<div class="fiche-produit-prix-stock">


<?php
			if(!empty($produit['prix_promo'])) {
				echo '<p>Prix PROMO:</p>';
				echo '<p class="prix clignotant">'.$produit['prix_promo'].'€  ';
				echo '<span class="prix prix_barre">'.$produit['prix'].'€</span></p>';
			}else {

				echo '<p>Prix :</p>';
				echo '<p class="prix">'.$produit['prix'].'€</p>';
			}
?>



									 <?php if ($produit['stock']>0){
										 echo '<p class="vert">En stock</p>';
										 }else{
										 echo '<p class="orange">Rupture de stock</p>';
										 };
									?>




<?php
//**********************************************************************************************************
//                         			FORMULAIRE 'ajout_panier' QUANTITE SI STOCK
//**********************************************************************************************************


if($produit['stock'] >0) // Affiche le formulaire de selection de quantité s'il y a du stock
	{
		//---- DEBUT FORMULAIRE -----------------------------------------------------------------------------

		echo "<form id='quantite' action='panier.php' method='post'>";
			echo "<input type='hidden' name='id_produit' readonly value='$produit[id_produit]'/>";
			echo "<label>Quantité </label>";
				echo "<select name='quantite'>";

			//------------ Menu déroulant qui permet de commander tout le stock restant ---------------------

					for($i=1; $i<= $produit['stock'] && $i<= 10 ; $i++)

					// Ici on lui dit de prendre comme valeur du stock soit la valeur du stock, soit 5 sachant que le code prendra la valeur la plus petite. Si par exemple le stock = 3 alors il n'affichera pas 5 mais 3.
						{
							echo "<option>$i</option>";
						}

			//-------------FIN menu déroulant quantité  ------------------------------------------------------

				echo "</select>";
				echo "<input type='submit' name='ajout_panier' value='Ajout au panier'/>";
		echo "</form>";

		//---- FIN FORMULAIRE -----------------------------------------------------------------------------

		}
	else
	{
		echo "<p>Rupture de stock</p>"; // S'il n'y a pas de stock (stock = 0)
	}

?>



								</div>
						</div>  <!-- Fin fiche-produit-action -->


					<!-- **************************************************************************************** -->
					<!--  							ONGLETS DESCRIPTIFS 								  -->
					<!-- **************************************************************************************** -->

					<div id="tabbed_area" class="contenu_fiche_produit">

					<ul class="tabs">
						<li>Descriptif</li>
					</ul>

							<div  id="content_1" class="content">
							<p><?php echo $produit['descriptif'] ?></p>

							<p><em>Caractéristiques</em> : </p>
								<ul>
									<li>Diamètre: <?php echo $produit['diametre'] ?>mm</li>
									<li>Matière: <?php echo $produit['matiere'] ?></li>
									<li>Hauteur et poids: <?php echo $produit['hauteur'] ?> mm pour <?php echo $produit['poids'] ?> g</li>
									<li>Contenance: <?php echo $produit['contenance'] ?> ml </li>
									<li><?php echo $produit['caracteristique5'] ?></li>
									<li><?php echo $produit['caracteristique6'] ?></li>
									<li><?php echo $produit['caracteristique7'] ?></li>
									<li><?php echo $produit['caracteristique8'] ?></li>
									<li><?php echo $produit['caracteristique9'] ?></li>
									<li><?php echo $produit['caracteristique10'] ?></li>
									<li><?php echo $produit['caracteristique11'] ?></li>
								</ul>
							</div>





						</div> <!-- fin DES ONGLETS DESCRIPTION  -->



					</div>	<!-- Fin de la description du produit -->




				</div> <!-- fin COLONNE 2 ......................... -->

			</div><!-- Fin de principale............................ -->
