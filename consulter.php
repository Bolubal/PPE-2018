<?php
// Page pour affecter les visites a un technicien.
session_start();

include 'style.php';
include 'fonction.php';

if (isset($_SESSION['matricule'])) { // Verifie si la personne est connectée.
    if ($_SESSION['role'] == "assistant") { // Verifie si la personne est un assistant.
        
        if (! isset($_POST['client']) and ! isset($_POST['modifier'])) {
            
            $valeur = requete("SELECT * FROM intervention WHERE 1");
            if ($valeur != false and mysqli_num_rows($valeur) > 0) { // Si il y a des interventions.
                echo '<h1>Intranet - ' . $_SESSION['prenom'] . ' ' . $_SESSION['nom'] . '</h1>
            <fieldset>
			<h2>Consulter les interventions</h2>
			<table border = 1 class="table table-hover">
			<tr>
                <th>Client</th>
				<th>Technicien</th>
				<th>Date</th>
				<th>Heure</th>
				<th>Modifier</th>
            
			</tr>';
                
                while ($row = $valeur->fetch_assoc()) {
                    $client = $row['numeroClient'];
                    $sql = requete("SELECT * FROM client WHERE numero=$client");
                    if ($sql != false) {
                        $reponse = $sql->fetch_assoc();
                        
                        $dateInt = explode('-', $row['dateInt']);
                        $date = $dateInt[2] . '/' . $dateInt[1] . '/' . $dateInt[0];
                        
                        $heureExplode = explode(':', $row['heure']);
                        $heure = $heureExplode[0] . ':' . $heureExplode[1];
                        
                        $numeroIntervention = $row['numero'];
                        $nomClient = $reponse['raisonSocial'];
                        echo "<form method='post' action=''>
				<tr class='center'>
                    <td>" . $nomClient . "</td>
				    <td>" . $row['technicien'] . "</td>
					<td>" . $date . "</td>
					<td>" . $heure . "</td>
				    <td><input id='modif' name='modif' value = 'Modifier' type='submit'/></td>
				</tr>
                <input id='client' name='client' value = '$nomClient' type='hidden'/>
                <input id='numeroIntervention' name='numeroIntervention' value='$numeroIntervention' type='hidden'>
                <input id='technicien' name='technicien' value = '" . $row['technicien'] . "' type='hidden'/>
                <input id='date' name='date' value = '" . $date . "' type='hidden'/>
                <input id='heure' name='heure' value = '" . $heure . "' type='hidden'/>
				</form>";
                    }
                }
                
                echo '</table>
            <button class="retour" type="button" onclick="window.location.href=\'accueil.php\'">Retour</button>
            </fieldset>';
            } else { // Si il n'y a pas d'intervention
                echo '
            <h1>Intranet - ' . $_SESSION['prenom'] . ' ' . $_SESSION['nom'] . '</h1>
            <fieldset>
            <h2>Consulter les interventions</h2>
                <p>Il n\'y a aucune intervention pour le moment.</p>
                <button class="retour" type="button" onclick="window.location.href=\'affecter.php\'">Affecter</button>
                <button class="retour" type="button" onclick="window.location.href=\'accueil.php\'">Retour</button>
            </fieldset>';
            }
        } else if (isset($_POST['client']) and ! isset($_POST['modifier'])) {
            $numeroIntervention = $_POST['numeroIntervention'];
            $client = $_POST['client'];
            $technicien = $_POST['technicien'];
            $date = $_POST['date'];
            $heure = $_POST['heure'];
            $explode = explode('/', $date);
            $dateAnglais = $explode[2] . '-' . $explode['1'] . '-' . $explode[0];
            
            echo ' <script src="js/scripts.js"></script> 
                    <h1>Intranet - ' . $_SESSION['prenom'] . ' ' . $_SESSION['nom'] . '</h1>
                    <fieldset>
                    <h2>Consulter les interventions</h2>
                    <form method="post" action="">
                    <p>
                        <label>Client:</label>
                        <select name="client" id="client" onchange="techAgence()">';
            
            $sql = requete("SELECT * FROM client WHERE 1");
            if ($sql != false and mysqli_num_rows($sql) > 0) { // Si il y a des client.
                while ($row = $sql->fetch_assoc()) {
                    echo '<option value="' . $row['numero'] . '">' . $row['raisonSocial'] . '</option>';
                }
            } else {
                echo 'erreur';
            }
            echo '
                    </select>
                    </p>
                    
                    <div id="techniciens">
                    <label>Technicien:</label>
                    <select name="technicien">';
            
            $requete = requete("SELECT * FROM client WHERE raisonSocial='$client'");
            if ($requete != false and mysqli_num_rows($requete) > 0) {
                $valeur = $requete->fetch_assoc();
                $requete2 = requete("SELECT * FROM technicien WHERE numAgence='" . $valeur['numAgence'] . "'");
                if ($requete2 != false and mysqli_num_rows($requete2) > 0) {
                    while ($result = $requete2->fetch_assoc()) {
                        echo '<option id="' . $result['matricule'] . '">' . $result['matricule'] . '</option>';
                    }
                } else {
                    echo 'erreur';
                }
            } else {
                echo 'erreur';
            }
            echo '
                    </select>
                    </div>
                    <p>
                        <label>Heure d\'intervention:</label>
                        <input type="time" name="heure" id="heure" autocomplete="off" value="' . $heure . '"/>
                    </p>
                    <p>
                        <label>Date d\'intervention:</label>
                        <input type="date" name="date" id="date" autocomplete="off" value="' . $dateAnglais . '"/>
                    </p>
            <input type="hidden" value=' . $numeroIntervention . ' name="numeroIntervention">
            <input type="submit" value="Modifier" id="modifier" name="modifier"/>
            <button class="retour" type="button" onclick="window.location.href=\'consulter.php\'">Retour</button>
            </fieldset>';
        } else {
            echo '<h1>Intranet - ' . $_SESSION['prenom'] . ' ' . $_SESSION['nom'] . '</h1>
                    <fieldset>
                    <h2>Consulter les interventions</h2>';
            
            $Intervention = $_POST['numeroIntervention'];
            $nouveauTechnicien = $_POST['technicien'];
            $nouveauClient = $_POST['client'];
            $nouvelleDate = $_POST['date'];
            $nouvelleHeure = $_POST['heure'];
            
            $sql = requete("UPDATE intervention SET dateInt='$nouvelleDate', heure='$nouvelleHeure', technicien='$nouveauTechnicien', numeroClient='$nouveauClient' WHERE numero='$Intervention'");
            if ($sql != false) {
                echo ' <p>Modification(s) réussie.</p>';
            } else {
                echo ' <p>Une erreur est survenue.</p>';
            }
            
            echo '
                    <button class="retour" type="button" onclick="window.location.href=\'consulter.php\'">Retour</button>
            
                    </fieldset>';
        }
    } else { // Si la personne est un technicien.
        echo '<p class="white">Vous n\'étes pas un assistant !</p><button type="button" onclick="window.location.href=\'accueil.php\'">Retour</button>';
    }
} else { // La personne n'est pas connectée.
    echo '<p class="white">Accés interdit ! Veuillez vous connecter pour accéder à ce contenu !</p><button type="button" onclick="window.location.href=\'index.php\'">Retour</button>';
}
?>