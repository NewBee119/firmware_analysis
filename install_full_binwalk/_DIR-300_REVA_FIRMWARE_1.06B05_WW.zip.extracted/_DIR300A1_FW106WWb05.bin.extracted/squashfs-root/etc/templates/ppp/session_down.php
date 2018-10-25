#!/bin/sh
echo [$0] $1 ... > /dev/console
<?
/* vi: set sw=4 ts=4: */
require("/etc/templates/troot.php");
?>
rm -f /var/etc/ppp/resolv.conf.$1 > /dev/console
<?
if (query("/runtime/func/wanactivetime") == "1" && query("/runtime/wan/inf:1/ppp/activetime/enable") == "1")
{
	$UP_TIME        = query("/runtime/wan/inf:1/ppp/uptime");
	$SYS_TIME       = query("/runtime/sys/uptime");
	$OLD_TIME       = query("/runtime/wan/inf:1/connecttime");
	if ($UP_TIME != ""){ $CONN_TIME = $SYS_TIME - $UP_TIME;}
		else {$CONN_TIME = 0;}

	if ($OLD_TIME != ""){ $OLD_TIME = $CONN_TIME + $OLD_TIME;}
		else { $OLD_TIME = $CONN_TIME; }
	set("/runtime/wan/inf:1/connecttime", $OLD_TIME);
	echo "rgdb -i -s /runtime/wan/inf:1/ppp/uptime \"\"\n";
	echo "rgdb -i -s /runtime/wan/inf:1/ppp/activetime/enable \"\"\n";
}

echo "xmldbc -x /runtime/stats/wan/inf:1/rx/bytes \"get:\"\n";
echo "xmldbc -x /runtime/stats/wan/inf:1/rx/packets \"get:\"\n";
echo "xmldbc -x /runtime/stats/wan/inf:1/tx/bytes \"get:\"\n";
echo "xmldbc -x /runtime/stats/wan/inf:1/tx/packets \"get:\"\n";

echo $template_root."/route.sh stop > /dev/console\n";
echo $template_root."/dnrd.sh restart > /dev/console\n";

$generate_start=0;
require($template_root."/misc/ntp_run.php");
require($template_root."/misc/dyndns_run.php");

/* kick upnpd to send notify */
echo "echo Kicking UPNPD ... > /dev/console\n";
echo $template_root."/upnpd/NOTIFY.WANIPConnection.1.sh\n";
echo "killall -SIGUSR1 upnpd\n";	/* This is for the old IGD daemon. */

/* Stop IGMP proxy */
echo "/etc/templates/igmpproxy.sh stop\n";
echo "[ -f ".$template_root."/hw_nat.sh ] && ".$template_root."/hw_nat.sh stop > /dev/console"; 
/* Disable ip_forward */
echo "echo 0 > /proc/sys/net/ipv4/ip_forward\n";
/* Decide if enable ip_forward */
echo "/etc/templates/rg.sh misc\n");
?>
