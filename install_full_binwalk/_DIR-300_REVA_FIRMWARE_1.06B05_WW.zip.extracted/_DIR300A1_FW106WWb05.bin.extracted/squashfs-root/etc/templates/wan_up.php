#!/bin/sh
echo "[$0] ..." > /dev/console
rgdb -i -s /runtime/wan/inf:1/uptime `uptime seconly`
<?
/* vi: set sw=4 ts=4: */
require("/etc/templates/troot.php");
require("/etc/templates/misc/gen_resolv.php");

$rg_script=1;

$router = query("/runtime/router/enable");
if ($router != 1)
{
	$wanif = query("/runtime/wan/inf:1/interface");
	if ($wanif != "")
	{
		echo "xmldbc -x /runtime/stats/wan/inf:1/rx/bytes \"get:scut -p ".$wanif.": -f 1 /proc/net/dev\"\n";
		echo "xmldbc -x /runtime/stats/wan/inf:1/rx/packets \"get:scut -p ".$wanif.": -f 2 /proc/net/dev\"\n";
		echo "xmldbc -x /runtime/stats/wan/inf:1/tx/bytes \"get:scut -p ".$wanif.": -f 9 /proc/net/dev\"\n";
		echo "xmldbc -x /runtime/stats/wan/inf:1/tx/packets \"get:scut -p ".$wanif.": -f 10 /proc/net/dev\"\n";
	}
	/* restart klogd to update WAN interface changed. */
	$klogd_only=1;
	require($template_root."/misc/logs_run.php");

	/* update others ... when WAN is up. */
	$generate_start=1;
	require($template_root."/misc/ntp_run.php");

	/* Start IGMP proxy */
	echo "/etc/templates/igmpproxy.sh restart\n";

	/* start UPNP. */
	echo $template_root."/upnpd.sh start\n";
}
else
{
	/* Relay the Domain info to LAN's DHCP clients. */
	$generate_start = 0;
	$dhcpd_if=query("/runtime/layout/lanif");
	$dhcpd_clearleases=0;
	require($template_root."/dhcp/dhcpd.php");
	$generate_start = 1;
	require($template_root."/dhcp/dhcpd.php");

	if(query("/security/firewall/httpAllow")==1 && query("/runtime/wan/inf:1/interface")!="")
	{ echo $template_root."/webs.sh restart > /dev/console\n"; }

	echo $template_root."/rg.sh wanup > /dev/console\n";

	/* Start DNRD when WAN is up ... */
	echo $template_root."/dnrd.sh restart > /dev/console\n";

	/* Start wol when WAN is up ... */
	echo $template_root."/wol.sh restart > /dev/console\n";

	/* Static route */
	echo $template_root."/route.sh restart > /dev/console\n";

	/* restart runtimed to update WAN interface changed. */
	$wanif = query("/runtime/wan/inf:1/interface");
	if ($wanif != "")
	{
		echo "xmldbc -x /runtime/stats/wan/inf:1/rx/bytes \"get:scut -p ".$wanif.": -f 1 /proc/net/dev\"\n";
		echo "xmldbc -x /runtime/stats/wan/inf:1/rx/packets \"get:scut -p ".$wanif.": -f 2 /proc/net/dev\"\n";
		echo "xmldbc -x /runtime/stats/wan/inf:1/tx/bytes \"get:scut -p ".$wanif.": -f 9 /proc/net/dev\"\n";
		echo "xmldbc -x /runtime/stats/wan/inf:1/tx/packets \"get:scut -p ".$wanif.": -f 10 /proc/net/dev\"\n";
	}

	/* restart klogd to update WAN interface changed. */
	$klogd_only=1;
	require($template_root."/misc/logs_run.php");

	/* update others ... when WAN is up. */
	$generate_start=1;
	require($template_root."/misc/ntp_run.php");
	require($template_root."/misc/dyndns_run.php");

	/* Kick upnpd to send notify. */
	echo "echo Kicking UPNPD ... > /dev/console\n";
	echo $template_root."/upnpd/NOTIFY.WANIPConnection.1.sh\n";
	echo "killall -SIGUSR1 upnpd\n";

	/* Start IGMP proxy */
	echo "/etc/templates/igmpproxy.sh restart\n";

	/* Start QOS */
	echo "/etc/templates/qos.sh restart\n";
	/*start hwnat if we have*/
	echo "[ -f ".$template_root."/hw_nat.sh ] && ".$template_root."/hw_nat.sh restart > /dev/console";
	
	/*firmcheck*/
	$rumtime_checkfw=query("/runtime/func/checkfw");
	if($rumtime_checkfw != "")
	{
		echo "sh /etc/templates/firmware.sh \n";
	}
}
?>
