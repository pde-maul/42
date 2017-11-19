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
			$comptage = execute_requete('SELECT id_produit FROM produit');
			//debug($comptage);
			$nb_produit = mysqli_num_rows($comptage);
			$nb_produit_page = 6;
			$nb_pages = ceil($nb_produit / $nb_produit_page);


			if(isset($_GET['action']) && $_GET['action']== "affichage" && !isset($_GET['page'])){
			 header("location:?action=affichage&page=1");
			 $limite = 0;
			}elseif(isset($_GET['action']) && $_GET['action']== "affichage") {
			$limite = ($_GET['page'] - 1)*$nb_produit_page;
			}


//**********************************************************************************************************
//                         		ENREGISTREMENT D'UN NOUVEAU PRODUIT via FORMULAIRE
//**********************************************************************************************************


	if(isset($_POST['enregistrement'])) // S'il y a clic sur bouton enregistrement
	{
		//echo "<pre>";print_r($_POST);echo "</pre>"; // Affiche ce qui a été saisi pour vérification
		//Nous allons faire les contrôles et pour cela nous savons que la référence est UNIQUE (voir BDD) donc on vérifie qu'elle n'existe pas déjà :

		$id_produit = execute_requete("SELECT id_produit FROM produit WHERE id_produit = '$_POST[id_produit]'");
							// Est-ce qu'il y a une ligne avec la même réference postée?


		// ATTENTION cette condition doit fonctionner dans le cas d'ajout d'produit et dans le cas d'une modification
		// Dans le cas d'un ajout, une réference produit ne peut être qu'unique. En modification la référence sera trouvé et récuperé

				//*****************************************************************************************

				if(mysqli_num_rows($id_produit)!= 0 /* PRECISIONS : */ && isset($_GET['action'])&& $_GET['action'] == "ajout")
		{
			$msg .= "<div class='erreur'>Réference déjà attribuée à un produit. Merci de vérifier votre saisie.</div>";
		}

		else     //*****************************************************************************************

		{

			$photo_bdd = "";
			$nom_photo = "";

			//-- DANS LE CAS D'UNE MODIFICATION DE PHOTO ---------------------------------------------


				// ICI une fois qu'on a rajouté notre input dans le formulaire (voir ci-dessous), on rajoute une condition dans le cas où photo_actuelle existe alors photo_bdd prend la valeur de $_POST['photo_actuelle']


			if(isset($_POST['photo_actuelle']))
			{
				$nom_photo = $_POST['photo_actuelle'];
			}


			//-- FIN MODIFICATION DE PHOTO ----------------------------------------------------------


			if(!empty($_FILES['photo']['name'])) // ici on execute le code uniquement s'il y a upload donc si on fait une modification ça ne marche pas.
			{
				//--DEBUT UPLOAD FICHIER-----------------------------------------------------------------

					//echo "<pre>";print_r($_FILES);echo "</pre>";

					// Superglobal qui permet de récupérer les informations sur les éléments uploadés
					//Ici au print_r on se rend compte que l'array de photo est situé dans un l'array FILES donc pour afficher le nom du fichier, on écrira : echo $_FILES['photo']['name'];


					/***************** AFFECTE UN NOM UNIQUE à un fichier ****************************/
					// Pour cela on utilise la ref du produit qui est unique. Attention a mettre la réference avant le nom sinon on aura "jean.jpg-246" au lieau de "246-jean.jpg". On met la réference pour éviter de créer des doublons entre les images qui auraient à la base le même nom.

					$nom_photo = "images/".$_POST['id_produit']."-".$_POST['ref']."-". $_FILES['photo']['name'];


					/***************** LIEN + NOM de l'IMAGE ****************************/
					// CHEMIN depuis le site

					$photo_bdd = RACINE_SITE2 . $nom_photo;
					//echo $photo_bdd;
					// ici on a /diw30_php/partie3/image/devis-moto.jpg
					//----------------------------------------------------



					/************ LIEN du serveur + Lien photo + NOM de l'IMAGE ***********/
					// CHEMIN FINAL depuis serveur

					$photo_dossier = $_SERVER['DOCUMENT_ROOT'].$photo_bdd;
					// ici, on aura : C:/wamp/www/  ++ /diw30_php/partie3/image/devis-moto.jpg
					//echo $photo_dossier;
					//----------------------------------------------------
					//echo $photo_dossier;

					/************** ICI il faut uploader l'image en base ******************/

					copy ($_FILES['photo']['tmp_name'],$photo_dossier);
					//Pour uploader un fichier, on utilise copy qui a 2 arguments (1er argument est le chemin temporaire et 2eme argument est le chemin final dans un dossier image depuis racine_serveur)

					//ATTENTION : mettre une contrainte pour télécharger UNIQUEMENT des fichiers avec des extensions images et non pas par exemple des fichiers PHP qui peuvent hacker notre site


				//--FIN UPLOAD FICHIER-------------------------------------------------------
			}

			$msg .= '<div id="msg">
						<p class="vert">Votre produit a bien été ajouté</p>
						</div>';

			// ICI on transforme INSERT INTO en REPLACE INTO et on ajoute $_POST[id_produit] pour récupérer la valeur
			// Rappel : REPLACE permet de faire UPDATE et INSERT en même temps (pour le cas de la modification).

			$titre = mysqli_real_escape_string ($mysqli, $_POST['titre']);
			$matiere = mysqli_real_escape_string ($mysqli, $_POST['matiere']);
			$descriptif = mysqli_real_escape_string ($mysqli, $_POST['descriptif']);
			$caracteristique5 = mysqli_real_escape_string ($mysqli, $_POST['caracteristique5']);
			$caracteristique6 = mysqli_real_escape_string ($mysqli, $_POST['caracteristique6']);
			$caracteristique7 = mysqli_real_escape_string ($mysqli, $_POST['caracteristique7']);
			$caracteristique8 = mysqli_real_escape_string ($mysqli, $_POST['caracteristique8']);
			$caracteristique9 = mysqli_real_escape_string ($mysqli, $_POST['caracteristique9']);
			$caracteristique10 = mysqli_real_escape_string ($mysqli, $_POST['caracteristique10']);
			$caracteristique11 = mysqli_real_escape_string ($mysqli, $_POST['caracteristique11']);
			$caracteristique12 = mysqli_real_escape_string ($mysqli, $_POST['caracteristique12']);
			$complement = mysqli_real_escape_string ($mysqli, $_POST['complement']);

			$envoi_descriptif = $_POST['descriptif'];


			$count = $_POST['categorie'];
			$categorie = "";
			/* On supprime toutes les categories de ID produit pour les remplacer */
			execute_requete("DELETE FROM details_categorie WHERE id_produit = '".$_POST['id_produit']."'");

			foreach($count as $cat)
			{
				/* Affichage des categories dans la colonne du produit */
				$categorie .= $cat.', ';

				/* On recupere l'ID de la categorie */
				$req_categorie = execute_requete("SELECT id_categorie FROM categorie WHERE nom_categorie = '".$cat."'");
				$id_categorie = mysqli_fetch_assoc($req_categorie);

				/* Ajout des categories dans details_categorie pour reinitialiser les champs */
				execute_requete("INSERT INTO details_categorie (id_categorie, id_produit) VALUES ($id_categorie[id_categorie], $_POST[id_produit])");
			}
			execute_requete("REPLACE INTO produit VALUES
			('$_POST[id_produit]' , '$titre' , '$nom_photo' , '$_POST[prix]' , '$_POST[prix_promo]' ,'".$categorie."' , '$_POST[stock]' , '$_POST[ref]' , '".$descriptif."' ,  '$_POST[diametre]' , '$matiere' , '$_POST[hauteur]' , '$_POST[poids]' , '$_POST[contenance]' , '$caracteristique5' , '$caracteristique6' , '$caracteristique7' , '$caracteristique8' , '$caracteristique9' , '$caracteristique10' , '$caracteristique11' , '$caracteristique12' , '$complement' , '5')");
		}
	}


//**********************************************************************************************************
//                         			SUPPRESSION D'UN PRODUIT AVEC IMAGE
//**********************************************************************************************************


	if(isset($_GET['action']) && $_GET['action']== "suppression")
	{
			// ------------- Suppression de l'image --------------------------------------------------

			$resultat = execute_requete("SELECT * FROM produit WHERE id_produit = '$_GET[id]'");
			$produit_a_supprimer = mysqli_fetch_assoc($resultat);
			//echo "<pre>";print_r($produit_a_supprimer);echo "</pre>";

						// Ici on voit que le seul indice de ce tableau est "photo" qui renvoie au lien de l'image
						//Pour utiliser unlink () : Il faut communiquer le chemin de l'image DEPUIS le serveur donc on crée une variable chemin depuis le serveur :

			$chemin_photo_dossier = $_SERVER['DOCUMENT_ROOT'].RACINE_SITE. $produit_a_supprimer['photo'];
						//echo $chemin_photo_dossier;
						// A retenir : $_SERVER['DOCUMENT_ROOT'] qui correspond au chemin depuis le serveur jusqu'à RACINE_SITE.
						// Attention car si on utilise chemin_photo_dossier on supprime une image qu'elle soit existante ou non, du coup on doit vérifier ce paramètre unlink($chemin_photo_dossier)
						// On supprime donc la photo phyiquement depuis le serveur UNIQUEMENT si le lien existe et UNIQUEMENT si l'image existe physiquement dans le dossier image

			if(!empty($produit_a_supprimer['photo']) && file_exists($chemin_photo_dossier))

			{
				unlink($chemin_photo_dossier);
			}


			// ------------- Suppresssion (et affichage) de toute la ligne du produit --------------------------------

			$msg .= '<div id="msg">
						<p class="vert">Suppression du produit '."$produit_a_supprimer[titre]".', ID n° '."$_GET[id]".', effectuée</p>
						</div>';


			execute_requete("DELETE FROM produit WHERE id_produit = '$_GET[id]'");
			//$_GET['action'] = "affichage"; // Petite astuce pour revenir sur la page affichage
	}


//**********************************************************************************************************
//                         				INCLUSION DE FICHIERS ET MESSAGES
//**********************************************************************************************************




	include("../inc/haut_de_site_inc.php");
	include("../inc/top_menu_inc.php");
	include("../inc/menu_inc.php");
	//**************** FIL D'ARIANE ************************* -->


	get_fil_ariane(array(
	'gestion_produit.php' => 'Administration du site',
	'final' => 'Gestion des produits'
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
					<div class="bouton-ajout"><img src="<?php echo RACINE_SITE ?>image/affichage.png" alt="loupe"/><a href = "?action=affichage">Tableau d'affichage</a><img src="<?php echo RACINE_SITE ?>image/symbole-plus.png" alt="symbole +"/><a href = "?action=ajout">Ajouter un produit</a></div>


<?php



//**********************************************************************************************************
//**********************************************************************************************************
//                         ------------ T.A.B.L.E.A.U ------------
//
//						AFFICHER DES PRODUITS DANS TABLEAU RECAPITULATIF
//**********************************************************************************************************
//**********************************************************************************************************


	if(isset($_GET['action']) && $_GET['action']== "affichage")
	{


		//--- TABLEAU HTML ---------------------------------

		$tri = "";
		$col = "";

		if(isset($_GET['tri']) && $_GET['tri']== "11")
		{
			$tri = "DESC";

		}elseif(isset($_GET['tri']) && $_GET['tri']== "11") {

			$tri = "ASC";
		}

		if(isset($_GET['col']))	{
			$col = "ORDER BY ".$_GET['col']."";
		}



		$resultat = execute_requete("SELECT *
			FROM promotion c
			RIGHT JOIN produit p
			ON p.id_promo = c.id_promo
			GROUP BY  p.id_produit ".$col." ".$tri." "); // EXECUTION DE LA REQUETE DE SELECTION
		//debug($resultat);


?>



					<div id="tableau">
						<table class="tableau_admin" summary="Submitted table designs">
						<caption>GESTION DES PRODUITS</caption>
						<thead>
						<tr>
						<th scope="col">Produit</th>
						<th scope="col" class="petit">Réf.</th>
						<th scope="col">Photo</th>
						<th scope="col">Catégorie</th>
						<th scope="col" class="petit">Prix</th>
						<th scope="col" class="petit">Qtité</th>
						<th scope="col" class="tailleth">Descriptif général</th>
						<th scope="col" class="tailleth">Liste des caractéristiques</th>
						<!-- <th scope="col" class="petit">Fidélité</th>
						<th scope="col" class="petit">Note</th> -->
						<th scope="col">Actions</th>
						</tr>

						<tr>
						<th scope="col"><div class="filtre"><a href="?action=affichage&page=1&tri=22&col=titre#tableau"><img src="<?php echo RACINE_SITE ?>image/fleche-haute.gif"/></a><a href="?action=affichage&page=1&tri=11&col=titre#tableau"><img src="<?php echo RACINE_SITE ?>image/fleche-bas.gif"/></a></div></th>

						<th scope="col" class="petit"><div class="filtre"><a href="?action=affichage&page=1&tri=22&col=ref#tableau"><img src="<?php echo RACINE_SITE ?>image/fleche-haute.gif"/></a><a href="?action=affichage&page=1&tri=11&col=ref#tableau"><img src="<?php echo RACINE_SITE ?>image/fleche-bas.gif"/></a></div></th>

						<th scope="col"><div class="filtre"><a href="?action=affichage&page=1&tri=22&col=photo#tableau"><img src="<?php echo RACINE_SITE ?>image/fleche-haute.gif"/></a><a href="?action=affichage&page=1&tri=11&col=photo#tableau"><img src="<?php echo RACINE_SITE ?>image/fleche-bas.gif"/></a></div></th>

						<th scope="col"><div class="filtre"><a href="?action=affichage&page=1&tri=22&col=categorie#tableau"><img src="<?php echo RACINE_SITE ?>image/fleche-haute.gif"/></a><a href="?action=affichage&page=1&tri=11&col=categorie#tableau"><img src="<?php echo RACINE_SITE ?>image/fleche-bas.gif"/></a></div></th>

						<th scope="col" class="petit"><div class="filtre"><a href="?action=affichage&page=1&tri=22&col=prix#tableau"><img src="<?php echo RACINE_SITE ?>image/fleche-haute.gif"/></a><a href="?action=affichage&page=1&tri=11&col=prix#tableau"><img src="<?php echo RACINE_SITE ?>image/fleche-bas.gif"/></a></div></th>

						<th scope="col" class="petit"><div class="filtre"><a href="?action=affichage&page=1&tri=22&col=stock#tableau"><img src="<?php echo RACINE_SITE ?>image/fleche-haute.gif"/></a><a href="?action=affichage&page=1&tri=11&col=stock#tableau"><img src="<?php echo RACINE_SITE ?>image/fleche-bas.gif"/></a></div></th>

						<th scope="col" class="tailleth"><div class="filtre"><a href="?action=affichage&page=1&tri=22&col=descriptif#tableau"><img src="<?php echo RACINE_SITE ?>image/fleche-haute.gif"/></a><a href="?action=affichage&page=1&tri=11&col=descriptif#tableau"><img src="<?php echo RACINE_SITE ?>image/fleche-bas.gif"/></a></div></th>

						<th scope="col" class="tailleth"></th>

						<!-- <th scope="col" class="petit"><div class="filtre"><a href="?action=affichage&page=1&tri=22&col=fidelite#tableau"><img src="<?php echo RACINE_SITE ?>image/fleche-haute.gif"/></a><a href="?action=affichage&page=1&tri=11&col=fidelite#tableau"><img src="<?php echo RACINE_SITE ?>image/fleche-bas.gif"/></a></div></th>

						<th scope="col" class="petit"></th> -->

						<th scope="col"></th>
						</tr>
						</thead>

						<tfoot>
						<tr>
						<th scope="row">Total</th>
						<td colspan="10"><?php echo mysqli_num_rows($resultat)?> produits</td>
						</tr>
						</tfoot>

						<tbody>

<?php
		while($ligne = mysqli_fetch_assoc($resultat))
			{
?>
						<tr>
							<th scope="row" id="r100"><a href="<?php echo RACINE_SITE ?>fiche-produit.php?id=<?php echo $ligne['id_produit'] ?>"><?php echo $ligne['titre'] ?></a></th>
							<td class="petit"><?php echo $ligne['ref'] ?></td>
							<td><a href="<?php echo RACINE_SITE ?>fiche-produit.php?id=<?php echo $ligne['id_produit'] ?>"><img style="max-width: 80px; max-height: 100px;" src="<?php echo RACINE_SITE ?><?php echo $ligne['photo'] ?>"/></a></td>
							<td><?php echo $ligne['categorie'] ?></td>
							<td class="petit"><?php echo $ligne['prix'] ?>€ TTC</td>
							<td class="petit"><?php echo $ligne['stock'] ?></td>
							<td class="taille"><div><?php echo $ligne['descriptif'] ?></div></td>
							<td class="taille">
								<div>
									<?php if( empty($ligne['diametre'])){ echo " ";}else{ echo "Diamètre: ".$ligne['diametre']." mm,"; };?>
									<?php if( empty($ligne['matiere'])){ echo " ";}else{ echo "Matière: ".$ligne['matiere'].", "; };?>
									<?php if( empty($ligne['hauteur'])){ echo " ";}else{ echo "Hauteur et poids: ".$ligne['hauteur']." mm pour "; };?>
									<?php if( empty($ligne['poids'] )){ echo " ";}else{ echo $ligne['poids']." g, "; };?>
									<?php if( empty($ligne['contenance'] )){ echo " ";}else{ echo "Contenance: ".$ligne['contenance']." ml "; };?>
									<?php if( empty($ligne['caracteristique5'] )){ echo " ";}else{ echo ", ".$ligne['caracteristique5']; };?>
									<?php if( empty($ligne['caracteristique6'] )){ echo " ";}else{ echo ", ".$ligne['caracteristique6']; };?>
									<?php if( empty($ligne['caracteristique7'])){ echo " ";}else{ echo ", ".$ligne['caracteristique7']; };?>
									<?php if( empty($ligne['caracteristique8'] )){ echo " ";}else{ echo ", ".$ligne['caracteristique8']; };?>
									<?php if( empty($ligne['caracteristique9'] )){ echo " ";}else{ echo ", ".$ligne['caracteristique9']; };?>
									<?php if( empty($ligne['caracteristique10'] )){ echo " ";}else{ echo ", ".$ligne['caracteristique10']; };?>
									<?php if( empty($ligne['caracteristique11'] )){ echo " ";}else{ echo ", ".$ligne['caracteristique11']; };?>
									<?php if( empty($ligne['caracteristique12'] )){ echo " ";}else{ echo ", ".$ligne['caracteristique12']; };?>
								</div>
							</td>
							<!-- <td class="petit"><?php echo $ligne['fidelite'] ?></td>
							<td class="petit"><?php echo round($MOYENNE,1); ?>/5</td> -->

							<td>
							<a href='?action=suppression&id=<?php echo $ligne['id_produit'] ?>'><img src="<?php echo RACINE_SITE ?>image/poubelle.gif"/></a>
							<a href='?action=modification&id=<?php echo $ligne['id_produit'] ?>'><img src="<?php echo RACINE_SITE ?>image/edit.gif"/></a>
							</td>

						</tr>
<?php
			}
?>

						</tbody></table>
					</div>
<?php

	}


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
			$resultat = execute_requete("SELECT * FROM produit WHERE id_produit = '$_GET[id]'");
			$_POST = mysqli_fetch_assoc($resultat);

			// Ici on aura pu donner un autre nom à cette variable ex : $modif et dire que $_POST = $modif mais on a raccourci l'opération en écrivant directement $_POST. Il faut se souvenir que la superglobal $_POST fonctionne pour l'ajout d'un salle mais pas pour la modification car on ne soumet pas de formulaire, donc va lui dire quoi aller chercher pour remplir $_POST.
		}

?>


					<form id="admin_gestion_produit" class="formulaire" action="" method="post" ENCTYPE="multipart/form-data" >
					<!-- enctype="multipart/form-data" est TRES important pour uploader des fichiers-->

					<input type="text" id="titre_produit" name="titre"   maxlength="60" placeholder="TITRE DU PRODUIT" value="<?php if(isset($_POST['titre'])) {echo $_POST['titre'];}?>"/>

					<div id="fiche_produit">

						<div class="image_fiche_produit">

					<!-- ici dans le cadre d'une modification il faut ajouter un input pour indiquer la valeur de la photo : -->
			<?php

				if(isset($_GET['action']) && $_GET['action'] == "modification")
					{
						echo "<label for='photo'>Photo actuelle </label>";
						echo "<input type='text' name='photo_actuelle' value='$_POST[photo]'/>";
						echo "<input type='file' name='photo' id='photo'><br />";
						echo "<img style='max-width: 395px; max-height: 281px; border: 1px solid #a6a1a1;'  src='".RACINE_SITE."/$_POST[photo]' /><br />"; // On affiche la photo actuelle

					}else{

						echo '<label for="photo">Photo</label>';
						echo '<input type="file" name="photo" id="photo"><br />';
						echo '<img style="max-width: 395px; max-height: 297px"  src="'.RACINE_SITE.'image/image-defaut.jpg" alt="image par defaut" />';

					}

			?>

						<label for="descriptif">Descriptif du produit</label>
						<textarea id="descriptif" name="descriptif" placeholder="Décrivez le produit en quelques lignes"><?php if(isset($_POST['descriptif'])) {echo $_POST['descriptif'];}?></textarea>


						</div>


						<div class="fiche-produit-action admin_fiche_produit_action ">

							<label class="id" for="id_produit">ID Produit</label>
							<input type="text" id="id_produit" name="id_produit" readonly value="<?php if(isset($_POST['id_produit'])) {echo $_POST['id_produit'];}?>" style="width : 40px" /> <br />


								<div class="fiche-produit-prix-stock admin_prix_stock">
									<p class="admin_prix_produit">Prix du produit :</p>
									<input class="gestion_produit_qtite" type="text" name="prix" id="prix" value="<?php if(isset($_POST['prix'])) {echo $_POST['prix'];}?>"/>
									<input class="gestion_produit_qtite" type="text" name="prix_promo" id="prix_promo" hidden value="<?php if(isset($_POST['prix_promo'])) {echo $_POST['prix_promo'];}?>"/>

									<p class="vert">Catégorie du produit :</p>
									<!-- <input type="checkbox" name="choix1" value="1"> glace vanille
									<input type="checkbox" name="choix2" value="2"> chantilly
									<input type="checkbox" name="choix3" value="3"> chocolat chaud
									<input type="checkbox" name="choix4" value="4"> biscuit
									 <select class="categorie_produit" name="categorie"> -->
									 <?php
									 $compte = execute_requete("SELECT nom_categorie
									 	FROM categorie");
										while ($cat = mysqli_fetch_assoc($compte))
										{
										?>
										 <input type="checkbox" name="categorie[]" value="<?php echo $cat['nom_categorie']; ?>" <?php if(isset($_POST['categorie'])&& $_POST['categorie']== $cat['nom_categorie']) echo "checked";?> /><?php echo $cat['nom_categorie']; ?><br/>
										<?php
									} ?>

								<br/>
										<label for="stock">Quantité en stock :</label>
										<input class="gestion_produit_qtite" type="text" name="stock" id="stock" value="<?php if(isset($_POST['stock'])) {echo $_POST['stock'];}?>"/><br/>

										<label for="ref">Réf. :</label><br/>
										<input class="gestion_produit_qtite" type="text" name="ref" id="ref" value="<?php if(isset($_POST['ref'])) {echo $_POST['ref'];}?>"/>


								</div>
						</div>



						<div class="clearfix"></div>

							<div  id="content_1" class="content caracteristique">


							<p><em>Enumérez les caractéristiques du produit</em> : </p>
								<ul>
									<li>1. Diamètre: <input class="input_nombre" type="text" name="diametre" id="diametre" placeholder="22" value="<?php if(isset($_POST['diametre'])) {echo $_POST['diametre'];}?>" />mm</li>

									<li>2. Matière: <input class="input_texte" type="text" name="matiere" id="matiere" placeholder="Acier inoxydable" value="<?php if(isset($_POST['matiere'])) {echo $_POST['matiere'];}?>"/></li>

									<li>3. Hauteur et poids: <input class="input_nombre" type="text" name="hauteur" id="hauteur" placeholder="59" value="<?php if(isset($_POST['hauteur'])) {echo $_POST['hauteur'];}?>" />
									mm pour
									<input class="input_nombre" type="text" name="poids" id="poids" placeholder="79" value="<?php if(isset($_POST['poids'])) {echo $_POST['poids'];}?>" />g</li>

									<li>4. Contenance: <input class="input_nombre" type="text" name="contenance" id="contenance" placeholder="5" value="<?php if(isset($_POST['contenance'])) {echo $_POST['contenance'];}?>"/>ml </li>

									<li>5. <input class="input_texte" type="text" name="caracteristique5" id="caracteristique5" placeholder="Ex : Connecteur 510 réglable" value="<?php if(isset($_POST['caracteristique5'])) {echo $_POST['caracteristique5'];}?>" /></li>

									<li>6. <input class="input_texte" type="text" name="caracteristique6" id="caracteristique6" placeholder="Ex : Contrôle du  tirage (airflow) avec bagues de couleur optionnelles" value="<?php if(isset($_POST['caracteristique6'])) {echo $_POST['caracteristique6'];}?>"/></li>

									<li>7. <input class="input_texte" type="text" name="caracteristique7" id="caracteristique7" placeholder='Ex : Plateau de construction modèle S "Small": Aluminium anodisé Ematal - isolante (voir ci-dessous)' value="<?php if(isset($_POST['caracteristique7'])) {echo $_POST['caracteristique7'];}?>" /></li>

									<li>8. <input class="input_texte" type="text" name="caracteristique8" id="caracteristique8" placeholder="Ex : Chambre d'atomisation: Aluminium anodisé Ematal - isolante (voir ci-dessous)" value="<?php if(isset($_POST['caracteristique8'])) {echo $_POST['caracteristique8'];}?>" /></li>

									<li>9. <input class="input_texte" type="text" name="caracteristique9" id="caracteristique9" placeholder="Ex : Tank: Verre borosilicate dépoli, interchangeable" value="<?php if(isset($_POST['caracteristique9'])) {echo $_POST['caracteristique9'];}?>"/></li>

									<li>10. <input class="input_texte" type="text" name="caracteristique10" id="caracteristique10" placeholder='Ex : Drip tip custom "SQuip"' value="<?php if(isset($_POST['caracteristique10'])) {echo $_POST['caracteristique10'];}?>" /></li>

									<li>11. <input class="input_texte" type="text" name="caracteristique11" id="caracteristique11" placeholder='Ex : Joints de rechange, vis de rechange, clef Allen inclus' value="<?php if(isset($_POST['caracteristique11'])) {echo $_POST['caracteristique11'];}?>" /></li>

									<li>12. <input class="input_texte" type="text" name="caracteristique12" id="caracteristique12" placeholder='Ex : Joints de rechange, vis de rechange, clef Allen inclus' value="<?php if(isset($_POST['caracteristique12'])) {echo $_POST['caracteristique12'];}?>" /></li>

									<br/>
									<label for="quantite">Compléments d'informations :</label>

									<textarea id="complement" name="complement" placeholder="Vous pouvez étirer la zone en bas à droite"><?php if(isset($_POST['complement'])) {echo $_POST['complement'];}?></textarea>

								</ul>
							</div>





						</div>

					<input type='submit' name='enregistrement' value='<?php echo strtoupper($_GET['action'])?> DU PRODUIT'/>


                    </form>
<?php

	}
?>

				</div> <!-- fin COLONNE 2 ......................... -->

			</div><!-- Fin de principale............................ -->
