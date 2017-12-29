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
            !empty($_POST["prenom"]) and
            !empty($_POST["adresse"]) and
            !empty($_POST["cp"]) and
            !empty($_POST["ville"]) and
            !empty($_POST["date"]) and
            !empty($_POST["lieu"]) and
            !empty($_POST["secu"]) and
            !empty($_POST["medecin"])){

                header('Location: interdit.php');

			} else {

				// Ecriture de la requête pour choisir un médecin pour le nouvel usager
				$requete = "SELECT *
							FROM Medecin
							ORDER BY Nom_M";

				// Execution de la requête sur le serveur
				if(!$resquery = mysqli_query($link,$requete)){
					die("Error : ".mysqli_errno($resquery)." : ".mysqli_error($resquery));
				} else {

                    // Affichage d'un formulaire
					echo'<!DOCTYPE html>

                    <html lang="fr">

                        <head>
                            <meta charset="utf-8" />
                            <title>Ajouter un usager</title>
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

                            <legend>Ajouter un usager</legend>';

                            // Message d'erreur si il n'a pas rempli tout les champs
                            if(isset($_POST['Valider']) AND $_POST['Valider']=='Valider'){
                                echo '<p class="erreur">Veuillez remplir tout les champs !</p><br/>';
                            }

    						echo '<form action="ajouterUsager.php" method="post">

    							<label for="secu">N° de sécurité sociale</label><input type="text" name="secu" id="secu"/><br/>

    							<label for="civilite">Civilité</label>
    							<input type="radio" name="civilite" value="M" id="M" class="petit"/> M
    							<input type="radio" name="civilite" value="Mme" id="Mme" class="petit"/> Mme <br/><br/>

    							<label for="nom">Nom</label><input type="text" name="nom" id="nom"/><br/>

    							<label for="prenom">Prénom</label><input type="text" name="prenom" id="prenom"/><br/>

    							<label for="adresse">Adresse</label>
    					   		<textarea name="adresse" id="adresse"></textarea><br/>

    					   		<label for="cp">Code postal</label><input type="text" name="cp" id="cp"/><br/>

    					   		<label for="ville">Ville</label><input type="text" name="ville"/><br/>

    					   		<label for="date">Date de naissance</label><input type="date" name="date" id="date" placeholder="AAAA-MM-JJ"/><br/>

    					   		<label for="lieu">Lieu de naissance</label><input type="text" name="lieu" id="lieu"/><br/>

                                <!-- Sélecteur avec les médecins -->
    					   		<label for="medecin">Médecin référent</label>
    					   		<select name="medecin" id="medecin">';
    				   			while($row = mysqli_fetch_array($resquery, MYSQL_ASSOC)){
                                    echo'<option value='.$row['ID_medecin'].'>'.$row['Civilite_M'].". ".$row['Nom_M']." ".$row['Prenom_M'].'</option>';
    				       		}
    					       	echo'</select><br/>

    							<input type="submit" name="Valider" value="Valider" class="petit"/>

    						</form>

    					</fieldset>

                    </div>';
				}
			}

            // On inclut le footer
            include("includes/footer.php");

		?>

	</body>

</html>
