<?php
// Page pour affecter les visites a un technicien.
session_start();

include 'style.php';
include 'fonction.php';

if (isset($_SESSION['matricule'])) { // Verifie si la personne est connectée.
    if ($_SESSION['role'] == "assistant") { // Verifie si la personne est un assistant.
        $valeur = requete("SELECT * FROM intervention WHERE 1");
        if ($valeur != false and mysqli_num_rows($valeur) > 0) { // Si il y a des interventions.
            echo '<h1>Intranet - ' . $_SESSION['prenom'] . ' ' . $_SESSION['nom'] . '</h1>
            <fieldset>
			<h2>Génerer PDF</h2>
			<table border = 1 class="table table-hover">
			<tr>
                <th>Client</th>
				<th>Technicien</th>
				<th>Date</th>
				<th>Heure</th>
				<th>PDF</th>
            
			</tr>';
            
            while ($row = $valeur->fetch_assoc()) {
                $client = $row['numeroClient'];
                $sql = requete("SELECT * FROM client WHERE numero=$client");
                if ($sql != false) {
                    $reponse = $sql->fetch_assoc();
                    
                    $dateInt = explode('-', $row['dateInt']);
                    $date = $dateInt[2].'/'.$dateInt[1].'/'.$dateInt[0];
                    
                    $heureExplode = explode(':', $row['heure']);
                    $heure = $heureExplode[0].':'.$heureExplode[1];
                    
                    $technicien = $row['technicien'];
                    $nomClient = $reponse['raisonSocial'];
                    echo "<form method='post' action='pdf.php' >
				<tr class='center'>
                    <td>" . $nomClient . "</td>
				    <td>" . $technicien . "</td>
					<td>" . $date . "</td>
					<td>" . $heure . "</td>
				<td><input id='selectionner' name='selectionner' value = 'PDF' type='submit'/></td>
				</tr>
                <input type='hidden' value='$technicien' name='technicien'>
                <input type='hidden' value='$client' name='client'>
                <input type='hidden' value='$date' name='date'>
                <input type='hidden' value='$heure' name='heure'>
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
            <h2>Génerer PDF</h2>
                <p>Il n\'y a aucune intervention pour le moment.</p>
                <button class="retour" type="button" onclick="window.location.href=\'affecter.php\'">Affecter</button>
                <button class="retour" type="button" onclick="window.location.href=\'accueil.php\'">Retour</button>
            </fieldset>';
        }
    } else { // Si la personne est un technicien.
        echo '<p class="white">Vous n\'étes pas un assistant !</p><button type="button" onclick="window.location.href=\'accueil.php\'">Retour</button>';
    }
} else { // La personne n'est pas connectée.
    echo '<p class="white">Accés interdit ! Veuillez vous connecter pour accéder à ce contenu !</p><button type="button" onclick="window.location.href=\'index.php\'">Retour</button>';
}
?>