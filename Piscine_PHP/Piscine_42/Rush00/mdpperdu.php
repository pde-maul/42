<?php
	include("inc/install.php");


//******************************************************************************
	if(isset($_POST['mdpperdu']))// Soyons précis car il peut y avoir plusieurs formulaires sur la même page. Est-ce que l'internaute a cliqué sur connexion ?
	{
		//echo "<pre>";print_r($_POST);echo"</pre>";
		$selection_membre = execute_requete ("SELECT * FROM membre WHERE email ='$_POST[email]'"); // Ici on prépare une variable qui va conserver la réponse mysqli sur la requête ci-dessus. Mais cette variable est inexploitable.

		if(mysqli_num_rows($selection_membre)>0) // Est-ce que Mysqli a retourné une ligne donc est-ce que le pseudo existe en base ? Est-ce donc le bon pseudo ?
			{
				//*****************************
				$membre = mysqli_fetch_assoc($selection_membre); // On rend les données exploitables. Etape obligatoire après une requête de selection.
				//echo "<pre>";print_r($membre);echo"</pre>";

				// ICI on transforme INSERT INTO en REPLACE INTO et on ajoute $_POST[id_article] pour récupérer la valeur
				// Rappel : REPLACE permet de faire UPDATE et INSERT en même temps (pour le cas de la modification).

				execute_requete("UPDATE membre SET mdp = '$_POST[mdp_oublie]' WHERE email ='$_POST[email]' ");


				$msg .= '<div id="msg">
						<p class="vert">Un nouveau mot de passe (temporaire) vient de vous être envoyé par email <br/>
						Pour toute autre modification, veuillez vous reconnecter avec ce mot de passe et le modifier</p>
						</div>';


//**********************************************************************************************************
//                         		  GENERER UN EMAIL
//**********************************************************************************************************


			$mail = $_POST['email']; // Déclaration de l'adresse de destination.'cloe.legoube@gmail.com' pour TEST
				if (!preg_match("#^[a-z0-9._-]+@(hotmail|live|msn).[a-z]{2,4}$#", $mail)) // On filtre les serveurs qui rencontrent des bogues.
				{
					$passage_ligne = "\r\n";
				}
				else
				{
					$passage_ligne = "\n";
				}
				//=====Déclaration des messages au format texte et au format HTML.
				$message_txt = "Cher(e) abonné(e)";
				$message_html = '<html>
								<head>
								<title>Réinitialiser votre mot de passe</title>
								</head>
								<body bgcolor="black">

								<font face="verdana"><font color="white"><font size="5"><p align="center"><font color="red"><u>Cher(e) abonné(e),</u></p></font></font>
								<font size="3">Pour pouvoir accéder de nouveau à notre site et réinitialiser votre mot de passe, veuillez vous connecter avec votre pseudo et le mot de passe ci-dessous : <br />
								<p>Votre mot de passe provisoire (à copier et modifier ensuite) : '.$_POST['mdp_oublie'].'<a href='.RACINE_SITE.'connexion.php"> -> Connection au site de Quai des Vaps</a></p>
								<p>Ce mot de passe est provisoire, merci de le changer dans les 48heures</p>
								<p align="left">Visitez notre site Quai des Vaps.fr, pour voir nos nouveaux produits</p>
								</body>
								</html>'; //On termine le message.
				//==========

				//=====Création de la boundary
				$boundary = "-----=".md5(rand());
				//==========

				//=====Définition du sujet.
				$sujet = "Quai des Vaps : Réinitialiser votre mot de passe";
				//=========

				$expediteur = "<contact@quaidesvaps.fr>";

				//=====Création du header de l'e-mail.
				$header = "From: \"Quai des Vaps\"<$expediteur>".$passage_ligne;
				$header .= "Reply-to: \"Quai des Vaps\" <$expediteur>".$passage_ligne;
				$header.= "MIME-Version: 1.0".$passage_ligne;
				$header.= "Content-Type: multipart/alternative;".$passage_ligne." boundary=\"$boundary\"".$passage_ligne;
				//==========

				//=====Création du message.
				$message = $passage_ligne."--".$boundary.$passage_ligne;
				//=====Ajout du message au format texte.
				$message.= "Content-Type: text/plain; charset=\"ISO-8859-1\"".$passage_ligne;
				$message.= "Content-Transfer-Encoding: 8bit".$passage_ligne;
				$message.= $passage_ligne.$message_txt.$passage_ligne;
				//==========
				$message.= $passage_ligne."--".$boundary.$passage_ligne;
				//=====Ajout du message au format HTML
				$message.= "Content-Type: text/html; charset=\"ISO-8859-1\"".$passage_ligne;
				$message.= "Content-Transfer-Encoding: 8bit".$passage_ligne;
				$message.= $passage_ligne.$message_html.$passage_ligne;
				//==========
				$message.= $passage_ligne."--".$boundary."--".$passage_ligne;
				$message.= $passage_ligne."--".$boundary."--".$passage_ligne;
				//==========

				//=====Envoi de l'e-mail.
				mail($mail,$sujet,$message,$header);
				//==========

			}
		else //Si ce n'est pas le bon pseudo alors :
			{
				$msg .= '<div id="msg">
						<p class="orange">Cette adresse email n\'existe pas.</p>
						</div>';
			}


	} // Fin if(isset($_POST['mdpperdu']))


	include("inc/haut_de_site_inc.php");
	include("inc/top_menu_inc.php");
	include("inc/menu_inc.php");


	//**************** FIL D'ARIANE ************************* -->


   get_fil_ariane(array(
   'connexion.php' => 'Connexion',
   'final' => 'Mot de passe oublié'
   ));





//**************** MESSAGE ************************* -->


						echo $msg;

?>
<!-- **************************************************************************************** -->
<!--  									PREMIERE COLONNE									  -->
<!-- **************************************************************************************** -->
<?php
	include("inc/aside_inc.php");
?>



<!-- **************************************************************************************** -->
<!--  									DEUXIEME COLONNE									  -->
<!-- **************************************************************************************** -->


				<div class="colonne2"> <!-- début colonne 2-->

					<div class="titre_h2 largeur_article boutique"><h2>MOT DE PASSE OUBLIE</h2></div>

					<div id="reinitialiser_mdp">
						<h3>Réinitialiser votre mot de passe</h3>
						<p> Afin de pouvoir réinitialiser votre mot de passe, vous devez nous fournir votre adresse email: </p>
							<form class="formulaire" id="mdp_oublie" method="post" action="">

								<label for="email">Email</label>

									<input type="email" id="email" name="email" placeholder="email" value="<?php if(isset($_POST['inscription'])) {echo $_POST['email'];}?>"/><br />

									<input type="hidden" id="mdp_oublie" name="mdp_oublie" value="<?php echo generer_mot_de_passe() ?>"/>

									<input type="submit" name="mdpperdu" value="RECEVOIR UN NOUVEAU MOT DE PASSE" />

							</form>
					</div>


				</div> <!-- fin COLONNE 2 ......................... -->

			</div><!-- Fin de principale............................ -->
