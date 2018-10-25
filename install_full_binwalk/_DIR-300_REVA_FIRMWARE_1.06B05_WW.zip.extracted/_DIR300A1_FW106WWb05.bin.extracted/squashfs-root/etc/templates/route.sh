#!/bin/sh
echo [$0] $1 ... > /dev/console
TROOT="/etc/templates"
case "$1" in
start|restart)
	[ -f /var/run/route_stop.sh ] && sh /var/run/route_stop.sh > /dev/console
	xmldbc -A $TROOT/misc/route_run.php -V generate_start=1 > /var/run/route_start.sh
	xmldbc -A $TROOT/misc/route_run.php -V generate_start=0 > /var/run/route_stop.sh
	sh /var/run/route_start.sh
	;;
stop)
	if [ -f /var/run/route_stop.sh ]; then
		sh /var/run/route_stop.sh
		rm -f /var/run/route_stop.sh
	fi
	;;
*)
	echo "usage: route.sh {start|stop|restart}"
	;;
esac
