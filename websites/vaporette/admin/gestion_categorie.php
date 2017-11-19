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

			$comptage = execute_requete('SELECT id_categorie FROM categorie');
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
//                         		ENREGISTREMENT D'UNE NOUVELLE CATEGORIE via FORMULAIRE
//**********************************************************************************************************


	if(isset($_POST['enregistrement'])) // S'il y a clic sur bouton enregistrement
	{
		//echo "<pre>";print_r($_POST);echo "</pre>"; // Affiche ce qui a été saisi pour vérification
		//Nous allons faire les contrôles et pour cela nous savons que la référence est UNIQUE (voir BDD) donc on vérifie qu'elle n'existe pas déjà :

		$id_categorie = execute_requete("SELECT * FROM categorie WHERE (id_categorie = '$_POST[id_categorie]')");
							// Est-ce qu'il y a une ligne avec la même réference postée?


		// ATTENTION cette condition doit fonctionner dans le cas d'ajout d'promotion et dans le cas d'une modification
		// Dans le cas d'un ajout, une réference promotion ne peut être qu'unique. En modification la référence sera trouvé et récuperé

				//*****************************************************************************************

				if($id_categorie->num_rows != 0 /* PRECISIONS : */ && isset($_GET['action'])&& $_GET['action'] == "ajout")
		{
			$msg .= '<div id="msg">
						<p class="orange">Réference déjà attribuée à une categorie. Merci de vérifier votre saisie.</p>
						</div>';
		}

		else     //*****************************************************************************************

		{


			$msg .= '<div id="msg">
						<p class="vert">La categorie a bien été ajoutée ou modifiée!</p>
						</div>';

			// ICI on transforme INSERT INTO en REPLACE INTO et on ajoute $_POST[id_promo] pour récupérer la valeur
			// Rappel : REPLACE permet de faire UPDATE et INSERT en même temps (pour le cas de la modification).

			execute_requete("REPLACE INTO categorie (id_categorie, nom_categorie) VALUES
			('$_POST[id_categorie]','$_POST[nom_categorie]')");
		}
	}
//**********************************************************************************************************
//                         		ENREGISTREMENT D'UNE NOUVELLE CATEGORIE via FORMULAIRE
//**********************************************************************************************************


	if(isset($_POST['modifier_produit'])) // S'il y a clic sur bouton enregistrement
	{
		//echo "<pre>";print_r($_POST);echo "</pre>"; // Affiche ce qui a été saisi pour vérification
		//Nous allons faire les contrôles et pour cela nous savons que la référence est UNIQUE (voir BDD) donc on vérifie qu'elle n'existe pas déjà :

		$id_produit = execute_requete("SELECT id_categorie FROM categorie WHERE id_categorie = '$_POST[id_categorie]'");
							// Est-ce qu'il y a une ligne avec la même réference postée?


			$msg .= '<div id="msg">
						<p class="vert">Votre categorie a bien été ajouté</p>
						</div>';


			execute_requete("UPDATE categorie SET id_categorie = $_POST[id_categorie], nom_categorie = $_POST[nom_categorie]");

	};

//**********************************************************************************************************
//                         			SUPPRESSION D'UNE CATEGORIE
//**********************************************************************************************************


	if(isset($_GET['action']) && $_GET['action']== "suppression")
	{

			// ------------- Suppresssion de toute la ligne de l'promotion--------------------------------

			$msg .= '<div id="msg">
						<p class="vert">Suppression de la catégorie '.$_GET['id_categorie'].' effectuée <br/><a href="?action=affichage">Retour au tableau d\'affichage</a></p>

						</div>';


			execute_requete("DELETE FROM categorie WHERE id_categorie = '$_GET[id_categorie]'");
			//$_GET['action'] = "affichage"; // Petite astuce pour revenir sur la page affichage
	}




	include("../inc/haut_de_site_inc.php");
	include("../inc/top_menu_inc.php");
	include("../inc/menu_inc.php");
	//**************** FIL D'ARIANE ************************* -->


	get_fil_ariane(array(
	'gestion_produit.php' => 'Administration du site',
	'final' => 'Gestion des categories'
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
					<div class="bouton-ajout"><img src="<?php echo RACINE_SITE ?>image/affichage.png" alt="loupe"/><a href="?action=affichage">Tableau d'affichage</a><img src="<?php echo RACINE_SITE ?>image/symbole-plus.png" alt="symbole +"/><a href="?action=ajout">Ajouter une categorie</a></div>
<?php
		if(isset($_GET['action']) && $_GET['action']== "affichage")
	{




		$promo = execute_requete("SELECT *
			FROM categorie
			GROUP BY  id_categorie ".$col." ".$tri." "); // EXECUTION DE LA REQUETE DE SELECTION
		//debug($resultat);

?>
					<!------------- TABLEAU ------------------>
					<!---------------------------------------->


					<div id="tableau">
					<table class="tableau_admin" summary="Submitted table designs">
					<caption>GESTION DES CATEGORIES</caption>
					<thead>
					<tr>
					<th scope="col">ID categorie</th>
					<th scope="col" class="grand">Nom de la categorie</th>
					<th scope="col">Actions</th>
					</tr>

					<tr>
					<th scope="col"><div class="filtre"><a href="?action=affichage&page=1&tri=22&col=id_categorie#tableau"><img src="<?php echo RACINE_SITE ?>image/fleche-haute.gif"/></a><a href="?action=affichage&page=1&tri=11&col=id_categorie#tableau"><img src="<?php echo RACINE_SITE ?>image/fleche-bas.gif"/></a></div></th>
					<th scope="col" class="grand"><div class="filtre"><a href="?action=affichage&page=1&tri=22&col=nom_categorie#tableau"><img src="<?php echo RACINE_SITE ?>image/fleche-haute.gif"/></a><a href="?action=affichage&page=1&tri=11&col=nom_categorie#tableau"><img src="<?php echo RACINE_SITE ?>image/fleche-bas.gif"/></a></div></th>
					<th scope="col"></th>
					</tr>
					</thead>

					<tfoot>
					<tr>
					<th scope="row" colspan="2">Total</th>
					<td colspan="3"><?php echo $promo->num_rows ?> categories de produit</td>s
					</tr>
					</tfoot>

					<tbody>

<?php
		while($promotion = $promo->fetch_assoc())
			{
?>
					<tr >
						<th scope="row" id="r100"><?php echo $promotion['id_categorie'] ?></th>
						<td class="grand"><?php echo $promotion['nom_categorie'] ?></td>
						<td>
							<a href='?action=suppression&id_categorie=<?php echo $promotion['id_categorie'] ?>'><img src="<?php echo RACINE_SITE ?>image/poubelle.gif"/></a>
							<a href='?action=modification&id_categorie=<?php echo $promotion['id_categorie'] ?>'><img src="<?php echo RACINE_SITE ?>image/edit.gif"/></a>
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
			$modif_promo = execute_requete("SELECT * FROM categorie WHERE id_categorie = '".$_GET['id']."'");
			$_POST = $modif_promo -> fetch_assoc ();

			// Ici on aura pu donner un autre nom à cette variable ex : $modif et dire que $_POST = $modif mais on a raccourci l'opération en écrivant directement $_POST. Il faut se souvenir que la superglobal $_POST fonctionne pour l'ajout d'un salle mais pas pour la modification car on ne soumet pas de formulaire, donc va lui dire quoi aller chercher pour remplir $_POST.
		}

?>

					<div id="tableau">
						<table class="tableau_admin" summary="Submitted table designs">
						<caption>GESTION DES CATEGORIES</caption>
						<thead>
						<tr>
						<th scope="col">ID categorie</th>
						<th scope="col" class="grand">Nom de la categorie</th>
						<th scope="col">Actions</th>
						</tr>

						<tr>
						<th scope="col" class="grand"><div class="filtre"><a href="#"><img src="<?php echo RACINE_SITE ?>image/fleche-haute.gif"/></a><a href="#"><img src="<?php echo RACINE_SITE ?>image/fleche-bas.gif"/></a></div></th>
						<th scope="col" class="grand"><div class="filtre"><a href="#"><img src="<?php echo RACINE_SITE ?>image/fleche-haute.gif"/></a><a href="#"><img src="<?php echo RACINE_SITE ?>image/fleche-bas.gif"/></a></div></th>
						<th scope="col"></th>
						</tr>
						</thead>

						<tbody>
					<form id="admin_gestion_produit" class="formulaire" action="" method="post" ENCTYPE="multipart/form-data" >
					<tr >
						<th scope="row" id="r100"><input class="input_texte" type="text" name="id_categorie" id="id_categorie" value="<?php if(isset($_POST['id_categorie'])) {echo $_POST['id_categorie'];}else if(isset($_GET['id_categorie'])) echo $_GET['id_categorie']; ?>" /></th>

						<td class="grand"><input class="input_texte" type="text" name="nom_categorie" id="nom_categorie" placeholder='Donner un <?php if($_GET['action'] == 'modification') echo "NOUVEAU"; ?> nom' value="<?php if(isset($_POST['nom_categorie'])) {echo $_POST['nom_categorie'];} ?>" /></td>
						<td>
							<a href='?action=suppression&id=<?php if(isset($_POST['id_categorie'])) {echo $_POST['id_categorie'];} ?>'><img src="<?php echo RACINE_SITE ?>image/poubelle.gif"/></a>
						</td>
					</tr>






						</tbody></table>

					</div>
					<input type='submit' name='enregistrement' value='<?php echo strtoupper($_GET['action'])?> DE LA CATEGORIE'/>
					</form>



<?php

	}
?>







				</div> <!-- fin COLONNE 2 ......................... -->

			</div><!-- Fin de principale............................ -->
