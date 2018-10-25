#!/bin/sh
echo "[$0] ..." > /dev/console
rgdb -i -s /runtime/wan/inf:1/uptime ""
<?
/* vi: set sw=4 ts=4: */
require("/etc/templates/troot.php");
$rg_script = 1;

$router = query("/runtime/router/enable");
if ($router != 1)
{
	echo "xmldbc -x /runtime/stats/wan/inf:1/rx/bytes \"get:\"\n";
	echo "xmldbc -x /runtime/stats/wan/inf:1/rx/packets \"get:\"\n";
	echo "xmldbc -x /runtime/stats/wan/inf:1/tx/bytes \"get:\"\n";
	echo "xmldbc -x /runtime/stats/wan/inf:1/tx/packets \"get:\"\n";
	echo $template_root."/upnpd.sh stop\n";
}
else
{
	/* Stop bpalogin */
	echo "echo Stoping bpalogin ... > /dev/console\n";
	echo "killall bpalogin > /dev/console\n";

	echo "xmldbc -x /runtime/stats/wan/inf:1/rx/bytes \"get:\"\n";
	echo "xmldbc -x /runtime/stats/wan/inf:1/rx/packets \"get:\"\n";
	echo "xmldbc -x /runtime/stats/wan/inf:1/tx/bytes \"get:\"\n";
	echo "xmldbc -x /runtime/stats/wan/inf:1/tx/packets \"get:\"\n";

	/* Static route */
	echo $template_root."/route.sh stop > /dev/console\n";

	/* Stop DDNS... */
	$generate_start=0;
	require($template_root."/misc/ntp_run.php");
	require($template_root."/misc/dyndns_run.php");

	/* kick upnpd to send notify */
	echo "echo Kicking UPNPD ... > /dev/console\n";
	echo $template_root."/upnpd/NOTIFY.WANIPConnection.1.sh\n";
	echo "killall -SIGUSR1 upnpd\n";

	/* Stop IGMP proxy */
	echo "/etc/templates/igmpproxy.sh stop\n";

	/* Disable ip_forward */
	echo "echo 0 > /proc/sys/net/ipv4/ip_forward\n";
	/*Disable hw nat if we have*/
	echo "[ -f ".$template_root."/hw_nat.sh ] && ".$template_root."/hw_nat.sh stop > /dev/console";
	
}
?>
