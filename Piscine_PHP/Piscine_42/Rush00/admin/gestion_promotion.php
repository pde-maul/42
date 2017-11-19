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
	}else {

		$_GET['action']== "affichage";

	}

				$comptage = execute_requete('SELECT id_produit FROM produit');
			//debug($comptage);
			$nb_produit = $comptage->num_rows;
			$nb_produit_page = 6;
			$nb_pages = ceil($nb_produit / $nb_produit_page);


			if(isset($_GET['action']) && $_GET['action']== "affichage" && !isset($_GET['page'])){
			 header("location:?action=affichage&page=1");
			 $limite = 0;
			}elseif(isset($_GET['action']) && $_GET['action']== "affichage") {
			$limite = ($_GET['page'] - 1)*$nb_produit_page;
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


//**********************************************************************************************************
//                         		ENREGISTREMENT D'UNE NOUVELLE promotion via FORMULAIRE
//**********************************************************************************************************


	if(isset($_POST['enregistrement'])) // S'il y a clic sur bouton enregistrement
	{
		//echo "<pre>";print_r($_POST);echo "</pre>"; // Affiche ce qui a été saisi pour vérification
		//Nous allons faire les contrôles et pour cela nous savons que la référence est UNIQUE (voir BDD) donc on vérifie qu'elle n'existe pas déjà :

		$id_promo = execute_requete("SELECT * FROM promotion WHERE (id_promo = '$_POST[id_promo]' OR code_promo = '$_POST[code_promo]')");
							// Est-ce qu'il y a une ligne avec la même réference postée?


		// ATTENTION cette condition doit fonctionner dans le cas d'ajout d'promotion et dans le cas d'une modification
		// Dans le cas d'un ajout, une réference promotion ne peut être qu'unique. En modification la référence sera trouvé et récuperé

				//*****************************************************************************************

				if($id_promo->num_rows != 0 /* PRECISIONS : */ && isset($_GET['action'])&& $_GET['action'] == "ajout")
		{
			$msg .= '<div id="msg">
						<p class="orange">Réference déjà attribuée à un promotion. Merci de vérifier votre saisie.</p>
						</div>';
		}

		else     //*****************************************************************************************

		{


			$msg .= '<div id="msg">
						<p class="vert">La promotion a bien été ajoutée ou modifiée!</p>
						</div>';

			// ICI on transforme INSERT INTO en REPLACE INTO et on ajoute $_POST[id_promo] pour récupérer la valeur
			// Rappel : REPLACE permet de faire UPDATE et INSERT en même temps (pour le cas de la modification).

			execute_requete("REPLACE INTO promotion VALUES
			('$_POST[id_promo]', '$_POST[code_promo]','$_POST[reduction]')");
		}
	}
//**********************************************************************************************************
//                         		ENREGISTREMENT D'UN NOUVEAU PRODUIT via FORMULAIRE
//**********************************************************************************************************


	if(isset($_POST['modifier_produit'])) // S'il y a clic sur bouton enregistrement
	{
		//echo "<pre>";print_r($_POST);echo "</pre>"; // Affiche ce qui a été saisi pour vérification
		//Nous allons faire les contrôles et pour cela nous savons que la référence est UNIQUE (voir BDD) donc on vérifie qu'elle n'existe pas déjà :

		$id_produit = execute_requete("SELECT id_produit FROM produit WHERE id_produit = '$_POST[id_produit]'");
							// Est-ce qu'il y a une ligne avec la même réference postée?


			$msg .= '<div id="msg">
						<p class="vert">Votre produit a bien été ajouté</p>
						</div>';


			if($_POST['id_promo'] != NULL && $_POST['prix_promo']!= 0){

				$msg .= '<div id="msg">
						<p class="orange">ATTENTION !! Nous vous informons que ce produit bénéficie à la fois d\'un prix promotion et d\'un pourcentage de réduction. Vous pouvez modifier votre choix en retournant sur le produit pour supprimer l\'une des 2 réductions</p>
						</div>';

			}


			execute_requete("UPDATE produit SET id_promo = $_POST[id_promo], prix_promo = $_POST[prix_promo] WHERE id_produit = $_POST[id_produit]");

	};

//**********************************************************************************************************
//                         			SUPPRESSION D'UNE promotion
//**********************************************************************************************************


	if(isset($_GET['action']) && $_GET['action']== "suppression")
	{

			// ------------- Suppresssion de toute la ligne de l'promotion--------------------------------

			$msg .= '<div id="msg">
						<p class="vert">Suppression de la promotion '.$_GET['id'].' effectuée <br/><a href="?action=affichage">Retour au tableau d\'affichage</a></p>

						</div>';


			execute_requete("DELETE FROM promotion WHERE id_promo = '$_GET[id]'");
			//$_GET['action'] = "affichage"; // Petite astuce pour revenir sur la page affichage
	}




	include("../inc/haut_de_site_inc.php");
	include("../inc/top_menu_inc.php");
	include("../inc/menu_inc.php");
	//**************** FIL D'ARIANE ************************* -->


	get_fil_ariane(array(
	'gestion_produit.php' => 'Administration du site',
	'final' => 'Gestion des promotions'
   ));

//***************** MESSAGE ************************* -->


					echo $msg;


//***************** MENU ADMIN ************************* -->


	include("../inc/menu_admin_inc.php");
?>


<!-- **************************************************************************************** -->
<!--  									DEUXIEME COLONNE									  -->
<!-- **************************************************************************************** -->


				<div id="colonne-unique" class="colonne2"> <!-- début colonne 2-->


					<div class="titre_h2 largeur_article"><h2>INTERFACE ADMINISTRATEUR</h2></div>
					<div class="bouton-ajout"><img src="<?php echo RACINE_SITE ?>image/affichage.png" alt="loupe"/><a href="?action=affichage">Tableau d'affichage</a><img src="<?php echo RACINE_SITE ?>image/symbole-plus.png" alt="symbole +"/><a href="?action=ajout">Ajouter une promotion</a></div>
<?php
		if(isset($_GET['action']) && $_GET['action']== "affichage")
	{




		$promo = execute_requete("SELECT *
			FROM promotion
			GROUP BY  id_promo ".$col." ".$tri." "); // EXECUTION DE LA REQUETE DE SELECTION
		//debug($resultat);

?>
					<!------------- TABLEAU ------------------>
					<!---------------------------------------->


					<div id="tableau">
					<table class="tableau_admin" summary="Submitted table designs">
					<caption>GESTION DES PROMOTIONS</caption>
					<thead>
					<tr>
					<th scope="col">ID promo</th>
					<th scope="col" class="petit">Code Promo</th>
					<th scope="col">Reduction (%)</th>
					<th scope="col">Description</th>
					<th scope="col">Actions</th>
					</tr>

					<tr>
					<th scope="col"><div class="filtre"><a href="?action=affichage&page=1&tri=22&col=id_promo#tableau"><img src="<?php echo RACINE_SITE ?>image/fleche-haute.gif"/></a><a href="?action=affichage&page=1&tri=11&col=id_promo#tableau"><img src="<?php echo RACINE_SITE ?>image/fleche-bas.gif"/></a></div></th>
					<th scope="col" class="petit"><div class="filtre"><a href="?action=affichage&page=1&tri=22&col=code_promo#tableau"><img src="<?php echo RACINE_SITE ?>image/fleche-haute.gif"/></a><a href="?action=affichage&page=1&tri=11&col=code_promo#tableau"><img src="<?php echo RACINE_SITE ?>image/fleche-bas.gif"/></a></div></th>
					<th scope="col"><div class="filtre"><a href="?action=affichage&page=1&tri=22&col=reduction#tableau"><img src="<?php echo RACINE_SITE ?>image/fleche-haute.gif"/></a><a href="?action=affichage&page=1&tri=11&col=reduction#tableau"><img src="<?php echo RACINE_SITE ?>image/fleche-bas.gif"/></a></div></th>
					<th scope="col"></th>
					<th scope="col"></th>
					</tr>
					</thead>

					<tfoot>
					<tr>
					<th scope="row" colspan="2">Total</th>
					<td colspan="3"><?php echo $promo->num_rows ?> codes promo</td>
					</tr>
					</tfoot>

					<tbody>

<?php
		while($promotion = $promo->fetch_assoc())
			{
?>
					<tr >
						<th scope="row" id="r100"><?php echo $promotion['id_promo'] ?></th>
						<td class="petit"><?php echo $promotion['code_promo'] ?></td>
						<td><?php echo $promotion['reduction'] ?>%</td>
						<td>Ce code donne droit à <?php echo $promotion['reduction'] ?>% de remise sur les produits associés au code <?php echo $promotion['code_promo'] ?></td>
						<td>
							<a href='?action=suppression&id=<?php echo $promotion['id_promo'] ?>'><img src="<?php echo RACINE_SITE ?>image/poubelle.gif"/></a>
							<a href='?action=modification&id=<?php echo $promotion['id_promo'] ?>'><img src="<?php echo RACINE_SITE ?>image/edit.gif"/></a>
						</td>
					</tr>

<?php
			}
?>
						</tbody></table>
					</div>
<?php




			$produit = execute_requete("SELECT *
			FROM promotion c
			RIGHT JOIN produit p
			ON p.id_promo = c.id_promo
			GROUP BY  p.id_produit ".$data." ".$tri.""); // EXECUTION DE LA REQUETE DE SELECTION
			//debug($produit);




?>



					<div id="tableau">
						<table class="tableau_admin" summary="Submitted table designs">
						<caption>GESTION DES REMISES SUR PRODUITS</caption>
						<thead>
						<tr>
						<th scope="col">Produit</th>
						<th scope="col" class="petit">Réf.</th>
						<th scope="col">Photo</th>
						<th scope="col">Catégorie</th>
						<th scope="col" class="petit">Prix</th>
						<th scope="col" class="petit">Prix Promo</th>
						<th scope="col" class="petit">Qtité</th>
						<th scope="col" class="petit">Code Promo</th>
						<th scope="col" class="petit">Réduction</th>
						<th scope="col">Actions</th>
						</tr>

						<tr>
						<th scope="col"><div class="filtre"><a href="?action=affichage&page=1&tri=22&data=titre#tableau"><img src="<?php echo RACINE_SITE ?>image/fleche-haute.gif"/></a><a href="?action=affichage&page=1&tri=11&data=titre#tableau"><img src="<?php echo RACINE_SITE ?>image/fleche-bas.gif"/></a></div></th>

						<th scope="col" class="petit"><div class="filtre"><a href="?action=affichage&page=1&tri=22&data=ref#tableau"><img src="<?php echo RACINE_SITE ?>image/fleche-haute.gif"/></a><a href="?action=affichage&page=1&tri=11&data=ref#tableau"><img src="<?php echo RACINE_SITE ?>image/fleche-bas.gif"/></a></div></th>

						<th scope="col"><div class="filtre"><a href="?action=affichage&page=1&tri=22&data=photo#tableau"><img src="<?php echo RACINE_SITE ?>image/fleche-haute.gif"/></a><a href="?action=affichage&page=1&tri=11&data=photo#tableau"><img src="<?php echo RACINE_SITE ?>image/fleche-bas.gif"/></a></div></th>

						<th scope="col"><div class="filtre"><a href="?action=affichage&page=1&tri=22&data=categorie#tableau"><img src="<?php echo RACINE_SITE ?>image/fleche-haute.gif"/></a><a href="?action=affichage&page=1&tri=11&data=categorie#tableau"><img src="<?php echo RACINE_SITE ?>image/fleche-bas.gif"/></a></div></th>

						<th scope="col" class="petit"><div class="filtre"><a href="?action=affichage&page=1&tri=22&data=prix#tableau"><img src="<?php echo RACINE_SITE ?>image/fleche-haute.gif"/></a><a href="?action=affichage&page=1&tri=11&data=prix#tableau"><img src="<?php echo RACINE_SITE ?>image/fleche-bas.gif"/></a></div></th>

						<th scope="col" class="petit"><div class="filtre"><a href="?action=affichage&page=1&tri=22&data=prix_promo#tableau"><img src="<?php echo RACINE_SITE ?>image/fleche-haute.gif"/></a><a href="?action=affichage&page=1&tri=11&data=prix_promo#tableau"><img src="<?php echo RACINE_SITE ?>image/fleche-bas.gif"/></a></div></th>

						<th scope="col" class="petit"><div class="filtre"><a href="?action=affichage&page=1&tri=22&data=stock#tableau"><img src="<?php echo RACINE_SITE ?>image/fleche-haute.gif"/></a><a href="?action=affichage&page=1&tri=11&data=stock#tableau"><img src="<?php echo RACINE_SITE ?>image/fleche-bas.gif"/></a></div></th>

						<th scope="col" class="petit"><div class="filtre"><a href="?action=affichage&page=1&tri=22&data=code_promo#tableau"><img src="<?php echo RACINE_SITE ?>image/fleche-haute.gif"/></a><a href="?action=affichage&page=1&tri=11&data=code_promo#tableau"><img src="<?php echo RACINE_SITE ?>image/fleche-bas.gif"/></a></div></th>

						<th scope="col" class="petit"><div class="filtre"><a href="?action=affichage&page=1&tri=22&data=reduction#tableau"><img src="<?php echo RACINE_SITE ?>image/fleche-haute.gif"/></a><a href="?action=affichage&page=1&tri=11&data=reduction#tableau"><img src="<?php echo RACINE_SITE ?>image/fleche-bas.gif"/></a></div></th>

						<th scope="col"></th>
						</tr>
						</thead>

						<tfoot>
						<tr>
						<th scope="row">Total</th>
						<td colspan="9"><?php echo $produit->num_rows ?> produits</td>
						</tr>
						</tfoot>

						<tbody>

<?php
		while($ligne = $produit->fetch_assoc())
			{
				//debug($ligne);
?>
						<tr>
							<th scope="row" id="r100"><a href="<?php echo RACINE_SITE ?>fiche-produit.php?id=<?php echo $ligne['id_produit'] ?>"><?php echo $ligne['titre'] ?></a></th>
							<td class="petit"><?php echo $ligne['ref'] ?></td>
							<td><a href="<?php echo RACINE_SITE ?>fiche-produit.php?id=<?php echo $ligne['id_produit'] ?>"><img style="max-width: 80px; max-height: 100px;" src="<?php echo RACINE_SITE ?><?php echo $ligne['photo'] ?>"/></a></td>
							<td><?php echo $ligne['categorie'] ?></td>

							<form class="formulaire" id="formulaire_promo" action="" method="post" ENCTYPE="multipart/form-data" >
							<?php
								if(!empty($ligne['prix_promo'])) {
									?><td class="petit prix_barre"><?php echo $ligne['prix'] ?>€ TTC</td>


										<td class="petit"><input class="input_texte input_promo" type="text" name="prix_promo" value="<?php echo $ligne['prix_promo']; ?>" />€ TTC</td><?php

								}else {

									?><td class="petit"><?php echo $ligne['prix'] ?>€ TTC</td>
										<td class="petit"><input class="input_texte input_promo" type="text" name="prix_promo" placeholder=' ' value="" />€ TTC</td><?php
								}
							?>

							<td class="petit"><?php echo $ligne['stock'] ?></td>
							<td class="petit"><?php echo $ligne['code_promo']?></td>
							<td class="petit select">

							<?php			if($ligne['code_promo'] != NULL){

														echo '<SELECT class="selected" name="id_promo" size="1">';

											}else { echo '<SELECT name="id_promo" size="1">'; }



											$reduction = execute_requete("SELECT *
												FROM promotion"); // EXECUTION DE LA REQUETE DE SELECTION

											echo "<OPTION value='NULL' selected>0 %";

											while($reduc = $reduction->fetch_assoc())
												{

													if($ligne['code_promo'] == $reduc['code_promo']){

														echo "<OPTION value='".$reduc['id_promo']."' selected>".$reduc['reduction']." %";

													}else { echo "<OPTION value='".$reduc['id_promo']."'>".$reduc['reduction']." %"; }
												}
									?>
									</SELECT>

							</td>
							<td>
							<a class="poubelle" href='?action=suppression&id=<?php echo $ligne['id_produit'] ?>'><img src="<?php echo RACINE_SITE ?>image/poubelle.gif"/></a>
							<input type="text" name="id_produit" id="id_produit" hidden value="<?php echo $ligne['id_produit'] ?>" />
							<input id='image_modifier' type='submit' name='modifier_produit' value=''/>
							</td>

						</tr>

					</form>
<?php
			}
?>

						</tbody></table>
					</div>
<?php

	};




//**********************************************************************************************************
//                         				AFFICHAGE FORMULAIRE
//								   AJOUT ET MODIFICATION D'UN PRODUIT
//**********************************************************************************************************


	// Attention lorsqu'on a plusieurs conditions de bien mettre des parenthèses. Pour && et ||, ça sera || qui sera privilégié.
	// S'il y a un AND et un OR dans un IF, le OR prend le dessus.


	if(isset($_GET['action']) && ($_GET['action']== "ajout" || $_GET['action']== "modification" ))
	{
		if(isset($_GET['id'])) // Est-ce qu'il y a quelque chose dans ID de l'URL ? (donc est-ce qu'il y a modification d'un salle précis ?)

		{
			$modif_promo = execute_requete("SELECT * FROM promotion WHERE id_promo = '".$_GET['id']."'");
			$_POST = $modif_promo -> fetch_assoc ();

			// Ici on aura pu donner un autre nom à cette variable ex : $modif et dire que $_POST = $modif mais on a raccourci l'opération en écrivant directement $_POST. Il faut se souvenir que la superglobal $_POST fonctionne pour l'ajout d'un salle mais pas pour la modification car on ne soumet pas de formulaire, donc va lui dire quoi aller chercher pour remplir $_POST.
		}

?>

					<div id="tableau">
						<table class="tableau_admin" summary="Submitted table designs">
						<caption>GESTION DES PROMOTIONS</caption>
						<thead>
						<tr>
						<th scope="col">ID promo</th>
						<th scope="col" class="petit">Code Promo</th>
						<th scope="col">Reduction (%)</th>
						<th scope="col">Actions</th>
						</tr>

						<tr>
						<th scope="col" class="petit"><div class="filtre"><a href="#"><img src="<?php echo RACINE_SITE ?>image/fleche-haute.gif"/></a><a href="#"><img src="<?php echo RACINE_SITE ?>image/fleche-bas.gif"/></a></div></th>
						<th scope="col" class="petit"><div class="filtre"><a href="#"><img src="<?php echo RACINE_SITE ?>image/fleche-haute.gif"/></a><a href="#"><img src="<?php echo RACINE_SITE ?>image/fleche-bas.gif"/></a></div></th>
						<th scope="col" class="petit"><div class="filtre"><a href="#"><img src="<?php echo RACINE_SITE ?>image/fleche-haute.gif"/></a><a href="#"><img src="<?php echo RACINE_SITE ?>image/fleche-bas.gif"/></a></div></th>
						<th scope="col"></th>
						</tr>
						</thead>


						<tbody>
					<form id="admin_gestion_produit" class="formulaire" action="" method="post" ENCTYPE="multipart/form-data" >
					<tr >
						<th scope="row" id="r100"><input class="input_texte" type="text" readonly name="id_promo" id="id_promo" value="<?php if(isset($_POST['id_promo'])) {echo $_POST['id_promo'];} ?>" /></th>

						<td class="petit"><input class="input_texte" type="text" name="code_promo" id="code_promo" placeholder='Choisir un code' value="<?php if(isset($_POST['code_promo'])) {echo $_POST['code_promo'];} ?>" /></td>
						<td><input class="input_texte" type="text" name="reduction" id="reduction" placeholder='Pourcentage de réduction' value="<?php if(isset($_POST['reduction'])) {echo $_POST['reduction'];} ?>" /></td>
						<td>
							<a href='?action=suppression&id=<?php if(isset($_POST['id_promo'])) {echo $_POST['id_promo'];} ?>'><img src="<?php echo RACINE_SITE ?>image/poubelle.gif"/></a>
						</td>
					</tr>






						</tbody></table>

					</div>
					<input type='submit' name='enregistrement' value='<?php echo strtoupper($_GET['action'])?> DU PRODUIT'/>
					</form>



<?php

	}
?>







				</div> <!-- fin COLONNE 2 ......................... -->

			</div><!-- Fin de principale............................ -->
