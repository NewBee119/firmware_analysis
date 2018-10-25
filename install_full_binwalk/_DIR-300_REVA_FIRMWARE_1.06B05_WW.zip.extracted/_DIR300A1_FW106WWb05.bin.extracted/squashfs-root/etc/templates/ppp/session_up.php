#!/bin/sh
echo [$0] $1 $2 ... > /dev/console
<? /* vi: set sw=4 ts=4: */
require("/etc/templates/troot.php");
$rg_script=1;

$wanif=query("/runtime/wan/inf:1/interface");
if ($wanif!="")
{
	$usepeerdns=query("/runtime/ppp/".$session."/usepeerdns");
	if ($usepeerdns==1)
	{
		$dns1=query("/runtime/wan/inf:1/primarydns");
		$dns2=query("/runtime/wan/inf:1/secondarydns");
	}
	else
	{
		$dns1=query("/dnsrelay/server/primarydns");
		$dns2=query("/dnsrelay/server/secondarydns");
		set("/runtime/wan/inf:1/primarydns", $dns1);
		set("/runtime/wan/inf:1/secondarydns", $dns2);
	}
	require("/etc/templates/misc/gen_resolv.php");

	echo "xmldbc -x /runtime/stats/wan/inf:1/rx/bytes \"get:scut -p ".$wanif.": -f 1 /proc/net/dev\"\n";
	echo "xmldbc -x /runtime/stats/wan/inf:1/rx/packets \"get:scut -p ".$wanif.": -f 2 /proc/net/dev\"\n";
	echo "xmldbc -x /runtime/stats/wan/inf:1/tx/bytes \"get:scut -p ".$wanif.": -f 9 /proc/net/dev\"\n";
	echo "xmldbc -x /runtime/stats/wan/inf:1/tx/packets \"get:scut -p ".$wanif.": -f 10 /proc/net/dev\"\n";
}
if (query("/runtime/func/wanactivetime") == "1")
{
	if(query("/wan/rg/inf:1/mode") == "3" || query("/wan/rg/inf:1/mode") == "6")
	{
		echo "rgdb -i -s /runtime/wan/inf:1/ppp/uptime `uptime seconly`\n";
		echo "rgdb -i -s /runtime/wan/inf:1/ppp/activetime/enable \"1\"\n";
	}
}

if(query("/security/firewall/httpAllow")==1 && query("/runtime/wan/inf:1/interface")!="")
{ echo $template_root."/webs.sh restart > /dev/console\n"; }

echo $template_root."/rg.sh wanup > /dev/console\n";

/* Start DNRD when WAN is up ... */
echo $template_root."/dnrd.sh restart > /dev/console\n";

/* Start wol when WAN is up ... */
echo $template_root."/wol.sh restart > /dev/console\n";

/* Static route */
echo $template_root."/route.sh restart > /dev/console\n";

if ($OnDemand!="1")
{
	/* Relay the Domain info to LAN's DHCP clients. */
	$generate_start = 0;
	$dhcpd_if=query("/runtime/layout/lanif");
	$dhcpd_clearleases=0;
	require($template_root."/dhcp/dhcpd.php");
	$generate_start = 1;
	require($template_root."/dhcp/dhcpd.php");

	if(query("/security/firewall/httpAllow")==1 && query("/runtime/wan/inf:1/interface")!="")
	{
		echo $template_root."/webs.sh restart > /dev/console\n";
	}

	/* restart klogd to update WAN interface changed. */
	$klogd_only=1;
	require($template_root."/misc/logs_run.php");

	/* update others ... when WAN is up. */
	$generate_start=1;
	require($template_root."/misc/ntp_run.php");
	require($template_root."/misc/dyndns_run.php");

	/* kick upnpd to send notify. */
	echo "echo Kicking UPNPD ... > /dev/console\n";
	echo $template_root."/upnpd/NOTIFY.WANIPConnection.1.sh\n";
	echo "killall -SIGUSR1 upnpd\n";

	/* Start IGMP proxy */
	echo "/etc/templates/igmpproxy.sh restart\n";

    /* Start QOS */
	echo "/etc/templates/qos.sh restart\n";
	
	echo "[ -f ".$template_root."/hw_nat.sh ] && ".$template_root."/hw_nat.sh restart > /dev/console"; 

}

?>
echo "[$0] $1 $2 (done) ..." > /dev/console
