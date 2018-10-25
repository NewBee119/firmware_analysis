#!/bin/sh
echo [$0] $1 ... > /dev/console
TROOT="/etc/templates"
[ ! -f $TROOT/extensions/qos_run.php ] && exit 0
case "$1" in
start|restart)
	[ -f /var/run/qos_stop.sh ] && sh /var/run/qos_stop.sh > /dev/console
	xmldbc -A $TROOT/extensions/qos_run.php -V generate_start=1 > /var/run/qos_start.sh
	xmldbc -A $TROOT/extensions/qos_run.php -V generate_start=0 > /var/run/qos_stop.sh
	sleep 2
	sh /var/run/qos_start.sh > /dev/console
	;;
stop)
	if [ -f /var/run/qos_stop.sh ]; then
		sh /var/run/qos_stop.sh > /dev/console
		rm -f /var/run/qos_stop.sh
	fi
	;;
*)
	echo "usage: qos.sh {start|stop|restart}"
	;;
esac
