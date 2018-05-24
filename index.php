<?php
// Page de login.

session_start();

if(isset($_SESSION['matricule'])){
    session_destroy();
}

echo '<!DOCTYPE html>
<html lang="fr" dir="ltr">

<head>
	<meta charset="UTF-8"/>
	<title>PPE</title>
	<link rel="shortcut icon" href="image/favicon.ico">
    <link rel="stylesheet" href="css/style.css" />

</head>
	<body>
			<h1>Intranet - Connexion</h1>
    
			<fieldset>
			<h2>Identification</h1>
			
			<form method="post" action="accueil.php">

			<p><label>Login:</label>
			<input type="text" placeholder ="Login" name="login" id="login" title="Entrer votre login" autocomplete="off" required/>
			</p>
			
			<p>
			<label>Mot de passe:</label>
			<input type="password" placeholder ="Mot de passe" name="password" id="password" title="Entrer votre mot de passe" autocomplete="off" required/>
			</p>
			
			<p>
			<input id="valider" name="valider"  value = "Valider" type="submit"/>
			</p>
    
		</form>
    
	</fieldset>
	</body>
</html>';

?>

