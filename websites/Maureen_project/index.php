<?php
	$auth = 0;
	include 'lib/includes.php';
	include 'partials/header.php';
	$select = $db->query('SELECT * FROM works');
	$works = $select->fetchAll();
	?>

<h1> Bonjour Maman <3 </h1>

<div class="row">
	<?php foreach ($works as $k => $work): ?>
		<div class="col-sm-3">
			<a href="view.php?id=<?= $work['id']; ?>">
				<h2><?= $work['name']; ?></h2>
			</a>
		</div>
	<?php endforeach ?>
</div>


<?php include 'lib/debug.php'; ?>
<?php include 'partials/footer.php'; ?>
