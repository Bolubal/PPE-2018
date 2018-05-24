<?php 

include 'fonction.php';
// Infos technicien
$technicien = $_POST['technicien'];

$valeur = requete("SELECT * FROM salarie WHERE matricule='$technicien'");
if ($valeur != false) {
    $row = $valeur->fetch_assoc();
    if (mysqli_num_rows($valeur) > 0) {
        $nom = $row['nom'];
        $prenom = $row['prenom'];
        $adresse = $row['adresse'];
    }
}

// Infos technicien
$client = $_POST['client'];

$sql = requete("SELECT * FROM client WHERE numero='$client'");
if ($sql != false) {
    $row2 = $sql->fetch_assoc();
    if (mysqli_num_rows($sql) > 0) {
        $raisonSocial = $row2['raisonSocial'];
        $email = $row2['mail'];
        $adressePostale = $row2['adressePostale'];
        $tel = 0 . $row2['numTel'];
        $numAgence = $row2['numAgence'];
        $requete = requete("SELECT * FROM agence WHERE numAgence='$numAgence'");
        if ($requete != false) {
            $row3 = $requete->fetch_assoc();
            if (mysqli_num_rows($requete) > 0) {
                $nomAgence = $row3['nom'];
            }
        }
    }
}

$date = $_POST['date'];
$heure = $_POST['heure'];

require('fpdf/fpdf.php');

$pdf = new FPDF();
$pdf->AddPage('P', 'A4');
$pdf->SetAutoPageBreak(false);
$pdf->SetFont('Arial', 'B', 18);
$pdf->Image('fpdf\image\intervention.jpg', 0, 0, 230, 220, 'jpg');

// Nom agence
$pdf->SetXY(19, 45);
$pdf->SetFont('Arial', '', 14);
$pdf->Cell(62, 1, $nomAgence);

// Nom technicien
$pdf->SetXY(18, 70);
$pdf->SetFont('Arial', '', 14);
$pdf->Cell(62, 1, $nom);

// Prenom technicien
$pdf->SetXY(25, 76);
$pdf->SetFont('Arial', '', 14);
$pdf->Cell(62, 1, $prenom);

// Adresse technicien
$pdf->SetXY(25, 82);
$pdf->SetFont('Arial', '', 14);
$pdf->Cell(62, 1, $adresse);

// Nom client
$pdf->SetXY(18, 106);
$pdf->SetFont('Arial', '', 14);
$pdf->Cell(62, 1, $raisonSocial);

// Email client
$pdf->SetXY(19, 111);
$pdf->SetFont('Arial', '', 14);
$pdf->Cell(62, 1, $email);

// Telephone client
$pdf->SetXY(30, 118);
$pdf->SetFont('Arial', '', 14);
$pdf->Cell(62, 1, $tel);

// Adresse client
$pdf->SetXY(24, 123);
$pdf->SetFont('Arial', '', 14);
$pdf->Cell(62, 1, $adressePostale);

// Date
$pdf->SetXY(16, 149.5);
$pdf->SetFont('Arial', '', 14);
$pdf->Cell(62, 1, $date);

// Heure
$pdf->SetXY(20, 156);
$pdf->SetFont('Arial', '', 14);
$pdf->Cell(62, 1, $heure);

$pdf->Output();
?>