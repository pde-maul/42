#!/usr/bin/php
<?php
echo "Entrez un nombre: ";
	while ($nb = fgets(STDIN))
	{
		$nb = trim($nb);
		if (!is_numeric($nb))
			echo "'$nb' n'est pas un chiffre\n";
		elseif ($nb % 2){
			echo "Le chiffre $nb est Impair\n";
		}
		elseif ($nb % 2 == 0){
			echo "Le chiffre $nb est Pair\n";
		}
		echo "Entrez un nombre: ";
	}
?>
