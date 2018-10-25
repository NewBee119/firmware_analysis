#!/bin/sh
echo [$0] $1 $2 $3 $4 ... > /dev/console

SERVICE="/runtime/upnpdev/root:2/service:1"
SHFILE="/var/run/NOTIFY.WFAWLANConfig.sh"

PARAMS="-V TARGET_SERVICE=$SERVICE -V EVENT_TYPE=$1 -V EVENT_MAC=$2 -V EVENT_PAYLOAD=$3 -V REMOTE_ADDR=$4"
xmldbc -A /etc/templates/upnpd/run.NOTIFY.WFADEV.php $PARAMS > $SHFILE
sh $SHFILE &
