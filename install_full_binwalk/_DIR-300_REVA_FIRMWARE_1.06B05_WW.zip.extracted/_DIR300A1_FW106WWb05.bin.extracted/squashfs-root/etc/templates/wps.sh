#!/bin/sh
echo [$0] $1 $2 ... > /dev/console
TEMPLATES="/etc/templates/wifi"
WPSCONF="/var/run/wps.config"
wps_en=`xmldbc -g /wireless/wps/enable`
case "$1" in
init)
	nvram=`cat /etc/config/nvram`
	PIN=`wps -g`
	rgcfg setenv -n $nvram -e pin=$PIN
	xmldbc -i -s /runtime/wps/pin "$PIN"
	;;
setie)
	xmldbc -A $TEMPLATES/wps.config.php > $WPSCONF
	wps -c $WPSCONF -e setup > /dev/console
	;;
eap:registrar|upnp:gdi)
	xmldbc -A $TEMPLATES/wps.config.php -V PARAM=enrollee > $WPSCONF
	wps -c $WPSCONF > /dev/console &
	;;
eap:enrollee|upnp:ssr)
	xmldbc -A $TEMPLATES/wps.config.php > $WPSCONF
	wps -c $WPSCONF > /dev/console &
	;;
pin)
	if [ "$wps_en" = "1" ]; then
	usockc /var/run/fresetd_unixsock WPS_IN_PROGRESS
	echo PIN=`xmldbc -i -g /runtime/wps/enrollee/pin` > /dev/console
	xmldbc -A $TEMPLATES/wps.config.php > $WPSCONF
	wps -c $WPSCONF -e int:pin > /dev/console &
	fi
	;;
pbc)
	if [ "$wps_en" = "1" ]; then
	usockc /var/run/fresetd_unixsock WPS_IN_PROGRESS
	xmldbc -i -s /runtime/wps/enrollee/pin 00000000
	xmldbc -A $TEMPLATES/wps.config.php > $WPSCONF
	wps -c $WPSCONF -e int:pbc > /dev/console &
	fi
	;;
*)
	echo "usage: $0 [init|setie|eap:enrollee|eap:registrar|upnp:gdi|upnp:ssr|pin|pbc]" > /dev/console
	exit 9
	;;
esac
exit 0
