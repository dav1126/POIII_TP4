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
	//Connexion à la base de données	
	try 
	{
		$bd = new PDO('mysql:host=localhost;dbname=stagiaires', 'root','');
	}
	catch (Exception $e)
	{
		die('Erreur : ' . $e->getMessage());
	}
	if (!empty($_POST["nom"]) && !empty($_POST["prenom"]))
	{
		//Requête dans la base de données pour aller chercher le id_nationalite
		$requeteIdNationalite = $bd -> query("select Id_nationalite from nationalite where Libelle =\"". $_POST['nationalite'] ."\";")
								or die(print_r($bd->errorInfo()));
		$idNationaliteRecord = $requeteIdNationalite -> fetch();
								
		//Requête dans la base de données pour aller chercher le id_type_formation
		$requeteIdTypeFormation = $bd -> query("select Id_type_formation from type_formation where Libelle =\"". $_POST['type_formation'] ."\";")
								or die(print_r($bd->errorInfo()));
		$idTypeFormationRecord = $requeteIdTypeFormation -> fetch();						
								
		
		//Requête dans la base de données pour aller modifier les informations du stagiaire
		//Si erreur, retourne false et éxécute le code après le or
		$requeteModifStagiaire = $bd -> query("update stagiaire 
										set nom =\"". $_POST['nom'] ."\",
											prenom = \"". $_POST['prenom'] ."\",
											Id_nationalite = \"". $idNationaliteRecord[0] ."\",
											Id_type_formation = \"". $idTypeFormationRecord[0] ."\" 
										where id = \"". $_POST['id'] ."\";")
								or die(print_r($bd->errorInfo()));
		echo "Modification effectuée avec succès!";
	}
	else
	echo "Erreur: Les champs nom et prenom ne peuvent être vide";
?>
<br/>
<a href="index.php">Stagiaire - Menu</a>
</body>
</html>
