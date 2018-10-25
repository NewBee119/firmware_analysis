# ppp_setup.php >>>
<?
/* vi: set sw=4 ts=4:
 *
 * The pap-secrets and chap-secrets will not be generated in this script.
 * Caller should prepare the secret files.
 *
 * The caller should also generate the ip-up and ip-down scripts in /etc/ppp
 */
if ($ppp_linkname == "")
{
	echo "echo No link name specified !!!! > /dev/console\n";
	exit;
}

/* Generate options file */
$options_file="/etc/ppp/options.".$ppp_linkname;
if ($ppp_mtu=="") { $ppp_mtu="1492"; }
if ($ppp_mru=="") { $ppp_mru="1492"; }
if (query("/wan/rg/inf:2/mode") == "1" || query("/wan/rg/inf:2/mode") == "2")
{
	$russia_pppoe = "1";
}
if (query("/wan/rg/inf:1/pptp/physical") == "1")
{
	$russia_pptp = "1";
}
$pppoe_mppe = query("/wan/rg/inf:1/pppoe/mppe/enable");
$pptp_mppe = query("/wan/rg/inf:1/pptp/mppe/enable");

fwrite( $options_file, "noauth nodeflate nobsdcomp nodetach");
if ($russia_pppoe == "1" || $russia_pptp == "1") 
{
	if ($pppoe_mppe == "1" || $pptp_mppe == "1")
	{ fwrite2( $options_file,"\n"); } else { fwrite2( $options_file," noccp\n"); } 
} else { fwrite2( $options_file," noccp\n"); }

if (query("/runtime/ppp_debug")==1) { fwrite2($options_file, "debug dump logfd 1\n"); }
fwrite2($options_file, "lcp-echo-failure 3\n");
fwrite2($options_file, "lcp-echo-interval 30\n");
fwrite2($options_file, "lcp-echo-failure-2 14\n");
fwrite2($options_file, "lcp-echo-interval-2 6\n");
fwrite2($options_file, "lcp-timeout-1 10\n");
fwrite2($options_file, "lcp-timeout-2 10\n");
fwrite2($options_file, "ipcp-accept-remote ipcp-accept-local\n");
fwrite2($options_file, "mtu ".$ppp_mtu."\n");
fwrite2($options_file, "mru ".$ppp_mru."\n");
fwrite2($options_file, "linkname ".$ppp_linkname."\n");
fwrite2($options_file, "ipparam ".$ppp_linkname."\n");

if ($ppp_username != "")					{ fwrite2($options_file, "user \"".$ppp_username."\"\n"); }
if ($ppp_defaultroute == 1)					{ fwrite2($options_file, "defaultroute\n"); }
if ($ppp_idle != "" && $ppp_idle != "0")	{ fwrite2($options_file, "idle ".$ppp_idle."\n"); }
if (query("/wan/rg/inf:1/pppoe/starspeed/enable") == "1")
{
if ($ppp_persist == 1)						{ fwrite2($options_file, "persist\nmaxfail 1\n"); }
}
else
{
if ($ppp_persist == 1)						{ fwrite2($options_file, "persist\nmaxfail 5\n"); }	
}
if ($ppp_usepeerdns == 1)					{ fwrite2($options_file, "usepeerdns\n"); }
set("/runtime/ppp/".$ppp_linkname."/usepeerdns", $ppp_usepeerdns);

if ($ppp_usepeerwins == 1)					{ fwrite2($options_file, "usepeerwins\n"); }
set("/runtime/ppp/".$ppp_linkname."/usepeerwins", $ppp_usepeerwins);

/* use statoc ip address or not */
if ($ppp_staticip != "") { $ignore_local=1; } else { $ignore_local=0; }
$ipaddr = $ppp_staticip;

if ($ppp_demand == 1)
{
	fwrite2($options_file, "demand\nconnect true\nktune\n");
	if ($ipaddr == "" || $ipaddr == "0.0.0.0") { $ipaddr = "10.112.112.112"; }
}
if ($ipaddr != "" && $ipaddr != "0.0.0.0")
{
	if ($ppp_type == "ppp3g")
	{
		fwrite2($options_file, "noipdefault\n");
	}
	else
	{
	fwrite2($options_file, $ipaddr.":10.112.112.113\n");
	}
	if ($ignore_local==1) { fwrite2($options_file, "ipcp-ignore-local\n"); }
}
else
{
	fwrite2($options_file, "noipdefault\n");
}

if ($ppp_type == "pppoe")
{
	if ($pppoe_acname != "")	{ $ACNM=" pppoe_ac_name ".$pppoe_acname; }
	if ($pppoe_service != "")	{ $SRVC=" pppoe_srv_name ".$pppoe_service; }
	fwrite2($options_file, "refuse-eap\n");
	if ($russia_pppoe == "1" && $pppoe_mppe == "1")
	{
		fwrite2($options_file, "refuse-chap\n");
		fwrite2($options_file, "refuse-mschap\n");
		fwrite2($options_file, "require-mppe\n");
	}
	fwrite2($options_file, "kpppoe pppoe_device ".$pppoe_if.$ACNM.$SRVC."\n");
	/* Starspeed (Mainland,China) */
	if ($starspeed_en == 1 && $starspeed_type != 0)
	{
		if ($user_format == 1)
		{
			fwrite2($options_file, "user-hex\n");
		}
	}

}
else if ($ppp_type == "pptp")
{
	fwrite2($options_file, "pty_pptp pptp_server_ip ".$pptp_server."\n");
	fwrite2($options_file, "name ".$ppp_username."\n");
	fwrite2($options_file, "refuse-eap\n");
	if ($russia_pptp == "1" && $pptp_mppe == "1")
	{
		fwrite2($options_file, "refuse-chap\n");
		fwrite2($options_file, "refuse-mschap\n");
		fwrite2($options_file, "require-mppe\n");
	}	
	fwrite2($options_file, "sync pptp_sync\n");
}
else if ($ppp_type == "l2tp")
{
	fwrite2($options_file, "pty_l2tp l2tp_peer ".$l2tp_server."\n");
	fwrite2($options_file, "sync l2tp_sync\n");
}
else if ($ppp_type == "ppp3g")
{
	/* Authentication protocol PAP only */
	if( $ppp_auth_proto == "2" )
	{
		fwrite2($options_file, "refuse-eap\n");
		fwrite2($options_file, "refuse-chap\n");
		fwrite2($options_file, "refuse-mschap\n");
		fwrite2($options_file, "refuse-mschap-v2\n");
	}
	/* Authentication protocol CHAP only */
	if( $ppp_auth_proto == "3" )
	{
		fwrite2($options_file, "refuse-eap\n");
		fwrite2($options_file, "refuse-pap\n");
		fwrite2($options_file, "refuse-mschap\n");
		fwrite2($options_file, "refuse-mschap-v2\n");
	}
	
	$chat_file = "/etc/ppp/".$ppp_linkname."chat.txt";
	//$dev_link = query("/runtime/stats/usb/devices/devlink");
	//if($dev_link == "") { $dev_link = "0"; }
	fwrite2($options_file, "tty_ppp3g ppp3g_chat ".$chat_file."\n");
	if( $ppp_modem != "" && $ppp_modem != "0" )
	{
		fwrite2($options_file, "modem\n");
	}
	fwrite2($options_file, "crtscts\n");
	
	$usb_modem_name=query("/runtime/alpha/usb_modem_name");
	if($usb_modem_name != "")
	{
		fwrite2($options_file, $usb_modem_name."\n");
	}
	else //for backward competibility with previous scripts
	{
		if($dev_link == "1")
	{
		fwrite2($options_file, "/dev/usb_modem_dialing_link\n");
	}
	else //for backward competibility with previous scripts
	{
		$usbmodemport=query("/runtime/alpha/usbmodemport");
		if( $usbmodemport == " " || $usbmodemport == "" ) { $usbmodemport="0"; }

		if( $ppp_modem == "acm" )
		{
			fwrite2($options_file, "/dev/usb/acm/".$usbmodemport."\n");
		}
		else if( $ppp_modem == "tts" )
		{
			fwrite2($options_file, "/dev/usb/tts/".$usbmodemport."\n");
		}
		else if( $ppp_modem == "tty" )
		{
			fwrite2($options_file, "/dev/ttyUSB".$usbmodemport."\n");
		}
	}
	} 
	
	fwrite2($options_file, "11520\n");
	fwrite2($options_file, "0.0.0.0:0.0.0.0\n");
	fwrite2($options_file, "novj\n");

	
	/* generate 3g chat script */
	//$chat_file = "/etc/ppp/".$ppp_linkname."chat.txt";
	fwrite($chat_file, "TIMEOUT 10\n");
	fwrite2($chat_file, "\'\' \\rAT\n");
	if( $ppp_pin != "" )
	{
		//ask if pin is required
		fwrite2($chat_file, "OK \\rAT+CPIN?\n");
		//expect pin READY, if not, send PIN and expect OK then send CGDCONT ... etc
		//fwrite2($chat_file, "\'\"+CPIN: READY\"-\\rAT+CPIN=\"".$ppp_pin."\"-OK\' \\rAT+CGDCONT=1,\"IP\",\"".$ppp_apn."\"\n");
		fwrite2($chat_file, "\'READY-\\rAT+CPIN=\"".$ppp_pin."\"-OK\' \\r\\d\\d\\dAT+CGDCONT=1,\"IP\",\"".$ppp_apn."\"\n");
	} 
	else
	{
	    //polo
	    $3g_qosmode = query("/3gqos/mode");
	    $3g_uprate = query("/3gqos/bandwidth/upstream");
	    $3g_downrate = query("/3gqos/bandwidth/downstream");
	    if($3g_qosmode =="1")
	    {
	   		fwrite2($chat_file,"OK \\rAT+CGEQREQ=1,2,".$3g_uprate.",".$3g_downrate."\n");				
	    }
	
		$td_type = query("/wan/rg/inf:1/ppp3g/tdcard");	
		if($td_type == "")
		{
			fwrite2($chat_file,"OK \\rAT+CFUN=1,0\n");		
		    fwrite2($chat_file, "OK \\rAT^SYSINFO\n");
		}
		else
		{
			if($td_type == "1")		//huayu td688
			{
	   	         fwrite2($chat_file,"OK \\rAT+CFUN=1,0\n");
   		         fwrite2($chat_file, "OK \\rAT^SYSINFO\n");
			}
			if($td_type == "2" || $td_type == "3")	//zhantang cg301, datang aircard901
			{
				fwrite2($chat_file,"OK \\rAT+CREG=1\n");		
				fwrite2($chat_file,"OK \\rAT+CGREG=1\n");		
			    fwrite2($chat_file, "OK \\rAT^SYSCONFIG=15,2,0,2\n");
				fwrite2($chat_file,"OK \\rAT+CFUN=1,0\n");		
			    fwrite2($chat_file, "OK \\rAT^SYSCONFIG?\n");
				fwrite2($chat_file,"OK \\rAT+CSQ\n");
			    fwrite2($chat_file, "OK \\rAT^SYSINFO\n");
			}
		}
		//polo
	    fwrite2($chat_file, "\'\' \\rAT+CGDCONT=1,\"IP\",\"".$ppp_apn."\"".",,0,0\n");
	}
	fwrite2($chat_file, "OK \\rATDT".$ppp_dialnumber."\\r\n");
	fwrite2($chat_file, "CONNECT \'\'\n");
	
	/* ppp option that calls the chat script */	
	//fwrite2($options_file, "connect \'chat -v -V -S -r /var/ppp-chat.log -f ".$chat_file."\'\n");
}
/* generate script files */
$ppp_loop_script = "/var/run/ppp-loop-".$ppp_linkname.".sh";
$ppp_script = "/var/run/ppp-".$ppp_linkname.".sh";

/* ppp loop script */
/*
 *	$ppp_persist means always on, but we do not loop forever when
 *	the phyiscal interface is using DHCP. We trigger DHCP client
 *	to renew and start PPP again when we get the IP address again.
 */
fwrite($ppp_loop_script, "#!/bin/sh\n");
if ($ppp_persist==1)
{
	if ($ppp_on_dhcp==1)
	{
		fwrite2($ppp_loop_script, "pppd file ".$options_file." > /dev/console\n");
		fwrite2($ppp_loop_script, "killall -SIGUSR1 udhcpc\n");
	}
	else
	{
		fwrite2($ppp_loop_script, "while [ 1 = 1 ]; do\n");
		fwrite2($ppp_loop_script, "pppd file ".$options_file." > /dev/console\n");
		fwrite2($ppp_loop_script, "done\n");
	}
}
else if($ppp_schedule != "" && $ppp_schedule != 0)
{
		fwrite2($ppp_loop_script, "route del default\n"); 
		fwrite2($ppp_loop_script, "while [ 1 = 1 ]; do\n");
		fwrite2($ppp_loop_script, "pppd file ".$options_file." > /dev/console\n");
		fwrite2($ppp_loop_script, "done\n");
}
else
{
	if (query("/wan/rg/inf:1/pptp/physical") == "1" || query("/wan/rg/inf:1/l2tp/physical") == "1") { fwrite2($ppp_loop_script, "route del default\n"); }
	fwrite2($ppp_loop_script, "pppd file ".$options_file." > /dev/console\n");
}

/* ppp script */
echo "rgdb -A ".$template_root."/ppp/ppp_run.php -V linkname=".$ppp_linkname." > ".$ppp_script."\n";
echo "chmod +x ".$ppp_script." ".$ppp_loop_script."\n";
?># ppp_setup.php <<<
