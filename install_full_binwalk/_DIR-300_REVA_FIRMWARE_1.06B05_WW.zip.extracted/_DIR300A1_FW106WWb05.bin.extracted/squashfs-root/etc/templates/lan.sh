#!/bin/sh
echo [$0] ... > /dev/console
TROOT="/etc/templates"
case "$1" in
start|restart)
	[ -f /var/run/lan_stop.sh ] && sh /var/run/lan_stop.sh > /dev/console
	rgdb -A $TROOT/lan_run.php -V generate_start=1 > /var/run/lan_start.sh
	rgdb -A $TROOT/lan_run.php -V generate_start=0 > /var/run/lan_stop.sh
	sh /var/run/lan_start.sh > /dev/console
	;;
stop)
	if [ -f /var/run/lan_stop.sh ]; then
		sh /var/run/lan_stop.sh > /dev/console
		rm -f /var/run/lan_stop.sh
	fi
	;;
delay_restart)
	sleep 2
	[ -f /var/run/lan_stop.sh ] && sh /var/run/lan_stop.sh > /dev/console
	rgdb -A $TROOT/lan_run.php -V generate_start=1 > /var/run/lan_start.sh
	rgdb -A $TROOT/lan_run.php -V generate_start=0 > /var/run/lan_stop.sh
	sh /var/run/lan_start.sh > /dev/console
	;;
*)
	echo "usage: lan.sh {start|stop|restart|delay_restart}"
	;;
esac
