<?php

			// Connexion au serveur MySQL
            $server = "db609499257.db.1and1.com";
			$login = "dbo609499257";
			$mdp = "cactus42";
			$db = "db609499257";
			$link = mysqli_connect($server, $login, $mdp, $db) or die("Error ".mysqli_error($link));

			// Vérification de la connexion
			if(mysqli_connect_errno()){
				echo 'Connect failed : \n'.mysqli_connect_error();
				exit();
			}

            if(isset($_POST['Oui']) AND $_POST['Oui']=='Oui'){

                header('Location: interdit.php');

            } else if(isset($_POST['Annuler']) AND $_POST['Annuler']=='Annuler'){
                header('Location: consultations.php'); // Si l'utilisateur annule on redirige vers les page des consultations

            } else {

                // Ecriture de la requête
    		    $requete = 'SELECT M.ID_medecin, C.ID_medecin, DATE_FORMAT(Date, "%d/%m/%Y") AS Date, Heure, Civilite_M, Nom_M, Prenom_M
                FROM Consultation as C, Medecin as M
                WHERE '.$_GET['n'].' = ID_Consultation AND C.ID_medecin = M.ID_medecin';

    			//Execution de la requête sur le serveur
    			if(!$resquery = mysqli_query($link,$requete)){
                    echo $requete;
    				die("Error : ".mysqli_errno($resquery)." : ".mysqli_error($resquery));
    		    } else {

                    while($row = mysqli_fetch_array($resquery, MYSQL_ASSOC)){

                        // Affichage du message de confirmation
                        echo '<!DOCTYPE html>

                        <html lang="fr">

                            <head>
                                <meta charset="utf-8" />
                                <title>Supprimer une consultation</title>
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

                                </header><br/><div class="formulaire">

                            <fieldset>

                                <legend>Supprimer une consultation</legend>

                                Voulez vous supprimer la consultation de '.$row['Civilite_M'].". ".$row['Nom_M']." ".$row['Prenom_M'].' du '.$row['Date'].' à '.substr($row['Heure'], 0, 2).'h'.substr($row['Heure'], 3, 2).' ?

                                <form action="supprimerConsultation.php?n='.$_GET['n'].'" method="post">
                					<input type="submit" name="Oui" value="Oui" class="petit"/>
                                    <input type="submit" name="Annuler" value="Annuler" class="petit"/>
                				</form>

                            </fieldset>

                        </div>';

                    }

    			}

            }

            // On inclut le footer
            include("includes/footer.php");

		?>

	</body>

</html>
