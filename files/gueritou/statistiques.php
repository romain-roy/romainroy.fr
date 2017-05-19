<!DOCTYPE html>

<html lang="fr">

    <head>
        <meta charset="utf-8" />
        <title>Statistiques</title>
        <link rel="stylesheet" href="style.css"/>
		<link rel="icon" type="image/x-icon" href="images/favicon.ico"/>
    </head>

	<body>

        <!-- En-tête -->
        <header>

            <h1>Cabinet Médical Guéritou</h1>

            <!-- Menu -->
            <nav>
                <ul>
                    <li><a href="consultations.php" title="Gérer les consultations">Consultations</a></li>
                    <li><a href="usagers.php" title="Gérer les usagers">Usagers</a></li>
                    <li><a href="medecins.php" title="Gérer les médecins">Médecins</a></li>
                    <li id="courant"><a  href="statistiques.php" title="Voir les statistiques">Statistiques</a></li>
                </ul>
            </nav>

        </header>

		<?php

			// Connexion au serveur MySQL
            $server = "db609499257.db.1and1.com";
			$login = "dbo609499257";
			$mdp = "O0a(O3o-";
			$db = "db609499257";
			$link = mysqli_connect($server, $login, $mdp, $db) or die("Error ".mysqli_error($link));

			// Verification de la connexion
			if(mysqli_connect_errno()){
				echo 'Connect failed : \n'.mysqli_connect_error();
				exit();
			}

            // Ecriture des requêtes
		    $requeteUsagers = "SELECT Civilite, DATE_FORMAT(Date_de_naissance, '%d/%m/%Y') AS Date_de_naissance
                               FROM Usager";

            $requeteMedecins = "SELECT C.ID_medecin, SUM(C.Duree) AS Duree, M.ID_medecin, M.Nom_M, M.Prenom_M, M.Civilite_M
                                 FROM Medecin AS M, Consultation AS C
                                 WHERE M.ID_medecin = C.ID_medecin
                                 GROUP BY C.ID_medecin";

			// Execution de la requête usagers sur le serveur
			if(!$resquery = mysqli_query($link,$requeteUsagers)){
				die("Error : ".mysqli_errno($resquery)." : ".mysqli_error($resquery));
		    } else {

                // Une fonction pour calculer l'âge d'un usager en fonction de sa date de naissance
                function DateAge($DateNaissance){
                        $DateNaissance = explode("/", $DateNaissance);
                        $Date = explode("/", date("d/m/Y"));
                        if (($DateNaissance[1] <= $Date[1]) && ($DateNaissance[0] <= $Date[0])) $Age = $Date[2] - $DateNaissance[2];
                        else $Age = $Date[2] - $DateNaissance[2] - 1;
                        return $Age;
                }

                // Variables contenant les valeurs nécessaires aux calculs des Statistiques
                $usagers = 0;
                $jeunesFemmes = 0;
                $jeunesHommes = 0;
                $femmesAdultes = 0;
                $hommesAdultes = 0;
                $femmesSeniors = 0;
                $hommesSeniors = 0;

                while($row = mysqli_fetch_array($resquery, MYSQL_ASSOC)){
                    $usagers = $usagers + 1;
                    if(DateAge($row['Date_de_naissance']) < 25 and $row['Civilite'] == 'M'){
                        $jeunesHommes = $jeunesHommes + 1;
                    }
                    if(DateAge($row['Date_de_naissance']) <= 50 and DateAge($row['Date_de_naissance']) >= 25 and $row['Civilite'] == 'M'){
                        $hommesAdultes = $hommesAdultes + 1;
                    }
                    if(DateAge($row['Date_de_naissance']) > 50 and $row['Civilite'] == 'M'){
                        $hommesSeniors = $hommesSeniors + 1;
                    }
                    if(DateAge($row['Date_de_naissance']) < 25 and $row['Civilite'] == 'Mme'){
                        $jeunesFemmes = $jeunesFemmes + 1;
                    }
                    if(DateAge($row['Date_de_naissance']) <= 50 and DateAge($row['Date_de_naissance']) >= 25 and $row['Civilite'] == 'Mme'){
                        $femmesAdultes = $femmesAdultes + 1;
                    }
                    if(DateAge($row['Date_de_naissance']) > 50 and $row['Civilite'] == 'Mme'){
                        $femmesSeniors = $femmesSeniors + 1;
                    }
                }

				echo'<div>

                        <h2>Statistiques</h2><br/>

                    </div>';

                    if($usagers > 0){

                        echo '<table class="stats">

                            <caption>Répartition des usagers du cabinet médical selon leur sexe et leur âge</caption>

                            <tr class="couleur1">
                                <th>Tranche d\'âge</th>
                                <th>Hommes</th>
                                <th>Femmes</th>
                                <th>Tous confondus</th>
                            </tr>

                            <tr class="couleur0">
                                <th>Moins de 25 ans</th>
                                <td>'.round((($jeunesHommes)/$usagers*100), 1).'%</td>
                                <td>'.round((($jeunesFemmes)/$usagers*100), 1).'%</td>
                                <td>'.round((($jeunesFemmes+$jeunesHommes)/$usagers*100), 1).'%</td>
                            </tr>

                            <tr class="couleur1">
                                <th>Entre 25 et 50 ans</th>
                                <td>'.round((($hommesAdultes)/$usagers*100), 1).'%</td>
                                <td>'.round((($femmesAdultes)/$usagers*100), 1).'%</td>
                                <td>'.round((($hommesAdultes+$femmesAdultes)/$usagers*100), 1).'%</td>
                            </tr>

                            <tr class="couleur0">
                                <th>Plus de 50 ans</th>
                                <td>'.round((($hommesSeniors)/$usagers*100), 1).'%</td>
                                <td>'.round((($femmesSeniors)/$usagers*100), 1).'%</td>
                                <td>'.round((($hommesSeniors+$femmesSeniors)/$usagers*100), 1).'%</td>
                            </tr>

                            <tr class="couleur1">
                                <th>Tous confondus</th>
                                <td>'.round((($hommesSeniors+$hommesAdultes+$jeunesHommes)/$usagers*100), 1).'%</td>
                                <td>'.round((($femmesSeniors+$femmesAdultes+$jeunesFemmes)/$usagers*100), 1).'%</td>
                                <td>100%</td>
                            </tr>

                        </table>';

                    }

			}

            // Execution de la requête médecins sur le serveur
            if(!$resquery = mysqli_query($link,$requeteMedecins)){
				die("Error : ".mysqli_errno($resquery)." : ".mysqli_error($resquery));
		    } else {

                echo'<table class="stats">

                    <caption>Temps de consultation de chaque médecin <em>(ayant effectué au moins une consultation)</em></caption>

                    <!-- On nommes les colonnes du tableau -->
                    <tr class="couleur1">
                        <th>Médecin</th>
                        <th>Nombre d\'heures effectuées</th>
                    </tr>';

                $compteur = 1;
                $modulo = 0;
                while($row = mysqli_fetch_array($resquery, MYSQL_ASSOC)){
                    $compteur = $compteur + 1;
                    $modulo = $compteur % 2; // Sert à savoir si on en est à un nombre pair ou impair lignes du tableau, afin de choisir une couleur différente une fois sur deux
                    // Récupération dans un format adapté des heures et minutes effectuées
                    $heures = floor((($row['Duree']/100)/60));
                    $minutes = ($row['Duree']/100) - ($heures * 60);

                    echo'<tr class="couleur'.$modulo.'">
                        <td>'.$row['Civilite_M'].'. '.$row['Nom_M'].' '.$row['Prenom_M'].'</td>
                        <td>'.$heures.'h';

                    // Pour quand même afficher les minutes sur deux chiffres si inférieur à 10
                    if($minutes < 10){
                        echo '0';
                    }

                    echo $minutes.'</td>
                    </tr>';
                }

                // Il n'y a plus de médecins, on ferme la table
                echo '</table>';

			}

            // On inclut le footer
            include("includes/footer.php");

		?>

	</body>

</html>
