<!DOCTYPE html>

<html lang="fr">

    <head>
        <meta charset="utf-8" />
        <title>Médecins</title>
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

        </header>

		<?php

			// Connexion au serveur MySQL
            $server = "db609499257.db.1and1.com";
			$login = "dbo609499257";
			$mdp = "cactus42";
			$db = "db609499257";
			$link = mysqli_connect($server, $login, $mdp, $db) or die("Error ".mysqli_error($link));

			// Verification de la connexion
			if(mysqli_connect_errno()){
				echo 'Connect failed : \n'.mysqli_connect_error();
				exit();
			}

            if(isset($_GET["recherche"])){

                // Ecriture de la requête en cas d'utilisation de la barre de recherche
    		    $requete = "SELECT *
    						FROM Medecin
    						WHERE Nom_M LIKE \"%".$_GET['recherche']."%\" OR Prenom_M LIKE \"%".$_GET['recherche']."%\"
    						ORDER BY Nom_M";

            } else {

    			// Ecriture de la requête
    		    $requete = "SELECT *
    						FROM Medecin
    						ORDER BY Nom_M";

            }

			// Execution de la requête sur le serveur
			if(!$resquery = mysqli_query($link,$requete)){
				die("Error : ".mysqli_errno($resquery)." : ".mysqli_error($resquery));
		    } else {

				echo'<div>

                        <h2>Liste des médecins</h2><br/>

                        <a href="ajouterMedecin.php" title="Ajouter un médecin" class="bouton"><img src="images/plus.png">Ajouter un médecin</a>

                        <form action="medecins.php" method="get">
                            <p><img src="images/loupe.png"><input type="text" name="recherche" id="recherche" placeholder="Rechercher un médecin" ';
                            // Pour afficher dans la barre de recherche ce que l'utilisateur a recherché
                            if(isset($_GET["recherche"])){
                                echo 'value="'.$_GET["recherche"].'"';
                            }
                            echo '/></p>
                        </form>

                    </div>

                    <table class="liste">';

				//Traitement de la requête
                $compteur = 0; // Sert à compter le nombre de médecins
                $modulo = 0; // Sert à savoir si on en est à un nombre pair ou impair de médecins, afin de choisir une couleur différente une fois sur deux
				while($row = mysqli_fetch_array($resquery, MYSQL_ASSOC)){
                    $compteur = $compteur + 1;
                    $modulo = $compteur % 2;

                    // On affiche une "colonne" de tableau pour chaque médecin de la base de données
                    echo'<tr class="couleur'.$modulo.'">

                            <td>
                                <strong>'.$row['Civilite_M'].'. '.$row['Nom_M'].' '.$row['Prenom_M'].'</strong>
                            </td>

                            <td class="droite">
                                <a href="ajouterConsultation.php?medecin='.$row['ID_medecin'].'" class="icone"><img src="images/calendrier.png" alt="Consultation"/>Prendre un rendez-vous</a><br/>
                                <a href="modifierMedecin.php?n='.$row['ID_medecin'].'" class="icone"><img src="images/crayon.png" alt="Modifier"/>Modifier un médecin</a><br/>
                                <a href="supprimerMedecin.php?n='.$row['ID_medecin'].'" class="icone"><img src="images/croix.png" alt="Supprimer"/>Supprimer un médecin</a>
                            </td>

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
