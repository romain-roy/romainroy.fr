<?php

			// Connexion au serveur MySQL
            $server = "db609499257.db.1and1.com";
			$login = "dbo609499257";
			$mdp = "O0a(O3o-";
			$db = "db609499257";
			$link = mysqli_connect($server, $login, $mdp, $db) or die("Error ".mysqli_error($link));

			// éerification de la connexion
			if(mysqli_connect_errno()){
				echo 'Connect failed : \n'.mysqli_connect_error();
				exit();
			}

            // Si le formulaire bien remplie
            if(!empty($_POST["civilite"]) and !empty($_POST["nom"]) and !empty($_POST["prenom"])){

                header('Location: interdit.php');

            } else {

                // Ecriture de la requête pour récupérer informations du médecin concerné
                $requete = 'SELECT *
    						FROM Medecin
    						WHERE '.$_GET['n'].' = ID_medecin';

    			// Execution de la requête sur le serveur
    			if(!$resquery = mysqli_query($link,$requete)){
    				die("Error : ".mysqli_errno($resquery)." : ".mysqli_error($resquery));
    		    } else {

                    while($row = mysqli_fetch_array($resquery, MYSQL_ASSOC)){

                        // Affichage d'un formulaire
                        echo '<!DOCTYPE html>

                        <html lang="fr">

                            <head>
                                <meta charset="utf-8" />
                                <title>Modifier un médecin</title>
                                <link rel="stylesheet" href="style.css"/>
                        		<link rel="icon" typz="image/x-icon" href="images/favicon.ico"/>
                            </head>

                        	<body>

                                <header>

                                    <!-- En-tête -->
                                    <h1>Cabinet Médical Guéritou</h1>

                                    <!-- Menu -->
                                    <nav>
                                        <ul>
                                            <li><a href="consultations.php" title="Gérer les consultations">Consultations</a></li>
                                            <li><a href="usagers.php" title="Gérer les usagers">Usagers</a></li>
                                            <li id="courant"><a href="medecins.php" title="Gérer les médecins">Médecins</a></li>
                                            <li><a  href="statistiques.php" title="Voir les statistiques">Statistiques</a></li>
                                        </ul>
                                    </nav>

                                </header><div class="formulaire">

                            <!-- Lien pour retourner à la liste des médecins si l\'utilisateur change d\'avis -->
                            <p><img src="images/fleche.png"><a href="medecins.php" title="Retourner à la liste des médecins">Retourner à la liste des médecins</a></p><br/>

                            <fieldset>

                                <legend>Modifier le profil d\'un médecin</legend>';

                                // Message d'erreur si il n'a pas rempli tout les champs
                                if(isset($_POST['Valider']) AND $_POST['Valider']=='Valider'){
                                    echo '<p class="erreur">Veuillez remplir tout les champs !</p><br/>';
                                }

                                echo '<form action="modifierMedecin.php?n='.$row['ID_medecin'].'" method="post">

    								<label for="civilite">Civilité</label>
    								<input type="radio" name="civilite" value="M" id="M" class="petit" ';

                                    // Pour que la valeur définie pour le médecin en cours soit cochée par défaut
                                    if($row['Civilite_M']=='M'){
                                        echo 'checked="checked"';
                                    }
                                    echo '/> M
    								<input type="radio" name="civilite" value="Mme" id="Mme" class="petit" ';
                                    if($row['Civilite_M']=='Mme'){
                                        echo 'checked="checked"';
                                    }
                                    echo '/> Mme <br/><br/>

    								<label for="nom">Nom</label><input type="text" name="nom" id="nom" value="'.$row['Nom_M'].'"/><br/>

    								<label for="prenom">Prénom</label><input type="text" name="prenom" id="prenom" value="'.$row['Prenom_M'].'"/><br/>

    								<input type="submit" value="Valider" name="Valider" class="petit"/>

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
