<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr">
<head>
<title>TP4</title>
<meta http-equiv="Content-Type" content="text/html;charset=iso-8859-1" />
</head>
<body>
<h1>Modification d'un stagiaire</h1>
<?php
//Connexion à la base de données	
try 
{
	$bd = new PDO('mysql:host=localhost;dbname=stagiaires', 'root','');
}
catch (Exception $e)
{
	die('Erreur : ' . $e->getMessage());
}

//Requête dans la base de données pour aller chercher les informations du stagiaire
//Si erreur, retourne false et éxécute le code après le or 
$requeteInfoStagiaires = $bd -> query("select stagiaire.Id, nom, prenom 
									 from stagiaire")
						or die(print_r($bd->errorInfo()));
//Affichage dans le tableau	
?>
<form action = "modification_stagiaire.php" method = "post">
	<table border = "2">
			<tr>
				<th>Nom</th>
				<th>Prénom</th>
				<th>Modifier</th>
			</tr>
<?php 
//Ajouter des lignes au tableau et afficher les infos sur chaque stagiaire					
while ($stagiaireRecord = $requeteInfoStagiaires -> fetch())
{
	//Afficher noms et prenoms
	echo "<tr>
			<td>" . $stagiaireRecord[1] . "</td>" .
			"<td>" . $stagiaireRecord[2] . "</td>";
	
	//Afficher les bouttons radios
	echo "<td><input type = \"radio\" name = \"modifier\" value =\"" . $stagiaireRecord[0] . "\"/></td>";
		    
}
?>

	</table>
<br/>
<input type = "submit" value = "Modifier" />
</form>
<br/>
<a href="index.php">Stagiaire - Menu</a>
</body>
</html>