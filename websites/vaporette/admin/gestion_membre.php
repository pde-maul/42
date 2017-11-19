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
//                         			SUPPRESSION D'UN MEMBRE
//**********************************************************************************************************


	if(isset($_GET['action']) && $_GET['action']== "suppression")
	{

			$resultat = execute_requete("SELECT *
			FROM membre m
			WHERE id_membre = '$_GET[id]'");
			$produit_supp = mysqli_fetch_assoc($resultat);



			// ------------- Suppresssion de toute la ligne du membre --------------------------------
			$msg .= '<div id="msg">
						<p class="vert">Suppression de '."$produit_supp[prenom]".' '."$produit_supp[nom]".', membre n° '."$_GET[id]".' effectuée</p>
					</div>';

			execute_requete("DELETE FROM membre WHERE id_membre = '$_GET[id]'");



			//$_GET['action'] = "affichage"; // Petite astuce pour revenir sur la page affichage

	}


//**********************************************************************************************************
//                         			AFFICHER PLUSIEURS PAGES
//**********************************************************************************************************

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
//                         		ENREGISTREMENT D'UN NOUVEAU MEMBRE via FORMULAIRE
//**********************************************************************************************************


	if(isset($_POST['membre'])) // S'il y a clic sur bouton enregistrement
	{
		//echo "<pre>";print_r($_POST);echo "</pre>"; // Affiche ce qui a été saisi pour vérification
		//Nous allons faire les contrôles et pour cela nous savons que la référence est UNIQUE (voir BDD) donc on vérifie qu'elle n'existe pas déjà :

		$id_membre = execute_requete("SELECT id_membre FROM membre WHERE id_membre = '$_POST[id_membre]'");
							// Est-ce qu'il y a une ligne avec la même réference postée?


		// ATTENTION cette condition doit fonctionner dans le cas d'ajout d'membre et dans le cas d'une modification
		// Dans le cas d'un ajout, une réference membre ne peut être qu'unique. En modification la référence sera trouvé et récuperé

				//*****************************************************************************************

				if(mysqli_num_rows($id_membre)!= 0 /* PRECISIONS : */ && isset($_GET['action'])&& $_GET['action'] == "ajout")
		{
			$msg .= '<div id="msg">
						<p class="orange">Réference déjà attribuée à un membre. Merci de vérifier votre saisie.</p>
					</div>';
		}

		else     //*****************************************************************************************

		{	// ICI on transforme INSERT INTO en REPLACE INTO et on ajoute $_POST[id_membre] pour récupérer la valeur
			// Rappel : REPLACE permet de faire UPDATE et INSERT en même temps (pour le cas de la modification).

			$prenom = mysqli_real_escape_string ($mysqli, $_POST['prenom']);
			$nom = mysqli_real_escape_string ($mysqli, $_POST['nom']);
			$adresse = mysqli_real_escape_string ($mysqli, $_POST['adresse']);

			execute_requete("REPLACE INTO membre VALUES
			('$_POST[id_membre]','$_POST[pseudo]','$_POST[mdp]','$nom','$prenom', '$_POST[naissance]','$_POST[email]', '$_POST[telephone]','$_POST[sexe]','$_POST[ville]','$_POST[cp]','$adresse','$_POST[statut]','','','' )");

			$msg .= '<div id="msg">
						<p class="vert">Le membre a bien été ajouté ou modifié!</p>
					</div>';

		}
	}







	include("../inc/haut_de_site_inc.php");
	include("../inc/top_menu_inc.php");
	include("../inc/menu_inc.php");
	//**************** FIL D'ARIANE ************************* -->


	get_fil_ariane(array(
	'gestion_produit.php' => 'Administration du site',
	'final' => 'Gestion des membres'
   ));

?>




<!-- **************** MESSAGE ************************* -->

					<?php 	echo $msg; ?>
					<!--<div id="msg">
						<p class="vert">Vous avez bien rentré toutes les informations</p>
						</div>
					-->

<!-- **************** MENU ADMIN ************************* -->

<?php
	include("../inc/menu_admin_inc.php");
?>
<!-- **************************************************************************************** -->
<!--  									DEUXIEME COLONNE									  -->
<!-- **************************************************************************************** -->




				<div id="colonne-unique" class="colonne2"> <!-- début colonne 2-->


					<div class="titre_h2 largeur_article"><h2>INTERFACE ADMINISTRATEUR</h2></div>
					<div class="bouton-ajout"><img src="<?php echo RACINE_SITE ?>image/affichage.png" alt="loupe"/><a href="?action=affichage">Tableau d'affichage</a><img src="<?php echo RACINE_SITE ?>image/symbole-plus.png" alt="symbole +"/><a href="?action=ajout">Ajouter un membre</a></div>
					<div id="tableau">

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


							$resultat = execute_requete("SELECT * FROM membre m
								GROUP BY  m.id_membre ".$col." ".$tri." LIMIT ".$limite.",".$nb_produit_page.""); // EXECUTION DE LA REQUETE DE SELECTION
							//debug($resultat);


?>

						<div class="pagination">
						<?php
							if(isset($_GET['page'])){
							$i = 1;
							echo "<p><a href='?action=affichage&page=".$i."'><< - </a>";
							for ($i = 1; $i <= $nb_pages; $i++) { echo "<a href='?action=affichage&page=".$i."'>".$i."</a> - "; }
							echo "<a href='?action=affichage&page=".$nb_pages."'>>></a></p>";
							};
						?>
						</div>



					<table class="tableau_admin" summary="Submitted table designs">
					<caption>GESTION DES MEMBRES</caption>
					<thead>
					<tr>
					<th scope="col" class="petit">N° ID</th>
					<th scope="col" >Pseudo</th>
					<th scope="col">Prénom</th>
					<th scope="col">Nom</th>
					<th scope="col">Naissance</th>
					<th scope="col" >Email</th>
					<th scope="col" >Telephone</th>
					<th scope="col" >Adresse</th>
					<th scope="col" class="petit">Sexe</th>
					<th scope="col" class="petit">Statut</th>
					<th scope="col">Actions</th>
					</tr>

					<tr>
					<th scope="col" class="petit"><div class="filtre"><a href="?action=affichage&page=1&tri=22&col=id_membre#tableau"><img src="<?php echo RACINE_SITE ?>image/fleche-haute.gif"/></a><a href="?action=affichage&page=1&tri=11&col=id_membre#tableau"><img src="<?php echo RACINE_SITE ?>image/fleche-bas.gif"/></a></div></th>

					<th scope="col" ><div class="filtre"><a href="?action=affichage&page=1&tri=22&col=pseudo#tableau"><img src="<?php echo RACINE_SITE ?>image/fleche-haute.gif"/></a><a href="?action=affichage&page=1&tri=11&col=pseudo#tableau"><img src="<?php echo RACINE_SITE ?>image/fleche-bas.gif"/></a></div></th>

					<th scope="col"><div class="filtre"><a href="?action=affichage&page=1&tri=22&col=prenom#tableau"><img src="<?php echo RACINE_SITE ?>image/fleche-haute.gif"/></a><a href="?action=affichage&page=1&tri=11&col=prenom#tableau"><img src="<?php echo RACINE_SITE ?>image/fleche-bas.gif"/></a></div></th>

					<th scope="col"><div class="filtre"><a href="?action=affichage&page=1&tri=22&col=nom#tableau"><img src="<?php echo RACINE_SITE ?>image/fleche-haute.gif"/></a><a href="?action=affichage&page=1&tri=11&col=nom#tableau"><img src="<?php echo RACINE_SITE ?>image/fleche-bas.gif"/></a></div></th>

					<th scope="col" ><div class="filtre"><a href="?action=affichage&page=1&tri=22&col=naissance#tableau"><img src="<?php echo RACINE_SITE ?>image/fleche-haute.gif"/></a><a href="?action=affichage&page=1&tri=11&col=naissance#tableau"><img src="<?php echo RACINE_SITE ?>image/fleche-bas.gif"/></a></div></th>

					<th scope="col" ><div class="filtre"><a href="?action=affichage&page=1&tri=22&col=email#tableau"><img src="<?php echo RACINE_SITE ?>image/fleche-haute.gif"/></a><a href="?action=affichage&page=1&tri=11&col=email#tableau"><img src="<?php echo RACINE_SITE ?>image/fleche-bas.gif"/></a></div></th>

					<th scope="col" ><div class="filtre"><a href="?action=affichage&page=1&tri=22&col=telephone#tableau"><img src="<?php echo RACINE_SITE ?>image/fleche-haute.gif"/></a><a href="?action=affichage&page=1&tri=11&col=telephone#tableau"><img src="<?php echo RACINE_SITE ?>image/fleche-bas.gif"/></a></div></th>

					<th scope="col"><div class="filtre"><a href="?action=affichage&page=1&tri=22&col=ville#tableau"><img src="<?php echo RACINE_SITE ?>image/fleche-haute.gif"/></a><a href="?action=affichage&page=1&tri=11&col=ville#tableau"><img src="<?php echo RACINE_SITE ?>image/fleche-bas.gif"/></a></div></th>

					<th scope="col" class="petit"><div class="filtre"><a href="?action=affichage&page=1&tri=22&col=sexe#tableau"><img src="<?php echo RACINE_SITE ?>image/fleche-haute.gif"/></a><a href="?action=affichage&page=1&tri=11&col=sexe#tableau"><img src="<?php echo RACINE_SITE ?>image/fleche-bas.gif"/></a></div></th>

					<th scope="col" class="petit"><div class="filtre"><a href="?action=affichage&page=1&tri=22&col=statut#tableau"><img src="<?php echo RACINE_SITE ?>image/fleche-haute.gif"/></a><a href="?action=affichage&page=1&tri=11&col=statut#tableau"><img src="<?php echo RACINE_SITE ?>image/fleche-bas.gif"/></a></div></th>

					<th scope="col"></th>
					</tr>


					</thead>

					<tfoot>
					<tr>
					<th scope="row">Total</th>
					<td colspan="11"><?php echo mysqli_num_rows($resultat)?> membres</td>
					</tr>
					</tfoot>

					<tbody>


<?php

							while($ligne = mysqli_fetch_assoc($resultat))
				{
					?>



					<tr >
						<th  class="petit" scope="row" id="r100"><?php echo $ligne['id_membre'] ?></th>
						<td><?php echo $ligne['pseudo'] ?></td>
						<td><?php echo $ligne['prenom'] ?></td>
						<td><?php echo $ligne['nom'] ?></td>
						<td><?php echo $ligne['naissance'] ?></td>
						<td><?php echo $ligne['email'] ?></td>
						<td><?php echo $ligne['telephone'] ?></td>
						<td><?php echo $ligne['adresse'] ?></br><?php echo $ligne['cp'] ?> <?php echo $ligne['ville'] ?></td>
						<td class="petit"><?php echo $ligne['sexe'] ?></td>
						<td class="petit"><?php echo $ligne['statut'] ?></td>
						<td>
						   <a href="?action=suppression&id=<?php echo $ligne['id_membre'] ?>"><img src="<?php echo RACINE_SITE ?>image/poubelle.gif"/></a>
						   <a href="?action=modification&id=<?php echo $ligne['id_membre'] ?>"><img src="<?php echo RACINE_SITE ?>image/edit.gif"/></a>
						</td>
					</tr>

<?php			}
					?>
					</tbody></table>

					</div>
<?php		}



//**********************************************************************************************************
//                         				AFFICHAGE FORMULAIRE
//								   AJOUT ET MODIFICATION D'UN MEMBRE
//**********************************************************************************************************


	// Attention lorsqu'on a plusieurs conditions de bien mettre des parenthèses. Pour && et ||, ça sera || qui sera privilégié.
	// S'il y a un AND et un OR dans un IF, le OR prend le dessus.


	if(isset($_GET['action']) && ($_GET['action']== "ajout" || $_GET['action']== "modification" ))
	{
		if(isset($_GET['id'])) // Est-ce qu'il y a quelque chose dans ID de l'URL ? (donc est-ce qu'il y a modification d'un membre précis ?)

		{
			$resultat = execute_requete("SELECT * FROM membre WHERE id_membre = '$_GET[id]'");
			$_POST = mysqli_fetch_assoc($resultat);

			// Ici on aura pu donner un autre nom à cette variable ex : $modif et dire que $_POST = $modif mais on a raccourci l'opération en écrivant directement $_POST. Il faut se souvenir que la superglobal $_POST fonctionne pour l'ajout d'un membre mais pas pour la modification car on ne soumet pas de formulaire, donc va lui dire quoi aller chercher pour remplir $_POST.
		}

?>
					<form class="formulaire" method="post" action="">
						<p class="ajout_membre">AJOUT D'UN NOUVEAU MEMBRE</p>

						<label for="id_membre">ID Membre</label>
							<input type="text" id="id_membre" name="id_membre" readonly value="<?php if(isset($_POST['id_membre'])) {echo $_POST['id_membre'];}?>" style="width : 40px" /> <br />

						<label for="statut">statut</label>
							<input class="radio" type="radio" name="statut" value="1"
									<?php if(isset($_POST['statut'])&& $_POST['statut']=="1")
										echo "checked";
									elseif (!isset($_POST['statut']))
										echo "checked";?>
							/> Administrateur
							<input class="radio" type="radio" name="statut" value="0"
									<?php if(isset($_POST['statut'])&& $_POST['statut']=="0")
										echo "checked";?>
							/> Membre
									<br />

						<label for="prenom">Votre nom et prénom <span>*</span></label><br />
						<input type="text" id="prenom" name="prenom"   maxlength="14" placeholder=" Votre prénom" value="<?php if(isset($_POST['prenom'])) {echo $_POST['prenom'];}?>"/>
						<input type="text" id="nom" name="nom"   maxlength="14" placeholder=" Votre nom" value="<?php if(isset($_POST['nom'])) {echo $_POST['nom'];}?>"/><br />

						<label for="pseudo">Votre nom d'utilisateur <span>*</span></label><br />
						<input type="text" id="pseudo" name="pseudo"   maxlength="14" placeholder=" Veuillez choisir un nom d'utilisateur" value="<?php if(isset($_POST['pseudo'])) {echo $_POST['pseudo'];}?>"/><br />

						<label for="email">Votre email <span>*</span></label><br />
						<input type="text" id="email" name="email"   maxlength="60" placeholder=" Votre adresse email" value="<?php if(isset($_POST['email'])) {echo $_POST['email'];}?>"/><br />

						<label for="telephone">Votre téléphone <span>*</span></label><br />
						<input type="text" id="telephone" name="telephone"   maxlength="10" placeholder=" Votre numéro de téléphone" value="<?php if(isset($_POST['telephone'])) {echo $_POST['telephone'];}?>"/><br />


                        <label for="mdp">Votre mot de passe<span>*</span></label><br />
						<input type="password" id="mdp" name="mdp"   maxlength="14" placeholder=" Veuillez choisir un mot de passe" value="<?php if(isset($_POST['mdp'])) {echo $_POST['mdp'];}?>"/>
						<input type="password" id="mdp2" name="mdp2"   maxlength="14" placeholder=" Veuillez re-saisir votre mot de passe" value="<?php if(isset($_POST['mdp'])) {echo $_POST['mdp'];}?>"/><br />


						<label for="sexe">Sexe</label>
									<input class="radio" type="radio" name="sexe" value="m"
											<?php if(isset($_POST['sexe'])&& $_POST['sexe']=="m")
												echo "checked";
											elseif (!isset($_POST['sexe']))
												echo "checked";?>
									/>Homme
									<input class="radio" type="radio" name="sexe" value="f"
											<?php if(isset($_POST['sexe'])&& $_POST['sexe']=="f")
												echo "checked";?>
									/>Femme
											<br />

						<label for="naissance">Date de naissance</label><br />
						<input type="date" id="naissance" name="naissance"   placeholder="YYYY-MM-JJ" value="<?php if(isset($_POST['naissance'])) {echo $_POST['naissance'];}?>"/><br />

						<label for="ville">Votre adresse</label><br />
							<input type="text" id="cp" name="cp" placeholder=" Votre code postal" value="<?php if(isset($_POST['cp'])) {echo $_POST['cp'];}?>"/>
							<input type="text" id="ville" name="ville" placeholder=" Votre ville" value="<?php if(isset($_POST['ville'])) {echo $_POST['ville'];}?>" />
							<br />
							<textarea id="adresse" name="adresse" placeholder=" Votre adresse et autres détails (Bat, Etage, Résidence...)"><?php if(isset($_POST['adresse'])) {echo $_POST['adresse'];}?></textarea>
							<br />

						 <input type="submit" name="membre" value="<?php echo strtoupper($_GET['action'])?> DE CE MEMBRE"/>
						<p>* tous les champs sont obligatoires</p>
                    </form>



<?php
}
?>

				</div> <!-- fin COLONNE 2 ......................... -->

			</div><!-- Fin de principale............................ -->
		
