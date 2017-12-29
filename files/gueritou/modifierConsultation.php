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


			if(!empty($_POST["medecin"]) and
                !empty($_POST["usager"]) and
                !empty($_POST["date"]) and
                !empty($_POST["heure"]) and
				!empty($_POST["duree"])){

				$requeteConsultations = "SELECT Heure AS HeureDebut, ADDTIME(Heure, Duree) AS HeureFin
                                            FROM Consultation
                                            WHERE Date = \"".$_POST["date"]."\"
                                            	AND ID_medecin = ".$_POST['medecin']."
                                            	AND ID_consultation <> ".$_GET['n'];

                // Fonction pour additionner une heure et une durée
                function Somme($heure, $duree){
                    $explore = explode(":",$heure);
                    $secondes = $explore[2];
                    $minutes = $explore[1]*60;
                    $heures = $explore[0]*3600;
                    $explore = explode(":",$duree);
                    $secondes += $explore[2];
                    $minutes += $explore[1]*60;
                    $heures += $explore[0]*3600;
                    $total = $heures+$minutes+$secondes;
                    $HeureFin = $total % 3600;
                	$time[0] = ($total - $HeureFin) / 3600 ;
                	$time[2] = $HeureFin % 60 ;
                	$time[1] = ($HeureFin - $HeureFin[2]) / 60;
                    $res = '';
                    if($time[0] < 10){
                        $res = '0';
                    }
                    $res = $res.$time[0].':';
                    if($time[1] < 10){
                        $res = $res.'0';
                    }
                    $res = $res.$time[1].':';
                    if($time[2] < 10){
                        $res = $res.'0';
                    }
                    $res = $res.$time[2];
                    return $res;
                }

                // Pour éviter le cheuvauchement pour un médecin
                $update = 1;
                if(!$resquery = mysqli_query($link,$requeteConsultations)){
                    die("Error : ".mysqli_errno($resquery)." : ".mysqli_error($resquery));
                } else {
                    while($row = mysqli_fetch_array($resquery, MYSQL_ASSOC)){
                        if(
                            ($_POST['heure'] < $row['HeureFin']) and
                            (Somme($_POST['heure'], $_POST['duree']) > $row['HeureDebut'])
                        ){
                            $update = 0;
                        }
                    }
                }

                if($update == 1){
                    header('Location: interdit.php');

				} else {
		                echo '<!DOCTYPE html>

                        <html lang="fr">

                            <head>
                                <meta charset="utf-8" />
                                <title>Modifier une consultation</title>
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
		                        <legend>Erreur</legend>
		                        Impossible d\'ajouter un rendez vous pour ce médecin à cette heure-ci, car une consultation est déjà prévue.
		                        <form action="consultations.php" method="post">
		                            <input type="submit" name="Retour" value="Retour" class="petit"/>
		                        </form>
		                    </fieldset>
		                </div>';
		            }

			} else {

				// Ecriture des requêtes
				$requeteConsultations = "SELECT *
							FROM Consultation
                            WHERE ".$_GET['n']." = ID_consultation";

                $requeteUsagers = "SELECT *
    						       FROM Usager
                                   ORDER BY Nom";

                $requeteMedecins = "SELECT *
                                    FROM Medecin
                                    ORDER BY Nom_M";

				// Execution de la requête sur le serveur
				if(!$resquery = mysqli_query($link,$requeteConsultations)){
					die("Error : ".mysqli_errno($resquery)." : ".mysqli_error($resquery));
				} else {

                    while($row = mysqli_fetch_array($resquery, MYSQL_ASSOC)){
                        $medecin = $row['ID_medecin'];
                        $usager = $row['N_securite_sociale'];
                        $date = $row['Date'];
                        $heure = $row['Heure'];
                        $duree = $row['Duree'];
                    }

					echo'<!DOCTYPE html>

                    <html lang="fr">

                        <head>
                            <meta charset="utf-8" />
                            <title>Modifier une consultation</title>
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

                            </header><div class="formulaire">

                    <!-- Lien pour retourner à la liste des consultations si l\'utilisateur change d\'avis -->
                    <p><img src="images/fleche.png"><a href="consultations.php" title="Retourner à la liste des consultations">Retourner à la liste des consultations</a></p><br/>

					<fieldset>

                        <legend>Ajouter une consultation</legend>';

                        // Message d'erreur si il n'a pas rempli tout les champs
                        if(isset($_POST['Valider']) AND $_POST['Valider']=='Valider'){
                            echo '<p class="erreur">Veuillez remplir tout les champs !</p><br/>';
                        }

						echo '<form action="modifierConsultation.php?n='.$_GET['n'].'" method="post">

                            <!-- Sélecteur avec les médecins -->
                            <label for="medecin">Médecin</label>
                            <select name="medecin" id="medecin">';
                            if(!$resquery = mysqli_query($link,$requeteMedecins)){
                                die("Error : ".mysqli_errno($resquery)." : ".mysqli_error($resquery));
                            } else {
                                while($row = mysqli_fetch_array($resquery, MYSQL_ASSOC)){
                                    if($row['ID_medecin'] == $medecin){
                                        echo'<option value='.$row['ID_medecin'].' selected="selected">'.$row['Civilite_M'].". ".$row['Nom_M']." ".$row['Prenom_M'].'</option>';
                                    } else {
                                        echo'<option value='.$row['ID_medecin'].'>'.$row['Civilite_M'].". ".$row['Nom_M']." ".$row['Prenom_M'].'</option>';
                                    }
                                }
                            }
                            echo'</select><br/>

                            <!-- Sélecteur avec les usagers -->
                            <label for="usager">Usager</label>
                            <select name="usager" id="usager">';
                            if(!$resquery = mysqli_query($link,$requeteUsagers)){
                                die("Error : ".mysqli_errno($resquery)." : ".mysqli_error($resquery));
                            } else {
                                while($row = mysqli_fetch_array($resquery, MYSQL_ASSOC)){
                                    if($row['N_securite_sociale'] == $usager){
                                        echo'<option value='.$row['N_securite_sociale'].' selected="selected">'.$row['Civilite'].". ".$row['Nom']." ".$row['Prenom'].'</option>';
                                    } else {
                                        echo'<option value='.$row['N_securite_sociale'].'>'.$row['Civilite'].". ".$row['Nom']." ".$row['Prenom'].'</option>';
                                    }
                                }
                            }
                            echo'</select><br/>

							<label for="date">Date</label><input type="date" name="date" id="date" placeholder="AAAA-MM-JJ" value="'.$date.'"/><br/>

                            <label for="heure">Heure</label><input type="time" name="heure" id="heure" placeholder="HH:MM:SS" value="'.$heure.'"/><br/>

                            <label for="duree">Durée</label><input type="time" name="duree" id="duree" placeholder="HH:MM:SS" value="'.$duree.'"/><br/>

							<input type="submit" value="Valider" name="Valider" class="petit"/>

						</form>

					</fieldset></div>';
				}
			}

            // On inclut le footer
            include("includes/footer.php");

		?>

	</body>

</html>
