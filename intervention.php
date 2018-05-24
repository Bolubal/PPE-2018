<?php
// Page pour valider les visites a un technicien.
session_start();

include 'style.php';
include 'fonction.php';

if (isset($_SESSION['matricule'])) { // Verifie si la personne est connectée.
    if ($_SESSION['role'] != "assistant") { // Verifie si la personne est un technicien.
        if (!isset($_POST['ok'])){ // Page principal qui affiche le formulaire.
            $numero = $_POST['numero'];
            echo '<h1>Intranet - ' . $_SESSION['prenom'] . ' ' . $_SESSION['nom'] . '</h1>
            <fieldset>
			<h2>Valider visite</h2>
            <form method="post" action="">
            <p>
            <label>Durée:</label>
            <input type="number" min="0" step="any" name="duree" required>
            </p>

            <p>
            <label>Commentaire:</label>
            <input type="text" name="commentaire" required>
            </p>

            <p>
            <label>Numero materiel:</label>
            <select name="materiel">';
            
            $sql = requete("SELECT * FROM materiel WHERE 1");
            
            if ($sql != false and mysqli_num_rows($sql) > 0) { // Si il y a des materiaux.
                while ($row = $sql->fetch_assoc()) {
                    echo '<option value="' . $row['numSerie'] . '">' . $row['numSerie'] . '</option>';
                }
            }

            echo"</select>
                 </p>
                 <p>
                 <input type='submit' value='Valider' name='ok'>
                 <input type='hidden' value='$numero' name='numero'>
                </form>";
                echo'
                <button type="button" onclick="window.location.href=\'accueil.php\'">Retour</button>
                </fieldset>';
            
        } else { // Une fois le formulaire lance.
            echo '<h1>Intranet - ' . $_SESSION['prenom'] . ' ' . $_SESSION['nom'] . '</h1>
            <fieldset>
            <h2>Valider visite</h2>';
            
            $duree = $_POST['duree'];
            $commentaire = $_POST['commentaire'];
            $numero = $_POST['numero'];
            $materiel = $_POST['materiel'];
            
            $sql = requete("INSERT INTO controler VALUES ('$duree', '$commentaire', '$materiel', '$numero')");
            if ($sql != false) {
                echo ' <p>Validation réussie.</p>';
            } else {
                echo ' <p>Une erreur est survenue.</p>';
            }
            
            echo '
			<button type="button" onclick="window.location.href=\'accueil.php\'">Accueil</button>
            </fieldset>';
        }
    } else { // Si la personne est un technicien.
        echo '<p class="white">Vous n\'étes pas un technicien !</p><button type="button" onclick="window.location.href=\'accueil.php\'">Retour</button>';
    }
} else { // La personne n'est pas connectée.
    echo '<p class="white">Accés interdit ! Veuillez vous connecter pour accéder à ce contenu !</p><button type="button" onclick="window.location.href=\'index.php\'">Retour</button>';
}
?>