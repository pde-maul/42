<?php
	include("inc/install.php");






	include("inc/haut_de_site_inc.php");
	include("inc/top_menu_inc.php");
	include("inc/menu_inc.php");

//**************** FIL D'ARIANE ************************* -->


   get_fil_ariane(array(
   'final' => 'Rechercher un produit'
   ));


//**************** MESSAGE ************************* -->


						echo $msg;

?>


<!-- **************************************************************************************** -->
<!--  									DEUXIEME COLONNE									  -->
<!-- **************************************************************************************** -->



<?php



			if(isset($_GET['recherche'])){


				if(!empty($_GET['recherche'])){

						$mot = explode(" ",$_GET['recherche']);
						$word = '%';

						for($i=0;$i<count($mot);$i++)
						{
							$word .= $mot[$i]."%";
						}

				}else{

						$word = "";
				};

					$recherche = execute_requete("SELECT DISTINCT *
					FROM produit
					WHERE titre LIKE '$word'
					OR (categorie LIKE '$word'
					OR ref LIKE '$word'
					OR prix LIKE '$word'
					OR descriptif LIKE '$word'
					OR diametre LIKE '$word'
					OR matiere LIKE '$word'
					OR hauteur LIKE '$word'
					OR poids LIKE '$word'
					OR contenance LIKE '$word'
					OR caracteristique5 LIKE '$word'
					OR caracteristique6 LIKE '$word'
					OR caracteristique7 LIKE '$word'
					OR caracteristique8 LIKE '$word'
					OR caracteristique9 LIKE '$word'
					OR caracteristique10 LIKE '$word'
					OR caracteristique11 LIKE '$word'
					OR caracteristique12 LIKE '$word'
					OR complement LIKE '$word'
					)" );


					$nb_produit = mysqli_num_rows($recherche);
?>


				<div id="rechercher" class="colonne2"> <!-- début colonne 2-->

					<div class="titre_h2 largeur_article boutique"><h2>RESULTAT DE VOTRE RECHERCHE DE PRODUIT</h2></div>
					<div class="mot_cherche"><h3>Tous les produits contenant "<?php echo $_GET['recherche'] ?>"</h3></div>
					<div><p>Nombre de produits résultant de votre recherche : <?php echo $nb_produit ?></p></div>
					<!-- PRODUIT -->



<?php


			while($produit = mysqli_fetch_assoc($recherche)){
						//debug($produit);

?>


					<div class="produit_boutique">
						<div class="image_boutique"><a href="<?php echo RACINE_SITE ?>fiche-produit.php?id=<?php echo $produit['id_produit'] ?>"><img style="max-width: 162px; max-height: 150px;" src="<?php echo RACINE_SITE ?><?php echo $produit['photo'] ?>" alt="<?php echo $produit['titre'] ?>" /></a></div>

						<div class="contenu_produit_boutique">
							<a href="<?php echo RACINE_SITE ?>fiche-produit.php?id=<?php echo $produit['id_produit'] ?>"><h3><?php echo $produit['titre'] ?></h3></a>

							<p><?php echo substr($produit['descriptif'],0, 200) ?>... </p>

						</div>

						<div class="produit-action">

							<div class="prix-fidelite">

								<div class="prix-stock">
									<p>Prix :</p>
									<p class="prix"> <?php echo $produit['prix'] ?>€ TTC</p>
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
						<!-- fin PRODUIT -->
<?php
			} // Fin du While



			};

?>

				</div> <!-- fin COLONNE 2 ......................... -->

			</div><!-- Fin de principale............................ -->
