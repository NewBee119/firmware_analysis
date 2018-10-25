#!/bin/sh
LANMAC=`xmldbc -i -g /runtime/layout/lanmac`
xmldbc -i -s /runtime/upnpdev/root:2/uuid `genuuid -s WFADevice -m $LANMAC`
