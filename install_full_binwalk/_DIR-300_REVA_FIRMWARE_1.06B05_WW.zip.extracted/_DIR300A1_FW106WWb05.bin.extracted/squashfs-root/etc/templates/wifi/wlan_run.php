#!/bin/sh
echo [$0] ... > /dev/console
<?
/* vi: set sw=4 ts=4: */
require("/etc/templates/troot.php");

$wlanif	= query("/runtime/layout/wlanif");
$lanif	= query("/runtime/layout/lanif");

if ($generate_start==1)
{
	echo "echo Start WLAN interface ".$wlanif." ... > /dev/console\n";

	if (query("/wireless/enable")!=1)
	{
		echo "echo Wireless is disabled ! > /dev/console\n";
		echo "usockc /var/run/fresetd_unixsock RADIO_OFF\n";
		exit;
	}

	/* Check if wireless mode changed. If yes, re-insert the wireless module. (for madwifi 5.0.2.46)*/
	$prev_wlmode=query("/runtime/wireless/wlanmode");
	$curr_wlmode=query("/wireless/wlanmode");
	/* Check if the country code is changed. If yes, re-insert the wireless module. */
	$prev_ccode=query("/runtime/sys/countrycode");
	$curr_ccode=query("/sys/countrycode");
	if($curr_ccode=="")
	{
		$curr_ccode=query("/runtime/nvram/countrycode");
	}

	if($prev_wlmode=="" || $prev_ccode=="")
	{
		set("/runtime/wireless/wlanmode",	$curr_wlmode);
		set("/runtime/sys/countrycode",		$curr_ccode);
	}
	else if($prev_wlmode!=$curr_wlmode || $prev_ccode!=$curr_ccode)
	{
		set("/runtime/wireless/wlanmode",	$curr_wlmode);
		set("/runtime/sys/countrycode",		$curr_ccode);
		require($template_root."/wifi/restart_wlan_driver.php");
	}

	/* Get configuration */
	anchor("/wireless");
	$ssid			= query("ssid");
	$channel		= query("channel");
	$autochannel	= query("autochannel");			if ($autochannel=="1")		{$channel="0";}
	$beaconinterval	= query("beaconinterval");		if ($beaconinterval=="")	{$beaconinterval="100";}
	$fraglength		= query("fraglength");			if ($fraglength=="")		{$fraglength="2346";}
	$rtslength		= query("rtslength");			if ($rtslength=="")			{$rtslength="2346";}
	$ssidhidden		= query("ssidhidden");			if ($ssidhidden!="1")		{$ssidhidden="0";}
	$wlmode			= query("wlanmode");			if ($wlmode=="")			{$wlmode="3";}
	$ctsmode		= query("ctsmode");				if ($ctsmode=="")			{$ctsmode="0";}
	$preamble		= query("preamble");			if ($preamble=="")			{$preamble="0";}
	$txrate			= query("txrate");
	$txpower		= query("txpower");				if ($txpower=="")			{$txpower="1";}
	$dtim			= query("dtim");				if ($dtim=="")				{$dtim="1";}
	$ar				= query("atheros/ar");			if ($ar!="1")				{$ar="0";}
	/* 0:Disabled, 1:Super G without Turbo, 2: Super G with Dynamic Turbo, 3: Super G with Static Turbo */
	$supermode		= query("atheros/supermode");	if ($supermode=="2")		{$turbo="1";}else{$turbo="0";}
	if($supermode==""){$supermode="0";}
	$xr				= query("atheros/xr");			if ($xr!="1"||$turbo=="1")	{$xr="0";}
	$wlan2wlan		= query("bridge/wlan2wlan");	if ($wlan2wlan!="0")		{$wlan2wlan="1";}
	$wlan2lan		= query("bridge/wlan2lan");		if ($wlan2lan!="0")			{$wlan2lan="1";}
	$wmm			= query("wmm");					if ($wmm!="1")				{$wmm="0";}
	$mcast_enable	= query("multicast_control/enable"); if($mcast_enable!="1")	{$mcast_enable="0";}

	if ($txrate!="0" && $txrate!="")	{ $TXRATE_CMD=$txrate."M auto\n"; }
	else								{ $TXRATE_CMD="auto\n"; }

	/* /wireless/wlanmode : 1:11b, 2:11g, 3:11b+11g, 4:11n, 5:11g+11n, 6:11g+11n, 7: 11b+11g+11n */
	/* mode: 0:auto, 1:11a, 2:11b, 3:11g, 4:11FH,
	 * 5:11a with dynamic turbo, 6:11g with tdynamic turbo, 7:11a with static turbo */
	if		($wlmode == 1) { echo "iwpriv ".$wlanif." mode 2\niwpriv ".$wlanif." pureg 0\n"; }
	else if	($wlmode == 2) { echo "iwpriv ".$wlanif." mode 3\niwpriv ".$wlanif." pureg 1\n"; }
	else if	($wlmode == 3) { echo "iwpriv ".$wlanif." mode 3\niwpriv ".$wlanif." pureg 0\n"; }
	if($mcast_enable=="1")
	{
		echo "brctl setbwctrl br0 ath0 0\n";
		$mcast_rate=query("/wireless/multicast_control/rate");
		if($mcast_rate==""){$mcast_rate="36000";}
		echo "iwpriv ".$wlanif." mcast_rate ".$mcast_rate."\n";
	}
    else
	{
		echo "iwpriv ".$wlanif." mcast_rate 1000\n";
		echo "brctl setbwctrl br0 ath0 900\n";
	}

	/* 802.11h, we didn't use it, so disable it. If it enabled, the IE will contain coutry code.*/
	echo "iwpriv "	.$wlanif." doth 0\n";
	/* generic settings ____________________________________________________ */
	echo "iwpriv "	.$wlanif." wmm "		.$wmm."\n";
	echo "iwpriv "	.$wlanif." bintval "	.$beaconinterval."\n";
	echo "iwpriv "	.$wlanif." dtim_period ".$dtim."\n";
	echo "iwpriv "	.$wlanif." hide_ssid "	.$ssidhidden."\n";
	echo "iwconfig ".$wlanif." channel "	.$channel."\n";
	echo "iwconfig ".$wlanif." rts "		.$rtslength."\n";
	echo "iwconfig ".$wlanif." frag "		.$fraglength."\n";
	echo "iwconfig ".$wlanif." rate "		.$TXRATE_CMD."\n";
	/* supper mode _________________________________________________________ */
	if ($supermode > 0)
	{
		echo "iwpriv ".$wlanif." ff "			.$supermode."\n";
		echo "iwpriv ".$wlanif." burst "		.$supermode."\n";
		echo "iwpriv ".$wlanif." compression "	.$supermode."\n";
		echo "iwpriv ".$wlanif." turbo "		.$turbo."\n";
		echo "iwpriv ".$wlanif." ar "			.$ar."\n";
	}
	/* b/g protection ______________________________________________________ */
	echo "iwpriv ".$wlanif." protmode "		.$ctsmode."\n";
	/* XR */
	echo "iwpriv ".$wlanif." xr "			.$xr."\n";
	/* WLAN/LAN bridge _____________________________________________________ */
	echo "echo ".$wlan2lan." > /proc/net/br_forward_br0\n";
	echo "iwpriv ".$wlanif." ap_bridge "	.$wlan2wlan."\n";
	/* aclmode 0:disable, 1:allow all of the list, 2:deny all of the list */
	echo "iwpriv ".$wlanif." maccmd 3\n";	// flush the ACL database.
	$aclmode=query("acl/mode");
	if ($aclmode != 1 && $aclmode != 2) { $aclmode = 0; }
	echo "iwpriv ".$wlanif." maccmd ".$aclmode."\n";
	if ($aclmode > 0)
	{
		for("/wireless/acl/mac")
		{
			$mac=query("/wireless/acl/mac:".$@);
			echo "iwpriv ".$wlanif." addmac ".$mac."\n";
		}
	}
	/* For HTTP throughput issue */
	echo "iwpriv ".$wlanif." abolt 48\n";

    /* for Txpower 1:100% 2:50% 3:25% 4:12.5% */
	echo "echo ".$txpower." > /proc/sys/dev/ath/hal/TPCstrength\n";  

	/* authentication mode _________________________________________________ */
	anchor("/wireless");
	$ssid			= query("ssid");
	$authentication	= query("authtype");
	$keylength		= query("wep/length");
	$defkey			= query("wep/defkey");
	$keyformat		= query("wep/format");
	$wpawepmode		= query("encrypttype");

	if ($authentication <= 1)
	{
		echo "iwpriv ".$wlanif." authmode 1\n";
		if ($wpawepmode == 1)
		{
			/* Now the wep key must be hex number, so using "query" is ok. */
			if($keyformat==1)   {$iw_keystring="s:\"".get("s","wep/key:".$defkey)."\" [".$defkey."]";}
			else                {$iw_keystring="\"".query("wep/key:".    $defkey)."\" [".$defkey."]";}
			echo "iwconfig ".$wlanif." key ".$iw_keystring."\n";

			/* shared-key */
			if($authentication==1)
			{
				echo "iwpriv ".$wlanif." authmode 2\n";
				echo "iwconfig ".$wlanif." key ".$iw_keystring."\n";
			}
		}
		echo "iwconfig ".$wlanif." essid \"".get("s","ssid")."\"\n";
	}

	/* Enable WPS ? */
	$HAPD_wps = 0;
	if (query("/runtime/func/wps")==1)
	{
		if (query("/wireless/wps/enable")==1)
		{
			$HAPD_wps = 1;
			$HAPD_eapuserfile = "/var/run/hostapd.wps.eap_user";
		}
		echo "sh /etc/templates/upnpd.sh restart\n";
	}

	/* Generate config file for hostapd */
	$HAPD_interface	= $wlanif;
	$HAPD_bridge	= $lanif;
	$HAPD_conf		= "/var/run/hostapd.".$HAPD_interface.".conf";
	anchor("/wireless");
	require($template_root."/wifi/hostapd_used.php");
	$hostapd_conf   = $HAPD_conf;
	echo "hostapd ".$hostapd_conf." &\n";
	echo "wlxmlpatch > /dev/console &\n";
	echo "ifconfig ".$wlanif." up\n";
	echo "usockc /var/run/fresetd_unixsock RADIO_ON\n";
	if (query("/wireless/wps/enable")=="1") { echo "/etc/templates/wps.sh setie\n"; }
	echo "echo Start WLAN interface ".$wlanif." Done !!! > /dev/console\n";
}
else
{
	echo "echo Stop WLAN interface ".$wlanif." ... > /dev/console\n";
	if (query("/wireless/wps/enable")=="1") { echo "killall wps > /dev/console\n"; }
	echo "iwconfig ".$wlanif." key off\n";
	echo "killall wlxmlpatch > /dev/null 2>&1\n";
	echo "killall hostapd > /dev/null 2>&1\n";
	echo "ifconfig ".$wlanif." down\n";
}
?>
