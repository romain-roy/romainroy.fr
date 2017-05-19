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

            // Si le formulaire bien remplie
			if(!empty($_POST["civilite"]) and !empty($_POST["nom"]) and !empty($_POST["prenom"])){

				header('Location: interdit.php');

			} else {

				//Ecriture de la requête
				$requete = "SELECT *
							FROM Medecin
                            ORDER BY ID_medecin";

				//Execution de la requête sur le serveur
				if(!$resquery = mysqli_query($link,$requete)){
					die("Error : ".mysqli_errno($resquery)." : ".mysqli_error($resquery));
				} else {

                    // Cette partie sert à donner au nouveau médecin un ID pas déjà pris et le plus bas possible
                    $compteur = 1;
                    $tampon = 0;
                    while($row = mysqli_fetch_array($resquery, MYSQL_ASSOC)){
                        if($row["ID_medecin"] > $tampon+1){
                            $compteur = $tampon+1;
                            break;
                        }
                        $tampon = $row["ID_medecin"];
                        $compteur = $compteur + 1;
                    }

                    // Affichage d'un formulaire
					echo'<!DOCTYPE html>

                    <html lang="fr">

                        <head>
                            <meta charset="utf-8" />
                            <title>Ajouter un médecin</title>
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
                                        <li><a href="usagers.php" title="Gérer les usagers">Usagers</a></li>
                                        <li id="courant"><a href="medecins.php" title="Gérer les médecins">Médecins</a></li>
                                        <li><a  href="statistiques.php" title="Voir les statistiques">Statistiques</a></li>
                                    </ul>
                                </nav>

                            </header><div class="formulaire">

                    <!-- Lien pour retourner à la liste des médecins si l\'utilisateur change d\'avis -->
                    <p><img src="images/fleche.png"><a href="medecins.php" title="Retourner à la liste des médecins">Retourner à la liste des médecins</a></p><br/>

                    <fieldset>

                        <legend>Ajouter un médecin</legend>';

                        // Message d'erreur si il n'a pas rempli tout les champs
                        if(isset($_POST['Valider']) AND $_POST['Valider']=='Valider'){
                            echo '<p class="erreur">Veuillez remplir tout les champs !</p><br/>';
                        }

					echo '<form action="ajouterMedecin.php?n='.$compteur.'" method="post">

							<label for="civilite">Civilité</label>
							<input type="radio" name="civilite" value="M" id="M" class="petit"/> M
							<input type="radio" name="civilite" value="Mme" id="Mme" class="petit"/> Mme <br/><br/>

							<label for="nom">Nom</label><input type="text" name="nom" id="nom"/><br/>

							<label for="prenom">Prénom</label><input type="text" name="prenom" id="prenom"/><br/>

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
