#!/bin/sh
echo [$0] $1 ... > /dev/console
TROOT="/etc/templates"
case "$1" in
start|restart)
	[ -f /var/run/neaps_stop.sh ] && sh /var/run/neaps_stop.sh > /dev/console
	xmldbc -A $TROOT/neaps/neaps_run.php -V generate_start=1 > /var/run/neaps_start.sh
	xmldbc -A $TROOT/neaps/neaps_run.php -V generate_stop=1  > /var/run/neaps_stop.sh
	xmldbc -A $TROOT/neaps/neaps_conf.php > /var/run/neaps.conf
	sh /var/run/neaps_start.sh > /dev/console
	;;
stop)
	if [ -f /var/run/neaps_stop.sh ]; then
		sh /var/run/neaps_stop.sh
		rm -f /var/run/neaps_stop.sh
	fi
	;;
*)
	echo "usage: lan.sh {start|stop|restart}"
	;;
esac
