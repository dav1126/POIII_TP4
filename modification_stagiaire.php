<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr">
<head>
<title>TP4</title>
<meta http-equiv="Content-Type" content="text/html;charset=iso-8859-1" />
</head>
<body>
<h1>Modification du stagiaire</h1>
<?php
if (isset($_POST["modifier"]))
{
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
	$id = $_POST["modifier"]; 
	$requeteInfoStagiaire = $bd -> query("select id, nom, prenom, nationalite.Libelle, type_formation.Libelle 
										   from stagiaire
										   join nationalite on stagiaire.Id_nationalite = nationalite.Id_nationalite
										   join type_formation on stagiaire.Id_type_formation = type_formation.Id_type_formation
										   where Id =" .$id. ";")
							or die(print_r($bd->errorInfo()));
	$stagiaireRecord = $requeteInfoStagiaire -> fetch();
	?>
	<br/>
	<br/>
	<form action = "modification_ok.php" method = "post">
	<input type = "hidden" name = "id" value = "<?php	echo $stagiaireRecord[0];?>"/>
	Nom:
	<input type = "text" name = "nom" value = "<?php	echo $stagiaireRecord[1];?>"/>
	<br/>
	Prenom:
	<input type = "text" name = "prenom" value = "<?php	echo $stagiaireRecord[2];?>"/>
	<br/>
	Nationalité:
	<select name = "nationalite">
	<?php 
		//Aller chercher les nationalités possible dans la base de données et les afficher en option, en affichant la nationalité initiale du stagiaire par défaut
		$requeteNationalites = $bd -> query("select nationalite.Libelle 
										   from nationalite;")
							or die(print_r($bd->errorInfo()));
		while ($nationaliteRecord = $requeteNationalites -> fetch())
		{
			if ($nationaliteRecord[0] == $stagiaireRecord[3])
				echo "<option selected>" . $nationaliteRecord[0];
			else 
				echo "<option>" . $nationaliteRecord[0];		
		}	
	?>
	</select>
	<br/>
	Type formation:
	<select name = "type_formation">
	<?php 
		//Aller chercher les type de formation possibles dans la base de données et les afficher en option, en affichant le type de formation initiale du stagiaire par défaut
		$requeteTypesFormation = $bd -> query("select Libelle 
										   from type_formation;")
							or die(print_r($bd->errorInfo()));
		while ($typeFormationRecord = $requeteTypesFormation -> fetch())
		{
			if ($typeFormationRecord[0] == $stagiaireRecord[4])
				echo "<option selected>" . $typeFormationRecord[0];
			else 
				echo "<option>" . $typeFormationRecord[0];		
		}
	?>	
	</select>
	<br/>
	<br/>
	<input type = "submit" value = "Modifier"/>	
<?php 	
}
?>
</form>
<a href="index.php">Stagiaire - Menu</a>
</body>
</html>
