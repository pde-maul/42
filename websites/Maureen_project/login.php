<?php
$auth = 0;
include 'lib/includes.php';

/**
* TRAITEMENT DU FORMULAIRE
**/
if(isset($_POST['username']) && isset($_POST['password'])){
	$username = $db->quote($_POST['username']);
	$password = sha1($_POST['password']);
	$sql = "SELECT * FROM users WHERE username=$username AND password='$password'";
	$select = $db->query($sql);

	if($select->rowCount() > 0){
		$_SESSION['Auth'] = $select->fetch();
		setFlash('Vous êtes maintenant connecté');
		header('Location:' . WEBROOT . 'admin/index.php');
		die();
	}
}

/**
* INCLUSION DU HEADER
**/
include 'partials/header.php';
?>

<form action="#" method="POST">
	<div class="form-group">
		<label for="username">Nom d'utilisateur</label>
		<?= input('username'); ?>
	</div>
	<div class="form-group">
		<label for="password">Mot de Passe</label>
		<input type="password" class="form-control" id="password" name="password">
	</div>
	<button type="submit" class="btn btn-default">Se Connecter</button>
</form>

<?php include 'lib/debug.php';
include 'partials/footer.php'; ?>
