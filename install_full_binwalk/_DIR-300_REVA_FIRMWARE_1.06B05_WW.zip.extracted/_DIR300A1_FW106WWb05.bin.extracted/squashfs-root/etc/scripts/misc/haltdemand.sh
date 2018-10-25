#!/bin/sh
echo [$0] ... > /dev/console
if [ "`rgdb -i -g /runtime/func/lpd`" = "1" ]; then
/etc/templates/lpd.sh stop		> /dev/console
fi
# stop Neaps
/etc/templates/neaps.sh stop	> /dev/console
# stop LLD2D
/etc/templates/lld2d.sh stop	> /dev/console
# Stop WAN
/etc/templates/wan.sh stop		> /dev/console
# Stop UPNP ...
/etc/templates/upnpd.sh stop	> /dev/console
# stop fresetd ...
killall fresetd
# stop DNRD
/etc/templates/dnrd.sh stop		> /dev/console
# Stop RG
/etc/templates/rg.sh stop		> /dev/console
# Stop WLAN
/etc/templates/wlan.sh stop		> /dev/console
# Stop LAN
/etc/templates/lan.sh stop		> /dev/console
# Start to burn flash
/etc/scripts/startburning.sh	> /dev/console
