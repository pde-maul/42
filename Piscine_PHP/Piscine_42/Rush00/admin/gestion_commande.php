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
//**********************************************************************************************************
//                         			SUPPRESSION D'UNE COMMANDE
//**********************************************************************************************************


	if(isset($_GET['action']) && $_GET['action']== "suppression")
	{

			$msg .= '<div id="msg"><p class="vert">Suppression de la commande n°'.$_GET['id'].' effectuée</p></div>';
			execute_requete("DELETE FROM commande WHERE id_commande = '$_GET[id]'");
			$_GET['action'] = "affichage"; // Petite astuce pour revenir sur la page affichage
	}



				//--- TABLEAU HTML ---------------------------------

		$tri = "";
		$col = "";
		$data = "";

		if(isset($_GET['tri']) && $_GET['tri']== "11")
		{
			$tri = "DESC";

		}elseif(isset($_GET['tri']) && $_GET['tri']== "11") {

			$tri = "ASC";
		}

		if(isset($_GET['col']))	{
			$col = "ORDER BY ".$_GET['col']."";
		}


			if(isset($_GET['data']))	{
			$data = "ORDER BY ".$_GET['data']."";
		}



	include("../inc/haut_de_site_inc.php");
	include("../inc/top_menu_inc.php");
	include("../inc/menu_inc.php");
	//**************** FIL D'ARIANE ************************* -->


	get_fil_ariane(array(
	'gestion_produit.php' => 'Administration du site',
	'final' => 'Gestion des commandes'
   ));




//********************** MESSAGE ************************* -->


			echo $msg;


//******************* MENU ADMIN ************************* -->


	include("../inc/menu_admin_inc.php");
?>
<!-- **************************************************************************************** -->
<!--  									DEUXIEME COLONNE									  -->
<!-- **************************************************************************************** -->


				<div id="colonne-unique" class="colonne2"> <!-- début colonne 2-->


					<div class="titre_h2 largeur_article"><h2>INTERFACE ADMINISTRATEUR</h2></div>
					<div class="bouton-ajout"><img src="<?php echo RACINE_SITE ?>image/affichage.png" alt="loupe"/><a href="?action=affichage"">Tableau d'affichage</a><img src="<?php echo RACINE_SITE ?>image/symbole-plus.png" alt="symbole +"/><a href="?action=ajout"">Ajouter un produit</a></div>

					<?php
					//************** Affichage du chiffre d'affaires global*******************************************
					$resultat = execute_requete("SELECT SUM(montant) AS CA FROM commande");
					$CA = mysqli_fetch_assoc($resultat);

					echo "<div class='CA'>Le Chiffre d'affaires (CA) de notre société est de : ".round($CA['CA'],2)." €</div>";
					//************************************************************************************************


					$resultat = execute_requete("SELECT * FROM commande GROUP BY  id_commande ".$col." ".$tri." "); // EXECUTION DE LA REQUETE DE SELECTION
					$CA_compte = mysqli_num_rows($resultat);
					?>





					<div id="tableau">
					<table class="tableau_admin" summary="Gestion administrateur">
					<caption>GESTION DES COMMANDES</caption>
					<thead>
					<tr>
					<th scope="col" class="petit">N° de commande</th>
					<th scope="col" >Montant</th>
					<th scope="col" class="petit">Id membre</th>
					<th scope="col">Date Commande</th>
					<th scope="col">Date Estimation</th>
					<th scope="col">Date Livraison</th>
					<th scope="col">Statut</th>
					<th scope="col">Actions</th>
					</tr>

					<tr>
					<th scope="col" class="petit"><div class="filtre"><a href="?action=affichage&page=1&tri=22&col=id_commande#tableau"><img src="<?php echo RACINE_SITE ?>image/fleche-haute.gif"/></a><a href="?action=affichage&page=1&tri=11&col=id_commande#tableau"><img src="<?php echo RACINE_SITE ?>image/fleche-bas.gif"/></a></div></th>

					<th scope="col" ><div class="filtre"><a href="?action=affichage&page=1&tri=22&col=montant#tableau"><img src="<?php echo RACINE_SITE ?>image/fleche-haute.gif"/></a><a href="?action=affichage&page=1&tri=11&col=montant#tableau"><img src="<?php echo RACINE_SITE ?>image/fleche-bas.gif"/></a></div></th>

					<th scope="col" class="petit"><div class="filtre"><a href="?action=affichage&page=1&tri=22&col=id_membre#tableau"><img src="<?php echo RACINE_SITE ?>image/fleche-haute.gif"/></a><a href="?action=affichage&page=1&tri=11&col=id_membre#tableau"><img src="<?php echo RACINE_SITE ?>image/fleche-bas.gif"/></a></div></th>

					<th scope="col"><div class="filtre"><a href="?action=affichage&page=1&tri=22&col=date_commande#tableau"><img src="<?php echo RACINE_SITE ?>image/fleche-haute.gif"/></a><a href="?action=affichage&page=1&tri=11&col=date_commande#tableau"><img src="<?php echo RACINE_SITE ?>image/fleche-bas.gif"/></a></div></th>

					<th scope="col"><div class="filtre"><a href="?action=affichage&page=1&tri=22&col=date_estimation#tableau"><img src="<?php echo RACINE_SITE ?>image/fleche-haute.gif"/></a><a href="?action=affichage&page=1&tri=11&col=date_estimation#tableau"><img src="<?php echo RACINE_SITE ?>image/fleche-bas.gif"/></a></div></th>

					<th scope="col"><div class="filtre"><a href="?action=affichage&page=1&tri=22&col=date_livraison#tableau"><img src="<?php echo RACINE_SITE ?>image/fleche-haute.gif"/></a><a href="?action=affichage&page=1&tri=11&col=date_livraison#tableau"><img src="<?php echo RACINE_SITE ?>image/fleche-bas.gif"/></a></div></th>

					<th scope="col"><div class="filtre"><a href="?action=affichage&page=1&tri=22&col=statut#tableau"><img src="<?php echo RACINE_SITE ?>image/fleche-haute.gif"/></a><a href="?action=affichage&page=1&tri=11&col=statut#tableau"><img src="<?php echo RACINE_SITE ?>image/fleche-bas.gif"/></a></div></th>

					<th scope="col"></th>
					</tr>
					</thead>

					<tfoot>
					<tr>
					<th scope="row" colspan="3">Nombre de commandes : </th>
					<td colspan="1"><?php echo $CA_compte ?></td>
					<td colspan="2"><strong>Chiffre d'affaires total : </strong></td>
					<td colspan="2"><?php echo round($CA['CA'],2) ?>€ TTC</td>
					</tr>
					</tfoot>

					<tbody>

					<?php

					while($ligne = mysqli_fetch_assoc($resultat))
					{

					?>


					<tr>
						<th  class="petit" scope="row" id="r100"><a href="?action=affichage&id=<?php echo $ligne['id_commande']?>#details_commande"><?php echo $ligne['id_commande'] ?></a></th>
						<td><?php echo $ligne['montant'] ?>€ TTC</td>
						<td><?php echo $ligne['id_membre'] ?></td>
						<td><?php echo $ligne['date_commande'] ?></td>
						<td><?php echo $ligne['date_estimation'] ?></td>
						<td><?php echo $ligne['date_livraison'] ?></td>
						<td><?php echo $ligne['statut'] ?></td>
						<td>
							<a href="?action=suppression&id=<?php echo $ligne['id_commande']?>"><img src="<?php echo RACINE_SITE ?>image/poubelle.gif"/></a>
							<a href="?action=modification&id=<?php echo $ligne['id_commande']?>">  <img src="<?php echo RACINE_SITE ?>image/edit.gif"/></a>
						</td>
					</tr>


					<?php

					}

					?>

					</tbody></table>


<?php
		//**********************************************************************************************************
		//                         				AFFICHAGE FORMULAIRE
		//									      DETAILS-COMMANDE
		//**********************************************************************************************************


					// Attention lorsqu'on a plusieurs conditions de bien mettre des parenthèses. Pour && et ||, ça sera || qui sera privilégié.
					// S'il y a un AND et un OR dans un IF, le OR prend le dessus.


				if(isset($_GET['id']))
				{

					$resultat = execute_requete("SELECT *
					FROM commande c, details_commande d, membre m, produit p
					WHERE c.id_commande = '$_GET[id]'
					AND c.id_commande = d.id_commande
					AND m.id_membre = c.id_membre
					AND p.id_produit = d.id_produit
					GROUP BY id_details_commande");



					//debug($resultat);

					if(mysqli_num_rows($resultat)== 0){
							 echo'<div class="clear"></div><div id="msg"><p class="orange">Pas de détail pour la commande n° '.$_GET['id'].'</p></div>';
							$_GET['action'] = "affichage";
							$_GET['id'] = "0";
					}else{
?>

							<table class="tableau_admin" id="details_commande" summary="Gestion administrateur">
<?php
								$commande = execute_requete("SELECT *
								FROM commande c, details_commande d, membre m, produit p
								WHERE c.id_commande = '$_GET[id]'
								AND c.id_commande = d.id_commande
								AND m.id_membre = c.id_membre
								AND p.id_produit = d.id_produit
								GROUP BY id_details_commande");

								$cde = mysqli_fetch_assoc($commande);
								echo "<caption>DETAIL DE LA COMMANDE N°".$_GET['id'].", effectuée par ".$cde['pseudo']." (membre n°".$cde['id_membre'].")</caption>";

?>
							<thead>
							<tr>
							<th scope="col" class="petit" >N° détail cde</th>
							<th scope="col" class="petit" >N° Cde</th>
							<th scope="col" class="petit">Id membre</th>
							<th scope="col" >Pseudo</th>
							<th scope="col" class="petit">Id produit</th>
							<th scope="col">Produit</th>
							<th scope="col" class="petit">Qtité</th>
							<th scope="col">Prix vendu</th>
							<th scope="col">Réduction appliquée</th>
							<th scope="col">Actions</th>
							</tr>

							<tr>
							<th scope="col" class="petit" ><div class="filtre"><a href="#"><img src="<?php echo RACINE_SITE ?>image/fleche-haute.gif"/></a><a href="#"><img src="<?php echo RACINE_SITE ?>image/fleche-bas.gif"/></a></div></th>
							<th scope="col" class="petit" ><div class="filtre"><a href="#"><img src="<?php echo RACINE_SITE ?>image/fleche-haute.gif"/></a><a href="#"><img src="<?php echo RACINE_SITE ?>image/fleche-bas.gif"/></a></div></th>
							<th scope="col" class="petit"><div class="filtre"><a href="#"><img src="<?php echo RACINE_SITE ?>image/fleche-haute.gif"/></a><a href="#"><img src="<?php echo RACINE_SITE ?>image/fleche-bas.gif"/></a></div></th>
							<th scope="col" ><div class="filtre"><a href="#"><img src="<?php echo RACINE_SITE ?>image/fleche-haute.gif"/></a><a href="#"><img src="<?php echo RACINE_SITE ?>image/fleche-bas.gif"/></a></div></th>
							<th scope="col" class="petit"><div class="filtre"><a href="#"><img src="<?php echo RACINE_SITE ?>image/fleche-haute.gif"/></a><a href="#"><img src="<?php echo RACINE_SITE ?>image/fleche-bas.gif"/></a></div></th>
							<th scope="col"><div class="filtre"><a href="#"><img src="<?php echo RACINE_SITE ?>image/fleche-haute.gif"/></a><a href="#"><img src="<?php echo RACINE_SITE ?>image/fleche-bas.gif"/></a></div></th>
							<th scope="col" class="petit"><div class="filtre"><a href="#"><img src="<?php echo RACINE_SITE ?>image/fleche-haute.gif"/></a><a href="#"><img src="<?php echo RACINE_SITE ?>image/fleche-bas.gif"/></a></div></th>
							<th scope="col"><div class="filtre"><a href="#"><img src="<?php echo RACINE_SITE ?>image/fleche-haute.gif"/></a><a href="#"><img src="<?php echo RACINE_SITE ?>image/fleche-bas.gif"/></a></div></th>
							<th scope="col"><div class="filtre"><a href="#"><img src="<?php echo RACINE_SITE ?>image/fleche-haute.gif"/></a><a href="#"><img src="<?php echo RACINE_SITE ?>image/fleche-bas.gif"/></a></div></th>
							<th scope="col"></th>
							</tr>
							</thead>
<?php
					//************** Affichage du montant de la commande *******************************************
					$total = execute_requete("SELECT SUM(prix*quantite) AS SOMME
					FROM commande c, details_commande d, membre m, produit p
					WHERE c.id_commande = '$_GET[id]'
					AND c.id_commande = d.id_commande
					AND m.id_membre = c.id_membre
					AND p.id_produit = d.id_produit ");

					$somme = mysqli_fetch_assoc($total);


					//************************************************************************************************
?>
							<tfoot>
							<tr>
							<th scope="row">Total</th>
							<td colspan="6"><?php echo mysqli_num_rows($resultat)?> produits pour cette commande</td>
							<td colspan="3"><?php echo $cde['montant'] ?>€ TTC</td>
							</tr>
							</tfoot>

							<tbody>
<?php


					// Ici on aura pu donner un autre nom à cette variable ex : $modif et dire que $_POST = $modif mais on a raccourci l'opération en écrivant directement $_POST. Il faut se souvenir que la superglobal $_POST fonctionne pour l'ajout d'un commande mais pas pour la modification car on ne soumet pas de formulaire, donc va lui dire quoi aller chercher pour remplir $_POST.

					// 2ème ligne de tableau et suivantes **********************

						while($ligne = mysqli_fetch_assoc($resultat))
						{
							//debug($ligne);
?>

							<tr>
								<th  class="petit" scope="row" id="r100"><a href="100.php"><?php echo $ligne['id_details_commande'] ?></a></th>
								<td class="petit"><?php echo $ligne['id_commande'] ?></td>
								<td class="petit"><?php echo $ligne['id_membre'] ?></td>
								<td><?php echo $ligne['pseudo'] ?></td>
								<td class="petit"><?php echo $ligne['id_produit'] ?></td>
								<td><?php echo $ligne['titre'] ?></td>
								<td class="petit"><?php echo $ligne['quantite'] ?></td>
																				<?php
			if(!empty($ligne['prix_promo'])) {

				if(!empty($ligne['reduction'])) {

					$prix = $ligne['prix_promo'] * (100 - $ligne['reduction'] )/100;
					echo "<td ><span class='prix_barre'>".$ligne['prix']."€</span><span class='prix_barre'>".$ligne['prix_promo']."€</span>-".$prix."€  </td>	";

				}else{

					echo "<td ><span class='prix_barre'>".$ligne['prix']."€</span>-".$ligne['prix_promo']."€  </td>	";

				}


			}elseif(!empty($ligne['reduction'])) {

				$prix = $ligne['prix'] * (100 - $ligne['reduction'] )/100;
				echo "<td ><span class='prix_barre'>".$ligne['prix']."€</span>-".$prix."€  </td>	";


			}else{

				echo '<td>'.$ligne['prix'].'€ </td>	';
			};



			if(!empty($ligne['reduction'])) {


								?><td><?php echo $ligne['reduction'] ?>% - code <?php echo $ligne['code_promo'] ?></td><?php
			}else{

								?><td></td><?php
			};

?>
								<td>
									<a href="?action=suppression&id=<?php echo $ligne['id_details_commande']?>"><img src="<?php echo RACINE_SITE ?>image/poubelle.gif"/></a>
									<a href="?action=modification&id=<?php echo $ligne['id_details_commande']?>">  <img src="<?php echo RACINE_SITE ?>image/edit.gif"/></a>
								</td>
							</tr>
<?php
						};
?>


						</tbody></table>

<?php
					};
				};
?>
					</div>

				</div> <!-- fin COLONNE 2 ......................... -->

			</div><!-- Fin de principale............................ -->
		
