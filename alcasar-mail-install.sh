#!/bin/bash

##############################################################################################################################
##																															##
##					ALCASAR SERVICE MAIL INSTALL																			##
##																															##	
##	Script by K@M3L 1101130512.1901090409 & T3RRY LaPlateforme_.															##
## 	V 1.0 June 2021.																										##
##  This script configure the mail service, install postfix if not installed.												##
##   - il y a trois choix de configuration:																					##
##    -1 service mail autonome, il faut une ip publique d'un nom de domaine enregistré										##
##  	pour la résolution DNS MX du domaine.																				##
##    -2 service mail qui dépend, relaye les couriels sortant un un autre serveur mail										##
##  	(local, distant, ... ) dans ce cas contrairement aux 2 autres,														##
##  	Iptables n'autorise la communication qu'avec ce serveur mail.														##
##    -3 le service mail utilise une adresse mail pour envoyer les couriels													##
##  	comme n'importe quel logciel de messagerie.																			##
##  																														##
##  l'interactvité avec l'administrateur à l'installation a pour but de														##
##   - récupérer des paramètres de configurations POSTFIX.																	##
##   - récupéter des paramètres de l'email pour le cas trois.																##
##   - récuperer les paramètres pour Iptables.																				##
##   - configurer la limitattion des inscriptions utilisateurs à un ou quelques domaines WLD white list domains				##
##   - configurer le bannisement d'un ou quelques domaines  lors des inscriptions utilisateurs BLD Black list domains		##
##																															##
##  Ces paramètres de configurations peuvent être envoyer au script sous forme d'options et arguments:						##
##   - -1 -2 -3 pour pour initialiser la varible qui détermine la configuration voir plus haut, elle est très importante.	##
##	 - -1 postfix est autonome, si on mets cette option veuillez ne pas rajouter d'autres options sauf -a, -w, -b			##
##   - -s pour le SMTP.																										##
##   - -p pour le port.																										##
##   - -r pour l'ip du serveur SMTP dans le cas relay, 0.0.0.0/0 pour les autres cas, ou le cas d'un SMTP avec ip dynamique.##
##   - -m pour le mail dans le cas 3.																						##
##   - -o pour le mot de passe du compte mail dans le cas 3.																##
##   - -w pour la WLD, l'argument est un tableau au format 'domain1.com, domain2.fr, ...'									##
##   - -b pour la BLD, l'argument est un tableau au format 'domain1, domain2, ...'											##
##   - -a pour le mail de l'admin, reception des log, et mail lors de nouvelle inscription									##
##																															##
##	ex alcasar-mail-install.sh -3 -s "smtp.gmail.com" -p 587 -m mail@gmail.com -o psswd -w 'domain1.com, domain2.com' 		##
##		-																													##
##############################################################################################################################


# test if the user is root
if [ "$EUID" -ne 0 ]
  then echo -e "\n\e[5mPlease run as root\n\e[m"
  exit
fi

# si il y a des options et des arguments on récupére les paramètres de configuration sans interagir avec l'admin
if [[ ${#} -ne 0 ]]
then
	while getopts ":s:p:r:m:o:a:w:b:123" option
	do
		echo "getopts a trouvé l'option $option"
		case $option in
			1)
				testConf=1
			;;
			2)
				testConf=2
			;;
			3)
				testConf=3
			;;
			s)
				smtp=$OPTARG
			;;
			p)
				port=$OPTARG
			;;
			r)
				smtpIP=$OPTARG
			;;
			m)
				mailAddr=$OPTARG
			;;
			o)
				pswd2=$OPTARG
			;;
			a)
				adminMail=$OPTARG
			;;
			w)
				wld=$OPTARG
			;;
			b)
				bld=$OPTARG
			;;
			:)
				echo "L'option $OPTARG requiert un argument"
				exit 1
			;;
			\?)
				echo "$OPTARG : option invalide"
				exit 1
			;;
		esac
	done
	
# si il n'y a des options et des arguments on récupére les paramètres de configuration en posant des questions a l'admin
else

	Lang=`echo $LANG|cut -c 1-2`

	# teste de la langue et déclaration des variables des messages affichés à l'écran en Français et Anglais
	if [ $Lang == "fr" ]
		then
			header_title="	                 Instalation du service de messagerie : \n\n"
			header_msg1=" ce service est indispensable si vous souhaitez envoyer les logs par mail, ou"
			header_msg2=" pour l'envoi des emails d'inscription et reset de mot de passe utilisateur.\n"
			header_msg3=" Il faut un nom de domaine enregistré avec SPF, DKIM, DMARc (ce service sera autonome),"
			header_msg4=" ou un serveur mail fonctionnel dans votre domaine (en local, cloud, relais SMTP, ...),"
			header_msg5=" ou un compte mail.\n"
			header_msg6=" vous serez peut être obligé de configurer votre Firewall, routeur, boxe, ..."
			header_msg7=" ou chez votre FAI pour laisser passer le traffic SMTP. \n\n"
			msgInstall1="Si vous n'avez pas de paramètres de votre serveur mail, ou un comptre mail veuillez répondre N/n (non)."
			msgInstall2="Vous pouvez réaliser l'installation à n'importe quel moment depuis la CLI 'alcasar-mail-install.sh',"
			msgInstall3="ou '/usr/local/bin/alcasar-mail-install.sh', ou depuis l'ACC.\n"
			msgInstallConfirm="voulez vous installer le service des emails (O/n)? : "
		else
			header_title=" 	                Mail service install : \n\n"
			header_msg1=" This service is essential if you want to send the logs by email, "
			header_msg2=" or for sending registration emails and user password reset."
			header_msg3=" You need a domain name registered with SPF, DKIM, DMARc (this service will be autonomous ),"
			header_msg4=" or a functional mail server in your domain (local, cloud, relay SMTP, ...),"
			header_msg5=" or a simpl mail account.\n"
			header_msg6=" you may have to configure your Firewall, router, boxing, ... ."
			header_msg7=" or at your ISP to let SMTP traffic pass.\n\n"
			msgInstall1="If you d'ont have the parameters of your mail server, or a mail account, please answer N/n (no)."
			msgInstall2="you can do this install any time from the cli 'alcasar-mail-install.sh',"
			msgInstall3="or '/usr/local/bin/alcasar-mail-install.sh', or from the ACC.\n"
			msgInstallConfirm="Do you want to install this mail service (Y/n)? : "
	fi

	# Header de l'installations
	printf '\n\e[1;33m%-6s\n' "#######################################################################################"
	printf '\e[1;33m%-6s\n\n' "---------------------------------------------------------------------------------------"
	echo -e "\033[31m ${header_title}"
	echo -e "\033[32m ${header_msg1}"
	echo -e "\033[32m ${header_msg2}"
	echo -e "\033[32m ${header_msg3}"
	echo -e "\033[32m ${header_msg4}"
	echo -e "\033[32m ${header_msg5}"
	echo -e "\033[32m ${header_msg6}"
	echo -e "\033[32m ${header_msg7}"
	printf '\e[1;33m%-6s\n' "---------------------------------------------------------------------------------------"
	printf '\e[1;33m%-6s\n\n\e[m' "#######################################################################################"

	# confiramation de l'installation du service pour l'envoi de mail
	echo ${msgInstall1}
	echo ${msgInstall2}
	echo -e ${msgInstall3}

	response=0

	PTN='^[oOyYnN]?$'

	until [[ "$response" =~ $PTN ]]
	do
		read -p "${msgInstallConfirm}" response
	done

	case $response in
		# on procéde a l'installation et la configuration
		o|O|y|Y)

echo "

Veuillez entrer le chiffre corrependant à votre choix :

1) Vous avez un nom de domaine enregistré (Serveur autonome).

2) Vous avez un serveur mail fonctionnel, ou un serveur SMTP relais.

3) Vous voulez utiliser un compte email.

"
			read -p "Entrez votre choix : " choice

			case $choice in

				# postfix est autonome
				1)
					# variable de test pour personaliser la configuration selon les cas
					testConf=1
				;;

				# postfix relaye les mail a un autre serveur SMTP
				2)
					read -p "Entrez le serveur SMTP ( EX : smtp.gmail.com ) : " smtp
					read -p "Entrez le PORT de votre serveur SMTP ( EX : 25, 465, 587 ) : " port
					read -p "Entrez l'IP de votre serveur SMTP pour n'autoriser la communication smtp qu'avec cette IP: " smtpIP
					
					testConf=2
				;;
					
				# postfix est utilisé comme un application de messagerie
				3)

					#récupération du mail et password pour la configuration
				   	read -p "veuillez saisir l'email via laquelle seront envoyés les emails : " mailAddr

					pswd1=1
					pswd2=2
					while [[ $pswd1 != $pswd2 ]]
					do
						read -sp "Veuillez entrez le mot de passe de votre mail : " pswd1; echo
						read -sp "Veuillez confirmer le mot de passe : " pswd2; echo
					done

					echo "Veuillez choisir le seveur SMTP de votre boite mail choix 1 à 9."
					echo "Ou choisissez 9 pour personaliser vous même le serveur et le port SMTP"

echo "

choix   compte de messagerie    adresse de messagerie                   serveur sortant     port sortant

1)      Orange/Wanadoo          orange.fr /wanadoo.fr                   smtp.orange.fr          465

2)      Hotmail         hotmail.com/.fr / live.com/.fr / msn.com        smtp.live.com           587

3)      outlook         hotmail.xx/live.xx/msn.com/outlook/office365    smtp.office365.com      587

4)      SFR                     sfr.fr                                  smtp.sfr.fr             465

5)      Free                    free.fr                                 smtp.free.fr            465

6)      Gmail                   gmail.com                               smtp.gmail.com          587

7)      Laposte                 laposte.net                             smtp.laposte.net        465

8)      Bouygues Telecom        bbox.fr                                 smtp.bbox.fr            587

9)      Personalisez le serveur SMTP et le PORT
"


# smtp-mail.outlook.com (port 587)

# on peut récupérer le SMTP depuis le mail mais pas sûr a 100%, le cas des mails de laplateforme.io, le smtp est Gmail 
# smtp=`echo $mailAddr | cut -d "@" -f2`

					read -p "Entrez votre choix : " confSMTP

					case $confSMTP in

						1)
							smtp="smtp.orange.fr"
							port=465			
						;;

						2)
							smtp="smtp.live.com"
							port=587
						;;

						3)
							smtp="smtp.office365.com"
							port=587
						;;

						4)
							smtp="smtp.sfr.fr"
							port=465			
						;;

						5)
							smtp="smtp.free.fr"
							port=465			
						;;

						6)
							echo "La boite de récéption Gmail concidére le sérvice mail comme application moins sécurisée."
							echo "Pour le bon fonctionnement du service, veuillez activer cette option depuis votre compte, onglet \"Sécurité\""
							echo "puis \"Accès moins sécurisé des applications\", ou depuis ce lien: https://myaccount.google.com/lesssecureapps ."
							smtp="smtp.gmail.com"
							port=587			
						;;

						7)
							smtp="smtp.laposte.net"
							port=465			
						;;

						8)
							smtp="smtp.bbox.fr"
							port=587			
						;;

						9)
							read -p "Entrez le serveur SMTP au format smtp.XXX.XX  ( EX : smtp.gmail.com ) : " smtp
							read -p "Entrez le PORT de votre serveur SMTP ( EX : 25, 465, 587 ) : " port
						;;

						*)
						;;
					esac

					smtpIP="0.0.0.0/0"
					testConf=3
				;;

				*)
				;;
			esac
		;;
		
		# l'installation n'est pas acceptée.
		n|N)
			echo "En cas de changement d'avis plus tard, le script d'installation se trouve dans /usr/share/etc/alcasar-mail.sh"
			#exit
		;;
		*)
		;;
	esac
fi

if [ ! -z $testConf ] && [ $testConf -eq 1 ]
then
	smtpIP="0.0.0.0/0"
	port=25
fi

# partie de paramètrage, commune avec les options et l'intéraction avec l'admin

if [ ! -z $testConf ]
then

	#tester si postfix est présent sinon l'installé
	rpm -q postfix >> /dev/null 2>&1

	if [ $? -eq 1 ] || [ ! -e /usr/sbin/postfix ] || [ ! -e /etc/postfix/main.cf ]
	then 
		dnf install -y postfix
	fi
		
	#on test si le fichier  /etc/postfix/main.cf.orig n'exist pas c'est que c'est la première fois que l'install s'execute,
	#alor il faut faire un backup du fichier de configuration original, sinon on restaure l'orginal pour écraser une configuration existante
	if [ ! -f /etc/postfix/main.cf.origin ]
	then
		# on sauvegarde le fichier de conf par défaut
		cp -f /etc/postfix/main.cf /etc/postfix/main.cf.origin
	else
		# on sauvegarde l'ancienne conf, et on restaure celle par défaut
		cp -f /etc/postfix/main.cf  /etc/postfix/main.cf.bak
		cp -f /etc/postfix/main.cf.origin  /etc/postfix/main.cf
	fi				

	#ajout de la configuration dans le fichier de configuration

	echo "myhostname = `hostname`" >> /etc/postfix/main.cf
fi
			
if [ ! -z $testConf ] && [ $testConf -ne 1 ]
then
	echo "relayhost = [${smtp}]:${port}" >> /etc/postfix/main.cf
fi

if [ ! -z $testConf ] && [ $testConf -eq 3 ]
then

	#tester si le module d'authentification SASL est présent sinon l'installé 
	rpm -q cyrus-sasl >> /dev/null 2>&1

	if [ $? -eq 1 ]
	then 
		dnf install -y cyrus-sasl
	fi

	# si le rrépértoir n'existe pas on le crée
	if [ ! -d /etc/postfix/sasl ]
	then
		mkdir /etc/postfix/sasl
	fi			

	# création de la db du password via SASL
	echo "[${smtp}]:$port $mailAddr:$pswd2" > /etc/postfix/sasl/sasl_passwd

	postmap /etc/postfix/sasl/sasl_passwd

	#protection des fichiers qui contiennent la conf du mail, et le password
	chown root:root /etc/postfix/sasl/sasl_passwd*
	chmod 0600 /etc/postfix/sasl/sasl_passwd*

cat << EOT >> /etc/postfix/main.cf

# Enable SASL authentication
smtp_sasl_auth_enable = yes
# Disallow methods that allow anonymous authentication
smtp_sasl_security_options = noanonymous
# Location of sasl_passwd
smtp_sasl_password_maps = hash:/etc/postfix/sasl/sasl_passwd

EOT
fi

if [ ! -z $testConf ]
then
	systemctl restart postfix

	# améliorer le code pour qu'il soit plus fléxible, faire des regex pour l'ip déjà présente, ainsi que le port
	# [0-9]{1,3}\.){3}[0-9]{1,3}\/([0-9]	sed -i -e 's/ \(\([0-9]\{1,3\}\.\)\{1,3\}\)?(\/0)/${smtpIP}/'
	# la régle iptables et à mettre dans le script d'iptables pour une configuration persistente
	# on change le port pour celui du SMTP, L'ip de setination, et on décommente les lignes concernées
	# a voir ne pas décommenter la ligne d'INPUT, on ser de postfix que pour l'envoi, donc que l'OUTPUT
	old_smtpIP=`grep "SMTP_IP=" /usr/local/etc/alcasar-iptables-local.sh  | cut -d "'" -f2`
	old_port=`grep "SMTP_PORT=" /usr/local/etc/alcasar-iptables-local.sh  | cut -d "=" -f2 | cut -f1`
	sed -ie "/SMTP_IP=/ s@${old_smtpIP}@${smtpIP}@" /usr/local/etc/alcasar-iptables-local.sh
	sed -ie "/SMTP_PORT=/ s/${old_port}/${port}/" /usr/local/etc/alcasar-iptables-local.sh
	sed -ie "/SMTP_IP=/ s/^#//" /usr/local/etc/alcasar-iptables-local.sh
	sed -ie "/SMTP_PORT/ s/^#//g" /usr/local/etc/alcasar-iptables-local.sh

	# à voir s'ils ont les droits d'execution, normalement non
	chmod 700 /usr/local/etc/alcasar-iptables-local.sh
	chmod 700 /usr/local/bin/alcasar-iptables.sh

	/usr/local/bin/alcasar-iptables.sh
			
	#récupération du mail d'administration pour recevoir les alertes inscription, et les log hébdomadaire
	echo "Le mail de l'administrateur sert à recevoir des mail lors d'une inscription utilisateur, et les log hébdo"
	read -p "veuillez saisir l'email d'aministration : " adminMail
	
	# pas besoin on va le lire depuis le fichier de conf mail.conf, a delete
#	sed -i -e "/\$adminMail = / s/\$adminMail = .*/\$adminMail = ${adminMail};/" /var/www/html/inscription_register.php


# voir si on a besoin de faire un backup si ce fichier existe déjà
# il faut ajouter des conditions pour ne valider que les variables initiaisées
cat << EOT > /usr/local/etc/alcasar-mail.conf

###############################################
##                                           ##
##          ALCASAR MAIL Parameters          ##
##                                           ##
###############################################


smtpIP=${smtpIP}
port=${port}
EOT

	if [ ! -z $smtp ]
	then
		echo "smtp=${smtp}" >> /usr/local/etc/alcasar-mail.conf
	fi

	if [ ! -z $mailAddr ]
	then
		echo "mailAddr=$mailAddr" >> /usr/local/etc/alcasar-mail.conf
	fi

	if [ ! -z $adminMail ]
	then
		echo "adminMail=$adminMail" >> /usr/local/etc/alcasar-mail.conf
	fi


	if [ ! -z $wld ]
	then
		echo "whiteDomain=${wld}" >> /usr/local/etc/alcasar-mail.conf
	fi

	if [ ! -z $bld ]
	then
		echo "blackDomain=${bld}" >> /usr/local/etc/alcasar-mail.conf
	fi


fi

# on substitu le mail dans le script d'envoi d'archive pour mettre le mail de l'admin admin@domain.com
# v2 pas besoin, on va lire le fichier de conf et récuperer le mail admin, a delete
#sed -i 's/admin\@domain.com/${adminMail}/g' /usr/local/bin/alcasar-mail-archive.sh

# on ajoute le cron weekly, lundi 5H45 aprés l'archivage chaque lundi à 5H35,
# on peut poser la question a l'admin s'il veut l'activer avant, dans ce cas le mettre avec la condition pour laé WLD/BLD
# dès que le script d'envoi sera pret, on décommente la ligne, depuis l'ACC on rajoute cette ligne directement dans le fichier depuis le php

#echo "45 5 * * 1 root /usr/local/bin/alcasar-mail-archive.sh" >> /etc/cron.d/alcasar-archive


# on peut appeler le script WLD/BLD en mode interactif, seulement si l'install est en mode interactif lui même,
# il n'est pas lancé en mode options, si la WLD/BLD éxiste en options, elle sera injéctée dans le fichier de conf direct


if [ ! -z $response ] && [[ $response =~ $PTN ]]			
then
	/usr/local/bin/alcasar-mail-wld-bld.sh
fi

exit 0
