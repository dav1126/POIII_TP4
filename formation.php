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
	//Connexion � la base de donn�es	
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
	Pr�nom:
	<input type = "text" name = "prenom"/>
	<br/>
	
	Nationalit�:
	<select name = "nationalite">
	<?php
				
		//Requ�te dans la base de donn�es pour aller chercher les nationalit�s possibles.
		//Si erreur, retourne false et �x�cute le code apr�s le or
		$requeteNation = $bd -> query('select libelle from nationalite')
				   or die(print_r($bd->errorInfo()));
		
		//Tant que les nationalit�s de la requ�te ne sont pas �puis�es, ajouter chaque nationalit� comme choix de la liste d�roulante
		while ($nationaliteRecord = $requeteNation -> fetch())
		{
			echo "<option>".$nationaliteRecord[0];
		}
		
		//Lib�rer la connexion au serveur cr��e par la requ�te
		$requeteNation -> closeCursor();
	?>
	</select>
	<br/>
	<br/>
	
	Type de la formation:
	<select name = "typeFormation">
	<?php
				
		//Requ�te dans la base de donn�es pour aller chercher les types de formation possibles.
		//Si erreur, retourne false et �x�cute le code apr�s le or 
		$requeteTypeFormation = $bd -> query('select Libelle from type_formation')
								or die(print_r($bd->errorInfo()));
								
		//Tant que les types de formation de la requ�te ne sont pas �puis�es, ajouter chaque nationalit� comme choix de la liste d�roulante
		while ($typeFormationRecord = $requeteTypeFormation -> fetch())
		{
			echo "<option>".$typeFormationRecord[0];
		}
		
		//Lib�rer la connexion au serveur cr��e par la requ�te
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
		//Requ�te dans la base de donn�es pour aller chercher les formateurs possibles, avec la salle qui leur est attitr�e.
		//Si erreur, retourne false et �x�cute le code apr�s le or 
		$requeteFormateurs = $bd -> query('select Id_formateur, prenom, nom, Libelle from formateur
										   natural join salle')
								or die(print_r($bd->errorInfo()));
								
		//Tant que les formateurs de la requ�te ne sont pas �puis�es, ajouter chaque formateur comme choix de checkbox
		while ($formateurRecord = $requeteFormateurs -> fetch())
		{	
				//Les checkbox sont plac�s dans un array nomm� formateur[]
				echo " <input type=\"checkbox\" name = \"formateur[".$formateurRecord[0]."]\"> " . $formateurRecord[1] . 
					 " " . $formateurRecord[2] . " dans la salle " . $formateurRecord[3] . " ";
				//Les date sont plac�es dans des array nomm� dateDebut[] et dateFin[].
				echo "d�but: " . "<input type=\"text\" name=\"dateDebut[".$formateurRecord[0]."]\"> " .
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