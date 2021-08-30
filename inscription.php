<?php

/********************************************************************************
*										*
*			ALCASAR INSCRIPTION					*
*										*
*	By K@M3L 1101130512.1901090409 & T3RRY @ La Plateforme_			*
*	V 1.0 June 2021.							*
*										*
*	Partie front de la page d'inscription des utilisateurs			*
*	elle envoi les infos à traiter à la page de traitement			*
*	via AJAX.								*
*										*
/*******************************************************************************/

$l_title = "Inscription";

//require_once "navbar.php";
require_once "header.php";
?>
	
	<div class="col-xs-12 col-md-10 col-md-offset-1"> 
		<!-- HeaderBox -->
		<div class="row banner">
			<!-- Logo box -->
			<div class="hidden-xs col-sm-3 col-md-2 col-lg-2"> 
				<img class="img-responsive img-A" src="images/organisme.png">
			</div>
			<!-- Title -->
			<div id="cadre_titre" class="titre_banner col-xs-12 col-sm-8">
				<div class="row">
					<p id="acces_controle" class="titre_controle"><?= $l_title ?></p>
				</div>
<!--				<div class="row">
				<?php if (isset($changePasswordMessage)): ?>
					<?= $changePasswordMessage ?>
				<?php endif; ?>
				</div>-->
			</div>
			<!-- Logo box -->
			<div class="img_banner hidden-xs col-sm-3 col-md-2 col-lg-2">
				<img class="img-responsive img-organisme" src="images/logo-alcasar_70.png">
			</div>
		</div>

		<section id="inscription" class="row">
			<form name="master" id="contenu_acces" onsubmit="return false;" class="col-xs-12 col-sm-12 col-md-offset-1 col-md-10">
				<div class="row input_row">
					<div class="label_name col-xs-3 col-sm-3 col-md-4">courrier électronique: *</div>
					<div class="col-xs-6 col-sm-6 col-md-4">
					<input type="email" placeholder="User@exemple.com" id="email" name="email" required class="form-control"/>
					</div>
					<small id="output_email" class="row"></small>
				</div>
				<div id="status">
					* Remplir tous les champs obligatoires
				</div>
				<div class="row input_row">
					<div class="col-xs-5 col-xs-offset-1 col-sm-4 col-sm-offset-2">
						<input id="buttons" class="btn btn-default" value="Annuler" onclick="window.location.href = 'index.php';">
					</div>
					<div class="col-xs-5 col-sm-4">
						<button type="submit" class="btn btn-primary btn-lg" id="buttons" >Inscription</button>
					</div>
				</div>
			</form>
		</section>
			

		<script type="text/javascript" src="js/jquery.min.js"></script>
		<script>
			$(document).ready(function(){
				$("#contenu_acces input").focus(function(){
					$("#status").fadeOut(800);
				});

				//Traitement du formulaire d'inscription
				$("#contenu_acces").submit(function(){
					var status = $("#status");
					var Fmail = $("#email").val();

					$.ajax({
						type: "post",
						url:  "inscription_traitement.php",
						data: {
							'Fmail' : Fmail,
						},
						beforeSend: function(){
							$("#status").attr("value", "Traitement en cours...");
						},
						success: function(data){
							if (data.match("success")) {
								status.html(data).fadeIn(400);
								alert("Encore une dernière étape!\n\nUn mot de passe temporaire vient de vous être envoyé à votre adresse électronique.\n Veuillez changer votre mot de passe.\n\n(Pensez à vérifier vos spams ou courriers indésirables, si vous ne voyez pas ce mail dans votre boîte de réception).\n\nSi vous ne recevez pas le mail dans les 24 heures, utilisez le formulaire de réinitialisation, ou contactez votre administrateur.")
								 document.location.href="index.php";
							} else {
							console.log(data);
								alert("Une erreur s'est produite.\n\nVeuillez renouveler votre inscription, utilisez le formulaire de réinitialisation, ou contactez votre administrateur.");
								status.css("color", "red").html(data).fadeIn(400);
							}
						}
					});
				});
			});
		</script>
	</div>
</body>
</html>
