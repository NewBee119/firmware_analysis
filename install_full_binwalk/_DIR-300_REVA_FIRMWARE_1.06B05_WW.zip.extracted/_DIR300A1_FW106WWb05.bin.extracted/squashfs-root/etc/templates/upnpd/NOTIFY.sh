#!/bin/sh
UPNPMSG=`xmldbc -i -g /runtime/upnpmsg`
[ "$UPNPMSG" = "" ] && UPNPMSG="/dev/null"
echo "SEND NOTIFY $1 $2 ..." > $UPNPMSG

if [ -f /etc/templates/upnpd/run.NOTIFY.$1.php ]; then
	xmldbc -A /etc/templates/upnpd/run.NOTIFY.$1.php -V "PARAM=$2" > /var/run/NOTIFY.$1.sh
	sh /var/run/NOTIFY.$1.sh
else
	echo "[$0] Invalid command: $1 !!"
fi
