#!/bin/bash

##########################################################################
##									##
##			ALCASAR SERVICE MAIL UNINSTALL			##
##									##	
##	Script by K@M3L 1101130512.1901090409 & T3RRY @ La Plateforme_	##
## 	V 1.0 June 2021.						##
##  This script uninstall the mail service, all configuration is lost.	##
##									##
##########################################################################


# test if the user is root
if [ "$EUID" -ne 0 ]
then
	echo -e "\n\e[5mPlease run as root\n\e[m"
	exit 1
fi

if [ -f /usr/local/etc/alcasar-mail.conf ]
then
	# on supprimme le backup de la conf, et on restaure le fichier de conf par defaut, on peut faire des test si les fichiers existent avant
	rm -f /etc/postfix/main.cf.bak
	mv -f /etc/postfix/main.cf.origin  /etc/postfix/main.cf
	
	# on suprimme le dossier contenant la configuration du mail par lequel les mails sont envoyés dans le cas 3
	rm -rf /etc/postfix/sasl

	# on suprimme le fichier de conf général
	rm -f /usr/local/etc/alcasar-mail.conf

	systemctl restart postfix

	# on ferme les ports iptables
	sed -i '/SMTP_IP=/ s/^/#/g' /usr/local/etc/alcasar-iptables-local.sh
	sed -i '/SMTP_PORT/ s/^/#/g' /usr/local/etc/alcasar-iptables-local.sh

	/usr/local/bin/alcasar-iptables.sh

	# on retire le cron d'envoi d'archive
#	sed -i '/alcasar-mail-archive.sh/d' /etc/cron.d/alcasar-archive

fi

exit 0
