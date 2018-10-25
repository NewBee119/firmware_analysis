#!/bin/sh
echo [$0] $1 ... > /dev/console
TROOT="/etc/templates"
case "$1" in
start)
	[ -f /var/run/rg_stop.sh ] && sh /var/run/rg_stop.sh > /dev/console
	rgdb -A $TROOT/rg/rg_stop.php > /var/run/rg_stop.sh
	rgdb -A $TROOT/rg/rg_start.php > /var/run/rg_start.sh
	sh /var/run/rg_start.sh > /dev/console
	;;
stop)
	if [ -f /var/run/rg_stop.sh ]; then
		sh /var/run/rg_stop.sh > /dev/console
		rm -f /var/run/rg_stop.sh
	fi
	;;
flushall|macfilter|ipfilter|firewall|vrtsrv|dmz|passthrough|misc|portt|lanchange|wanup|blocking|arp_attack|policy)
	rgdb -A $TROOT/rg/rg_$1.php > /var/run/rg_$1.sh
	sh /var/run/rg_$1.sh > /dev/console
	;;
*)
	echo "usage: rg.sh [start|stop|flushall ..." > /dev/console
	echo "              ipfilter|vrtsrv|dmz|passthrough|misc|portt| ..." > /dev/console
	echo "              lanchange|wanup|macfilter|blocking|firewall| ..." > /dev/console
	echo "              arp_attack|policy]" > /dev/console
	;;
esac
