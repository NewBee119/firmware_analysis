#!/bin/sh
wan_type=`rgdb -g /wan/rg/inf:1/mode`
wan2mode=`rgdb -g /wan/rg/inf:2/mode`
starspeed=`rgdb -g /wan/rg/inf:1/pppoe/starspeed/enable`
langcode=`cat /www/locale/alt/langcode`
if [ "$wan_type" != "3" -o "$starspeed" != "1" ];then
	exit;
fi
if [ "$wan2mode" != "" ];then
	exit;
fi
if [ -f /www/locale/alt/langcode -a "$langcode" = "zhcn" ];then
	echo "starspeed: save current state";
else
	exit;
fi
pap_type=`rgdb -i -g /runtime/wan/rg/inf:1/pppoe/starspeed/currstate`
rgdb -s /wan/rg/inf:1/pppoe/starspeed/type "$pap_type"
submit COMMIT
