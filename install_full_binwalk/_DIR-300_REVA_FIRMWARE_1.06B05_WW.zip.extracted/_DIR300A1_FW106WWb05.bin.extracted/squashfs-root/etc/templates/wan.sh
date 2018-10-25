#!/bin/sh
echo [$0] $1 ... > /dev/console
TROOT="/etc/templates"
case "$1" in
start|restart)
	[ -f /var/run/wan_stop.sh ] && sh /var/run/wan_stop.sh > /dev/console
	sleep 1
	rgdb -A $TROOT/wan_run.php -V generate_start=1 > /var/run/wan_start.sh
	rgdb -A $TROOT/wan_run.php -V generate_start=0 > /var/run/wan_stop.sh
	sh /var/run/wan_start.sh > /dev/console
	if [ -f $TROOT/ledctrl.sh ]; then
		sh $TROOT/ledctrl.sh INET GREEN > /dev/console
	fi
	;;
stop)
	if [ -f /var/run/wan_stop.sh ]; then
		if [ -f $TROOT/ledctrl.sh ]; then
			sh $TROOT/ledctrl.sh INET ORG > /dev/console
		fi
		sh /var/run/wan_stop.sh > /dev/console
		rm -f /var/run/wan_stop.sh
	fi
	;;
*)
	echo "usage: $0 {start|stop|restart}"
	;;
esac
