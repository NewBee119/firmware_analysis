#!/bin/sh
echo [$0] ... > /dev/console
TROOT=`rgdb -i -g /runtime/template_root`
[ "$TROOT" = "" ] && TROOT="/etc/templates"
rgdb -A $TROOT/dhcp/dhcpd_restart.php > /var/run/dhcpd_restart.sh
sh /var/run/dhcpd_restart.sh
sh $TROOT/gzone.sh restart
