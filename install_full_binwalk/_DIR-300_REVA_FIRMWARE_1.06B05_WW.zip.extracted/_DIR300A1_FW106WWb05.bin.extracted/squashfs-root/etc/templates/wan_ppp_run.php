#!/bin/sh
echo [$0] $1 ... > /dev/console
<?
/* vi: set sw=4 ts=4: */
require("/etc/templates/troot.php");
$rg_script=1;
$timeset=query("/runtime/timeset");

$wanmode = query("/wan/rg/inf:1/mode");
if		($wanmode == "4")	{ anchor("/wan/rg/inf:1/pptp"); $PPPMODE="PPTP"; }
else if	($wanmode == "5")	{ anchor("/wan/rg/inf:1/l2tp"); $PPPMODE="L2TP"; }
else
{
	echo "echo \"Not in PPTP/L2TP mode ...\" > /dev/console\n";
	exit;
}

$ppp_linkname = "session1";

if ($generate_start==1)
{
	if ($server == "")
	{
		echo "echo No server available ... > /dev/console\n";
		exit;
	}

	/* generate authentication info */
	echo "[ -f /etc/ppp/chap-secrets ] && rm -f /etc/ppp/chap-secrets\n";
	echo "echo -n > /etc/ppp/pap-secrets\n";
	echo "ln -s /etc/ppp/pap-secrets /etc/ppp/chap-secrets\n";

	/* Prepare ip-up, ip-down and ppp-status */
	echo "cp ".$template_root."/ppp/ip-up /etc/ppp/.\n";
	echo "cp ".$template_root."/ppp/ip-down /etc/ppp/.\n";
	echo "cp ".$template_root."/ppp/ppp-status /etc/ppp/.\n";


	/* query ppp parameters */
	if ($PPPMODE == "PPTP")	{ $ppp_type = "pptp"; $pptp_server=$server; }
	else					{ $ppp_type = "l2tp"; $l2tp_server=$server; }
	$ppp_staticip	= "";
	$ppp_username	= get("s", "user");
	$ppp_password	= get("s", "password");
	$ppp_idle		= query("idletimeout");
	$ppp_persist	= query("autoreconnect");
	$ppp_demand		= query("ondemand");
	$ppp_schedule	= query("schedule/id");
	$ppp_usepeerdns	= "1";	//query("autodns");
	if(query("/runtime/func/router_netbios")==1) { $ppp_usepeerwins= "1"; }
	$ppp_mtu		= query("mtu");
	$ppp_mru		= query("mtu");
	$ppp_defaultroute = 1;
	if ($ppp_demand!=1)	{ $ppp_idle=0; }

	/* During DHCP mode, we will renew the IP and trigger PPP connection again, instead of using ppp-loop. */
	if ($phy_method=="DHCP") { $ppp_on_dhcp=1; } else { $ppp_on_dhcp=0; }

	echo "echo \"\\\"".$ppp_username."\\\" * \\\"".$ppp_password."\\\" *\" >> /etc/ppp/pap-secrets\n";
	require($template_root."/ppp/ppp_setup.php");
	if ($ppp_persist == 1) { echo "sh /var/run/ppp-".$ppp_linkname.".sh start > /dev/console\n"; }
	if ($ppp_demand == 1)
	{
		if ($PPPMODE == "PPTP")	{ set("/runtime/wan/inf:1/connecttype", "4"); }
		else					{ set("/runtime/wan/inf:1/connecttype", "5"); }
		anchor("/runtime/wan/inf:1");
		set("ip", "10.112.112.112");
		set("netmask", "255.255.255.255");
		set("gateway", "10.112.112.113");
		set("primarydns", "10.112.112.114");
	}
	if($ppp_schedule != "" && $ppp_schedule != 0)
	{
		set("scheduled", "1");
		$sch_sock_path="/var/run/schedule_usock";
		$UNIQUEID=$ppp_schedule;
		require("/etc/templates/rg/__schedule.php");
		$sch_cmd ="usockc ".$sch_sock_path." \"act=add";
		if( $timeset != 1){$sch_cmd=$sch_cmd." et=1";}
		$sch_cmd=$sch_cmd." start=".$START." end=".$END." days=".$DAYS." cmd=[sh /var/run/ppp-".$ppp_linkname.".sh]\"\n";
		echo $sch_cmd." > /dev/console\n";
	}
}
else
{
	if ($PPPMODE == "PPTP")	{ $ppp_type = "pptp"; }
	else					{ $ppp_type = "l2tp"; }
	$ppp_schedule   = query("/wan/rg/inf:1/".$ppp_type."/schedule/id");
	if($ppp_schedule != "" && $ppp_schedule != 0)
	{
		set("/runtime/wan/inf:1/scheduled", "0");
		$sch_sock_path="/var/run/schedule_usock";
		$UNIQUEID=$ppp_schedule;
		require("/etc/templates/rg/__schedule.php");
		$sch_cmd ="usockc ".$sch_sock_path." \"act=del cmd=[sh /var/run/ppp-".$ppp_linkname.".sh]\"\n";
		echo $sch_cmd." > /dev/console\n";
	}
	echo "echo Stop WAN ".$PPPMODE." ... > /dev/console\n";
	echo "sh /var/run/ppp-".$ppp_linkname.".sh stop > /dev/console\n";
}
?>
