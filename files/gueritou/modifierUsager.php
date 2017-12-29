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

            // Si le formulaire bien remplie
            if(!empty($_POST["civilite"]) and
            !empty($_POST["nom"]) and
            !empty($_POST["adresse"]) and
            !empty($_POST["cp"]) and
            !empty($_POST["ville"]) and
            !empty($_POST["date"]) and
            !empty($_POST["lieu"]) and
            !empty($_POST["medecin"])){

                header('Location: interdit.php');

            } else {

                // Ecriture des requêtes

                // Requête pour récupérer les informations de l'usager concerné
                $requeteUsager = 'SELECT U.N_securite_sociale, U.Civilite, U.Nom, U.Prenom, U.Adresse, U.Code_Postal, U.Ville, U.Date_de_naissance, U.Lieu_naissance,
    		    			   M.ID_medecin, M.Civilite_M, M.Nom_M, M.Prenom_M
    						FROM Usager AS U, Medecin AS M
    						WHERE U.ID_medecin = M.ID_medecin AND '.$_GET['n'].' = U.N_securite_sociale';

                // Requête pour récupérer la liste des médecins au cas où l'utilisateur souhaite modifier le médecin référent de l'usager traité
                $requeteMedecins = 'SELECT *
    						FROM Medecin
                            GROUP BY Nom_M';

    			// Execution de la requête sur le serveur
    			if(!$resquery = mysqli_query($link,$requeteUsager)){
    				die("Error : ".mysqli_errno($resquery)." : ".mysqli_error($resquery));
    		    } else {

                    while($row = mysqli_fetch_array($resquery, MYSQL_ASSOC)){

                        // Affichage d'un formulaire
                        echo '<!DOCTYPE html>

                        <html lang="fr">

                            <head>
                                <meta charset="utf-8" />
                                <title>Modifier un usager</title>
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
                                            <li><a href="consultations.php" title="Gérer les consultations">Consultations</a></li>
                                            <li id="courant"><a href="usagers.php" title="Gérer les usagers">Usagers</a></li>
                                            <li><a href="medecins.php" title="Gérer les médecins">Médecins</a></li>
                                            <li><a  href="statistiques.php" title="Voir les statistiques">Statistiques</a></li>
                                        </ul>
                                    </nav>

                                </header><div class="formulaire">

                            <!-- Lien pour retourner à la liste des usagers si l\'utilisateur change d\'avis -->
                            <p><img src="images/fleche.png"><a href="usagers.php" title="Retourner à la liste des usagers">Retourner à la liste des usagers</a></p><br/>

                            <fieldset>

                                <legend>Modifier le profil d\'un usager</legend>';

                                // Message d'erreur si il n'a pas rempli tout les champs
                                if(isset($_POST['Valider']) AND $_POST['Valider']=='Valider'){
                                    echo '<p class="erreur">Veuillez remplir tout les champs !</p><br/>';
                                }

                                echo '<form action="modifierUsager.php?n='.$row['N_securite_sociale'].'" method="post">

    								<label for="civilite">Civilité</label>
    								<input type="radio" name="civilite" value="M" id="M" class="petit" ';

                                    // Pour que la valeur définie pour l'usager en cours soit cochée par défaut
                                    if($row['Civilite']=='M'){
                                        echo 'checked="checked"';
                                    }
                                    echo '/> M
    								<input type="radio" name="civilite" value="Mme" id="Mme" class="petit" ';
                                    if($row['Civilite']=='Mme'){
                                        echo 'checked="checked"';
                                    }
                                    echo '/> Mme <br/><br/>

    								<label for="nom">Nom</label><input type="text" name="nom" id="nom" value="'.$row['Nom'].'"/><br/>

    								<label for="prenom">Prénom</label><input type="text" name="prenom" id="prenom" value="'.$row['Prenom'].'"/><br/>

    								<label for="adresse">Adresse</label>
    						   		<textarea name="adresse" id="adresse">'.$row['Adresse'].'</textarea><br/>

    						   		<label for="cp">Code postal</label><input type="text" name="cp" id="cp" value="'.$row['Code_Postal'].'"/><br/>

    						   		<label for="ville">Ville</label><input type="text" name="ville" value="'.$row['Ville'].'"/><br/>

    						   		<label for="date">Date de naissance</label><input type="date" name="date" id="date" placeholder="AAAA-MM-JJ" value="'.$row['Date_de_naissance'].'"/><br/>

    						   		<label for="lieu">Lieu de naissance</label><input type="text" name="lieu" id="lieu" value="'.$row['Lieu_naissance'].'"/><br/>

                                    <!-- Sélecteur avec les médecins -->
                                    <label for="medecin">Médecin référent</label>
    						   		<select name="medecin" id="medecin">';
                                    $tampon = $row['ID_medecin'];
                                    if(!$resquery = mysqli_query($link,$requeteMedecins)){
                        				die("Error : ".mysqli_errno($resquery)." : ".mysqli_error($resquery));
                        		    } else {
        					   			while($row = mysqli_fetch_array($resquery, MYSQL_ASSOC)){
                                            if($row['ID_medecin'] == $tampon){
                                                echo'<option value='.$row['ID_medecin'].' selected="selected">'.$row['Civilite_M'].". ".$row['Nom_M']." ".$row['Prenom_M'].'</option>';
                                            } else {
                                                echo'<option value='.$row['ID_medecin'].'>'.$row['Civilite_M'].". ".$row['Nom_M']." ".$row['Prenom_M'].'</option>';
                                            }
        					       		}
                                    }
    						       	echo'</select><br/>

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
