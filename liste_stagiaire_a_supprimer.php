<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr">
<head>
<title>TP4</title>
<meta http-equiv="Content-Type" content="text/html;charset=iso-8859-1" />
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

//Si le bouton de suppression a été cliqué, supprimer les stagiaire cochés
if (isset($_POST['bouttonSupprimer']))
{
	if (isset($_POST["stagiaire"]))
	{
		$stagiaires = $_POST["stagiaire"];
		//Pour toutes les cases à cocher de suppression de stagiaires
		foreach($stagiaires as $key => $s)
		{
			//Si la case a été cochée
			if (isset($s))
			{
				$requeteSupprimerStagiaires1 = $bd -> query("delete from stagiaire_formateur where Id_stagiaire =".$key.";")
													or die(print_r($bd->errorInfo()));
				$requeteSupprimerStagiaires2 = $bd -> query("delete from stagiaire where Id =".$key.";")
													or die(print_r($bd->errorInfo()));																				
			}
		}
		echo "Stagiaire supprimé avec succès!";
	}
	else
	echo "Cocher un stagiaire a supprimer!";

}

//Requête dans la base de données pour aller chercher les informations du stagiaire
//Si erreur, retourne false et éxécute le code après le or 
$requeteInfoStagiaires = $bd -> query("select stagiaire.Id, nom, prenom, nationalite.Libelle, type_formation.Libelle 
									 from stagiaire
									 join nationalite on stagiaire.Id_nationalite = nationalite.Id_nationalite
									 join type_formation on stagiaire.Id_type_formation = type_formation.Id_type_formation;")
						or die(print_r($bd->errorInfo()));
//Affichage dans le tableau	
?>
<form action = "" method = "post">
	<table border = "2">
			<tr>
				<th>Nom</th>
				<th>Prénom</th>
				<th>Nationalité</th>
				<th>Type de formation</th>
				<th>Formateur - Salle - Date de début - Date de fin</th>
				<th>Suppression</th>
			</tr>
<?php 
//Ajouter des lignes au tableau et afficher les infos sur chaque stagiaire					
while ($stagiaireRecord = $requeteInfoStagiaires -> fetch())
{
	echo "<tr>
			<td>" . $stagiaireRecord[1] . "</td>" .
			"<td>" . $stagiaireRecord[2] . "</td>" .
			"<td>" . $stagiaireRecord[3] . "</td>" .
			"<td>" . $stagiaireRecord[4] . "</td>" ;
	
	//Requête dans la base de données pour aller chercher les informations de formations de chaque stagiaire
	//Si erreur, retourne false et éxécute le code après le or 
	$requeteInfoFormations = $bd -> query("select nom, salle.Libelle, Date_debut, Date_fin 
									 from stagiaire_formateur
									 join formateur on formateur.Id_formateur = stagiaire_formateur.Id_formateur
									 join salle on formateur.Id_salle = salle.Id_salle
									 where stagiaire_formateur.Id_stagiaire = " . $stagiaireRecord[0] . ";")
							or die(print_r($bd->errorInfo()));
	//Afficher l'info sur chaque formation dans le tableau
	echo "<td>";						
	while ($formationRecord = $requeteInfoFormations -> fetch())
	{
		echo    $formationRecord[0] . 
				" - " . 
				$formationRecord[1] .
				" - " .
				date("Y/m/d", strtotime($formationRecord[2])) .
				" - " . 
				date("Y/m/d", strtotime($formationRecord[3])) .
				"<br/>";
	}
	echo "</td>";

	//Afficher les cases à cocher dans la colonne suppression
	echo "<td> <input type=\"checkbox\" name = \"stagiaire[" . $stagiaireRecord[0] . "]\">";
	
}
?>
	</table>
	<br/>
<input type = "submit" name = "bouttonSupprimer" value = "Supprimer"/>
<br/>
<br/>
<a href="index.php">Stagiaire - Menu</a>
</form>
</body>
</html>