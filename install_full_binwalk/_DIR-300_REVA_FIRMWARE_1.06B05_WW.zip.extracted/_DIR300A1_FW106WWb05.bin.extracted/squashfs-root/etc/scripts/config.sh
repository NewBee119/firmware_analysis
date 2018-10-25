#!/bin/sh
nvram=`cat /etc/config/nvram`
image_sign=`cat /etc/config/image_sign`

case "$1" in
start)
	echo "Mounting proc and var ..."
	mount -t proc none /proc
	mount -t ramfs ramfs /var
	mkdir -p /var/etc /var/log /var/run /var/state /var/tmp /var/etc/ppp /var/etc/config /var/dnrd /var/etc/iproute2
	echo -n > /var/etc/resolv.conf
	echo -n > /var/TZ
	echo "127.0.0.1 hgw" > /var/hosts

	echo "***************  |  SYS:001" > /var/log/messages

	# if no PIN, generate one
	pin=`rgcfg getenv -n $nvram -e pin`
	[ "$pin" = "" ] && rgcfg setenv -n $nvram -e pin=`wps -g`

	# prepare db...
	echo "Start xmldb ..." > /dev/console
	xmldb -n $image_sign -t > /dev/console &
	sleep 1
	/etc/scripts/misc/profile.sh get
	/etc/templates/timezone.sh set
	/etc/templates/logs.sh

	echo "Inserting modules ..." > /dev/console
	# wireless driver
	insmod /lib/modules/wlan.o
	insmod /lib/modules/wlan_xauth.o
	insmod /lib/modules/wlan_wep.o
	insmod /lib/modules/wlan_tkip.o
	insmod /lib/modules/wlan_scan_sta.o
	insmod /lib/modules/wlan_scan_ap.o
	insmod /lib/modules/wlan_ccmp.o
	insmod /lib/modules/wlan_acl.o
	insmod /lib/modules/ath_hal.o
	insmod /lib/modules/ath_rate_atheros.o
	# insert module ath_dfs.o for madwifi driver v5.2.0.112
	test -f /lib/modules/ath_dfs.o && insmod /lib/modules/ath_dfs.o
	# get the country code for madwifi, default is fcc.
	ccode=`rgdb -g /sys/countrycode`
	[ "$ccode" = "" ] && ccode=`rgcfg getenv -n $nvram -e countrycode`
	[ "$ccode" = "" ] && ccode="840"
	echo "The countrycode is $ccode." > /dev/console
	insmod /lib/modules/ath_ahb.o countrycode=$ccode

	wlanconfig ath0 create wlandev wifi0 wlanmode ap
	env_wlan=`rgcfg getenv -n $nvram -e wlanmac`
	[ "$env_wlan" = "" ] && env_wlan="00:13:10:d1:00:02"
	ifconfig ath0 hw ether $env_wlan
	insmod /lib/modules/ar231x_access.o

	# bring up network devices
	env_wan=`rgcfg getenv -n $nvram -e wanmac`
	[ "$env_wan" = "" ] && env_wan="00:13:10:d1:00:01"
	ifconfig eth0 hw ether $env_wan up
	rgdb -i -s /runtime/wan/inf:1/mac "$env_wan"

	TIMEOUT=`rgdb -g /nat/general/tcpidletimeout`
	[ "$TIMEOUT" = "" ] && TIMEOUT=7200 && rgdb -s /nat/general/tcpidletimeout $TIMEOUT
	echo "$TIMEOUT" > /proc/sys/net/ipv4/netfilter/ip_conntrack_tcp_timeout_established

	# Setup VLAN
	vconfig set_name_type DEV_PLUS_VID_NO_PAD > /dev/console
	vconfig add eth0 0	> /dev/null 2>&1
	vconfig add eth0 2	> /dev/null 2>&1
	# Setup bridge
	brctl addbr br0 	> /dev/console
	brctl stp br0 off	> /dev/console
	brctl setfd br0 0	> /dev/console
	# Start up LAN interface & httpd
	ifconfig br0 0.0.0.0 up			> /dev/console
	/etc/templates/webs.sh start	> /dev/console
	;;
stop)
	umount /tmp
	umount /proc
	umount /var
	;;
esac
