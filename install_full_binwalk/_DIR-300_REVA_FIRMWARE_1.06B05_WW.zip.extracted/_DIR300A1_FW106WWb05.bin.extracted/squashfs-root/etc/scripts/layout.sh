#!/bin/sh
echo [$0] $1 ... > /dev/console
case "$1" in
start)
	[ -f /var/run/layout_stop.sh ] && sh /var/run/layout_stop.sh > /dev/console
	rgdb -A /etc/scripts/layout_run.php -V generate_start=1 > /var/run/layout_start.sh
	rgdb -A /etc/scripts/layout_run.php -V generate_start=0 > /var/run/layout_stop.sh
	sh /var/run/layout_start.sh > /dev/console
	;;
stop)
	[ -f /var/run/layout_stop.sh ] && sh /var/run/layout_stop.sh > /dev/console
	rm -f /var/run/layout_stop.sh
	;;
*)
	echo "Usage: $0 {start|stop}"
	;;
esac
