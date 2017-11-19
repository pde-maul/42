<?php
include("inc/install.php");

if(!utilisateur_est_connecte())
{
	header("location:connexion.php");
}

//**********************************************************************************************************
//                         		MODIFICATION DU COMPTE
//**********************************************************************************************************


$verif = 0;
if(isset($_POST['modification']))
{
	$href = "profil.php";
	$resultat = execute_requete("SELECT * FROM membre WHERE id_membre = '".$_SESSION['utilisateur']['id_membre']."'");
	if(mysqli_num_rows($resultat)== 0 && isset($_GET['action']) && $_GET['action']== "update")
	{

		$msg .= '<div id="msg">
			<p class="orange">Vous n\'êtes pas enregistré, merci de vous enregistrer.</p>
			</div>';
	}
	else     //*****************************************************************************************
	{
		$verif_caractere = preg_match('/^[a-zA-Z0-9._-]+$/' , $_POST['pseudo']);
		if(!$verif_caractere && !empty($_POST['pseudo']))
		{
			$msg .= '<div id="msg">
				<p class="orange">Le pseudo comporte des caractères non autorisés. Les caractères autorisés sont : A à Z et de 0 à 9</p>
				</div>';
		}
		if (strlen($_POST['pseudo']) < 3 || strlen($_POST['pseudo']) > 14 )
		{
			$msg .=  '<div id="msg">
				<p class="orange">Le pseudo doit avoir entre 4 et 14 caractères inclus</p>
				</div>';
		}
		if (strlen($_POST['mdp']) < 3 || strlen($_POST['mdp']) > 14 )
		{

			$id_membre = mysqli_fetch_assoc($resultat);
			//echo $id_membre['id_membre'];

			$msg .= '<div id="msg">
				<p class="orange">Le mot de passe doit avoir entre 4 et 14 caractères inclus</p>
				</div>';
		}
		if ($_POST['mdp'] !== $_POST['mdp2'])
		{
			$msg .= '<div id="msg">
				<p class="orange">Mots de passe non identiques, veuillez ressaisir votre mot de passe.</p>
				</div>';
		}
		if(empty($_POST['mdp'])||empty($_POST['pseudo']))
		{
			$msg .=  '<div id="msg">
				<p class="orange">Veuillez remplir tous les champs obligatoires (*)</p>
				</div>';
		}
		if (empty($msg))
		{
			if ($_POST['pseudo'] !== $_SESSION['utilisateur']['pseudo'])
			{
				$membre = execute_requete("SELECT * FROM membre WHERE pseudo = '$_POST[pseudo]'");
				if(mysqli_num_rows($membre)> 0)
				{
					$msg .=  '<div id="msg">
						<p class="orange">Pseudo déjà utilisé. Veuillez vous connecter à votre compte ou saisir un nouveau pseudo s\'il ne correspond pas au vôtre</p>
						</div>';
				}
				else
				{
					foreach ($_POST as $key => $value)
					{
						if ($key === "mdp")
						{
							$_POST[$key] = hash("whirlpool", $value);
						}
						else
						{
							$_POST[$key] = htmlentities($value, ENT_QUOTES);
						}
					}
					execute_requete("UPDATE membre SET
						pseudo = '$_POST[pseudo]' ,
						mdp = '$_POST[mdp]',
						statut = '".$_SESSION['utilisateur']['statut']."'
						WHERE id_membre = '".$_SESSION['utilisateur']['id_membre']."'");
					$_SESSION['utilisateur']['pseudo'] = $_POST['pseudo'];
					$_SESSION['utilisateur']['mdp'] = $_POST['mdp'];
					$msg .= '<div id="msg">
							<p class="vert">Votre compte a été actualisé.</p>
							<p class="vert">Pour toute autre modification, veuillez vous reconnecter.</p>
							</div>';
					$verif = 1;
//		header("location:profil.php");
				}
			}
			else
			{
				foreach ($_POST as $key => $value)
				{
					if ($key === "mdp")
					{
						$_POST[$key] = hash("whirlpool", $value);
					}
					else
					{
						$_POST[$key] = htmlentities($value, ENT_QUOTES);
					}
				}
				execute_requete("UPDATE membre SET
					pseudo = '$_POST[pseudo]' ,
					mdp = '$_POST[mdp]',
					statut = '".$_SESSION['utilisateur']['statut']."'
					WHERE id_membre = '".$_SESSION['utilisateur']['id_membre']."'");
				$_SESSION['utilisateur']['pseudo'] = $_POST['pseudo'];
				$_SESSION['utilisateur']['mdp'] = $_POST['mdp'];
				$msg .= '<div id="msg">
						<p class="vert">Votre compte a été actualisé.</p>
						<p class="vert">Pour toute autre modification, veuillez vous reconnecter.</p>
						</div>';
					$verif = 1;
			//	header("location:profil.php");
			}
		}
	}
}
else
{
	$href = "profil.php?action=update#mettre_a_jour";
}

$hrefsupp = "index.php?action=delete&pseudo=".$_SESSION['utilisateur']['pseudo'];



include("inc/haut_de_site_inc.php");
include("inc/top_menu_inc.php");
include("inc/menu_inc.php");

get_fil_ariane(array('final' => 'Mon profil'));
echo $msg;
?>
<div id="colonne-double" class="colonne2">
	<div class="titre_h2 largeur_article"><h2>MON PROFIL</h2></div>
		<div class="informations-utilisateurs">
			<div class="col1">
				<p>Pseudo : </p>
			</div>
			<div class="col2">
<?php
if(utilisateur_est_connecte())
{
	if(isset($_POST['modification'])){echo "<p>".$_POST['pseudo']."</p>";}else{ echo "<p>".$_SESSION['utilisateur']['pseudo']."</p>";};
}
?>
			</div>
			<div class="col3 bouton-ajout"><img src="<?php echo RACINE_SITE ?>image/modifier.gif" alt="feuille de papier icone"/><a href="<?php echo $href ?>">Modifier mon profil</a></div>
			</div>
			<div class="col3 bouton-ajout"><img src="<?php echo RACINE_SITE ?>image/modifier.gif" alt="feuille de papier icone"/><a href="<?php echo $hrefsupp ?>">Supprimer mon profil</a></div>
			<div id="mettre_a_jour" class="clear"></div>
<?php
//**********************************************************************************************************
//                         		FORMULAIRE DE MODIFICATION DE PROFIL
//**********************************************************************************************************

if(isset($_GET['action']) && $_GET['action'] === "update" && isset($_POST['modification']) && $verif === 1)
{
	echo '<div id="msg">
		<p class="vert">Vous avez bien rentré toutes les informations</p>
		</div>';
}
else if (isset($_GET['action']) && $_GET['action'] == "update" && $verif === 0)
{
?>
					<div class="titre_h2 largeur_article"><h2>METTRE A JOUR VOTRE PROFIL</h2></div>
					<div id="modification-profil">
							<form class="formulaire formulaire_profil" method="post" action="">
								<label for="pseudo">Votre nom d'utilisateur (pseudo)<span>*</span></label><br />
								<input type="text" id="pseudo" name="pseudo"   maxlength="14" placeholder=" Veuillez choisir un nom d'utilisateur" value="<?php if(isset($_POST['modification'])) {echo $_POST['pseudo'];}else{ echo $_SESSION['utilisateur']['pseudo'];}?>"/><br />
								<label for="mdp">Votre mot de passe<span>*</span></label><br />
								<input type="password" id="mdp" name="mdp"   maxlength="14" placeholder=" Veuillez choisir un mot de passe" value="<?php if(isset($_POST['modification'])) {echo $_POST['mdp'];}?>"/>
								<input type="password" id="mdp2" name="mdp2"   maxlength="14" placeholder=" Veuillez re-saisir votre mot de passe" value="<?php if(isset($_POST['modification'])) {echo $_POST['mdp'];}?>"/><br />
								 <input type="submit" name="modification" value="MODIFIER MES INFORMATIONS"/>
							</form>
					</div>
<?php
};

?>
	<div class="titre_h2 largeur_article"><h2>ETAT DE MES COMMANDES</h2></div>
<?php
$profil = execute_requete("SELECT *
	FROM commande
	WHERE id_membre = ".$_SESSION['utilisateur']['id_membre']."");

					while($commande = mysqli_fetch_assoc($profil))
					{
					?>


						<div class="etat-commande">
						<p><img src="<?php echo RACINE_SITE ?>image/shopping.png" alt="homme profil"/>
						<a href="?action=affichage&id=<?php echo $commande['id_commande']?>#titre_details_commande">COMMANDE N° <?php echo $commande['id_commande'] ?></a>
						- Montant : <?php echo $commande['montant'] ?>€ TTC</p>
						<ul>
							<li>Commande effectuée le <?php echo $commande['date_commande'] ?></li>
							<li>Date de livraison estimée au <?php echo $commande['date_estimation'] ?></li>
							<li>Commande livrée le <?php echo $commande['date_livraison'] ?></li>
						</ul>
						<p><img src="<?php echo RACINE_SITE ?>image/loupe.png" alt="loupe"/><a href="?action=affichage&id=<?php echo $commande['id_commande']?>#titre_details_commande"> Détails de ma commande</a></p>
						<p><img src="<?php echo RACINE_SITE ?>image/livraison.png" alt="homme profil"/> ETAT : <?php echo $commande['statut'] ?></p>
						</div>

<?php
}

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
							 echo'<div id="msg" ><p class="orange" id="titre_details_commande">Pas de détail pour la commande n° '.$_GET['id'].'</p></div>';
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


		echo "<div id='titre_details_commande' class='titre_h2 largeur_article'><h2>DETAIL DE LA COMMANDE n°".$_GET['id']." d'un montant de ".$cde['montant']."€ TTC </h2></div>";

?>
							<thead>
							<tr>
							<th scope="col" >Photo</th>
							<th scope="col">Produit</th>
							<th scope="col" >Qtité</th>
							<th scope="col">Prix TTC</th>
							<th scope="col">Points fidélité</th>
							<th scope="col"  >N° détail cde</th>
							<th scope="col"  >N° Cde</th>
							<th scope="col" >Id produit</th>
							</tr>

							<tr>
							<th scope="col" ><div class="filtre"><a href="#"><img src="<?php echo RACINE_SITE ?>image/fleche-haute.gif"/></a><a href="#"><img src="<?php echo RACINE_SITE ?>image/fleche-bas.gif"/></a></div></th>
							<th scope="col" ><div class="filtre"><a href="#"><img src="<?php echo RACINE_SITE ?>image/fleche-haute.gif"/></a><a href="#"><img src="<?php echo RACINE_SITE ?>image/fleche-bas.gif"/></a></div></th>
							<th scope="col"><div class="filtre"><a href="#"><img src="<?php echo RACINE_SITE ?>image/fleche-haute.gif"/></a><a href="#"><img src="<?php echo RACINE_SITE ?>image/fleche-bas.gif"/></a></div></th>
							<th scope="col" ><div class="filtre"><a href="#"><img src="<?php echo RACINE_SITE ?>image/fleche-haute.gif"/></a><a href="#"><img src="<?php echo RACINE_SITE ?>image/fleche-bas.gif"/></a></div></th>
							<th scope="col"><div class="filtre"><a href="#"><img src="<?php echo RACINE_SITE ?>image/fleche-haute.gif"/></a><a href="#"><img src="<?php echo RACINE_SITE ?>image/fleche-bas.gif"/></a></div></th>
							<th scope="col"  ><div class="filtre"><a href="#"><img src="<?php echo RACINE_SITE ?>image/fleche-haute.gif"/></a><a href="#"><img src="<?php echo RACINE_SITE ?>image/fleche-bas.gif"/></a></div></th>
							<th scope="col"  ><div class="filtre"><a href="#"><img src="<?php echo RACINE_SITE ?>image/fleche-haute.gif"/></a><a href="#"><img src="<?php echo RACINE_SITE ?>image/fleche-bas.gif"/></a></div></th>
							<th scope="col" ><div class="filtre"><a href="#"><img src="<?php echo RACINE_SITE ?>image/fleche-haute.gif"/></a><a href="#"><img src="<?php echo RACINE_SITE ?>image/fleche-bas.gif"/></a></div></th>
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
							<th colspan="3" scope="row">Total</th>
							<td colspan="1"><?php echo $somme['SOMME'] ?>€ TTC</td>
							<td colspan="4"><?php echo mysqli_num_rows($resultat)?> produits pour cette commande</td>
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
								<td ><a href="<?php echo RACINE_SITE ?>fiche-produit.php?id=<?php echo $ligne['id_produit'] ?>"><img style="max-width: 80px; max-height: 100px;" src="<?php echo RACINE_SITE ?><?php echo $ligne['photo'] ?>"/></a></td>
								<th ><?php echo $ligne['titre'] ?></th>
								<td ><?php echo $ligne['quantite'] ?></td>
								<td class="moyen"><?php echo $ligne['prix'] ?>€ TTC</td>
								<td class="moyen"><?php echo $ligne['fidelite'] ?> POINTS</td>
								<td><?php echo $ligne['id_details_commande'] ?></td>
								<td ><?php echo $ligne['id_commande'] ?></td>
								<td ><?php echo $ligne['id_produit'] ?></td>
							</tr>
<?php
		};
?>


						</tbody></table>

<?php
	};
};
?>

				</div> <!-- fin COLONNE 2 ......................... -->

			</div><!-- Fin de principale............................ -->
