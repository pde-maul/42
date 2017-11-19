<?php
	include("inc/install.php");

		//******************************************************************************
			//Si l'utilisateur est connecté il n'a pas à voir le formulaire de connexion donc on le redirige vers profil.

			if(utilisateur_est_connecte())
			{
							header("location:profil.php");

			};

//******************************************************************************
	if(isset($_POST['connexion']))// Soyons précis car il peut y avoir plusieurs formulaires sur la même page. Est-ce que l'internaute a cliqué sur connexion ?
	{
		//echo "<pre>";print_r($_POST);echo"</pre>";
		$selection_membre = execute_requete ("SELECT * FROM membre WHERE pseudo ='$_POST[pseudo]'"); // Ici on prépare une variable qui va conserver la réponse mysqli sur la requête ci-dessus. Mais cette variable est inexploitable.
		if(mysqli_num_rows($selection_membre)>0) // Est-ce que Mysqli a retourné une ligne donc est-ce que le pseudo existe en base ? Est-ce donc le bon pseudo ?
			{
				//*****************************
				$membre = mysqli_fetch_assoc($selection_membre); // On rend les données exploitables. Etape obligatoire après une requête de selection.
				//echo "<pre>";print_r($membre);echo"</pre>";
				if($membre['mdp'] == hash("whirlpool", $_POST['mdp'])) // Est-ce que le MDP dans la BDD correspond au MDP posté par l'internaute.
				{
					foreach($membre as $key => $value) // On récupère les infos de l'internaute enregistré dans une session
					{
						$_SESSION['utilisateur'][$key] = $value;
						// ici on a un tableau imbriqué $_SESSION (utilisateur/panier/commande...) + tableau array Utilisateur avec les informations de l'utilisateur (pseudo/nom/prenom...)
					}
						//echo "<pre>";print_r($_SESSION);echo"</pre>";
						//header("location:profil.php");
						//Ici on redirige l'internaute vers cette page mais ATTENTION il ne faut pas de code HTML ou d'echo au dessus de cette ligne.
						if( $serveur2 == $page_precedente){
							header ("location:panier.php");
						}else{
							header("location:profil.php");

						};
					//******************************************************************************
					//**								COOKIES
					//******************************************************************************

							if (isset($_POST['remember'] )) {

								setcookie ("cookname", $_SESSION['utilisateur']['pseudo']) ;
								//echo $_COOKIE['cookname'];
							}

							 if (isset ($_COOKIE['cookname'])) {

								$_SESSION['utilisateur']['pseudo'] = $_COOKIE ['cookname'];
							}
					//*****************************************************************************************
				}
				else //Si ce n'est pas le bon mot de passe alors :
				{
					$msg .= "<div id='msg'><p class='orange'>Erreur de mot de passe</p></div>";
				}
				//******************************
			}
		else //Si ce n'est pas le bon pseudo alors :
			{
				$msg .= "<div id='msg'><p class='orange'>Erreur de pseudo</p></div>";
			};

	} // Fin if(isset($_POST['connexion']))

//**********************************************************************************************************
//
//**********************************************************************************************************

	include("inc/haut_de_site_inc.php");
	include("inc/top_menu_inc.php");
	include("inc/menu_inc.php");

//**************** FIL D'ARIANE ************************* -->


   get_fil_ariane(array(
   'final' => 'Connexion'
   ));

?>

<!-- **************** MESSAGE ************************* -->

			<?php echo $msg; ?>
			<!--<p class="vert">Vous avez bien rentré toutes les informations</p>-->

<!-- **************************************************************************************** -->
<!--  									DEUXIEME COLONNE									  -->
<!-- **************************************************************************************** -->


		<div id="colonne-unique" class="colonne2 connexion"> <!-- début colonne 2-->


			<div class="titre_h2 largeur_article"><h2>CONNEXION</h2></div>


			<div class="left">
			<img src="image/inscription.jpg" alt="ordinateurs dans une salle informatique"/>
			<h3>Votre compte</h3>
			</div>


			 <form class="formulaire" method="post" action="">
				<h4>Connectez-vous à votre compte</h4>
				<label for="pseudo">Votre nom d'utilisateur</label>
				<input type="text" id="pseudo" name="pseudo"   maxlength="14" placeholder=" Votre nom d'utilisateur" value="<?php if(isset($_POST['pseudo'])) {echo $_POST['pseudo'];}?>"/><br />

                <label for="mdp">Votre mot de passe</label>
				<input type="password" id="mdp" name="mdp"   maxlength="14" placeholder=" Votre mot de passe" value="<?php if(isset($_POST['mdp'])) {echo $_POST['mdp'];}?>"/><br />


				<input type="checkbox" id="remember" name="remember" placeholder="" value="<?php if(isset($_POST['remember'])) {echo $_POST['remember'];}?>"/>	<label for="remember">Se souvenir de moi</label>

					<p><a href="<?php echo RACINE_SITE ?>mdpperdu.php">Mot de passe oublié ?</a></p>
				<br />
				 <input type="submit" name="connexion" value="SE CONNECTER"/>

				 <p>Vous n'avez pas encore de compte sur Quai des Vaps ?</p>
				 <p><a href="<?php echo RACINE_SITE ?>inscription.php">Créez un compte maintenant</a> et bénéficiez de nombreux avantages <br />grâce à <a href="">votre carte de fidélité</a></p>

            </form>
		</div> <!-- fin COLONNE 2 ......................... -->

	</div><!-- Fin de principale............................ -->
