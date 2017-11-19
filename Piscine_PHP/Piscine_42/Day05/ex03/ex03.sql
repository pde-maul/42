INSERT INTO ft_table (login, groupe, date_de_creation)
SELECT nom, 'other', date_naissance FROM `db_pde-maul`.fiche_personne
WHERE CHAR_LENGTH(nom) < 9 AND
nom LIKE '%a%'
ORDER BY nom ASC
LIMIT 10;
