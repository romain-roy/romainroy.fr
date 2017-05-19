<!DOCTYPE html>

<html lang="fr">

    <head>
        <meta charset="utf-8" />
        <title>Consultations</title>
        <link rel="stylesheet" href="style.css"/>
		<link rel="icon" typz="image/x-icon" href="images/favicon.ico"/>
    </head>

	<body>

        <!-- En-tête -->
        <header>

            <h1>Cabinet Médical Guéritou</h1>

            <!-- Menu -->
            <nav>
                <ul>
                    <li id="courant"><a href="consultations.php" title="Gérer les consultations">Consultations</a></li>
                    <li><a href="usagers.php" title="Gérer les usagers">Usagers</a></li>
                    <li><a href="medecins.php" title="Gérer les médecins">Médecins</a></li>
                    <li><a  href="statistiques.php" title="Voir les statistiques">Statistiques</a></li>
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

			// Vérification de la connexion
			if(mysqli_connect_errno()){
				echo 'Connect failed : \n'.mysqli_connect_error();
				exit();
			}

    			// Ecriture des requêtes

                if(isset($_GET["filtre"])){

                    // Requête si l'utilisateur utilise le filtre par médecin
        		    $requeteConsultations = "SELECT ID_Consultation, ID_medecin, N_securite_sociale, DATE_FORMAT(Date, '%d/%m/%Y') AS FDate, Date, Heure, Duree
        						             FROM Consultation
                                             WHERE ID_medecin = ".$_GET["filtre"]."
        						             ORDER BY Date desc, Heure desc";
                } else {

                    $requeteConsultations = "SELECT ID_Consultation, ID_medecin, N_securite_sociale, DATE_FORMAT(Date, '%d/%m/%Y') AS FDate, Date, Heure, Duree
        						             FROM Consultation
        						             ORDER BY Date desc, Heure desc";
                }


    		    $requeteUsagers = "SELECT *
    						       FROM Usager";

                $requeteMedecins = "SELECT *
                                    FROM Medecin
                                    ORDER BY Nom_M";

			// Execution de la requête sur le serveur

            // On récupère les usagers
            if(!$resquery = mysqli_query($link,$requeteUsagers)){
                die("Error : ".mysqli_errno($resquery)." : ".mysqli_error($resquery));
            } else {
                while($row = mysqli_fetch_array($resquery, MYSQL_ASSOC)){
                    $tabUsagers[$row["N_securite_sociale"]] = $row["Civilite"].". ".$row["Nom"]." ".$row["Prenom"];
                }
            }

            // On récupère les médecins
            if(!$resquery = mysqli_query($link,$requeteMedecins)){
                die("Error : ".mysqli_errno($resquery)." : ".mysqli_error($resquery));
            } else {
                while($row = mysqli_fetch_array($resquery, MYSQL_ASSOC)){
                    $tabMedecins[$row['ID_medecin']] = $row["Civilite_M"].". ".$row["Nom_M"]." ".$row["Prenom_M"];
                }
            }

                echo '<div>

                        <h2>Liste des consultations</h2><br/>

                        <a href="ajouterConsultation.php" title="Ajouter une consultation" class="bouton"><img src="images/plus.png">Ajouter une consultation</a>

                        <form action="consultations.php" method="get">

                            <!-- Sélecteur avec les médecins -->
                            <label for="filtre" class="petit">Filtrer par médecin :</label>
                            <select name="filtre" id="filtre" class="petit">';
                            if(!$resquery = mysqli_query($link,$requeteMedecins)){
                                die("Error : ".mysqli_errno($resquery)." : ".mysqli_error($resquery));
                            } else {
                                while($row = mysqli_fetch_array($resquery, MYSQL_ASSOC)){
                                    if(isset($_GET['filtre'])){
                                        if($row['ID_medecin'] == $_GET['filtre']){
                                            echo'<option value='.$row['ID_medecin'].' selected="selected">'.$row['Civilite_M'].". ".$row['Nom_M']." ".$row['Prenom_M'].'</option>';
                                        } else {
                                            echo'<option value='.$row['ID_medecin'].'>'.$row['Civilite_M'].". ".$row['Nom_M']." ".$row['Prenom_M'].'</option>';
                                        }
                                    } else {
                                        echo'<option value='.$row['ID_medecin'].'>'.$row['Civilite_M'].". ".$row['Nom_M']." ".$row['Prenom_M'].'</option>';
                                    }
                                }
                            }
                            echo'</select><br/>

                            <!-- Pour voir tous les médecins au cas où on ait utilisé le filtre -->
                            <input type="submit" value="Valider" class="petit"/> <a href="consultations.php" title="Voir tous les médecins">Voir tous les médecins</a>

                        </form>

                    </div>

                    <table class="consultation">';

                if(!$resquery = mysqli_query($link,$requeteConsultations)){
    				die("Error : ".mysqli_errno($resquery)." : ".mysqli_error($resquery));
    		    } else {

                    // Affichage des consultations

                    $compteur = 0; // Sert à compter le nombre d'usagers
                    $modulo = 0; // Sert à savoir si on en est à un nombre pair ou impair d'usagers, afin de choisir une couleur différente une fois sur deux
    				while($row = mysqli_fetch_array($resquery, MYSQL_ASSOC)){
                        $compteur = $compteur + 1;
                        $modulo = $compteur % 2;

                        // On affiche une "colonne" de tableau pour chaque consultation de la base de données
                        echo '<tr class="couleur'.$modulo.'">

                        		<td class="droite">
                                    <a href="modifierConsultation.php?n='.$row['ID_Consultation'].'" class="icone"> <img src="images/crayon.png" alt="Modifier"/>Modifier une consultation</a><br/>
                                    <a href="supprimerConsultation.php?n='.$row['ID_Consultation'].'" class="icone"> <img src="images/croix.png" alt="Supprimer"/>Supprimer une consultation</a>
                                </td>

                                <td>
                                    Le <strong>'.$row['FDate'].'</strong> à <strong>'.substr($row['Heure'], 0, 2).'h'.substr($row['Heure'], 3, 2).'</strong><br/>
                                    Durée : '.substr($row['Duree'], 0, 2).'h'.substr($row['Duree'], 3, 2).'
                                </td>

                                <td>
                                    <strong>'.$tabUsagers[$row["N_securite_sociale"]].'</strong><br/>a rendez vous avec <strong>'.$tabMedecins[$row['ID_medecin']].'</strong>.
                                </td>

                                </tr>';

    			 }

                 // Il n'y a plus de consultations, on ferme la table
                 echo '</table>';

			}

            // On inclut le footer
            include("includes/footer.php");

		?>

	</body>

</html>
