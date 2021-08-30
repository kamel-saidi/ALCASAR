<?php

echo <<< EOT

<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>ALCASAR - $l_title</title>
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/pass.css">
	<link rel="icon" href="images/favicon-48.ico" type="image/ico">
	<script type="text/javascript" src="js/jquery.min.js"></script>
	<script type="text/javascript" src="js/bootstrap.min.js"></script>
</head>

<body>

<nav class="navbar navbar navbar-inverse" style="margin-bottom:0px;">
	<div class="container-fluid">
		<div class="navbar-header">
			<a href="index.php"><img src="images/logo-alcasar_70.png" width="50" ><font color=red><b> ALCASAR Accueil</b></font></a>
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
		</div>
		<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
			<ul class="nav navbar-nav">
				<li>
					<a href="index.php?url=www.euronews.com">Login</a>
				</li>
				<li>
					<a href="inscription.php">Inscription</a>
				</li>
				<li>
					<a href="password.php">Changement de mot de passe</a>
				</li>
				<li>
					<a href="reset.php">Réinitialisation de mot de passe</a>
				</li>
			</ul>
			<ul class="nav navbar-nav navbar-right">
				<a href="contact.php" class="navbar-brand">Contact</a>
				<a href="contact.php"><img src="images/organisme.png" width="40"></a>
			</ul>
		</div>
	</div>
</nav>
EOT;
?>
