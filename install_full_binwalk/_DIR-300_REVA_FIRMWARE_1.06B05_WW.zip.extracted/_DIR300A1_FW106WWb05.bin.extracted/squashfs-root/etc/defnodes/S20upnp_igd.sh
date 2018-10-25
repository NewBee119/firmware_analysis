#!/bin/sh
SN=`devdata get -e sn`
[ "$SN" = "" ] && SN="00000001"
xmldbc -s /sys/serialnumber $SN
LANMAC=`xmldbc -i -g /runtime/layout/lanmac`
xmldbc -i -s /runtime/upnpdev/root:1/uuid `genuuid -s InternetGatewayDevice -m $LANMAC`
