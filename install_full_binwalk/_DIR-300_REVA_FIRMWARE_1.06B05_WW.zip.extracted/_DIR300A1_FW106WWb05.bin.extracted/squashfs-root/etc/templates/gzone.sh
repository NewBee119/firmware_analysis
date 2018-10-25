#!/bin/sh
echo [$0] ... > /dev/console
TROOT="/etc/templates"
[ ! -f $TROOT/extensions/lld2d_run.php ] && exit 0
case "$1" in
start|restart)
	[ -f /var/run/gzone_stop.sh ] && sh /var/run/gzone_stop.sh > /dev/console
	xmldbc -A $TROOT/extensions/gzone_run.php -V generate_start=1 > /var/run/gzone_start.sh
	xmldbc -A $TROOT/extensions/gzone_run.php -V generate_start=0 > /var/run/gzone_stop.sh
	sh /var/run/gzone_start.sh > /dev/console
	;;
stop)
	if [ -f /var/run/gzone_stop.sh ]; then
		sh /var/run/gzone_stop.sh > /dev/console
		rm -f /var/run/gzone_stop.sh
	fi
	;;
*)
	echo "usage: gzone.sh {start|stop|restart}"
	;;
esac
