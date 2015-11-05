<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr">
<head>
<title>TP4</title>
<meta http-equiv="Content-Type" content="text/html;charset=iso-8859-1" />
<link href="style.css" rel="stylesheet" type="text/css" />
</head>
<body>
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
	?>
	
	<h1>Ajouter un stagiaire en formation</h1>
	<br/>
	<form action = "insertion_stagiaire.php" method = "post">
	Nom:
	<input type = "text" name = "nom"/>
	<br/>
	Prénom:
	<input type = "text" name = "prenom"/>
	<br/>
	
	Nationalité:
	<select name = "nationalite">
	<?php
				
		//Requête dans la base de données pour aller chercher les nationalités possibles.
		//Si erreur, retourne false et éxécute le code après le or
		$requeteNation = $bd -> query('select libelle from nationalite')
				   or die(print_r($bd->errorInfo()));
		
		//Tant que les nationalités de la requête ne sont pas épuisées, ajouter chaque nationalité comme choix de la liste déroulante
		while ($nationaliteRecord = $requeteNation -> fetch())
		{
			echo "<option>".$nationaliteRecord[0];
		}
		
		//Libérer la connexion au serveur créée par la requête
		$requeteNation -> closeCursor();
	?>
	</select>
	<br/>
	<br/>
	
	Type de la formation:
	<select name = "typeFormation">
	<?php
				
		//Requête dans la base de données pour aller chercher les types de formation possibles.
		//Si erreur, retourne false et éxécute le code après le or 
		$requeteTypeFormation = $bd -> query('select Libelle from type_formation')
								or die(print_r($bd->errorInfo()));
								
		//Tant que les types de formation de la requête ne sont pas épuisées, ajouter chaque nationalité comme choix de la liste déroulante
		while ($typeFormationRecord = $requeteTypeFormation -> fetch())
		{
			echo "<option>".$typeFormationRecord[0];
		}
		
		//Libérer la connexion au serveur créée par la requête
		$requeteTypeFormation -> closeCursor();
	?>
	</select>
	<br/>
	<br/>
	Formateurs par date:
	<br/>
	<i>Entrer les dates au format AAAA/MM/JJ.</i>
	<br/>
	<br/>
	<?php 
		//Requête dans la base de données pour aller chercher les formateurs possibles, avec la salle qui leur est attitrée.
		//Si erreur, retourne false et éxécute le code après le or 
		$requeteFormateurs = $bd -> query('select Id_formateur, prenom, nom, Libelle from formateur
										   natural join salle')
								or die(print_r($bd->errorInfo()));
								
		//Tant que les formateurs de la requête ne sont pas épuisées, ajouter chaque formateur comme choix de checkbox
		while ($formateurRecord = $requeteFormateurs -> fetch())
		{	
				//Les checkbox sont placés dans un array nommé formateur[]
				echo " <input type=\"checkbox\" name = \"formateur[".$formateurRecord[0]."]\"> " . $formateurRecord[1] . 
					 " " . $formateurRecord[2] . " dans la salle " . $formateurRecord[3] . " ";
				//Les date sont placées dans des array nommé dateDebut[] et dateFin[].
				echo "début: " . "<input type=\"text\" name=\"dateDebut[".$formateurRecord[0]."]\"> " .
					 " fin: " . "<input type=\"text\" name=\"dateFin[".$formateurRecord[0]."]\"> ";
				echo "<br/>";
		}
	?>	
	<br/>
	<input type = "submit" value = "Envoyer"/>
	<br/>
	<br/>
	<a href="index.php">Stagiaire - Menu</a>		
	</form>
</body>
</html>