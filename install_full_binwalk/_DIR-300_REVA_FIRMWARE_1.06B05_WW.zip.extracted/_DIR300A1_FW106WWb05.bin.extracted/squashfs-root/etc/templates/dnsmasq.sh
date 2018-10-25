#!/bin/sh
echo [$0] ... > /dev/console
TROOT=`rgdb -i -g /runtime/template_root`
[ "$TROOT" = "" ] && TROOT="/etc/templates"
case "$1" in
start|restart)
	[ -f /var/run/dnsmasq_stop.sh ] && sh /var/run/dnsmasq_stop.sh > /dev/console
	rgdb -A $TROOT/misc/dnsmasq_run.php -V generate_start=1 > /var/run/dnsmasq_start.sh
	rgdb -A $TROOT/misc/dnsmasq_run.php -V generate_start=0 > /var/run/dnsmasq_stop.sh
	sh /var/run/dnsmasq_start.sh > /dev/console
	;;
stop)
	if [ -f /var/run/dnsmasq_stop.sh ]; then
		sh /var/run/dnsmasq_stop.sh > /dev/console
		rm -f /var/run/dnsmasq_stop.sh
	fi
	;;
*)
	echo "usage: dnsmasq.sh {start|stop|restart}"
	;;
esac
