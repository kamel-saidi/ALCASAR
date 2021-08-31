# ALCASAR MAIL SERVICE

* alcasar-mail-install.sh
* alcasar-mail-uninstall.sh
* alcasar-mail-wld.sh
Ces fichiers sont à mettre dans le dossier /usr/local/bin pour être executés en tant que commande depuis la cli.


* header.php
* inscription.php
* inscription_traitement.php
Ces fichiers sont à mettre dans le dossier /var/www/html.


* services.php
Ce fichier est à mettre dans le dossier /var/www/html/acc/admin.



- alcasar-mail-install.sh :

l'interactvité avec l'administrateur à l'installation a pour but de :
   - récupérer des paramètres de configuration POSTFIX.
   - récupéter des paramètres de l'email pour le cas 3.
   - récuperer les paramètres pour Iptables.
   - configurer la limitattion des inscriptions utilisateurs à un ou plusieurs domaines WhiteList Domains (WLD).

   - il y a trois choix de configuration:
    -1 service mail autonome, il faut une ip publique d'un nom de domaine enregistré
  	pour la résolution DNS MX du domaine.
    -2 service mail qui dépend, relaie les couriels sortant un autre serveur mail
  	(local, distant, ... ) dans ce cas contrairement aux 2 autres,
  	Iptables n'autorise la communication qu'avec ce serveur mail.
    -3 le service mail utilise une adresse mail pour envoyer les couriels
  	comme n'importe quel logciel de messagerie.

  Ces paramètres de configurations peuvent être envoyés au script sous forme d'options et arguments :
   - -1 -2 -3 pour pour initialiser la varible qui détermine la configuration voir plus haut, elle est très importante.
   - -1 postfix est autonome, si on met cette option veuillez ne pas ajouter d'autres options sauf -a, -w, -b.
   - -s pour le SMTP FQDN ou IP.
   - -p pour le port.
   - -r pour l'ip du serveur SMTP dans le cas relay, 0.0.0.0/0 pour les autres cas, ou le cas d'un SMTP avec ip dynamique.
   - -m pour le mail dans le cas 3.
   - -o pour le mot de passe du compte mail dans le cas 3. Attention si commande tapée le passe est entré en clair.
   - -w pour la WLD, l'argument est un tableau au format 'domain1.com domain2.fr ...'.
   - -a pour le mail de l'admin, réception des logs et mail lors de nouvelles inscriptions.
 
 Selon le cas il y a des options obligatoires et d'autres facultatives:
  Cas 1 : la seule option obligatoire est -1
  Cas 2 : -2 -s "SMTP" -p port -r "ip du relais pour le firewall" "sinon il prendra par défaut 0.0.0.0./0"
  Cas 3 : -3 -m "mail" -o "password" -s "SMTP" -p "port"

 Les autres options peuvent être ajoutées aux options obligatoires, ou misent seules
	ex : alcasar-mail-install.sh -3 -s "smtp.gmail.com" -p 587 -m mail@gmail.com -o psswd -w 'domain1.com, domain2.com'.
  ex : alcasar-mail-install.sh -a admin@mail.com.
  ex : alcasar-mail-install.sh -w 'domain1.com, domain2.com' -a admin@mail.com.

S'il est lancé en mode interactif il éxecute à la fin alcasar-mail-wld-bld.sh.




- alcasar-mail-wld.sh :

	- WHITE LIST WLD :

    La liste blanche limite les inscriptions utilisateurs à un, ou plusieurs domaines
	  configurés depuis ce script, ou depuis l'ACC.
	  Les utilisateurs utilisant d'autres domaines ne pourront pas s'inscrire, ni utiliser ALCASAR.
    ex: la white list contient le domaine "localdomain.com",
	  il n'y aura que les utilisateurs avec un mail "XXXX@localdomain.com" qui pourront s'inscrire.

  
- alcasar-mail-uninstall.sh :

  il remet la conf à zéro comme avant l'install.

- header.php
  La barre de navigation.
  
- inscription.php
  La partie front de la page d'inscription des utilisateurs, si WLD est configurée alors les inscriptions seront limitées, filtrées.
  
- inscription_traitement.php
  La partie back de la page d'inscription.
  
  
- services.php
  La page des services de l'ACC, rajout de l'état de POSTFIX dans les services optioenels, et la partie des configurations pour l'envoi des emails, pour l'email de l'administrateur, de la WLD.
  
  il faut impérativement éditer le fichier /etc/sudoers via la commande visudo et :
  - ajouter cette ligne parmis les alias des commandes
    Cmnd_Alias      MAIL_SERVICE=/usr/local/bin/alcasar-mail-install.sh,/usr/local/bin/alcasar-mail-uninstall.sh            # Service mail commands to execute with web server
  - modifier cette ligne 
ADMWEB  LAN_ORG=(root)  NOPASSWD: NET,SYSTEM_BACKUP,SQL,BL,NF,EXPORT,RADDB,LOGOUT,UAM,SERVICE,GAMMU,SSL,HTDIGEST,LOG_GEN,LDAP,IOT_CAPTURE,WIFI4EU,MAIL_SERVICE



