#!/bin/bash

##################################################################################################################
##														##
##			ALCASAR SERVICE MAIL INSTALL / WHITELIST DOMAINS					##
##														##
##	Script by K@M3L 1101130512.1901090409 & T3RRY @ La Plateforme_						##
## 	V 1.0 June 2021.											##
##  This script configure the mail service, WhiteList Domains.							##
##														##
##    - WhiteList Domains WLD :											##
##														##
##	La liste blanche limite les inscriptions utilisateurs à un, ou plusieurs domaines.			##
##	tous les autres domaines sont automatiquement bannis.							##
##	configurés depuis ce script, ou depuis l'ACC.								##
##	Les utilisateurs utilsant d'autres domaines ne pourant pas s'inscrire, ni utiliser ALCASAR.		##
##	EX: la white liste contient le domaine "localdomain.com",						##
##	il n'y aura que les utilisateurs avec un mail "XXXX@localdomain.com" qui peuvent s'inscrire.		##
##														##
##################################################################################################################


Lang=`echo $LANG|cut -c 1-2`
#Lang="en"
if [ $Lang == "fr" ]
then
	header_title="	              Configuration de la WhiteList Domains MX (WLD)\n\n"
	header_msg1=" Ce service est indispensable si vous souhaitez limiter les inscriptions des utilisateurs."
	header_msg2=" La liste blanche (WhiteList Domains WLD) limite les inscriptions utilisateurs à un ou plusieurs domaines."
	header_msg3=" Les utilisateurs utilsant d'autres domaines ne pourant pas s'inscrire, ni utiliser ALCASAR."
	wldMsg="voulez vous limiter les inscriptions utilisateurs à un ou plusieurs domaine ? (o/n) : "
	wldNewD="Entrez le nom de domaine à autoriser (ex : gmail.com, hotmail.fr...) : "
	addOther="Voulez vous ajouter un autre domaine ? (o/n) : "
else 
	header_title="	              Configuration of the WhiteList MX domains (WLD)\n\n"
	header_msg1=" This service is essential if you want to limit users registrations,"
	header_msg2=" The WhiteList Domains (WLD) limits user registrations to one or more domains."
	header_msg3=" Users using other domains cannot register or use ALCASAR."
	wldMsg="Do you want to limit user registrations to one or more domains ? (y/n) : "
	wldNewD="Enter the domain name to authorize (Ex : gmail.com, hotmail.fr, ..) : "
	addOther="Do you want to add another domain ? (o/n) : "
fi

# test if the user is root
if [ "$EUID" -ne 0 ]
then
	echo -e "\e[5m\nPlease run as root\n\e[m"
	exit 1
fi

# Header de l'installation
printf '\n\e[1;33m%-6s\n' "#######################################################################################"
printf '\e[1;33m%-6s\n\n' "---------------------------------------------------------------------------------------"
echo -e "\033[31m ${header_title}"
echo -e "\033[32m ${header_msg1}"
echo -e "\033[32m ${header_msg2}"
echo -e "\033[31m ${header_msg3} \n"
printf '\e[1;33m%-6s\n' "---------------------------------------------------------------------------------------"
printf '\e[1;33m%-6s\n\n\e[m' "#######################################################################################"


# Partie WLD White List Domain

declare -a whiteDomain

read -p "$wldMsg" response1

PTN='^[oOyY]?$'

while [[ $response1 =~ $PTN ]]
do
	read -p "$wldNewD" domain
	read -p "$addOther" response1

	whiteDomain+=("$domain")
done

if [ ! -z $whiteDomain ]
then
	sed -i '/whiteDomain/d' /usr/local/etc/alcasar-mail.conf 2>/dev/null
	echo "whiteDomain=${whiteDomain[*]}" >> /usr/local/etc/alcasar-mail.conf
fi

exit 0
