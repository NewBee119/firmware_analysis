#!/bin/sh
echo "[$0] $1" > /dev/console
RESOLV_CONF="/etc/resolv.conf"

case "$1" in
deconfig)
	echo "deconfig $interface" > /dev/console
	if [ `rgdb -i -g /runtime/wan/inf:2/connectstatus` != "connected" ]; then
		echo "DHCP is not connected !" > /dev/console
		exit 0
	fi

	ifconfig $interface 0.0.0.0
	[ -f /etc/templates/wandown.sh ] && /etc/templates/wandown.sh > /dev/console &
	rgdb -i -s /runtime/wan/inf:2/connectstatus disconnected
	rgdb -i -s /runtime/wan/inf:2/ip ""
	rgdb -i -s /runtime/wan/inf:2/netmask ""
	rgdb -i -s /runtime/wan/inf:2/gateway ""
	rgdb -i -s /runtime/wan/inf:2/primarydns ""
	rgdb -i -s /runtime/wan/inf:2/secondarydns ""
	rgdb -i -s /runtime/wan/inf:2/domain ""
	rgdb -i -s /runtime/wan/inf:2/interface ""
	rgdb -i -s /runtime/wan/inf:2/mtu ""
	[ -f /etc/templates/dhcpd.sh ] && /etc/templates/dhcpd.sh > /dev/console
	;;
bound)
	echo "config $interface $ip/$subnet/$broadcast" > /dev/console
	[ -n "$broadcast" ] && BROADCAST="broadcast $broadcast"
	[ -n "$subnet" ] && NETMASK="netmask $subnet"
	ifconfig $interface $ip $BROADCAST $NETMASK
	rgdb -i -s /runtime/wan/inf:2/ip "$ip"
	rgdb -i -s /runtime/wan/inf:2/netmask "$subnet"
	rgdb -i -s /runtime/wan/inf:2/interface "$interface"

	if [ -n "$router" ]; then
		for i in $router ; do
			rgdb -i -s /runtime/wan/inf:2/gateway "$i"
			$ROUTER=$i
		done
	fi

	PDNS="";
	for i in $dns ; do
		echo "adding dns $i..." > /dev/console
		if [ "`chnet $i $subnet`" != "`chnet $ip $subnet`" ]; then
			route add -host $i gw $ROUTER dev $interface
		fi
		if [ "$PDNS" = "" ]; then
			rgdb -i -s /runtime/wan/inf:2/primarydns "$i"
			PDNS="$i"
		else
			rgdb -i -s /runtime/wan/inf:2/secondarydns "$i"
		fi
	done

	[ -n "$domain" ] && echo search $domain >> $RESOLV_CONF
	rgdb -i -s /runtime/wan/inf:2/domain "$domain"
	rgdb -i -s /runtime/wan/inf:2/lease "$lease"
	rgdb -i -s /runtime/wan/inf:2/connectstatus connected
	[ -f /etc/templates/dhcpd.sh ] && /etc/template/dhcpd.sh > /dev/console
	[ -f /etc/templates/wanup.sh ] && /etc/templates/wanup.sh > /dev/console &
	;;
esac
exit 0
