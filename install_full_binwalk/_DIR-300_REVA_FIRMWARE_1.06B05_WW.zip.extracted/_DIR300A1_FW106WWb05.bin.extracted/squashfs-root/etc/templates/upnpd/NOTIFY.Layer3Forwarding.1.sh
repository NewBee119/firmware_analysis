#!/bin/sh
echo [$0] $1 ... > /dev/console
TROOT=`xmldbc -i -g /runtime/template_root`
[ "$TROOT" = "" ] && TROOT="/etc/templates"

SERVICE="/runtime/upnpdev/root:1/service:2"
PHPFILE="NOTIFY.Layer3Forwarding.1.php"

xmldbc -A $TROOT/upnpd/run.NOTIFY.propchange.php -V TARGET_SERVICE=$SERVICE -V TARGET_PHP=$PHPFILE > /var/run/$PHPFILE.sh
sh /var/run/$PHPFILE.sh &
