#!/bin/sh
echo [$0] ... > /dev/console
TROOT="/etc/templates"
[ ! -f $TROOT/extensions/enable_gzone_run.php ] && exit 0
case "$1" in
start|restart)
	[ -f /var/run/enable_gzone_stop.sh ] && sh /var/run/enable_gzone_stop.sh > /dev/console
	xmldbc -A $TROOT/extensions/enable_gzone_run.php -V generate_start=1 > /var/run/enable_gzone_start.sh
	xmldbc -A $TROOT/extensions/enable_gzone_run.php -V generate_start=0 > /var/run/enable_gzone_stop.sh
	sh /var/run/enable_gzone_start.sh > /dev/console
	;;
stop)
	if [ -f /var/run/enable_gzone_stop.sh ]; then
		sh /var/run/enable_gzone_stop.sh > /dev/console
		rm -f /var/run/enable_gzone_stop.sh
	fi
	;;
*)
	echo "usage: enable_gzone.sh {start|stop|restart}"
	;;
esac
