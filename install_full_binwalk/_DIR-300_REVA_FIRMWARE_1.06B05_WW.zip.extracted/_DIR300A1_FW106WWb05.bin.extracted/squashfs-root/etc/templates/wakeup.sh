#!/bin/sh
echo [$0] PC...>/dev/console
TROOT=`rgdb -i -g /runtime/template_root`
[ "$TROOT" = "" ] && TROOT="/etc/templates"
rgdb -A $TROOT/wake_pc.php > /var/run/wake_pc.sh
sh /var/run/wake_pc.sh

