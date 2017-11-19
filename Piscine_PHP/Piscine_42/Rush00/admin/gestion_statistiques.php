<?php
	include("../inc/install.php");

//**********************************************************************************************************
//                         				CONTROLE DE L'ACCES A LA PAGE ADMIN
//**********************************************************************************************************

	if(!utilisateur_est_connecte_et_admin()) // Nous voulons limiter l'accès à cette page aux seuls membres admin. Est-ce que l'internaute n'est pas connecté et n'est pas admin?
	{
		header("location:../connexion.php"); // Redirection vers la page connexion
		die ();

		// TRES IMPORTANT -> pour éviter d'executer le code en dessous, on fait un DIE (comme break), le code s'arrête ici.
	}


	include("../inc/haut_de_site_inc.php");
	include("../inc/top_menu_inc.php");
	include("../inc/menu_inc.php");
	//**************** FIL D'ARIANE ************************* -->


	get_fil_ariane(array(
	'gestion_produit.php' => 'Administration du site',
	'final' => 'Consulter les statistiques'
   ));

?>
<!-- **************** MENU ADMIN ************************* -->

<?php
	include("../inc/menu_admin_inc.php");
?>


<!-- **************************************************************************************** -->
<!--  									DEUXIEME COLONNE									  -->
<!-- **************************************************************************************** -->
				<div id="colonne-unique" class="colonne2"> <!-- début colonne 2-->

					<div class="titre_h2 largeur_article"><h2>INTERFACE ADMINISTRATEUR</h2></div>

					<aside class="statistiques">
						<div class="aside_block">

<!-- ******* BOITE 2 : gauche ******* -->
					<div class="block">
						<div class="titre_h2 largeur"><h2>MEILLEURES VENTES</h2></div>
						<a class="lien_stat" href="?action=vente#resultat"><div class="stat stat2 <?php if(isset($_GET['action']) && $_GET['action']== "vente") echo 'result2'?>">
								<p>Top 8</p>
								<p> des produits les plus vendus</p>
								<i class="fa fa-bar-chart fa-3x"></i>
						</div></a>
					</div>


<!-- ******* BOITE Fdroite ******* -->
					<div class="block">
						<div class="titre_h2 largeur"><h2>RENTABILITE</h2></div>
						<a class="lien_stat" href="?action=prix#resultat"><div class="stat stat4 <?php if(isset($_GET['action']) && $_GET['action']== "prix") echo 'result4'?>">
								<p>Top 8</p>
								<p>des membres qui achetent le plus cher</p>
								<i class="fa fa-money fa-3x"></i>
						</div>	</a>
					</div>

				</div>



				</aside>



<?php


//**********************************************************************************************************
//                         		 Top 5 des produits les mieux vendus
//**********************************************************************************************************


	if(isset($_GET['action']) && $_GET['action']== "vente")
	{

		$vente = execute_requete("SELECT *, COUNT(id_details_commande) AS produit
			FROM produit p, details_commande d
			WHERE p.id_produit = d.id_produit
			GROUP BY p.id_produit
			ORDER BY COUNT(d.id_produit) DESC LIMIT 0,8" );

			$j=1;
			echo '<div id="resultat"  class="titre_h2 largeur_article clear"><h2>Top 5 des produits les mieux vendus</h2></div>';
			echo "<div class='block_produit'>";

			while($produit = mysqli_fetch_assoc($vente)){
				//debug($produit);
			$compte = $produit['produit'];
?>



				<div class="produit">

						<h3 class="result2">N° <?php echo $j++ ?> du classement</h3>
						<h4 class="h4_stat">Produit commandé : <br/><?php echo $compte ?> fois </h4>
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
				</div>
<?php		}
?>

	</div>

<?php
	};

//**********************************************************************************************************
//                         Top 5 des membres qui achetent le plus cher(en terme de prix)
//**********************************************************************************************************


if(isset($_GET['action']) && $_GET['action']== "prix")
	{

		$prix = execute_requete("SELECT *, SUM(montant) AS montant
			FROM commande c, membre m
			WHERE c.id_membre = m.id_membre
			GROUP BY c.id_membre
			ORDER BY montant DESC LIMIT 0,8" );
			$i=1;

			echo '<div id="resultat"  class="titre_h2 largeur_article clear"><h2>Top 5 des membres qui achètent le cher</h2></div>';

			while($produit = mysqli_fetch_assoc($prix)){

				$compte = round($produit['id_membre'],2);
?>

					<div class="produit">
					<h3 class="result4">N° <?php echo $i++ ?> du classement</h3>
<?php
				echo "
						<div class='offre_produit margin_r35'>
						<h4>Montant des achats :<br/> ".round($produit['montant'],2)."€ TTC</h4>

						<div class='titre_salle'>
						<p>Pseudo : ".$produit['pseudo']."</p>
						<h5>Détails du membre : </h5>
						<p>".$produit['prenom'].", ".$produit['nom']."</p>
						<p>".$produit['adresse']."</p>
						<p>".$produit['cp']." ".strtoupper($produit['ville'])."</p>
						<p> Sexe : ".strtoupper($produit['sexe']).", Statut : ".$produit['statut']."</p>
						</div>

						</div>

					</div>	";

			}
?>

<?php
	}
?>
				</div> <!-- fin COLONNE 2 ......................... -->

			</div><!-- Fin de principale............................ -->
