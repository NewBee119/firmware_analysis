#!/bin/sh
echo [$0] $1 ... > /dev/console
TEMPLATES="/etc/templates/wifi"
case "$1" in
start|restart)
	[ -f /var/run/wlan_stop.sh ] && sh /var/run/wlan_stop.sh > /dev/console
    rgdb -A $TEMPLATES/wlan_run.php -V generate_start=1 > /var/run/wlan_start.sh
    rgdb -A $TEMPLATES/wlan_run.php -V generate_start=0 > /var/run/wlan_stop.sh
    sh /var/run/wlan_start.sh > /dev/console
	;;
stop)
	if [ -f /var/run/wlan_stop.sh ]; then
    	sh /var/run/wlan_stop.sh > /dev/console
    	rm -f /var/run/wlan_stop.sh
	fi
	;;
*)
	echo "$0 [start|stop|restart]" > /dev/console
	;;
esac
