<?php
/* written by steweb57 & Rexy */
# Choice of language
$Language = 'en';
if(isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])){
	$Langue		= explode(",",$_SERVER['HTTP_ACCEPT_LANGUAGE']);
	$Language	= strtolower(substr(chop($Langue[0]),0,2)); }
if($Language == 'fr') {
	$l_services_title	= "Configuration des services";
	$l_main_services	= "Services principaux";
	$l_filter_services	= "Services de filtrage";
	$l_opt_services		= "Services optionnels";
	$l_service_title 	= "Rôle du service";
	$l_service_start 	= "Démarrer";
	$l_service_stop 	= "Arréter";
	$l_service_restart 	= "Redémarrer";
	$l_service_status 	= "Status";
	$l_service_status_img_ok= "Démarré";
	$l_service_status_img_ko= "Arrété";
	$l_service_action 	= "Actions";
	$l_radiusd		= "Serveur d'authentification et d'autorisation";
	$l_chilli		= "Passerelle d'interception et serveur DHCP";
	$l_e2guardian		= "Filtre d'URL et de contenu WEB";
	$l_mysqld		= "Serveur de la base des usagers";
	$l_lighttpd		= "Serveur WEB (Alcasar Control Center)";
	$l_sshd			= "Accès sécurisée distant";
	$l_clamav_freshclam	= "Mise à jour de l'antivirus (toutes les 4 heures)";
	$l_clamav_daemon	= "Antimalware";
	$l_ntpd			= "Service de mise à l'heure réseau";
	$l_fail2ban		= "Détecteur d'intrusion";
	$l_nfcapd 		= "Collecteur de flux NetFlow";
	$l_vnstat		= "Grapheur de flux réseau";
	$l_unbound		= "Serveur DNS principal";
	$l_unbound_blacklist	= "Serveur DNS pour la Blacklist";
	$l_unbound_whitelist	= "Serveur DNS pour la Whitelist";
	$l_dnsmasq_whitelist	= "Serveur DNS pour la Whitelist (IPSET)";
	$l_unbound_blackhole	= "Serveur DNS 'trou noir'";
	$l_ulogd_ssh		= "journalisation des accès par SSH";
	$l_ulogd_ext_access	= "journalisation des tentatives d'accès externes";
	$l_ulogd_traceability	= "journalisation des connexions WEB filtrés";
	$l_wifi4eu_id	= "Entrez votre identifiant réseau";
	$l_execute		= "Exécuter";
	$l_stop_restart		= "Arrêt et redémarrage du système";
	$l_halt			= "Arréter le système";
	$l_reboot		= "Relancer le système";
} else if($Language == 'es') {
	$l_services_title	= "Configuración de Servicios";
	$l_main_services	= "Servicios Principales";
	$l_filter_services	= "Servicios de Filtrado";
	$l_opt_services		= "Servicios Opcionales";
	$l_service_title 	= "función del servicio";
	$l_service_start 	= "Iniciar";
	$l_service_stop 	= "Detener";
	$l_service_restart 	= "Reiniciar";
	$l_service_status 	= "Estado";
	$l_service_status_img_ok= "Corriendo";
	$l_service_status_img_ko= "Detenido";
	$l_service_action 	= "Acciones";
	$l_radiusd		= "Servidor de autenticación y autorización.";
	$l_chilli		= "Pasarela de interceptación y servidor DHCP";
	$l_e2guardian		= "Filtro de contenidos URL y WEB";
	$l_mysqld		= "Motor de base de datos para usuarios";
	$l_lighttpd		= "Servidor WEB (ALCASAR Control Center)";
	$l_sshd			= "Servidor Seguro Acceso Remoto";
	$l_clamav_freshclam		= "Proceso de actualización Antivirus (cada 4 horas)";
	$l_clamav_daemon	= "Antimalware";
	$l_ntpd			= "Servidor de hora";
	$l_fail2ban		= "Sistema de Detección de Intrusos";
	$l_nfcapd		= "Colector de flujo NetFlow";
	$l_vnstat		= "Graficador de tráfico de red";
	$l_unbound		= "Servidor DNS principal ";
	$l_unbound_blacklist	= "Servidor DNS de Lista Negra";
	$l_unbound_whitelist	= "Servidor DNS de Lista Blanca";
	$l_dnsmasq_whitelist	= "Servidor DNS de Lista Blanca (IPSET)";
	$l_unbound_blackhole	= "Agujero negro DNS";
	$l_ulogd_ssh		= "Proceso de registro para accesos SSH";
	$l_ulogd_ext_access	= "Proceso de registro de intentos de accesos externos";
	$l_ulogd_traceability	= "Proceso de registro de acceso WEB";
	$l_wifi4eu_id	= "Introduzca su identificador de red";
	$l_execute		= "Ejecutar";
	$l_stop_restart		= "Apagado y Reinicio del sistema";
	$l_halt			= "Apagar el sistema";
	$l_reboot		= "Reiniciar el sistema";
} else {
	$l_services_title	= "Services configuration";
	$l_main_services	= "Main services";
	$l_filter_services	= "Filtering services";
	$l_opt_services		= "Optional services";
	$l_service_title 	= "Role of the service";
	$l_service_start 	= "Start";
	$l_service_stop 	= "Stop";
	$l_service_restart 	= "Restart";
	$l_service_status 	= "Status";
	$l_service_status_img_ok= "Running";
	$l_service_status_img_ko= "Stopped";
	$l_service_action 	= "Actions";
	$l_radiusd		= "Authentication and authorisation server";
	$l_chilli		= "Interception gateway and DHCP server";
	$l_e2guardian		= "URL and WEB content filter";
	$l_mysqld		= "User database server";
	$l_lighttpd		= "WEB server (ALCASAR Control Center)";
	$l_sshd			= "Secure remote access";
	$l_clamav_freshclam	= "Antivirus update process (every 4 hours)";
	$l_clamav_daemon= "Antimalware";
	$l_ntpd			= "Network time server";
	$l_fail2ban		= "Intrusion Dectection System";
	$l_nfcapd		= "Netflow collector";
	$l_vnstat		= "Network grapher";
	$l_unbound		= "Main DNS server";
	$l_unbound_blacklist	= "Blacklist DNS server";
	$l_unbound_whitelist	= "Whitelist DNS server";
	$l_dnsmasq_whitelist	= "Whitelist DNS server (IPSET)";
	$l_unbound_blackhole	= "Blackhole DNS server";
	$l_ulogd_ssh		= "SSH access logging process";
	$l_ulogd_ext_access	= "Extern access attempts logging process";
	$l_ulogd_traceability	= "Filtering WEB access logging process";
	$l_wifi4eu_id	= "Enter your network identifier";
	$l_execute		= "Execute";
	$l_stop_restart		= "Halt and restart the system";
	$l_halt			= "Halt le system";
	$l_reboot		= "Restart the system";
}

/****************************************************************
*	                   CONST				*
*****************************************************************/
define ("CONF_FILE", "/usr/local/etc/alcasar.conf");

/********************************************************
*			CONF FILE test 			*
*********************************************************/
if (!file_exists(CONF_FILE)){
	exit("Fichier de configuration ".CONF_FILE." non présent");
}
if (!is_readable(CONF_FILE)){
	exit("Vous n'avez pas les droits de lecture sur le fichier ".CONF_FILE);
}
$file_conf = fopen(CONF_FILE, 'r');
if (!$file_conf) {
	exit('Error opening the file '.CONF_FILE);
}
while (!feof($file_conf)) {
	$buffer = fgets($file_conf, 4096);
	if ((strpos($buffer, '=') !== false) && (substr($buffer, 0, 1) !== '#')) {
		$tmp = explode('=', $buffer, 2);
		$conf[trim($tmp[0])] = trim($tmp[1]);
	}
}
fclose($file_conf);
$wifi4eu = $conf['WIFI4EU'];
$wifi4eu_code = $conf['WIFI4EU_CODE'];
// Doing an action on a service (start,stop or restart)
function serviceExec($service, $action){
	if (($action == "start")||($action == "stop")||($action == "restart")){
		exec("sudo /usr/bin/systemctl $action ".escapeshellarg($service), $retval, $retstatus);
		if ($service == "sshd"){ 
			if ($action == "start"){
				//exec("sudo /usr/bin/systemctl enable ".escapeshellarg($service));
				file_put_contents(CONF_FILE, str_replace('SSH=off', 'SSH=on', file_get_contents(CONF_FILE))); // in order to keep that conf for SSH at next reboot
				exec("sudo /usr/local/bin/alcasar-iptables.sh");
				}
			if ($action == "stop"){
				//exec("sudo /usr/bin/systemctl disable ".escapeshellarg($service));
				file_put_contents(CONF_FILE, str_replace('SSH=on', 'SSH=off', file_get_contents(CONF_FILE)));
				exec("sudo /usr/local/bin/alcasar-iptables.sh");
				}
			}
		return $retstatus;
	} else {
		return false;
	}
}

// Testing if a service is active
function checkServiceStatus($service){
	$response = false;
	exec("sudo /usr/bin/systemctl is-active ".escapeshellarg("$service.service"), $retval);
	foreach( $retval as $val ) {
		if ($val == "active"){
			$response = true;
			break;
		}
	}
	return $response;
}

//-------------------------------
// WIFI4EU
//-------------------------------
if (isset($_POST['wifi4eu'])){
	switch ($_POST['wifi4eu']){
		case 'on' :
			$network_code = trim($_POST['wifi4eu_id']);
		        if ($network_code == '') {
				$network_code = '123e4567-e89b-12d3-a456-426655440000'; // WIFI4EU test code
			}
			file_put_contents(CONF_FILE, preg_replace('/WIFI4EU_CODE=.*/', 'WIFI4EU_CODE='.$network_code, file_get_contents(CONF_FILE)));
			exec("sudo /usr/local/bin/alcasar-wifi4eu.sh -on");
		break;
		case 'off' :
			exec("sudo /usr/local/bin/alcasar-wifi4eu.sh -off");
		break;
	}
	header('Location: '.$_SERVER['PHP_SELF']);
}

//-------------------------------
// Stop/restart system
//-------------------------------
if (isset($_POST['system'])){
	switch ($_POST['system']){
		case 'reboot' :
			exec ("sudo /usr/local/bin/alcasar-logout.sh all");
			exec ("sudo /usr/sbin/shutdown -r now");
		break;
		case 'halt' :
			exec ("sudo /usr/local/bin/alcasar-logout.sh all");
			exec ("sudo /usr/sbin/shutdown -h now");
		break;
	}
}

//-------------------------------
// Actions on services
//-------------------------------
$autorizeService = array("radiusd","chilli","mysqld","lighttpd","unbound-forward","ulogd-ssh","ulogd-ext-access","ulogd-traceability","unbound-blacklist","unbound-whitelist","dnsmasq-whitelist","unbound-blackhole","e2guardian","clamav-daemon","clamav-freshclam","sshd","ntpd","fail2ban","nfcapd","vnstat");
$autorizeAction = array("start","stop","restart");

if (isset($_GET['service'])&&(in_array($_GET['service'], $autorizeService))) {
    if (isset($_GET['action'])&&(in_array($_GET['action'], $autorizeAction))) {
    	$execStatus = serviceExec($_GET['service'], $_GET['action']);
		// execStatus non exploité
	}
}

//-------------------------------
// Check services status
//-------------------------------
$MainServiceStatus = array();
$MainServiceStatus['chilli'] = checkServiceStatus("chilli");
$MainServiceStatus['radiusd'] = checkServiceStatus("radiusd");
$MainServiceStatus['mysqld'] = checkServiceStatus("mysqld");
$MainServiceStatus['lighttpd'] = checkServiceStatus("lighttpd");
$MainServiceStatus['unbound'] = checkServiceStatus("unbound");
$MainServiceStatus['nfcapd'] = checkServiceStatus("nfcapd");
$MainServiceStatus['ulogd_ssh'] = checkServiceStatus("ulogd-ssh");
$MainServiceStatus['ulogd_ext_access'] = checkServiceStatus("ulogd-ext-access");
$MainServiceStatus['ulogd_traceability'] = checkServiceStatus("ulogd-traceability");
$MainServiceStatus['sshd'] = checkServiceStatus("sshd");
$MainServiceStatus['ntpd'] = checkServiceStatus("ntpd");
$MainServiceStatus['fail2ban'] = checkServiceStatus("fail2ban");
$MainServiceStatus['vnstat'] = checkServiceStatus("vnstat");

$FilterServiceStatus = array();
$FilterServiceStatus['unbound_blacklist'] = checkServiceStatus("unbound-blacklist");
$FilterServiceStatus['unbound_whitelist'] = checkServiceStatus("unbound-whitelist");
$FilterServiceStatus['dnsmasq_whitelist'] = checkServiceStatus("dnsmasq-whitelist");
$FilterServiceStatus['unbound_blackhole'] = checkServiceStatus("unbound-blackhole");
$FilterServiceStatus['e2guardian'] = checkServiceStatus("e2guardian");
$FilterServiceStatus['clamav_daemon'] = checkServiceStatus("clamav-daemon");
$FilterServiceStatus['clamav_freshclam'] = checkServiceStatus("clamav-freshclam");

/****************
*	MAIN	*
*****************/

?><!DOCTYPE HTML>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title><?php echo $l_services_title; ?></title>
	<link rel="stylesheet" href="/css/acc.css" type="text/css">
</head>
<body>
<div class="panel">
	<div class="panel-header"><?= $l_main_services ?></div>
	<div class="panel-row">
	<table width="100%" border=0 cellspacing=0 cellpadding=0>
		<tr align="center"><td><?php echo $l_service_status;?></td><td colspan="2"><?php echo $l_service_title;?></td><td colspan="3"><?php echo $l_service_action;?></td></tr>
		<?php foreach( $MainServiceStatus as $serviceName => $statusOK ) { ?>
		<tr>
		<?php if ($statusOK) { ?>
			<td align="center"><img src="/images/state_ok.gif" width="15" height="15" alt="<?php echo $l_service_status_img_ok; ?>"></td>
			<td align="center"><?php $comment="l_$serviceName"; echo "<b>$serviceName</b></td><td>${$comment}" ;?> </td>
			<td width="80" align="center">---</td>
			<td width="80" align="center"><?php if (($serviceName != "chilli") && ($serviceName != "lighttpd")) { echo "<a href=\"".$_SERVER['PHP_SELF']."?action=stop&service=".str_replace('_','-',$serviceName)."\"> $l_service_stop</a>"; } else echo "---";?></td>
			<td width="80" align="center"><a href="<?php echo $_SERVER['PHP_SELF']."?action=restart&service=".str_replace('_','-',$serviceName)."\"> $l_service_restart";?></a></td>
		<?php } else { ?>
			<td align="center"><img src="/images/state_error.gif" width="15" height="15" alt="<?php echo $l_service_status_img_ko ?>"></td>
			<td align="center"><?php $comment="l_$serviceName"; echo "$serviceName</td><td>${$comment}" ;?> </td>
			<td width="80" align="center"><a href="<?php echo $_SERVER['PHP_SELF']."?action=start&service=".str_replace('_','-',$serviceName)."\"> $l_service_start";?></a></td>
			<td width="80" align="center">---</td>
			<td width="80" align="center">---</td>
		<?php } ?>
		</tr>
		<?php } ?>
	</table>
	</div>
</div>
<div class="panel">
	<div class="panel-header"><?= $l_filter_services ?></div>
	<div class="panel-row">
	<table width="100%" border=0 cellspacing=0 cellpadding=0>
		<tr align="center"><td><?php echo $l_service_status;?></td><td colspan="2"><?php echo $l_service_title;?></td><td colspan="3"><?php echo $l_service_action;?></td></tr>
		<!--	<TR align="center"> -->
		<?php foreach( $FilterServiceStatus as $serviceName => $statusOK ) { ?>
		<tr>
		<?php if ($statusOK) { ?>
			<td align="center"><img src="/images/state_ok.gif" width="15" height="15" alt="<?php echo $l_service_status_img_ok; ?>"></td>
			<td align="center"><?php $comment="l_$serviceName"; echo "<b>$serviceName</b></td><td>${$comment}" ;?> </td>
			<td width="80" align="center">---</td>
			<td width="80" align="center"><a href="<?php echo $_SERVER['PHP_SELF']."?action=stop&service=".str_replace('_','-',$serviceName)."\"> $l_service_stop";?></a></td>
			<td width="80" align="center"><a href="<?php echo $_SERVER['PHP_SELF']."?action=restart&service=".str_replace('_','-',$serviceName)."\"> $l_service_restart";?></a></td>
			<?php } else { ?>
			<td align="center"><img src="/images/state_error.gif" width="15" height="15" alt="<?php echo $l_service_status_img_ko ?>"></td>
			<td align="center"><?php $comment="l_$serviceName"; echo "$serviceName</td><td>${$comment}" ;?> </td>
			<td width="80" align="center"><a href="<?php echo $_SERVER['PHP_SELF']."?action=start&service=".str_replace('_','-',$serviceName)."\"> $l_service_start";?></a></td>
			<td width="80" align="center">---</td>
			<td width="80" align="center">---</td>
			<?php } ?>
		</tr>
		<?php } ?>
	</table>
	</div>
</div>
<div class="panel">
	<div class="panel-header"><?= $l_opt_services ?></div>
	<div class="panel-row">
	<form action="<?php echo $_SERVER['PHP_SELF']?>" method=POST>
	<table width="100%" border=0 cellspacing=0 cellpadding=0>
		<tr align="center"><td><?php echo $l_service_status;?></td><td colspan="2"> </td><td colspan="3"><?php echo $l_service_action;?></td></tr>
		<tr>
			<?php if ($wifi4eu == "on") { ?>
			<td align="center"><img src="/images/state_ok.gif" width="15" height="15" alt="<?php echo $l_service_status_img_ok; ?>"></td>
			<td align="center"><b>WIFI4EU</b></td><td><?php echo "network ID : $wifi4eu_code"; ?></td>
			<td width="80" align="center">---</td>
			<td width="80" align="center"><input type=submit value="<?echo $l_service_stop;?>"><input type=hidden name="wifi4eu" value="off"></td>
			<td width="80" align="center">---</td>
			<?php } else { ?>
			<td align="center"><img src="/images/state_error.gif" width="15" height="15" alt="<?php echo $l_service_status_img_ko; ?>"></td>
			<td align="center">WIFI4EU</td><td><?php echo $l_wifi4eu_id; ?> : <input type ="text" name="wifi4eu_id" value="<?php echo $wifi4eu_code; ?>" size="40"></td>
			<td width="80" align="center"><input type=submit value="<?echo $l_service_start;?>"><input type=hidden name="wifi4eu" value="on"></td>
			<td width="80" align="center">---</td>
			<td width="80" align="center">---</td>
			<?php } ?>
		</tr>
	</table>
	</form>
	</div>
</div>
<div class="panel">
	<div class="panel-header"><?= $l_stop_restart ?></div>
	<div class="panel-row">
	<table width="100%" border=0 cellspacing=0 cellpadding=1>
		<tr><td valign="middle" align="left">
			<form action="<?php echo $_SERVER['PHP_SELF']?>" method=POST>
			<select name='system'>
				<option selected value="reboot"><?echo "$l_reboot";?>
				<option value="halt"><?echo "$l_halt";?>
			</select>
			<input type=submit value="<?echo "$l_execute";?>">
			</form>
		</td></tr>
	</table>
	</div>
</div>
</body>
</html>
