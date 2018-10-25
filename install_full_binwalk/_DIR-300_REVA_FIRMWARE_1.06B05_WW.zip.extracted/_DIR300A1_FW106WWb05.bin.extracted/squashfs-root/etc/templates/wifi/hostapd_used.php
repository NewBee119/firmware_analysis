<?
/* vi: set sw=4 ts=4: */
$authtype	= query("authtype");
$encrtype	= query("encrypttype");
$Rport		= query("wpa/radius:1/port");
$Rhost		= query("wpa/radius:1/host");
$Rsecret	= query("wpa/radius:1/secret");
$wpapsk		= query("wpa/key");
$rkeyint	= query("wpa/grp_rekey_interval");
$ssid		= query("ssid");

if		($authtype==0) { $HAPD_wpa=0; $HAPD_ieee8021x=0; }	/* Open					*/
else if	($authtype==1) { $HAPD_wpa=0; $HAPD_ieee8021x=0; }	/* Shared-Key			*/
else if	($authtype==2) { $HAPD_wpa=1; $HAPD_ieee8021x=1; }	/* WPA					*/
else if	($authtype==3) { $HAPD_wpa=1; $HAPD_ieee8021x=0; }	/* WPA-PSK				*/
else if	($authtype==4) { $HAPD_wpa=2; $HAPD_ieee8021x=1; }	/* WPA2					*/
else if	($authtype==5) { $HAPD_wpa=2; $HAPD_ieee8021x=0; }	/* WPA2-PSK				*/
else if	($authtype==6) { $HAPD_wpa=3; $HAPD_ieee8021x=1; }	/* WPA+WPA2				*/
else if	($authtype==7) { $HAPD_wpa=3; $HAPD_ieee8021x=0; }	/* WPA-PSK + WPA2-PSK	*/

/* Create config file for hostapd */
fwrite($HAPD_conf,  "driver=madwifi\n");
fwrite2($HAPD_conf, "eapol_key_index_workaround=0\n");
fwrite2($HAPD_conf, "logger_syslog=0\nlogger_syslog_level=0\nlogger_stdout=0\nlogger_stdout_level=0\ndebug=0\n");

fwrite2($HAPD_conf, "interface=".$HAPD_interface."\n");
fwrite2($HAPD_conf, "bridge="	.$HAPD_bridge	."\n");
fwrite2($HAPD_conf, "ssid="		.$ssid			."\n");
fwrite2($HAPD_conf, "wpa="		.$HAPD_wpa		."\n");
fwrite2($HAPD_conf, "ieee8021x=".$HAPD_ieee8021x."\n");
fwrite2($HAPD_conf, "wps="		.$HAPD_wps		."\n");

/* Generate WPS config */
if ($HAPD_wps==1)
{
	fwrite2($HAPD_conf, "start_enrollee_cmd=/etc/templates/wps.sh eap:registrar &\n");
	fwrite2($HAPD_conf, "start_registrar_cmd=/etc/templates/wps.sh eap:enrollee &\n");
	fwrite2($HAPD_conf, "eap_user_file=".$HAPD_eapuserfile."\n");

	fwrite ($HAPD_eapuserfile, "\"WFA-SimpleConfig-Registrar-1-0\" WPS\n");
	fwrite2($HAPD_eapuserfile, "\"WFA-SimpleConfig-Enrollee-1-0\" WPS\n");
}

if ($HAPD_wpa > 0)
{
	if ($rkeyint!="")		{ fwrite2($HAPD_conf, "wpa_group_rekey=".$rkeyint."\n");}
	if		($encrtype==2)	{ fwrite2($HAPD_conf, "wpa_pairwise=TKIP\n");		}
	else if	($encrtype==3)	{ fwrite2($HAPD_conf, "wpa_pairwise=CCMP\n");		}
	else if	($encrtype==4)	{ fwrite2($HAPD_conf, "wpa_pairwise=TKIP CCMP\n");	}

	if ($HAPD_ieee8021x == 1)
	{
		fwrite2($HAPD_conf, "wpa_key_mgmt=WPA-EAP\n");
		fwrite2($HAPD_conf, "auth_server_addr=".$Rhost."\n");
		fwrite2($HAPD_conf, "auth_server_port=".$Rport."\n");
		fwrite2($HAPD_conf, "auth_server_shared_secret=".$Rsecret."\n");
	}
	else
	{
		fwrite2($HAPD_conf, "wpa_key_mgmt=WPA-PSK\n");
		if (query("wpa/format")=="1")	{fwrite2($HAPD_conf, "wpa_passphrase=".$wpapsk."\n");}
		else							{fwrite2($HAPD_conf, "wpa_psk=".$wpapsk."\n");}
	}
}
?>
