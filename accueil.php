<?php
// Page d'accueil du site.
session_start();
include 'style.php';
include 'fonction.php';

if ((isset($_POST['login']) and isset($_POST['password'])) or isset($_SESSION['matricule'])) { // Si il y a un pseudo et un mot de passe.
    if (! isset($_SESSION['matricule'])) { // Si la personne viens de se connecter.
        $login = trim($_POST['login']);
        $password = trim(md5($_POST['password']));
        
        if ($login != "" and $password != "") { // Si les 2 valeurs sont remplies.
            
            $valeur = requete("SELECT * FROM salarie WHERE login = '$login' AND mdp = '$password'");
            if ($valeur != false) {
                $row = $valeur->fetch_assoc();
                if (mysqli_num_rows($valeur) > 0) {
                    $matricule = $row['matricule'];
                    $nom = $row['nom'];
                    $prenom = $row['prenom'];
                    $_SESSION['matricule'] = $matricule;
                    $_SESSION['nom'] = $nom;
                    $_SESSION['prenom'] = $prenom;
                    $exectest = requete("SELECT * FROM technicien WHERE matricule = '$matricule'");
                    if ($exectest != false) {
                        $row2 = $exectest->fetch_assoc();
                        if (mysqli_num_rows($exectest) > 0) { // Technicien
                            $_SESSION['role'] = "technicien";
                            technicien();
                        } else { // Assistant
                            $_SESSION['role'] = "assistant";
                            assistant();
                        }
                    }
                    
                } else {
                    header("Location: index.php");
                }
            }
        } else { // Si le pseudo et le mot de passe ne sont pas saisies.
            echo '<p class="white">Accés interdit ! Veuillez vous connecter pour accéder à ce contenu !</p><button type="button" onclick="window.location.href=\'index.php\'">Retour</button>';
        }
    } else { // Si la personne été deja login.
        if($_SESSION['role'] == "assistant"){
            assistant();
        }else{
            technicien();
        }
        
    }
} else { // Si le pseudo et le mot de passe ne sont pas saisies.
    echo '<p class="white">Accés interdit ! Veuillez vous connecter pour accéder à ce contenu !</p><button type="button" onclick="window.location.href=\'index.php\'">Retour</button>';
}

?>