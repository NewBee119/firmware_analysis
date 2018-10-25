#!/bin/sh
UPNPMSG=`xmldbc -i -g /runtime/upnpmsg`
[ "$UPNPMSG" = "" ] && UPNPMSG="/dev/null"
echo "GOT M-SEARCH: $1 $2 $3 ..." > $UPNPMSG

if [ -f /etc/templates/upnpd/run.M-SEARCH.$1.php ]; then
	xmldbc -A /etc/templates/upnpd/run.M-SEARCH.$1.php -V "TARGET_HOST=$2" -V "PARAM=$3" > /var/run/M-SEARCH.$1.sh
	sh /var/run/M-SEARCH.$1.sh
else
	echo "[$0] Invalid command: $1 !!"
fi
