#!/bin/sh
echo [$0] ... > /dev/console
TROOT=`rgdb -i -g /runtime/template_root`
[ "$TROOT" = "" ] && TROOT="/etc/templates"
rgdb -A $TROOT/misc/firmware_run.php > /var/run/firmware.sh
sh /var/run/firmware.sh
