#!/bin/sh
echo [$0] ... > /dev/console
TROOT=`rgdb -i -g /runtime/template_root`
[ "$TROOT" = "" ] && TROOT="/etc/templates"
rgdb -A $TROOT/logs.php > /var/run/logs_run.sh
sh /var/run/logs_run.sh
