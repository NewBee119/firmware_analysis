#!/bin/sh
type=`rgdb -g /wan/rg/inf:1/etherlinktype`
[ "$type" = "" ] && type=0
echo Set WAN port media type $type > /dev/console
slinktype -i 4 -d "$type"
sleep 2
