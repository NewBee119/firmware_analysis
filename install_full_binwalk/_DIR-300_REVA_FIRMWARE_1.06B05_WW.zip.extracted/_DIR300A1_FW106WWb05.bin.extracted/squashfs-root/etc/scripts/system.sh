#!/bin/sh
case "$1" in
start)
	echo "start fresetd ..."		> /dev/console
	fresetd &
	echo "start scheduled ..."		> /dev/console
	/etc/templates/scheduled.sh start	> /dev/console
	echo "setup layout ..."			> /dev/console
	/etc/scripts/layout.sh start	> /dev/console
	echo "start LAN ..."			> /dev/console
	/etc/templates/lan.sh start		> /dev/console
	echo "enable LAN ports ..."		> /dev/console
	/etc/scripts/enlan.sh			> /dev/console
	echo "start WLAN ..."			> /dev/console
	/etc/templates/wlan.sh start	> /dev/console
	echo "start Guest Zone"			> /dev/console
	/etc/templates/gzone.sh start > /dev/console
	/etc/templates/enable_gzone.sh start	> /dev/console
	echo "start RG ..."				> /dev/console
	/etc/templates/rg.sh start		> /dev/console
	echo "start DNRD ..."			> /dev/console
	/etc/templates/dnrd.sh start	> /dev/console
	# start telnet daemon
	/etc/scripts/misc/telnetd.sh	> /dev/console
	# Start UPNPD
	if [ "`rgdb -i -g /runtime/router/enable`" = "1" ]; then
	echo "start UPNPD ..."			> /dev/console
	/etc/templates/upnpd.sh start	> /dev/console
	fi
	echo "start WAN ..."			> /dev/console
	/etc/scripts/misc/setwantype.sh	> /dev/console
	/etc/templates/wan.sh start		> /dev/console
	echo "start LLD2D ..."			> /dev/console
	/etc/templates/lld2d.sh start	> /dev/console
	if [ "`rgdb -i -g /runtime/func/neaps`" = "1" ]; then
	echo "start Neaps ..."			> /dev/console
	/etc/templates/neaps.sh start	> /dev/console
	fi
	if [ "`rgdb -i -g /runtime/func/lpd`" = "1" ]; then
	echo "start lpd ..."			> /dev/console
	/etc/templates/lpd.sh start		> /dev/console
	fi
	echo "start igmpproxy ..."
	/etc/templates/igmpproxy.sh start > /dev/console
	if [ "`rgdb -i -g /runtime/func/router_netbios`" = "1" ]; then
	echo "start Netbios ..."		> /dev/console
	netbios &				> /dev/consele
	fi
	if [ -f /etc/templates/smbd.sh ]; then
	echo "start smbtree search ..."
	/etc/templates/smbd.sh smbtree_start > /dev/console
	echo "start smbmount ..."
	/etc/templates/smbd.sh smbmount_start > /dev/console
	fi
	if [ -f /etc/templates/ledctrl.sh ]; then
	echo "Change the STATUS LED..."
	/etc/templates/ledctrl.sh STATUS GREEN > /dev/console
	fi
	if [ -f /etc/scripts/misc/profile_ca.sh ]; then
	echo "get certificate file ..."			> /dev/console
	/etc/scripts/misc/profile_ca.sh start	> /dev/console
	fi
	if [ -f /etc/templates/wimax.sh ]; then
	echo "start wimax connection ..."
	/etc/templates/wimax.sh start > /dev/console
	fi
	if [ -f /etc/scripts/misc/plugplay.sh ]; then
	echo "start usb plugplay ..."
	/etc/scripts/misc/plugplay.sh > /dev/console
	fi
	if [ "`pidof lcm_draw`" = "" ]; then
		if [ -f /etc/templates/lcm_drawd.sh ]; then
			echo "start lcm draw ..."
			/etc/templates/lcm_drawd.sh start > /dev/console
		fi
	fi
	;;
stop)
	if [ -f /etc/scripts/misc/plugplay.sh ]; then
	echo "stop usb plugplay ..."
	killall plugplay.sh > /dev/console
	fi
	if [ -f /etc/templates/wimax.sh ]; then
	echo "stop wimax connection ..."
	/etc/templates/wimax.sh stop > /dev/console
	fi
	if [ "`rgdb -i -g /runtime/func/router_netbios`" = "1" ]; then
        echo "stop Netbios ..."                > /dev/console
        killall netbios                        > /dev/consele
        fi
	echo "stop igmpproxy ..."
	/etc/templates/igmpproxy.sh stop > /dev/console
	if [ "`rgdb -i -g /runtime/func/lpd`" = "1" ]; then
	echo "stop lpd ..."				> /dev/console
	/etc/templates/lpd.sh stop		> /dev/console
	fi
	if [ "`rgdb -i -g /runtime/func/neaps`" = "1" ]; then
	echo "stop Neaps ..."			> /dev/console
	/etc/templates/neaps.sh stop	> /dev/console
	fi
	echo "stop LLD2D ..."			> /dev/console
	/etc/templates/lld2d.sh stop	> /dev/console
	echo "stop WAN ..."				> /dev/console
	/etc/templates/wan.sh stop		> /dev/console
	if [ "`rgdb -i -g /runtime/router/enable`" = "1" ]; then
	echo "stop UPNPD ..."			> /dev/console
	/etc/templates/upnpd.sh stop	> /dev/console
	fi
	echo "stop DNRD ..."			> /dev/console
	/etc/templates/dnrd.sh stop		> /dev/console
	echo "stop RG ..."				> /dev/console
	/etc/templates/rg.sh stop		> /dev/console
	echo "stop Guest Zone ..."		> /dev/console
	/etc/templates/enable_gzone.sh stop > /dev/console
	/etc/templates/gzone.sh stop	> /dev/console
	echo "stop WLAN ..."			> /dev/console
	/etc/templates/wlan.sh stop		> /dev/console
	echo "stop LAN ..."				> /dev/console
	/etc/templates/lan.sh stop		> /dev/console
	echo "reset layout ..."			> /dev/console
	/etc/scripts/layout.sh stop		> /dev/console
	echo "stop scheduled ..."			> /dev/console
	/etc/templates/scheduled.sh stop		> /dev/console
	echo "stop fresetd ..."			> /dev/console
	killall fresetd
	;;
restart)
	sleep 3
	$0 stop
	$0 start
	;;
*)
	echo "Usage: system.sh {start|stop|restart}"
	;;
esac
exit 0
