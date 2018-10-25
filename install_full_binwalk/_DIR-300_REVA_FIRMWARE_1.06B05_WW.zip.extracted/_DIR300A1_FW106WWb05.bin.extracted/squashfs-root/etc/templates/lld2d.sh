#!/bin/sh
echo [$0] ... > /dev/console
TROOT="/etc/templates"
[ ! -f $TROOT/extensions/lld2d_run.php ] && exit 0
case "$1" in
start|restart)
	[ -f /var/run/lld2d_stop.sh ] && sh /var/run/lld2d_stop.sh > /dev/console
	rgdb -A $TROOT/extensions/lld2d_run.php -V generate_start=1 > /var/run/lld2d_start.sh
	rgdb -A $TROOT/extensions/lld2d_run.php -V generate_start=0 > /var/run/lld2d_stop.sh
	sh /var/run/lld2d_start.sh > /dev/console
	;;
stop)
	if [ -f /var/run/lld2d_stop.sh ]; then
		sh /var/run/lld2d_stop.sh > /dev/console
		rm -f /var/run/lld2d_stop.sh
	fi
	;;
*)
	echo "usage: lan.sh {start|stop|restart}"
	;;
esac
