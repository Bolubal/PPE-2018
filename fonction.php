<?php

// Page des fonctions.
function requete($requete) // Requete SQL.
{
    $link = mysqli_connect("localhost", "root", "root", "ppe");
    
    if ($link === false) {
        
        die("ERROR: Could not connect. " . mysqli_connect_error());
    }
    
    $sql = $requete;
    $valeur = mysqli_query($link, $sql);

    mysqli_close($link);   
    return $valeur;
}

function assistant()
{
    echo '<h1>Intranet - ' . $_SESSION['prenom'] . ' ' . $_SESSION['nom'] . '</h1>
            <fieldset>
                <h2>Accueil</h2>
                <p><a href="recherche.php">• Rechercher une fiche client</a></p>
                <p><a href="affecter.php">• Affecter visites à un technicien</a></p>
                <p><a href="generer.php">• Générer une fiche d\'intervention</a></p>
                <p><a href="consulter.php">• Consulter les interventions</a></p>
                <p><a href="statistique.php">• Statistiques techniciens</a></p>
                <p><a href="index.php">• Déconnexion</a></p>
            </fieldset>';
}

function technicien()
{
    echo '<h1>Intranet - ' . $_SESSION['prenom'] . ' ' . $_SESSION['nom'] . '</h1>
            <fieldset>
                <h2>Liste des interventions</h2>
                <table border = 1 class="table table-hover">
                <tr class="center">
                    <th>Client</td>
				    <th>Date</td>
					<th>Heure</td>
                    <th>Distance</td>
					<th>Duree</td>
				    <th>Valider</td>
				</tr>';
                
                $matricule = $_SESSION['matricule'];
                
                $valeur = requete("SELECT * FROM controler WHERE 1");
                if ($valeur != false) {
                    $row3 = $valeur->fetch_assoc();
                    if (mysqli_num_rows($valeur) > 0) {
                        $intervention = $row3['numero'];
                    }else{
                        $intervention = null;
                    }
                }
                
                $sql = requete("SELECT * FROM intervention WHERE technicien='$matricule' AND numero !='$intervention'");
                if ($sql != false and mysqli_num_rows($sql) > 0) { // Si il y a des interventions.   
                    
                    while ($row = $sql->fetch_assoc()) {
                        $date = $row['dateInt'];
                        $heure = $row['heure'];
                        $iencli = $row['numeroClient'];
                        $numero = $row['numero'];
                        $requete = requete("SELECT * FROM client WHERE numero = $iencli");
                        if ($requete != false) {
                            $row2 = $requete->fetch_assoc();
                            if (mysqli_num_rows($requete) > 0) {
                                $distance = $row2['distance'];
                                $duree = $row2['dureeDeplacement'];
                                $nom = $row2['raisonSocial'];
                            }
                        }
                        echo "<form method='post' action='intervention.php'>
                                <tr class='center'>
                                <td>$nom</td>
                                <td>$date</td>
                                <td>$heure</td>
                                <td>$distance</td>
                                <td>$duree</td>
                                <td><input id='valider' name='valider' value = 'Valider' type='submit'/></td>

                
                            <input id='numero' name='numero' value = '" . $numero . "' type='hidden'/>
                            </form>
                            </tr>";
                    }
                    
                }
            echo'
            </table>
            <p><a href="index.php">• Déconnexion</a></p>
            </fieldset>';
}

function modifier($table, $set1, $set2, $set3, $set4, $set5, $set6, $set7, $set8, $set9, $set10, $where) // Modifie des valeurs.
{
    $link = mysqli_connect("localhost", "root", "root", "ppe");
    
    if ($link === false) {
        
        die("ERROR: Could not connect. " . mysqli_connect_error());
    }
    
    $sql = "UPDATE $table SET $set1, $set2, $set3, $set4, $set5, $set6, $set7, $set8, $set9, $set10 WHERE $where";
    
    
    mysqli_query($link, $sql);
    
    mysqli_close($link);
}

function listeContrat($contrat = null)
{
    $link = mysqli_connect("localhost", "root", "root", "ppe");
    
    if ($link === false) {
        
        die("ERROR: Could not connect. " . mysqli_connect_error());
    }
    
    $sql = "SELECT * FROM contratmaintenance";
    
    $valeur = mysqli_query($link, $sql);
    
    if ($valeur != false) {
        $liste = '<select name="contrat">';
        while ($row = $valeur->fetch_assoc()) {
            $selected = null;
            if ($row['numContrat'] == $contrat) {
                $selected = "selected";
            }
            $liste = $liste . '<option value="'.$row['numContrat'].'" '.$selected.'>'.$row['numContrat'].'</option>';
        }
        $liste = $liste . '</select>';
    }
    
    mysqli_close($link);
    return $liste;
}

function listeAgence($agence = null)
{
    $link = mysqli_connect("localhost", "root", "root", "ppe");
    
    if ($link === false) {
        
        die("ERROR: Could not connect. " . mysqli_connect_error());
    }
    
    $sql = "SELECT * FROM agence";
    
    $valeur = mysqli_query($link, $sql);
    
    if ($valeur != false) {
        $liste = '<select name="agence">';
        while ($row = $valeur->fetch_assoc()) {
            $selected = null;
            if ($row['numAgence'] == $agence) {
                $selected = "selected";
            }
            $liste = $liste . '<option value="'.$row['numAgence'].'" '.$selected.'>'.$row['nom'].'</option>';
        }
        $liste = $liste . '</select>';
    }
    
    mysqli_close($link);
    return $liste;
}
?>