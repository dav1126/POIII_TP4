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
		
	if (isset($_POST))
	{	//Si les champs nom et prenom sont remplis
		if (!empty($_POST["nom"]) && !empty($_POST["prenom"]))
		{				
			if (isset($_POST['formateur']))
			{	
				$formateur = $_POST['formateur'];
				$dateDebut = $_POST['dateDebut'];
				$dateFin = $_POST['dateFin'];
				
				$dateDebutMin = "";//Date de d�but des formations la plus t�t
				$dateFinMax = "";//Date de fin des formations la plus tard
				
				//V�rifier que pour les formateurs choisi, les champs de d�but et de fin des dates valides
				$champsDebutFinOK = true;//bool�ean indiquant si les date de d�but et de fin sont entr�es pour les formateurs s�lectionn�s
				foreach($formateur as $key => $f)
				{
					//Si la case de formateur a �t� coch�e
					if (isset($f))
					{
						$date1 = strtotime($dateDebut[$key]);
						$date2 = strtotime($dateFin[$key]);
						
						//V�rification que les formats entr�s correspondent � un format de date valide.
						//Si ce n'est pas le cas, la fonction strtotime() renvoie une variable vide.
						if ($date1 == "" || $date2 == "")
							$champsDebutFinOK = false;
						
						//V�rification que la date de debut pr�c�de la date de fin
						if ($date1 > $date2)
							$champsDebutFinOK = false;

						//Enregistrer la date de d�but la plus t�t et la date de fin la plus tard
						if ($dateDebutMin == "" || $dateDebutMin > $date1)
							$dateDebutMin = $date1;
					
						if ($dateFinMax == "" || $dateFinMax < $date2) 						
							$dateFinMax = $date2;
					}
				}	
						
				if ($champsDebutFinOK)
				{							
				//Insert dans la table Stagiaire
					$nom = ($_POST["nom"]);
					$prenom = ($_POST["prenom"]);
					$nationalite = ($_POST["nationalite"]);
					$typeFormation = $_POST["typeFormation"];
					
					//insertStagiaireu�te pour aller chercher le id de la nationalit�
					$select1 = $bd -> query("Select Id_nationalite from nationalite where Libelle =\"" . $nationalite . "\"")
					or die(print_r($bd->errorInfo()));
					$idNationalite = $select1 -> fetch();

					//insertStagiaireu�te pour aller chercher le id du type de formation
					$select2 = $bd -> query("Select Id_type_formation from type_formation where Libelle =\"" .$typeFormation . "\"")
					or die(print_r($bd->errorInfo()));
					$idTypeFormation = $select2 -> fetch();				
				
					//insertStagiaireu�te d'insertion dans la table Stagiaire
					$insertStagiaire = $bd->prepare("INSERT into stagiaire values (null, :nom, :prenom, :idNationalite, :idTypeFormation)")
					or die(print_r($bd->errorInfo()));
					$insertStagiaire -> bindParam(':nom', $nom);
					$insertStagiaire -> bindParam(':prenom', $prenom);
					$insertStagiaire -> bindParam(':idNationalite', $idNationalite[0]);
					$insertStagiaire -> bindParam(':idTypeFormation', $idTypeFormation[0]);
					$insertStagiaire -> execute() or die(print_r($bd->errorInfo()));
					
				//Insert dans la table stagiaire_formateur
					//insertStagiaireu�te pour aller chercher le id du stagiaire que l'on vient d'ins�rer. 
					//Ce id est n�cessairement le max de la table, �tant donn� que le champ est auto_increment
					$query3 = $bd -> query("Select max(Id) from stagiaire");
					$idStagiaire = $query3 -> fetch();
					
					//Pour tous les formateurs pr�sent�s � la page formation.php
					foreach($formateur as $key => $f)
					{	
						//Si le formateur �tait s�lectionn� � la page formation.php
						if (isset($f))
						{
							//insertStagiaireu�te d'insertion dans la table stagiaire_formateur
							$insertStagiaireFormateur = $bd -> prepare("INSERT into stagiaire_formateur values (:idStagiaire, :idFormateur, :dateDebut, :dateFin)");
							$insertStagiaireFormateur -> bindParam(':idStagiaire', $idStagiaire[0]) or die(print_r($bd->errorInfo()));
							$insertStagiaireFormateur -> bindParam(':idFormateur', $key);
							$insertStagiaireFormateur -> bindParam(':dateDebut', $dateDebut[$key]);
							$insertStagiaireFormateur -> bindParam(':dateFin', $dateFin[$key]);
							$insertStagiaireFormateur -> execute() or die(print_r($bd->errorInfo()));
						}
					}
					
					?>
					<b>Stagiaire enregistr�.</b> <a href="formation.php">Revenir au formulaire</a>
					<br/>
					<table border="2">
						<tr>
							<td>Nom</td>
							<td><?php echo $nom; ?></td>
						</tr>
						<tr>
							<td>Prenom</td>
							<td><?php echo $prenom; ?></td>
						</tr>
						<tr>
							<td>Date d�but</td>
							<td><?php echo date("Y/m/d", $dateDebutMin); ?></td>
						</tr>
						<tr>
							<td>Date fin</td>
							<td><?php echo date("Y/m/d", $dateFinMax); ?></td>
						</tr>
					</table>
					<?php 
				}	
				
				else 
				{
					echo "Erreur: Pour les formations s�lectionn�es, veuillez entrer des dates valides de d�but et de fin selon le format sp�cifi�. <br/>
						  Assurez-vous que les dates de d�buts soient plus t�t que les dates de fin";
				}
			}
			else 
			{
				echo "Erreur: veuillez choisir au moins un formateur.";
			}	
		}
		else 
		{
			echo "Erreur: veuillez remplir les champs nom et pr�nom";
		}
	}
	?>
</body>
</html>

