<?php
// Page pour affecter les visites a un technicien.
session_start();

include 'style.php';
include 'fonction.php';

if (isset($_SESSION['matricule'])) { // Verifie si la personne est connectée.
    if ($_SESSION['role'] == "assistant") { // Verifie si la personne est un assistant.

        echo '<script src="js/scripts.js"></script> 
            <h1>Intranet - ' . $_SESSION['prenom'] . ' ' . $_SESSION['nom'] . '</h1>
            <fieldset>
			<h2>Statistiques technicien</h2>';
        
        $requete = requete("SELECT * FROM technicien WHERE 1");
        if ($requete != false and mysqli_num_rows($requete) > 0) { // Si il y a des techniciens.
            echo 'Selectionnez un technicien: <select name="technicien" id="technicien" onChange="infoTech()">';
            
            while ($row = $requete->fetch_assoc()) {
                echo '<option value="'.$row["matricule"].'">'.$row["matricule"].'</option>';
            }
            
            echo '</select>
            
            <div id="info"></div>
            </p><button type="button" onclick="window.location.href=\'accueil.php\'">Retour</button>
            ';
            
            
        }
    } else { // Si la personne est un technicien.
        echo '<p class="white">Vous n\'étes pas un assistant !</p><button type="button" onclick="window.location.href=\'accueil.php\'">Retour</button>';
    }
} else { // La personne n'est pas connectée.
    echo '<p class="white">Accés interdit ! Veuillez vous connecter pour accéder à ce contenu !</p><button type="button" onclick="window.location.href=\'index.php\'">Retour</button>';
}
?>