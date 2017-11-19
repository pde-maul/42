<!DOCTYPE html>
<html>
	<head>
		<title>Quai des Vaps, Boutique de Cigarettes Electronique située à deux pas de Chez Vous !</title>
		<meta charset="utf-8">
		<?php
				// --------------------------- Création de la requête fiche produit ---------------------------------------
	if(isset($_GET['id'])) // Est-ce qu'il y a bien un ID renseigné dans l'URL
	{
		$resultat = execute_requete("SELECT *
			FROM produit p, promotion c
			WHERE p.id_produit = ".$_GET['id']."
			AND	(c.id_promo = p.id_promo
			OR p.id_promo IS NULL)
			GROUP BY  p.id_produit");
			// On fait une requête de selection pour aller chercher les infos dU produit en fonction de l'ID de l'URL
	$produit = mysqli_fetch_assoc($resultat);
		?>
		<meta property="fb:app_id"          content="1234567890" />
		<meta property="og:type"            content="article" />
		<meta property="og:url"             content="<?php echo RACINE_SITE ?>fiche-produit.php?id=<?php echo $_GET['id'] ?>&page=1" />
		<meta property="og:title"           content="<?php echo $produit['titre'] ?> - <?php echo $produit['prix'] ?>€ TTC (vendu par Quaidesvaps.fr)" />
		<meta property="og:image"           content="<?php echo RACINE_SITE ?><?php echo $produit['photo'] ?>" />
		<meta property="og:description"    content="<?php echo $produit['descriptif'] ?>" />
		<?php
	}
		?>
		<!-- Les feuilles de styles CSS -->
		<link  rel="stylesheet" href="<?php echo RACINE_SITE ?>styles/structure.css" />
		<link  rel="stylesheet" href="<?php echo RACINE_SITE ?>styles/header.css" />
		<link  rel="stylesheet" href="<?php echo RACINE_SITE ?>styles/generale.css" />
		<link  rel="stylesheet" href="<?php echo RACINE_SITE ?>styles/responsive.css" />

		<link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
		<link href='http://fonts.googleapis.com/css?family=Marck+Script' rel='stylesheet' type='text/css'>

		<!-- Pop up MAP -->
        <script type="text/javascript">

        function open_infos()
        {
            width = 800;
            height = 600;
            if(window.innerWidth)
            {
                    var left = (window.innerWidth-width)/2;
                    var top = (window.innerHeight-height)/2;
            }
            else
            {
                    var left = (document.body.clientWidth-width)/2;
                    var top = (document.body.clientHeight-height)/2;
            }
            window.open('map.html','nom_de_ma_popup','menubar=no, scrollbars=no, top='+top+', left='+left+', width='+width+', height='+height+'');
        }
      </script>
	</head>
	<body>
	<div id="page"><!-- debut page -->
