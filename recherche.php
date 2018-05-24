<?php
// Page de recherche d'une fiche client.
session_start();
include 'style.php';
include 'fonction.php';

if (isset($_SESSION['matricule'])) { // Verifie si la personne est connectée.
    if ($_SESSION['role'] == "assistant") { // Verifie si la personne est un assistant.
        if (!isset($_POST['id']) AND !isset($_POST['numero'])) { // Verifie si le formulaire n'a pas été envoyé.
            echo '<h1>Intranet - ' . $_SESSION['prenom'] . ' ' . $_SESSION['nom'] . '</h1>
            <fieldset>
            <h2>Rechercher une fiche client</h2>
			<form method="post" action="" >
                <p>
                <label>Numéro client:</label>
                <input type="text" placeholder ="Numéro client" name="numero" id="numero" title="Entrer le numéro du client" autocomplete="off" required/>
                </p>
				<input id="valider" name="valider"  value = "Valider" type="submit"/>
				<button type="button" onclick="window.location.href=\'accueil.php\'">Retour</button>
			</form>
			</fieldset>';
        } else if(!isset($_POST['id'])){ // Le formulaire a été envoyé.
            $id = $_POST['numero'];
            
            $valeur = requete("SELECT * FROM client WHERE numero=$id");
            if ($valeur != false) {
                $row = $valeur->fetch_assoc();
                if (mysqli_num_rows($valeur) > 0) {
                    
                    echo '<h1>Intranet - ' . $_SESSION['prenom'] . ' ' . $_SESSION['nom'] . '</h1>
                    <fieldset>
                    <h2>Modifier une fiche client</h2>
                    <form method="post" action="">
                    <p>
                        <label>Numéro client:</label>
                        <input type="text" name="id" id="id" readonly value="'.$id.'"/>
                    </p>
                    <p>
                        <label>Raison social:</label>
                        <input type="text" name="social" id="social" autocomplete="off" value="'.$row['raisonSocial'].'"/>
                    </p>
                    <p>
                        <label>Siren:</label>
                        <input type="text" name="siren" id="siren" autocomplete="off" value="'.$row['numeroSiren'].'"/>
                    </p>
                    <p>
                        <label>Code APE:</label>
                        <input type="text" name="ape" id="ape" autocomplete="off" value="'.$row['codeAPE'].'"/>
                    </p>
                    <p>
                        <label>Adresse:</label>
                        <input type="text" name="adresse" id="adresse" autocomplete="off" value="'.$row['adressePostale'].'"/>
                    </p>
                    <p>
                        <label>Téléphone:</label>
                        <input type="text" name="telephone" id="telephone" autocomplete="off" value="'.$row['numTel'].'"/>
                    </p>
                    <p>
                        <label>Fax:</label>
                        <input type="text" name="fax" id="fax" autocomplete="off" value="'.$row['numFax'].'"/>
                    </p>
                    <p>
                        <label>Email:</label>
                        <input type="text" name="mail" id="mail" autocomplete="off" value="'.$row['mail'].'"/>
                    </p>
                    <p>
                        <label>Distance:</label>
                        <input type="text" name="distance" id="distance" autocomplete="off" value="'.$row['distance'].'"/>
                        <label>km(s)</label>
                    </p>
                    <p>
                        <label>Durée déplacement:</label>
                        <input type="text" name="deplacement" id="deplacement" autocomplete="off" value="'.$row['dureeDeplacement'].'"/>
                        <label>heure(s)</label>
                    </p>
                    <p>
                        <label>Agence:</label>';
                        echo listeAgence($row['numAgence']);
                    echo'
                    </p>
            <input type="submit" value="Modifier" id="modifier" name="modifier"/>
            <button class="retour" type="button" onclick="window.location.href=\'recherche.php\'">Retour</button>
            </fieldset>';
                }else{ // Si le client n'a pas été trouvé.
                    echo '<p class="white">Aucun client ne correspond a ce numero.</p><button type="button" onclick="window.location.href=\'recherche.php\'">Retour</button>';
                }
            }else{ // Si le client n'a pas été trouvé.
                echo '<p class="white">Aucun client ne correspond a ce numero.</p><button type="button" onclick="window.location.href=\'recherche.php\'">Retour</button>';
            }
        }else{ // Si des modifications ont été faite.
            
            if(strstr($_POST['distance'], ',')){ // Remplace les , par des points.
                $_POST['distance'] = str_replace(",",".",$_POST['distance']);
            }
            
            if(strstr($_POST['deplacement'], ',')){ // Remplace les , par des points.
                $_POST['deplacement']= str_replace(",",".",$_POST['deplacement']);
            }
            
            if(strstr($_POST['adresse'], '\'')){ // Remplace les , par des espaces.
                $_POST['adresse']= str_replace('\''," ",$_POST['adresse']);
            }
            
            modifier("client","raisonSocial='".trim($_POST['social'])."'","numeroSiren='".trim($_POST['siren'])."'","codeAPE='".trim($_POST['ape'])."'",
                    "adressePostale='".trim($_POST['adresse'])."'","numTel='".trim($_POST['telephone'])."'","numFax='".trim($_POST['fax'])."'",
                    "mail='".trim($_POST['mail'])."'","distance='".trim($_POST['distance'])."'","dureeDeplacement='".trim($_POST['deplacement'])."'",
                    "numAgence='".trim($_POST['agence'])."'" ,"numero='".trim($_POST['id'])."'");
            
            echo '<h1>Intranet - ' . $_SESSION['prenom'] . ' ' . $_SESSION['nom'] . '</h1>
                    <fieldset>
                    <h2>Modifier une fiche client</h2>
                    <form method="post" action="">
                    <p>
                        <label>Numéro client:</label>
                        <input type="text" name="id" id="id" readonly value="'.trim($_POST['id']).'"/>
                    </p>
                    <p>
                        <label>Raison social:</label>
                        <input type="text" name="social" id="social" autocomplete="off" value="'.trim($_POST['social']).'"/>
                    </p>
                    <p>
                        <label>Siren:</label>
                        <input type="text" name="siren" id="siren" autocomplete="off" value="'.trim($_POST['siren']).'"/>
                    </p>
                    <p>
                        <label>Code APE:</label>
                        <input type="text" name="ape" id="ape" autocomplete="off" value="'.trim($_POST['ape']).'"/>
                    </p>
                    <p>
                        <label>Adresse:</label>
                        <input type="text" name="adresse" id="adresse" autocomplete="off" value="'.trim($_POST['adresse']).'"/>
                    </p>
                    <p>
                        <label>Téléphone:</label>
                        <input type="text" name="telephone" id="telephone" autocomplete="off" value="'.trim($_POST['telephone']).'"/>
                    </p>
                    <p>
                        <label>Fax:</label>
                        <input type="text" name="fax" id="fax" autocomplete="off" value="'.trim($_POST['fax']).'"/>
                    </p>
                    <p>
                        <label>Email:</label>
                        <input type="text" name="mail" id="mail" autocomplete="off" value="'.trim($_POST['mail']).'"/>
                    </p>
                    <p>
                        <label>Distance:</label>
                        <input type="text" name="distance" id="distance" autocomplete="off" value="'.trim($_POST['distance']).'"/>
                        <label>km(s)</label>
                    </p>
                    <p>
                        <label>Durée déplacement:</label>
                        <input type="text" name="deplacement" id="deplacement" autocomplete="off" value="'.trim($_POST['deplacement']).'"/>
                        <label>heure(s)</label>
                    </p>
                    <p>
                        <label>Agence:</label>';
                    echo listeAgence($_POST['agence']);
                    echo'
                    </p>
            <input type="submit" value="Modifier" id="modifier" name="modifier"/>
            <button class="retour" type="button" onclick="window.location.href=\'recherche.php\'">Retour</button>
            <p>Modification(s) réussie.</p>
			</fieldset>';
        }
    } else { // Si la personne est un technicien.
        echo '<p class="white">Vous n\'étes pas un assistant !</p><button type="button" onclick="window.location.href=\'accueil.php\'">Retour</button>';
    }
} else { // La personne n'est pas connectée.
    echo '<p class="white">Accés interdit ! Veuillez vous connecter pour accéder à ce contenu !</p><button type="button" onclick="window.location.href=\'index.php\'">Retour</button>';
}
?>