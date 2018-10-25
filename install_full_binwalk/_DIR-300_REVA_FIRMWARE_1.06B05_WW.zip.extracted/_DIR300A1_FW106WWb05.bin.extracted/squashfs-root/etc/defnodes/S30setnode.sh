#!/bin/sh
MAC=`xmldbc -i -g /runtime/layout/wlanmac`
N5=`echo $MAC | cut -d: -f5`
N6=`echo $MAC | cut -d: -f6`
DEFSSID="dlink$N5$N6"
xmldbc -i -s /runtime/wps/registrar/default_ssid $DEFSSID
