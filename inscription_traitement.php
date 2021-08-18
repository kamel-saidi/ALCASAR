<?php 

/********************************************************************************									*										*
*			ALCASAR INSCRIPTION					*
*										*
*	By K@M3L 1101130512.1901090409 & T3RRY LaPlateforme_.			*
*	V 1.0 June 2021.							*
*										*
*	Partie back de la page d'inscription des utilisateurs			*
*	elle traite les infos de la partie front de l'inscription		*
*	-Lit le fichier de configuration /usr/local/etc/alcasar-mail.conf.	*
*	-Verifie si le login est présent dans la radcheck.			*
*	-Verifie si le mail est présent dans la userinfo.			*
*	-Verifie si le domaine du mail est sur WLD ou BLD (optionnel).		*
*	-Inscrit l'utilisateur avec mot de passe aléatoir.			*
*	-Envoi l'email a l'utilisaeur, et à l'admin avec date et IP.		*
*										*
*********************************************************************************/

if (is_file("acc/manager/lib/langues.php"))
	include("acc/manager/lib/langues.php");

if(!isset($create)) $create=0;
if(!isset($show)) $show=0;
if(!isset($login)) $login = '';
if(!isset($cn)) $cn = '';
if(!isset($mail)) $mail = '';
if(!isset($langue_imp)) $langue_imp = '';
if(!isset($selected)) $selected = array();
if(!isset($selected['='])) $selected['='] = '';


require('/etc/freeradius-web/config.php');
require('acc/manager/lib/attrshow.php');
require('acc/manager/lib/defaults.php');

if (false && /* Hide operator column */ $config['general_lib_type'] == 'sql' && $config['sql_use_operators'] == 'true') {
	$colspan = 2;
	$show_ops = 1;
	require('acc/manager/lib/operators.php');
} else {
	$show_ops = 0;
	$colspan = 1;
}

if (is_file("acc/manager/lib/sql/drivers/$config[sql_type]/functions.php"))
	require("acc/manager/lib/sql/drivers/$config[sql_type]/functions.php");
else{
	echo "<b>Could not include SQL library</b><br>\n";
	exit();
}

require('acc/manager/lib/functions.php');
if ($config['sql_use_operators'] == 'true'){
	include_once("acc/manager/lib/operators.php");
	$text = ',op';
	$passwd_op = ",':='";
}

$da_abort=0;
$op_val2 = '';


function GenPassword($nb_car="8")
{
// Random password
	$password = "";
	$chaine  = "aAzZeErRtTyYuUIopP152346897mMLkK";
	$chaine .= "jJhHgGfFdDsSqQwWxXcCvVbBnN152346897";
	while($nb_car != 0) {
		//$i = rand(0,71);
		// Bug corrigé
		$i = rand(0,66);
		$password .= $chaine[$i];
		$nb_car--;
	}
	return $password;
}

// Lecture du fichier de configuration, récupération des listes WLD/BLD et l'email de l'admin
$alcasarMailConf = "/usr/local/etc/alcasar-mail.conf";
if (is_file ($alcasarMailConf)){
	$tab=file($alcasarMailConf);
	if ($tab){
		foreach ($tab as $line){

			$field=explode("=", $line);

			switch ($field[0]){
				case 'whiteDomain':
					$whiteDomain = explode(" ", strtolower(trim($field[1])));
				break;
				case 'blackDomain':
					$blackDomain = explode(" ", strtolower(trim($field[1])));
				break;
				case 'adminMail':
					$adminMail = $field[1];
				break;
			}
		}
	}
}

if(isset($_POST['Fmail'])){

	extract($_POST);

	$Fmail = htmlentities(strtolower(trim($Fmail)));
	
	if(!filter_var($Fmail, FILTER_VALIDATE_EMAIL)){  
		echo "<b>Adresse email invalide !</b><br>\n";
		exit();
	}
	
	//on récupere le nom de domain du mail@domain.com
	list($user, $domain) = explode('@', $Fmail);

	// on vérifi si le domaine est dans la WLD, sinon on bloque
	if (!empty($whiteDomain)){
		if (!in_array($domain, $whiteDomain)){
			echo "ce domaine $domain n'est pas autorisé pour les inscriptions white";
			exit();
		}
	}

	// on vérifi si le domaine est dans la BLD, si c'est le cas on bloque
	if (!empty($blackDomain)){
		if (in_array($domain, $blackDomain)){
			echo "Ce domaine $domain n'est pas autorisé pour les inscriptions black";
			exit();
		}
	}
	
	$login  = $Fmail;
	
	// si le login est présent
	$link = @da_sql_pconnect($config);
	if ($link) {
		$sql = "SELECT id FROM $config[sql_check_table] WHERE username = '$login';";
		$res = @da_sql_query($link,$config, $sql);
	}
	
	$login_check = 	da_sql_num_rows($res,$config);
	
//	da_sql_close($link,$config)
	
	// si le mail est présent
	$link = @da_sql_pconnect($config);
	if ($link) {
		$sql = "SELECT id FROM $config[sql_user_info_table] WHERE mail = '$Fmail';";
		$res = @da_sql_query($link,$config, $sql);
	}
	
	$email_check = 	da_sql_num_rows($res,$config);
	
//	da_sql_close($link,$config)


	if($login_check > 0) {
		echo "<b>L'adresse mail est déjà utilisé en tant que login.</b><br>\n";
	} else if($email_check > 0) {
		echo "<b>Cette adresse mail est déjà utilisée.</b><br>\n";
	} else {

		$password = GenPassword();
		
		// si on ajoute des inputs pour les infos user
/*		$Fcn = "$prenom".".$nom";
		$Fou = "";
		$Fhomephone = "";
		$Ftelephonenumber = "";
		$Fmobile = "";
*/

		$link = da_sql_pconnect($config);
		if ($link){
			mysqli_set_charset($link,"utf8");
			if (is_file("acc/manager/lib/crypt/$config[general_encryption_method].php")){
				include_once("acc/manager/lib/crypt/$config[general_encryption_method].php");

				$passwd = da_encrypt($password);
				$passwd = da_sql_escape_string($link, $passwd);
				$res = da_sql_query($link,$config,
				"INSERT INTO $config[sql_check_table] (attribute,value,username $text)
				VALUES ('$config[sql_password_attribute]','$passwd','$login' $passwd_op);");
				if (!$res || !da_sql_affected_rows($link,$res,$config)){
					echo "<b>Unable to add user $login: " . da_sql_error($link,$config) . "</b><br>\n";
					$da_abort=1;
				}

				if ($config['sql_use_user_info_table'] == 'true' && !$da_abort){
					$res = da_sql_query($link,$config,
					"SELECT username FROM $config[sql_user_info_table] WHERE
					username = '$login';");
					if ($res){
						if (!da_sql_num_rows($res,$config)){
							$Fcn = (isset($Fcn)) ? da_sql_escape_string($link, $Fcn) : '';
							$Fmail = (isset($Fmail)) ? da_sql_escape_string($link, $Fmail) : '';
							$Fou = (isset($Fou)) ? da_sql_escape_string($link, $Fou) : '';
							$Fhomephone = (isset($Fhomephone)) ? da_sql_escape_string($link, $Fhomephone) : '';
							$Ftelephonenumber = (isset($Ftelephonenumber)) ? da_sql_escape_string($link, $Ftelephonenumber) : '';
							$Fmobile = (isset($Fmobile)) ? da_sql_escape_string($link, $Fmobile) : '';
							$res = da_sql_query($link,$config,
							"INSERT INTO $config[sql_user_info_table]
							(username,name,mail,department,homephone,workphone,mobile) VALUES
							('$login','$Fcn','$Fmail','$Fou','$Fhomephone','$Ftelephonenumber','$Fmobile');");

							if (!$res || !da_sql_affected_rows($link,$res,$config))
								echo "<b>Could not add user information in user info table: " . da_sql_error($link,$config) . "</b><br>\n";
						}
						else
							echo "<b>Cet usager existe d&eacute;j&agrave; dans la table 'info'</b><br>\n";
					}
					else
						echo "<b>Could not add user information in user info table: " . da_sql_error($link,$config) . "</b><br>\n";
				}
				// si on veut ajouter les nouveau utilisateurs a un groupe par défaut, autre que celui par défaut d'alcasar
				if (isset($Fgroup) && $Fgroup != ''){
					$Fgroup = da_sql_escape_string($link, $Fgroup);
					$res = da_sql_query($link,$config,
					"SELECT username FROM $config[sql_usergroup_table]
					WHERE username = '$login' AND groupname = '$Fgroup';");
					if ($res){
						if (!da_sql_num_rows($res,$config)){
							$res = da_sql_query($link,$config,
							"INSERT INTO $config[sql_usergroup_table]
							(username,groupname) VALUES ('$login','$Fgroup');");
							if (!$res || !da_sql_affected_rows($link,$res,$config))
								echo "<b>Could not add user to group $Fgroup. SQL Error</b><br>\n";
						}
						else
							echo "<b>User already is a member of group $Fgroup</b><br>\n";
					}
					else
						echo "<b>Could not add user to group $Fgroup: " . da_sql_error($link,$config) . "</b><br>\n";
				}
				if (!$da_abort){
					if (isset($Fgroup) && $Fgroup != '')
						require('acc/manager/lib/defaults.php');
					foreach($show_attrs as $key => $attr){
						if ($attrmap["$key"] == 'none')
							continue;
						if ($key == "Filter-Id" && $$attrmap["$key"] == "None")
							continue;
						if ($attrmap["$key"] == ''){
							$attrmap["$key"] = $key;
							$attr_type["$key"] = 'replyItem';
							$rev_attrmap["$key"] = $key;
						}
						if (isset($attr_type["$key"]) && $attr_type["$key"] == 'checkItem'){
							$table = "$config[sql_check_table]";
							$type = 1;
						}
						else if (isset($attr_type["$key"]) && $attr_type["$key"] == 'replyItem'){
							$table = "$config[sql_reply_table]";
							$type = 2;
						}
						$val = (isset($_POST[$attrmap["$key"]])) ? $_POST[$attrmap["$key"]] : '';
						$val = da_sql_escape_string($link, $val);
						$op_name = $attrmap["$key"] . '_op';
						$op_val = (isset($$op_name)) ? $$op_name : '';
						if ($op_val != ''){
							$op_val = da_sql_escape_string($link, $op_val);
							if (check_operator($op_val,$type) == -1){
								echo "<b>Invalid operator ($op_val) for attribute $key</b><br>\n";
								continue;
							}
							$op_val2 = ",'$op_val'";
						}
						$chkdef = (isset($default_vals["$key"])) ? check_defaults($val,$op_val,$default_vals["$key"]) : 0;
						if ($val == '' || $chkdef)
							continue;
						$sqlquery = "INSERT INTO $table (attribute,value,username $text)
							VALUES ('$attrmap[$key]','$val','$login' $op_val2);";
						$res = da_sql_query($link,$config,$sqlquery);
						if (!$res || !da_sql_affected_rows($link,$res,$config))
							echo "<b>Query failed for attribute $key: " . da_sql_error($link,$config) . "</b><br>\n";
					}
				}
				
				
				// L'utilisateur est ajouter dans la radcheck, ses info dans la userinfo, on envoi le mail avec identifiant et passwd

				$ip = $_SERVER['REMOTE_ADDR'];
				$time = date_create('now')->format('d-m-Y H:i:s');
				$domain = $conf["DOMAIN"];
				$hostname  = $conf["HOSTNAME"].'.'.$domain;
				$hostname  = alcasar.laplateforme.io;
				
				$to = $Fmail;
				$from = "administrateur@$domain";
				$subject = "Activation de votre compte ALCASAR";
				$message = "<!DOCTYPE html>
						<html>
							<head>	
								<meta charset=\"UTF-8\" />
							</head>
							<body>
								Bonjour,<br/><br/>

								<h3>Vous vous êtes inscrit à ALCASR $domain, <strong>ALCASAR</strong>!</h3>
								<p>Ceci est un mail automatique avec vos identifiants, veuillez changer votre mot de passe.<br/>

								<h4>Indentifiants de connexion:</h4>  
								<pre>								
									Adresse e-mail : $Fmail 
									Login :		$login
									Mot de passe :   $password
								</pre> 
								<p>Rendez-vous sur le portail <a href=\"https://$hostname\">$domain</a></p>
							</body>
						</html>";

				$header = "From: $from\n";
				$header .= "MIME-Version: 1.0\n";
				$header .= "Content-type: text/html; charset=utf-8\n";

				if(mail($to, $subject, $message, $header)){
					echo "<center><b>success : $l_user '$login' $l_created</b></center><br>";
					echo "<center><b>success : Email avec identifiants envoyé.</b></center><br>";

					
					// le mail pour l'uitilisateur est envoyé, si l'admin a configuré son mail, on lui envoi
					// une notification d'inscription avec l'ip, l'heure, et le login de l'utilisateur
					if (!empty($adminMail)){
						$to = $adminMail;
						$from = "administrateur@$domain";
						$subject = "Nouvelle inscription sur ALCASAR";
						$message = "<!DOCTYPE html>
							<html>
								<head>	
									<meta charset=\"UTF-8\" />
								</head>
								<body>
									Bonjour,<br/><br/>

									<h3>Nouvelle inscription à <strong>ALCASR $domain</strong>!</h3>
									<p>Ceci est un mail automatique.<br/>

									<h4>Indentifiants de connexion:</h4>  
									<pre>								
										Adresse IP :	$ip
										Heure :		$time;
										Login :		$login
										Email :		$Fmail 
									</pre> 
									<p>ALCASAR<a href=\"https://$hostname\">$domain</a></p>
								</body>
							</html>";

						$header = "From: $from\n";
						$header .= "MIME-Version: 1.0\n";
						$header .= "Content-type: text/html; charset=utf-8\n";
						mail($to, $subject, $message, $header);
		
					}

					
				} else {
					//Le mot de passe est généré aléatoirement, si le mail n'est pas envoyé, on supprime le compte de la bdd ou on lui demande d'utiliser la page reset
/*					$link = da_sql_pconnect($config);

					$res2 = da_sql_query($link,$config,
					"DELETE FROM $config[sql_user_info_table] WHERE username = '$login';");

					$res3 = da_sql_query($link,$config,
						"DELETE FROM $config[sql_check_table] WHERE username = '$login';");

//					da_sql_close($link,$config)
*/
					echo "<b>Erreur lors de l'envoi du mail, veuillez renouveler votre inscription. utilisez le formulaire de réinitialisation, ou contactez votre administrateur.</b><br>\n";
				}
			}
			else
				echo "<b>Could not open encryption library file</b><br>\n";
		}
		else
			echo "<b>Could not connect to SQL database</b><br>\n";
	}
}
?>
