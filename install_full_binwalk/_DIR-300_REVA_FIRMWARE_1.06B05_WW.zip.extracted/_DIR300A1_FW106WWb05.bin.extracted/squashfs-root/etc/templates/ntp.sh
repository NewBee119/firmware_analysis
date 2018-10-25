#!/bin/sh
echo "[$0] ..." > /dev/console
TROOT=`rgdb -i -g /runtime/template_root`
[ "$TROOT" = "" ] && TROOT="/etc/templates"
START=1
[ "$1" = "stop" ] && START=0
rgdb -i -s /runtime/time/ntp/state ""
rgdb -i -s /runtime/timeset ""
rgdb -A $TROOT/misc/ntp_run.php -V generate_start=$START > /var/run/ntp.sh
sh /var/run/ntp.sh > /dev/console
