#!/bin/sh
echo [$0] ... > /dev/console
TROOT=`rgdb -i -g /runtime/template_root`
[ "$TROOT" = "" ] && TROOT="/etc/templates"
case "$1" in
start|restart)
	[ -f /var/run/upnpd_stop.sh ] && sh /var/run/upnpd_stop.sh > /dev/console
	xmldbc -A $TROOT/upnpd/upnpd_run.php -V generate_start=1 > /var/run/upnpd_start.sh
	xmldbc -A $TROOT/upnpd/upnpd_run.php -V generate_start=0 > /var/run/upnpd_stop.sh
	sh /var/run/upnpd_start.sh > /dev/console
	;;
stop)
	if [ -f /var/run/upnpd_stop.sh ]; then
		sh /var/run/upnpd_stop.sh > /dev/console
		rm -f /var/run/upnpd_stop.sh
	fi
	;;
*)
	echo "usage: [$0] {start|stop|restart}"
	;;
esac
