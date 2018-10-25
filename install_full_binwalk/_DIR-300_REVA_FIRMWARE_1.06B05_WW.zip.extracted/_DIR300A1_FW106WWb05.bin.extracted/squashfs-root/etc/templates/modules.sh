#!/bin/sh
case "$1" in
insmod)
#	insmod /lib/modules/ip_conntrack_proto_esp_m.o
#	insmod /lib/modules/ip_conntrack_ike_m.o
#	insmod /lib/modules/ip_conntrack_esp_m.o
#	insmod /lib/modules/ip_nat_proto_esp_m.o
#	insmod /lib/modules/ip_nat_ike_m.o
#	insmod /lib/modules/ip_nat_esp_m.o
	insmod /lib/modules/sw_tcpip.o
	insmod /lib/modules/ifresetcnt.o
	insmod /lib/modules/ipt_string.o
	if [ "`rgdb -i -g /runtime/func/stun/enabled`" = "1" ]; then
		if [ "`rgdb -i -g /runtime/layout/image_sign`" = "wrgg27_dlwbr_dir320" ]; then
			echo 4096 > /proc/sys/net/ipv4/ip_conntrack_max
		elif [ "`rgdb -i -g /runtime/layout/image_sign`" = "wrgn18_dlwbr_dir605" ]; then
			echo 8192 > /proc/sys/net/ipv4/ip_conntrack_max
		else
			echo 2048 > /proc/sys/net/ipv4/ip_conntrack_max
		fi
		insmod /lib/modules/ip_stun_func.o
	fi
	if [ "`rgdb -i -g /runtime/func/netsniper`" = "1" ]; then
		insmod /lib/modules/ipt_PERS.o
	fi
	;;
rmmod)
#	rmmod ip_nat_esp_m
#	rmmod ip_nat_ike_m
#	rmmod ip_nat_proto_esp_m
#	rmmod ip_conntrack_esp_m
#	rmmod ip_conntrack_ike_m
#	rmmod ip_conntrack_proto_esp_m
	if [ "`rgdb -i -g /runtime/func/stun/enabled`" = "1" ]; then
		rmmod ip_stun_func
	fi
	rmmod ipt_string
	rmmod ifresetcnt
	rmmod sw_tcpip
	if [ "`rgdb -i -g /runtime/func/netsniper`" = "1" ]; then
		rmmod ipt_PERS
	fi
	;;
esac
