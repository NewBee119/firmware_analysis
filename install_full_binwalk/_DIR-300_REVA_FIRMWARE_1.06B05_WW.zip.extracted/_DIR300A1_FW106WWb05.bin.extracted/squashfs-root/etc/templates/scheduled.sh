#!/bin/sh
echo [$0] $1 ... > /dev/console
TROOT="/etc/templates"
[ ! -f $TROOT/extensions/scheduled_run.php ] && exit 0
case "$1" in
start|restart)
	[ -f /var/run/scheduled_stop.sh ] && sh /var/run/scheduled_stop.sh > /dev/console
	xmldbc -A $TROOT/extensions/scheduled_run.php -V generate_start=1 > /var/run/scheduled_start.sh
	xmldbc -A $TROOT/extensions/scheduled_run.php -V generate_start=0 > /var/run/scheduled_stop.sh
	sh /var/run/scheduled_start.sh > /dev/console
	;;
stop)
	if [ -f /var/run/scheduled_stop.sh ]; then
		sh /var/run/scheduled_stop.sh > /dev/console
		rm -f /var/run/scheduled_stop.sh
	fi
	;;
*)
	echo "usage: scheduled.sh {start|stop|restart}"
	;;
esac
