<!DOCTYPE html>

<html lang="fr">

    <head>
        <meta charset="utf-8" />
        <title>Interdiction</title>
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
                    <li><a  href="statistiques.php" title="Voir les statistiques">Statistiques</a></li>
                </ul>
            </nav>

        </header>

        <br/>

        <div class="formulaire">
            <fieldset>
                <legend>Erreur</legend>
                    Permission non accordée.
                <form action="usagers.php" method="post">
                    <input type="submit" name="Retour" value="Retour" class="petit"/>
                </form>
            </fieldset>
        </div>

<?php include("includes/footer.php"); ?>
