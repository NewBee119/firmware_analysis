# ppp3g >>>>
<? /* vi: set sw=4 ts=4: */
$rg_script=1;
$ppp3g_session="session1";
$keep_alive_script_file = "/var/run/keep_alive.sh";
$keep_alive_pid_file = "/var/run/keep_alive.pid";

if ($generate_start == 1)
{
	anchor("/runtime/layout");
	$wanif=query("wanif");

	/* Generate authentication info */
	echo "[ -f /etc/ppp/chap-secrets ] && rm -f /etc/ppp/chap-secrets\n";
	echo "echo -n > /etc/ppp/pap-secrets\n";
	echo "ln -s /etc/ppp/pap-secrets /etc/ppp/chap-secrets\n";

	/* Prepare ip-up ip-down */
	echo "cp ".$template_root."/ppp/ip-up /etc/ppp/.\n";
	echo "cp ".$template_root."/ppp/ip-down /etc/ppp/.\n";
	echo "cp ".$template_root."/ppp/ppp-status /etc/ppp/.\n";
	echo "if [-f ".$template_root."/ppp/keep_alive ]; then \n";
	echo "\tcp ".$template_root."/ppp/keep_alive /etc/ppp/.\n";
	echo "fi\n";

	set("/runtime/wan/inf:1/connecttype", "3");
	anchor("/runtime/wan/inf:1");
	set("ip", "");
	set("netmask", "");
	set("gateway", "");
	set("primarydns", "");
	set("secondarydns", "");
	set("mtu", "");

	$ppp_linkname	= $ppp3g_session;

	/* Query PPP parameters */
	anchor("/wan/rg/inf:1/ppp3g");
	$ppp_type			= "ppp3g";
/*	if (query("mode")==1)	{ $ppp_staticip = query("staticip"); }
	else					{ $ppp_staticip = ""; } */
	$ppp_staticip 		= "";
	$ppp_modem_type 	= query("/runtime/stats/usb/devices/driver");

	if ($ppp_modem_type == "acm")
	{
		$ppp_modem = "acm";
	}
	else if ($ppp_modem_type == "serial")
	{
		$ppp_modem = "tts";
	}
	else if ($ppp_modem_type == "serial_tty")
	{
		$ppp_modem = "tty";
	}
	else if ($ppp_modem_type == "0")
	{
		$ppp_modem = "0";	
	}
	else
	{
		$ppp_modem = "";
	}
	
	$ppp_pin			= query("pincode");
	$ppp_dialnumber		= query("dialnumber");
	$ppp_apn			= query("apn");
	$ppp_username		= get("s", "username");
	$ppp_password		= get("s", "password");
	$ppp_auth_proto		= query("authproto");
	$ppp_idle			= query("idletimeout");
	$ppp_persist		= query("autoreconnect");
	$ppp_demand			= query("ondemand");
	$ppp_usepeerdns		= 1 // query("autodns"); 
	$ppp_mtu			= query("mtu"); 
	$ppp_mru			= query("mtu"); 
	$ppp_defaultroute	= 1;
	
	/* add by bison */
        /* hendry modify */
	$ppp_reconnmode		= query("reconnmode");
//for on demand
	if ($ppp_reconnmode == 1){ $ppp_persist=1; $ppp_demand=1; }
	else if ($ppp_reconnmode == 0){ $ppp_persist=1; $ppp_demand=0; }
	else { $ppp_demand=0; $ppp_persist=0; }

	if ($ppp_demand!=1) { $ppp_idle=0; }

	echo "echo \"\\\"".$ppp_username."\\\" * \\\"".$ppp_password."\\\" *\" >> /etc/ppp/pap-secrets\n";
	require($template_root."/ppp/ppp_setup.php");
	
	/* mark by bison */
	/* unmark by hendry */
	if ($ppp_persist == 1 && $ppp_modem != "" && $ppp_modem != "0") 
{ echo "sh /var/run/ppp-".$ppp_linkname.".sh start > /dev/console\n"; }
	

	if ($ppp_demand == 1)
	{
		set("/runtime/wan/inf:1/connecttype", "3");
		anchor("/runtime/wan/inf:1");
		set("ip", "10.112.112.112");
		set("netmask", "255.255.255.255");
		set("gateway", "10.112.112.113");
		set("primarydns", "10.112.112.114");
	}
	
	/* keep alive mechanism */
	/* it works through shell script that ping server through constant interval */
	anchor("/wan/rg/inf:1/ppp3g");
	$keep_alive_ping_dst_1 = query("keepaliveserver1");	
	$keep_alive_ping_dst_2 = query("keepaliveserver2");
	if ($keep_alive_ping_dst_1 == "")
	{ $keep_alive_ping_dst_1 = "www.google.com"; }
	$keep_alive_sleep_interval = query("keepaliveint");
	if ($keep_alive_sleep_interval == "")
	{ $keep_alive_sleep_interval = 60; }
	require($template_root."/keep_alive_run.php");
	
//	echo "echo \"keep_alive_script_file=".$keep_alive_script_file."\"\n");
//	echo "echo \"keep_alive_ping_dst_1=".$keep_alive_ping_dst_1."\"\n");
//	echo "echo \"keep_alive_ping_dst_2=".$keep_alive_ping_dst_2."\"\n");
//	echo "echo \"keep_alive_sleep_interval=".$keep_alive_sleep_interval."\"\n");
	
	/* if interval is empty, we assume the user don't want the keep_alive service */
//	if ($ppp_persist == 1)
//	{ 
//		echo "sh /var/run/keep_alive.sh > /dev/console &\n"; 
//		echo "echo $! > ".$keep_alive_pid_file."\n"; 
//	}
}
else
{
	echo "sh /var/run/ppp-".$ppp3g_session.".sh stop > /dev/console\n";
//	if ($ppp_persist == 1)
//	{
//		echo "if [ -f \"".$keep_alive_pid_file."\" ]; then\n";
//		echo "\tpid=`cat ".$keep_alive_pid_file."`\n";
//		echo "\tif [ \"$pid\" != \"0\" ]; then\n";
//		echo "\t\tkill $pid > /dev/console 2>&1\n";
//		echo "\tfi\n";
//		echo "\trm -f ".$keep_alive_pid_file."\n";
//		echo "fi\n";
//	}
}
?># ppp3g <<<
