#!/bin/sh
echo [$0] $1 ... > /dev/console
TROOT="/etc/templates"
[ ! -f $TROOT/extensions/igmpproxy_run.php ] && exit 0
case "$1" in
start|restart)
	[ -f /var/run/igmpproxy_stop.sh ] && sh /var/run/igmpproxy_stop.sh > /dev/console
	xmldbc -A $TROOT/extensions/igmpproxy_run.php -V generate_start=1 > /var/run/igmpproxy_start.sh
	xmldbc -A $TROOT/extensions/igmpproxy_run.php -V generate_start=0 > /var/run/igmpproxy_stop.sh
	sh /var/run/igmpproxy_start.sh > /dev/console
	;;
stop)
	if [ -f /var/run/igmpproxy_stop.sh ]; then
		sh /var/run/igmpproxy_stop.sh > /dev/console
		rm -f /var/run/igmpproxy_stop.sh
	fi
	;;
*)
	echo "usage: igmpproxy.sh {start|stop|restart}"
	;;
esac
