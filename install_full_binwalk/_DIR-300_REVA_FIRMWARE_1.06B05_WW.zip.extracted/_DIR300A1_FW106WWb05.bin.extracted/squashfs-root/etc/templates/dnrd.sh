#!/bin/sh
echo [$0] ... > /dev/console
TROOT=`rgdb -i -g /runtime/template_root`
[ "$TROOT" = "" ] && TROOT="/etc/templates"
case "$1" in
start|restart)
	[ -f /var/run/dnrd_stop.sh ] && sh /var/run/dnrd_stop.sh > /dev/console
	rgdb -A $TROOT/misc/dnrd_run.php -V generate_start=1 > /var/run/dnrd_start.sh
	rgdb -A $TROOT/misc/dnrd_run.php -V generate_start=0 > /var/run/dnrd_stop.sh
	sh /var/run/dnrd_start.sh > /dev/console
	;;
stop)
	if [ -f /var/run/dnrd_stop.sh ]; then
		sh /var/run/dnrd_stop.sh > /dev/console
		rm -f /var/run/dnrd_stop.sh
	fi
	;;
*)
	echo "usage: dnrd.sh {start|stop|restart}"
	;;
esac
