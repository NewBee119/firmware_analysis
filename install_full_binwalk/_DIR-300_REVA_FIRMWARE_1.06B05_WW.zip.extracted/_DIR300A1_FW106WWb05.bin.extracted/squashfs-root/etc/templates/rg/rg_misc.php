#!/bin/sh
echo [$0] ...> /dev/console
echo 0 > /proc/sys/net/ipv4/ip_forward
<?
/* vi: set sw=4 ts=4: */
require("/etc/templates/troot.php");
$rg_script=1;

/* Get configuration */
require($template_root."/rg/flush_readconfig.php");

require($template_root."/rg/flush_misc.php");
require($template_root."/rg/flush_main.php");
?>

stun_enable=`rgdb -i -g /runtime/func/stun/enabled`
if [ "$stun_enable" = "1" ]; then
	delay=`iptables -t nat -L POSTROUTING -n|grep STUN`
	if [ "$delay" != "" ]; then 
		echo 1 > /proc/sys/net/ipv4/ip_forward
	fi
else
	delay=`iptables -t nat -L POSTROUTING -n|grep MASQUERADE`
	if [ "$delay" != "" ]; then 
		echo 1 > /proc/sys/net/ipv4/ip_forward
	fi
fi
