<?php
include 'fonction.php';
if (isset($_POST['client'])) {
    $client = $_POST['client'];
    
    $sql = requete("SELECT * FROM client WHERE numero=$client");
    if ($sql != false and mysqli_num_rows($sql) > 0) {
        $row = $sql->fetch_assoc();
        $agence = $row['numAgence'];
        $requete = requete("SELECT * FROM technicien WHERE numAgence='$agence'");
        if ($requete != false and mysqli_num_rows($requete) > 0) {
            echo '<label>Technicien:</label> <select name="technicien">';
            
            while ($valeur = $requete->fetch_assoc()) {
                echo '<option value="' . $valeur['matricule'] . '">' . $valeur['matricule'] . '</option>';
            }
        } else {
            echo 'erreur';
        }
        echo '</select>';
    }
}

else if(isset($_POST['technicien'])){
    $technicien = $_POST['technicien'];
    
    $sql = requete("SELECT sum(tempsPasse) as temps, count('numero') as nombre FROM controler C, intervention I WHERE C.numero=I.numero AND I.technicien='$technicien'");
    if ($sql != false and mysqli_num_rows($sql) > 0) {
        $row = $sql->fetch_assoc();
            echo '<table>
                    <tr>
                        <th>Nombre d\'interventions</th>
                        <th>Temps total</th>
                    </tr>

                    <tr>
                        <td>'.$row['nombre'].'</td>
                        <td>'.$row['temps'].'</td>
                </table>';
    }else{
        echo 'intervention';
    }
}

?>