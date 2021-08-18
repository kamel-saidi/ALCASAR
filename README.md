# ALCASAR MAIL SERVICE

- alcasar-mail-install.sh
- alcasar-mail-uninstall.sh
- alcasar-mail-wld-bld.sh
Ces fichiers sont à mettre dans le dossier /usr/local/bin pour être executés en tant que commande depuis la cli.

- inscription.php
- inscription_traitement.php
Ces fichiers sont à mettre dans le dossier /var/www/html.

- alcasar-mail-install.sh :

l'interactvité avec l'administrateur à l'installation a pour but de :
   - récupérer des paramètres de configurations POSTFIX.
   - récupéter des paramètres de l'email pour le cas trois.
   - récuperer les paramètres pour Iptables.
   - configurer la limitattion des inscriptions utilisateurs à un ou quelques domaines WLD white list domains.
   - configurer le bannisement d'un ou quelques domaines  lors des inscriptions utilisateurs BLD Black list domains.

   - il y a trois choix de configuration:
    -1 service mail autonome, il faut une ip publique d'un nom de domaine enregistré
  	pour la résolution DNS MX du domaine.
    -2 service mail qui dépend, relaye les couriels sortant un un autre serveur mail
  	(local, distant, ... ) dans ce cas contrairement aux 2 autres,
  	Iptables n'autorise la communication qu'avec ce serveur mail.
    -3 le service mail utilise une adresse mail pour envoyer les couriels
  	comme n'importe quel logciel de messagerie.

  Ces paramètres de configurations peuvent être envoyer au script sous forme d'options et arguments :
   - -1 -2 -3 pour pour initialiser la varible qui détermine la configuration voir plus haut, elle est très importante.
   - -1 postfix est autonome, si on mets cette option veuillez ne pas rajouter d'autres options sauf -a, -w, -b.
   - -s pour le SMTP FQDN ou IP.
   - -p pour le port.
   - -r pour l'ip du serveur SMTP dans le cas relay, 0.0.0.0/0 pour les autres cas, ou le cas d'un SMTP avec ip dynamique.
   - -m pour le mail dans le cas 3.
   - -o pour le mot de passe du compte mail dans le cas 3. Attention si commande tapée le passe est entré en clair.
   - -w pour la WLD, l'argument est un tableau au format 'domain1.com domain2.fr ...'.
   - -b pour la BLD, l'argument est un tableau au format 'domain1 domain2 ...'.
   - -a pour le mail de l'admin, reception des log, et mail lors de nouvelle inscription.
 
 Selon le cas il y a des options obligatoires et d'autres facultatives:
  Cas 1 : la seule option obligatoire est -1
  Cas 2 : -2 -s "SMTP" -p port -r "ip du relais pour le firewall" "sinonil prendra par défaut 0.0.0.0./0"
  Cas 3 : -3 -m "mail" -o "password" -s "SMTP" -p 3port"
  
 Les autre options peuvent être ajoutées a l'obligatoires, ou seules
	ex : alcasar-mail-install.sh -3 -s "smtp.gmail.com" -p 587 -m mail@gmail.com -o psswd -w 'domain1.com, domain2.com'.
  ex : alcasar-mail-install.sh -b 'domain1.com, domain2.com'.
  ex : alcasar-mail-install.sh -a admin@mail.com.
  ex : alcasar-mail-install.sh -w 'domain1.com, domain2.com' -a admin@mail.com.

S'il est lancé en mode interactif il éxecute à la fin alcasar-mail-wld-bld.sh.




- alcasar-mail-wld-bld.sh :

	- WHITE LIST WLD :

    La liste blanche limite les inscriptions utilisateurs a un, ou plusieurs domaines
	  configurés depuis ce script, ou depuis l'ACC.
	  Les utilisateurs utilsant d'autres domaines ne pourant pas s'inscrire, ni utiliser ALCASAR.
    EX: la white liste cotient le domaine "localdomain.com",
	  il n'aura que les utilisateur avec un mail "XXXX@localdomain.com" qui peuvent s'inscrire.

  - Black LIST BLD :
    La liste noire empêche les inscriptions utilisateurs d'un, ou plusieurs domaines
	  configurés depuis ce script, ou depuis l'ACC.
	  Les utilisateurs utilsant le/les domaines de la BLK ne pourant pas s'inscrire, ni utiliser ALCASAR.
    EX: la black liste cotient le domaine "gmail.com",
	  il n'aura que les utilisateur avec un mail different de "XXXX@gmail.com" qui peuvent s'inscrire.

  ATTENTION :	ON NE DOIT UTILISER QUE L'UNE DES DEUX,
  si on utilise la WLD tous les autres domaines sont automatiquement bannis.
  si on utilise la BLD tous les autres domaines sont automatiquement autorisés.



- alcasar-mail-uninstall.sh :

  il remet la conf à zéro comme avant l'install.


- inscription.php
  La partie front de la page d'inscription des utilisateurs, si WLD ou BLD sont configurées alors les inscriptions seront limitées, filtrées.
  
- inscription_traitement.php
  La partie back de la page d'inscription.
