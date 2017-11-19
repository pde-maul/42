<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="">
		<meta name="author" content="">

		<title>Administration du site</title>

		<!-- Boostrap core CSS -->
		<link href="<?= WEBROOT; ?>css/bootstrap.min.css" rel="stylesheet">

	</head>

	<body>
		<div class="navbar navbar-fixed-top navbar-inverse" role="navigation">
			<div class="container">
				<div class="navbar-header">
					<a class="navbar-brand" href="index.php">Mon portfolio </a>
				</div>
				<ul class="nav navbar-nav">
					<li>
						<a href="category.php">Catégories</a>
					</li>
					<li>
						<a href="work.php">Réalisations</a>
					</li>
				</ul>
			</div>   <!-- fin div container -->
		</div> <!-- fin div navbar -->

		<div class="container">

			<p>&nbsp;</p>
			<p>&nbsp;</p>
			<p>&nbsp;</p>

			<?= flash(); ?>
