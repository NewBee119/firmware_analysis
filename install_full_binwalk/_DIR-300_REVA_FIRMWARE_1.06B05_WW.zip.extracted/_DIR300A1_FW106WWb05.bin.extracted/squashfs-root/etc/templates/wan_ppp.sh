#!/bin/sh
echo [$0] $1 $2 $3 ... > /dev/console
TROOT=`rgdb -i -g /runtime/template_root`
[ "$TROOT" = "" ] && TROOT="/etc/templates"
case "$1" in
start|restart)
	[ -f /var/run/wan_ppp_stop.sh ] && sh /var/run/wan_ppp_stop.sh > /dev/console
	rgdb -A $TROOT/wan_ppp_run.php -V generate_start=1 -V server=$2 -V phy_method=$3 > /var/run/wan_ppp_start.sh
	rgdb -A $TROOT/wan_ppp_run.php -V generate_start=0 > /var/run/wan_ppp_stop.sh
	sh /var/run/wan_ppp_start.sh > /dev/console
	;;
stop)
	if [ -f /var/run/wan_ppp_stop.sh ]; then
		sh /var/run/wan_ppp_stop.sh > /dev/console
		rm -f /var/run/wan_ppp_stop.sh
	fi
	;;
*)
	echo "usage: wan_ppp.sh {start|stop} [server ip]"
	;;
esac
