<?php
include("inc/install.php");
if(isset($_POST['inscription']))
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
		$membre = execute_requete("SELECT * FROM membre WHERE pseudo = '$_POST[pseudo]'");
		if(mysqli_num_rows($membre)>0)

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
			execute_requete("INSERT INTO membre (pseudo, mdp, statut) VALUES ('$_POST[pseudo]', '$_POST[mdp]', 0) ");
			$serveur = $_SERVER['HTTP_REFERER'];
			$serveur2 = RACINE_SITE."panier.php";
			if($serveur = $serveur2){
				header ("location:connexion.php" );
			}else{
				header("location:profil.php");
			};
			$msg.= '<div id="msg">
				<p class="vert">Félicitations! Vous venez de créer votre compte.</p>
				</div>';
		}
	}
}

include("inc/haut_de_site_inc.php");
include("inc/top_menu_inc.php");
include("inc/menu_inc.php");

get_fil_ariane(array('final' => 'Inscription'));
echo $msg;
?>

<div id="colonne-unique" class="colonne2">
	<div class="titre_h2 largeur_article"><h2>INSCRIPTION</h2></div>
		<form class="formulaire" method="post" action="">
			<p> Déjà membre ? <a href="<?php echo RACINE_SITE ?>connexion.php">Connectez-vous</a><p>
			<label for="pseudo">Votre nom d'utilisateur <span>*</span></label><br />
			<input type="text" id="pseudo" name="pseudo"   maxlength="14" placeholder="3 caractères min" value="<?php if(isset($_POST['inscription'])) {echo $_POST['pseudo'];}?>"/><br />
			<label for="mdp">Votre mot de passe<span>*</span></label><br />
			<input type="password" id="mdp" name="mdp"   maxlength="14" placeholder="3 caractères min" value="<?php if(isset($_POST['inscription'])) {echo $_POST['mdp'];}?>"/>
			<input type="password" id="mdp2" name="mdp2"   maxlength="14" placeholder=" Veuillez re-saisir votre mot de passe" value="<?php if(isset($_POST['inscription'])) {echo $_POST['mdp2'];}?>"/><br />
<?php
if($_SERVER['HTTP_REFERER'] == RACINE_SITE."panier.php")
{
	$membre = execute_requete("SELECT * FROM membre WHERE id_membre = '".$_SESSION['utilisateur']['id_membre']."'");
	$membre_connecte = mysqli_fetch_assoc($membre)
;
?>
			<label for="ville">Adresse de livraison (*modifier si différente)</label><br />
			<input type="text" id="cp2" name="cp2" placeholder=" Votre code postal" value="<?php echo $membre_connecte['cp']?>"/><br />
			<input type="text" id="ville2" name="ville2" placeholder=" Votre ville" value="<?php echo $membre_connecte['ville']?>" /><br />
			<textarea id="adresse2" name="adresse2" placeholder=" Votre adresse et autres détails (Bat, Etage, Résidence...)"><?php echo $membre_connecte['adresse']?></textarea>
			<br />
<?php
};
?>
			<p>En cliquant sur Créer un compte, j’accepte les <a href="#">Conditions d’utilisation</a> et la <a href="#">Déclaration sur la confidentialité et les cookies</a>.</p>
			<br />
			 <input type="submit" name="inscription" value="CREEZ VOTRE COMPTE"/>
			<p>* tous les champs sont obligatoires</p>
		</form>
		<div class="right">
			<img src="image/inscription.jpg" alt="ordinateurs dans une salle informatique"/>
			<h3>Votre compte</h3>
			<p>Inscrivez-vous gratuitement et demandez votre carte de fidélité.
				Votre carte vous permettra de collecter de nombreux points de fidélité qui vous donneront accès à des réductions très avantageuses.</p>
		</div>
	</div>
</div>
