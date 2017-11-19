SELECT ROUND(AVG(nbr_siege)) AS 'moyenne'
FROM salle
GROUP BY etage_salle;
