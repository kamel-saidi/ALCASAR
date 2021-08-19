#!/bin/bash

##################################################################################################################
##														##
##			ALCASAR SERVICE MAIL INSTALL / WHITE LIST/BLACK LIST DOMAIN				##
##														##
##	Script by K@M3L 1101130512.1901090409 & T3RRY LaPlateforme_.						##
## 	V 1.0 June 2021.											##
##  This script configure the mail service, WHITE/BLACK list domain.						##
##														##
##	- WHITE LIST WLD :											##
##														##
##    La liste blanche limite les inscriptions utilisateurs a un, ou plusieurs domaines				##
##	  configurés depuis ce script, ou depuis l'ACC.								##
##	  Les utilisateurs utilsant d'autres domaines ne pourant pas s'inscrire, ni utiliser ALCASAR.		##
##    EX: la white liste cotient le domaine "localdomain.com",							##
##	  il n'aura que les utilisateur avec un mail "XXXX@localdomain.com" qui peuvent s'inscrire		##
##														##
##  - Black LIST BLD :												##
##    La liste noire empêche les inscriptions utilisateurs d'un, ou plusieurs domaines				##
##	  configurés depuis ce script, ou depuis l'ACC.								##
##	  Les utilisateurs utilsant le/les domaines de la BLK ne pourant pas s'inscrire, ni utiliser ALCASAR.	##
##    EX: la black liste cotient le domaine "gmail.com",							##
##	  il n'aura que les utilisateur avec un mail different de "XXXX@gmail.com" qui peuvent s'inscrire	##
##														##
##														##
##  ATTENTION :	ON NE DOIT UTILISER QUE L'UNE DES DEUX,								##
##	si on utilise la WLD tous les autres domaines sont automatiquement bannis.				##
##	si on utilise la BLD tous les autres domaines sont automatiquement autorisés.				##
##														##
##################################################################################################################


Lang=`echo $LANG|cut -c 1-2`
#Lang="en"
if [ $Lang == "fr" ]
then
	header_title="	              Configuration de la white/black liste du domaine MX \n\n"
	header_msg1=" ce service est indispensable si vous souhaitez limiter les inscriptions des utilisateurs,"
	header_msg2=" La liste blanche (White List Domains WLD) limite les inscriptions utilisateurs à un ou plusieurs domaines."
	header_msg3=" La liste noire (Black List Domains BLD) bannît, interdit les inscriptions utilisateurs sur un ou plusieurs domaines."
	header_msg4=" IMPORTANT : IL FAUT CHOISIR SOIT LISTE BLANCHE, SOIT LISTE NOIRE, PAS LES DEUX."
	wldMsg="voulez vous limiter les inscriptions utilisateurs à un ou plusieurs domaine ? (o/n) : "
	wldNewD="Entrez le le nom de domaine à autoriser (Ex : gmail.com, hotmail.fr, ..) : "
	bldMsg="Voulez vous bannir un ou plusieurs domaine lors des inscriptions utilisateurs ? (o/n) : "
	bldNewD="Entrez le le nom de domaine à bannir (Ex : gmail.com, hotmail.fr, ..) : "
	addOther="Voulez vous ajouter un autre domaine ? (o/n) : "
else 
	header_title="	              Configuration of the white/black list MX domaine \n\n"
	header_msg1=" this service is essential if you want to limit users registrations,"
	header_msg2=" The white list (White List Domains WLD) limits user registrations to one or more domains. "
	header_msg3=" The black list (Black List Domains BLD) bans, prohibits users registrations on one or more domains."
	header_msg4=" IMPORTANT : YOU MUST CHOOSE EITHER WHITE LIST OR BLACK LIST, NOT BOTH."
	wldMsg="Want to limit user registrations to one or more domains ? (y/n) : "
	wldNewD="Enter the domain name to authorize (Ex : gmail.com, hotmail.fr, ..) : "
	bldMsg="Do you want to ban one or more domains during user registrations ? (o/n) : "
	bldNewD="Enter the domain name to be banned (Ex : gmail.com, hotmail.fr, ..) : "
	addOther="Do you want to add another domain ? (o/n) : "
fi

# test if the user is root
if [ "$EUID" -ne 0 ]
then
	echo -e "\e[5m\nPlease run as root\n\e[m"
	exit 1
fi

# Header de l'installations
printf '\n\e[1;33m%-6s\n' "#######################################################################################"
printf '\e[1;33m%-6s\n\n' "---------------------------------------------------------------------------------------"
echo -e "\033[31m ${header_title}"
echo -e "\033[32m ${header_msg1}"
echo -e "\033[32m ${header_msg2}"
echo -e "\033[32m ${header_msg3}"
echo -e "\033[31m\n\n ${header_msg4} \n"
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


# Partie BLD Black List Domain

declare -a blackDomain

read -p "$bldMsg" response2

while [[ $response2 =~ $PTN ]]
do
	read -p "$bldNewD" domain
	read -p "$addOther" response2

	blackDomain+=("$domain")
done

if [ ! -z $blackDomain ]
then
	sed -i '/blackDomain/d' /usr/local/etc/alcasar-mail.conf 2>/dev/null
	echo "blackDomain=${blackDomain[*]}" >> /usr/local/etc/alcasar-mail.conf
fi

exit 0