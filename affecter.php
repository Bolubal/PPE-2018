<?php
// Page pour affecter les visites a un technicien.
session_start();

include 'style.php';
include 'fonction.php';

if (isset($_SESSION['matricule'])) { // Verifie si la personne est connectée.
    if ($_SESSION['role'] == "assistant") { // Verifie si la personne est un assistant.
        if (! isset($_POST['agence']) and ! isset($_POST['client']) and ! isset($_POST['c'])) { // Page principal qui affiche les agents.
            echo '<h1>Intranet - ' . $_SESSION['prenom'] . ' ' . $_SESSION['nom'] . '</h1> 
            <fieldset>
			<h2>Affecter visite à un technicien</h2>
			<table border = 1 class="table table-hover">
			<tr>
				<th>Nom</th>
				<th>Prenom</th>
				<th>Adresse</th>
				<th>Date Embauche</th>
				<th>Qualification</th>
				<th>Téléphone</th>
				<th>Mail</th>
				<th>Date diplome</th>
				<th>Agence</th>
				<th>Affecter</th>
            
			</tr>';
            
            $valeur = requete("SELECT * FROM technicien WHERE 1");
            if ($valeur != false) { // Affiche tous les agents.
                
                while ($row = $valeur->fetch_assoc()) {
                    $matricule = $row['matricule'];
                    $affecter = requete("SELECT salarie.*
										FROM salarie,technicien
										WHERE technicien.matricule = '$matricule'
										AND technicien.matricule = salarie.matricule ");
                    
                    $donnees = $affecter->fetch_assoc();
                    
                    echo "<form method='post' action='' >
				<tr>
					<td>" . $donnees['nom'] . "</td>
					<td>" . $donnees['prenom'] . "</td>
				    <td>" . $donnees['adresse'] . "</td>
				    <td>" . $donnees['dateEmbauche'] . "</td>
				    <td>" . $row['qualifiacation'] . "</td>
				    <td>" . $row['tel'] . "</td>
				    <td>" . $row['mail'] . "</td>
				    <td>" . $row['dateObtention'] . "</td>
				    <td>" . $row['numAgence'] . "</td>
				    <td><input id='selectionner' name='selectionner' value = 'Affecter' type='submit'/></td>
				</tr>
                <input type='hidden' id='agence' name='agence' value=" . $row['numAgence'] . ">
                <input type='hidden' id='technicien' name='technicien' value=" . $row['matricule'] . ">
				</form>";
                }
            }
            
            echo '</table>
            <button class="retour" type="button" onclick="window.location.href=\'accueil.php\'">Retour</button>
            </fieldset>';
        } else if (isset($_POST['agence'])) { // Page secondaire qui affiche les clients.
            $valeur = requete("SELECT * FROM client WHERE numAgence= '" . $_POST['agence'] . "'");
            if ($valeur != false and mysqli_num_rows($valeur) > 0) { // Affiche tous les clients dans la meme agence.
                
                echo '<h1>Intranet - ' . $_SESSION['prenom'] . ' ' . $_SESSION['nom'] . '</h1>
            <fieldset>
			<h2>Affecter visite à un technicien</h2>';
                
                echo '
			<table border = 1 class="table table-hover">
			<tr>
				<th>Numéro</th>
				<th>Raison Social</th>
				<th>Siren</th>
				<th>Code APE</th>
				<th>Adresse</th>
				<th>Téléphone</th>
				<th>Fax</th>
				<th>Email</th>
				<th>Distance</th>
				<th>Durée déplacement</th>
                <th>Agence</th>
                <th>Affecter</th>
                
			</tr>';
                
                while ($row = $valeur->fetch_assoc()) {
                    $sql = requete("SELECT * FROM intervention WHERE numeroClient = " . $row['numero'] . "");
                    if ($sql != false and mysqli_num_rows($sql) == 0) { // Si il le client n'a pas encore de contrat.
                        echo "<form method='post' action='' >
				<tr>
					<td>" . $row['numero'] . "</td>
					<td>" . $row['raisonSocial'] . "</td>
				    <td>" . $row['numeroSiren'] . "</td>
				    <td>" . $row['codeAPE'] . "</td>
				    <td>" . $row['adressePostale'] . "</td>
				    <td>" . $row['numTel'] . "</td>
                    <td>" . $row['numFax'] . "</td>
				    <td>" . $row['mail'] . "</td>
				    <td>" . $row['distance'] . "</td>
				    <td>" . $row['dureeDeplacement'] . "</td>
                    <td>" . $row['numAgence'] . "</td>
				    <td><input id='selectionner' name='selectionner' value = 'Affecter' type='submit'/></td>
				</tr>
                <input type='hidden' id='client' name='client' value=" . $row['numero'] . ">
                <input type='hidden' id='technicien' name='technicien' value=" . $_POST['technicien'] . ">
				</form>";
                    }
                }
                
                echo '</table>
            <button class="retour" type="button" onclick="window.location.href=\'affecter.php\'">Retour</button>
            </fieldset>';
            } else {
                echo '<p class="white">Il n\'y a aucun client dans cette agence.</p><button type="button" onclick="window.location.href=\'affecter.php\'">Retour</button>';
            }
        } else if (isset($_POST['client'])) { // Troisiéme page qui permet de choisir la date de visite.
            echo '<h1>Intranet - ' . $_SESSION['prenom'] . ' ' . $_SESSION['nom'] . '</h1>
            <fieldset>
			<h2>Affecter visite à un technicien</h2>
            <form method="post" action="affecter.php">
            <p>            
                <label>Jour de l\'intervention:</label>
                <input type="date" id="date" name="date" placeholder="jj/mm/aaaa" required>
            </p>
            <p>            
                <label>Heure de l\'intervention:</label>
                <input type="time" id="heure" name="heure" placeholder="--:--" required>
            </p>

            <input type="hidden" id="c" name="c" value="' . $_POST['client'] . '">
            <input type="hidden" id="t" name="t" value="' . $_POST['technicien'] . '">
            <input id="selectionner" name="selectionner" value = "Affecter" type="submit"/>
            <button class="retour" type="button" onclick="window.location.href=\'affecter.php\'">Retour</button>
            </form>
            </fieldset>';
        } else if (isset($_POST['c'])) { // Derniére étape de confirmation.
            $client = trim($_POST['c']);
            $technicien = $_POST['t'];
            $date = $_POST['date'];
            $heure = $_POST['heure'];
            
            echo '<h1>Intranet - ' . $_SESSION['prenom'] . ' ' . $_SESSION['nom'] . '</h1>
            <fieldset>
			<h2>Affecter visite à un technicien</h2>';
            $valeur = requete("INSERT INTO intervention VALUES (NULL,'$date','$heure','$technicien',$client)");
            if ($valeur != false) { // Si la requete c'est effectuée.
                echo '
            <p>L\'intervention a bien été affectée.</p>
            <form method="post" action="pdf.php">
            <input type="hidden" value="'.$technicien.'" name="technicien">
            <input type="hidden" value="'.$client.'" name="client">
            <input type="hidden" value="'.$date.'" name="date">
            <input type="hidden" value="'.$heure.'" name="heure">
            <input type="submit" value="PDF">
            </form>
            <button class="retour" type="button" onclick="window.location.href=\'accueil.php\'">Retour</button>';
            } else {
                '<p>Une erreur est survenue.<p>
                 <button class="retour" type="button" onclick="window.location.href=\'affecter.php\'">Retour</button>';
            }
            echo '
            </fieldset>';
        } else { // En cas d'erreur.
            echo '<p class="white">Une erreur est survenue.</p><button type="button" onclick="window.location.href=\'affecter.php\'">Retour</button>';
        }
    } else { // Si la personne est un technicien.
        echo '<p class="white">Vous n\'étes pas un assistant !</p><button type="button" onclick="window.location.href=\'accueil.php\'">Retour</button>';
    }
} else { // La personne n'est pas connectée.
    echo '<p class="white">Accés interdit ! Veuillez vous connecter pour accéder à ce contenu !</p><button type="button" onclick="window.location.href=\'index.php\'">Retour</button>';
}
?>