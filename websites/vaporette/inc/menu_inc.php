<?php
$compte = execute_requete("SELECT nom_categorie
	FROM categorie");

?>

<!-- **************************************************************************************** -->
<!--  										MENU PRINCIPAL									  -->
<!-- **************************************************************************************** -->
	<!-- Logo -->
	<p id="logo">
		<img src="<?php echo RACINE_SITE ?>image/logo.png" alt="Logo de quai des vaps, 3 e-cigarettes, noir et rouge" />
	</p>
	<div id="header_droite"><!-- début header_droite -->
		<nav id="main-menu"><!-- début menu -->
					<div id="nav_image">
					<a href="<?php echo RACINE_SITE ?>index.php"><img src="<?php echo RACINE_SITE ?>image/icone_home.png" alt="Icone maison home"
					onmouseover="this.src='<?php echo RACINE_SITE ?>image/icone_home_blanc.png'"
					onmouseout="this.src='<?php echo RACINE_SITE ?>image/icone_home.png'"/></a>
					</div>
					<?php
					while ($cat = mysqli_fetch_assoc($compte))
					{
					?>
					<a href="<?php echo RACINE_SITE ?>boutique.php?cat=<?php echo $cat['nom_categorie'];?>"><?php echo $cat['nom_categorie'];?></a>
					<?php
					}
					?>
					<!-- <a href="<?php echo RACINE_SITE ?>boutique.php?cat=E-cigarettes">E-cigarettes</a>
					<a href="<?php echo RACINE_SITE ?>boutique.php?cat=E-liquides">E-liquides</a>
					<a href="<?php echo RACINE_SITE ?>boutique.php?cat=Accessoires">Accessoires</a> -->
					<a href="<?php echo RACINE_SITE ?>promotions.php">Promotions</a>
		</nav><!-- fin menu -->
		<div id="recherche"><!-- début recherche -->

		<form id="recherche" method="post" action="<?php echo RACINE_SITE ?>recherche.php">
				<div id="label">
					<label><input type="submit" name="envoi_recherche" value="Rechercher" alt="Rechercher"/></label>
				</div>
				<input type="text" name="recherche" placeholder="  Recherche..."/>
		</form>
	</div><!-- fin recherche -->
	</div><!-- fin header_droite -->
</div><!-- fin header_box -->
</header><!-- fin header -->

<!-- **************************************************************************************** -->
<!--  										PRINCIPALE										  -->
<!-- **************************************************************************************** -->
		<div id="principale">
