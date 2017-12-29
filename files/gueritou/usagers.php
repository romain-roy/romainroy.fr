<!DOCTYPE html>

<html lang="fr">

    <head>
        <meta charset="utf-8" />
        <title>Usagers</title>
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

        </header>

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

            if(isset($_GET["recherche"])){

                // Ecriture de la requête en cas d'utilisation de la barre de recherche
    		    $requete = "SELECT U.N_securite_sociale, U.Civilite, U.Nom, U.Prenom, U.Adresse, U.Code_Postal, U.Ville,
    		    				DATE_FORMAT(U.Date_de_naissance, '%d/%m/%Y') AS Date_de_naissance, U.Lieu_naissance,
    		    				M.Civilite_M, M.Nom_M, M.Prenom_M, U.ID_medecin
    						FROM Usager AS U, Medecin AS M
    						WHERE U.ID_medecin = M.ID_medecin
                                AND (U.Nom LIKE \"%".$_GET['recherche']."%\" OR U.Prenom LIKE \"%".$_GET['recherche']."%\")
    						ORDER BY U.Nom";

            } else {

    			// Ecriture de la requête
    		    $requete = "SELECT U.N_securite_sociale, U.Civilite, U.Nom, U.Prenom, U.Adresse, U.Code_Postal, U.Ville,
    		    				DATE_FORMAT(U.Date_de_naissance, '%d/%m/%Y') AS Date_de_naissance, U.Lieu_naissance,
    		    				M.Civilite_M, M.Nom_M, M.Prenom_M, U.ID_medecin
    						FROM Usager AS U, Medecin AS M
    						WHERE U.ID_medecin = M.ID_medecin
    						ORDER BY U.Nom";

            }

			// Execution de la requête sur le serveur
			if(!$resquery = mysqli_query($link,$requete)){
				die("Error : ".mysqli_errno($resquery)." : ".mysqli_error($resquery));
		    } else {

				echo '<div>

                        <h2>Liste des usagers</h2><br/>

                        <a href="ajouterUsager.php" title="Ajouter un usager" class="bouton"><img src="images/plus.png">Ajouter un usager</a>

                        <form action="usagers.php" method="get">
                            <p><img src="images/loupe.png"><input type="text" name="recherche" id="recherche" placeholder="Rechercher un usager" ';
                            // Pour afficher dans la barre de recherche ce que l'utilisateur a recherché
                            if(isset($_GET["recherche"])){
                                echo 'value="'.$_GET["recherche"].'"';
                            }
                            echo '/></p>
                        </form>

                    </div>

                    <table class="liste">';

				// Traitement de la requête
                $compteur = 0; // Sert à compter le nombre d'usagers
                $modulo = 0; // Sert à savoir si on en est à un nombre pair ou impair d'usagers, afin de choisir une couleur différente une fois sur deux
				while($row = mysqli_fetch_array($resquery, MYSQL_ASSOC)){
                    $compteur = $compteur + 1;
                    $modulo = $compteur % 2;

                    // On affiche une "colonne" de tableau pour chaque usager de la base de données
                    echo '<tr class="couleur'.$modulo.'">

                            <td>
                                <strong>'.$row['Civilite'].'. '.$row['Nom'].' '.$row['Prenom'].'</strong><br/>'.
                                $row['Adresse'].'<br/>'.
                                $row['Code_Postal'].' '.$row['Ville'].'
                            </td>

                            <td>
                                Né';
                                // Pour afficher un e à Né si c'est une fille
                                if($row['Civilite'] == 'Mme'){
                                    echo 'e';
                                }
                                echo ' le '.$row['Date_de_naissance'].' à '.$row['Lieu_naissance'].'<br/>
                                N° de sécurité sociale : '.$row['N_securite_sociale'].'<br/>
                                Médecin référent : '.$row['Civilite_M'].'. '.$row['Nom_M'].' '.$row['Prenom_M'].'
                            </td>

                            <td class="droite">
                                <a href="ajouterConsultation.php?usager='.$row['N_securite_sociale'].'&medecin='.$row['ID_medecin'].'" class="icone"><img src="images/calendrier.png" alt="Consultation"/>Prendre un rendez-vous</a><br/>
                                <a href="modifierUsager.php?n='.$row['N_securite_sociale'].'" class="icone"><img src="images/crayon.png" alt="Modifier"/>Modifier un usager</a><br/>
                                <a href="supprimerUsager.php?n='.$row['N_securite_sociale'].'" class="icone"><img src="images/croix.png" alt="Supprimer"/>Supprimer un usager</a>
                            </td>

                        </tr>';

				}


                // Il n'y a plus d'usagers, on ferme la table
                echo '</table>';

			}

            // On inclut le footer
            include("includes/footer.php");

		?>

	</body>

</html>
