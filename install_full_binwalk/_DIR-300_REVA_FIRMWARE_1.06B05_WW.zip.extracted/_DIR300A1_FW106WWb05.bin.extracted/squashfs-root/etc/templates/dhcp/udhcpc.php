#!/bin/sh
<?
/* vi: set sw=4 ts=4: */
require("/etc/templates/troot.php");

$wanmode=query("/wan/rg/inf:1/mode");
if ($wanmode == 7)
{
	$autodns = 1;
}
else
{
	//$autodns=query("/wan/rg/inf:1/dhcp/autodns");
	$autodns = 1;
	$dns1=query("/dnsrelay/server/primarydns");
	$dns2=query("/dnsrelay/server/secondarydns");
	if ($dns1 != "" && $dns1 != "0.0.0.0") { $autodns=0; }
	if ($dns2 != "" && $dns2 != "0.0.0.0") { $autodns=0; }
}

?>
echo "[$0] $1" > /dev/console
RESOLV_CONF="/etc/resolv.conf"

case "$1" in
deconfig)
	echo "deconfig $interface" > /dev/console
	if [ `rgdb -i -g /runtime/wan/inf:1/connectstatus` == "disconnected" ]; then
		echo "DHCP is not connected !" > /dev/console
		exit 0
	fi

	echo "deleting routers" > /dev/console
	while route del default gw 0.0.0.0 dev $interface ; do
		:
	done
	ifconfig $interface 0.0.0.0
	[ -f <?=$template_root?>/wandown.sh ] && <?=$template_root?>/wandown.sh > /dev/console 
	rgdb -i -s /runtime/wan/inf:1/ip ""
	rgdb -i -s /runtime/wan/inf:1/netmask ""
	rgdb -i -s /runtime/wan/inf:1/gateway ""
	rgdb -i -s /runtime/wan/inf:1/primarydns ""
	rgdb -i -s /runtime/wan/inf:1/secondarydns ""
	rgdb -i -s /runtime/wan/inf:1/winstype	""
	rgdb -i -s /runtime/wan/inf:1/scope	""
	rgdb -i -s /runtime/wan/inf:1/primarywins ""
	rgdb -i -s /runtime/wan/inf:1/secondarywins ""
	rgdb -i -s /runtime/wan/inf:1/domain ""
	rgdb -i -s /runtime/wan/inf:1/interface ""
	rgdb -i -s /runtime/wan/inf:1/mtu ""
	rgdb -i -s /runtime/wan/inf:1/connectstatus disconnected

	for i in `rgdb -i -g /runtime/wan/inf:1/classlessstaticroute/number`; do
		rgdb -d /runtime/wan/inf:1/classlessstaticroute:1
	done

	for i in `rgdb -i -g /runtime/wan/inf:1/staticroute/number`; do
		rgdb -d /runtime/wan/inf:1/staticroute:1
	done	

	[ -f <?=$template_root?>/dhcpd.sh ] && <?=$template_root?>/dhcpd.sh > /dev/console 
	;;

renew|bound)
	#delete leading and trailing whitespace
	tmp=`echo "$ip"|sed 's/[ \t]*$//'|sed 's/^[ ]*//'`
	ip=$tmp
	tmp=`echo "$subnet"|sed 's/[ \t]*$//'|sed 's/^[ ]*//'`
	subnet=$tmp
	tmp=`echo "$broadcast"|sed 's/[ \t]*$//'|sed 's/^[ ]*//'`
	broadcast=$tmp
	echo "config $interface $ip/$subnet/$broadcast" > /dev/console
	old_ip=`rgdb -i -g /runtime/wan/inf:1/ip`
	old_mask=`rgdb -i -g /runtime/wan/inf:1/netmask`
	old_gw=`rgdb -i -g /runtime/wan/inf:1/gateway`
	old_dns1=`rgdb -i -g /runtime/wan/inf:1/primarydns`
	old_dns2=`rgdb -i -g /runtime/wan/inf:1/secondarydns`
	old_wins1=`rgdb -i -g /runtime/wan/inf:1/primarywins`
	old_wins2=`rgdb -i -g /runtime/wan/inf:1/secondarywins`
	old_mtu=`rgdb -i -g /runtime/wan/inf:1/mtu`
	echo "[DHCPC] : old/new=$old_ip/$ip" > /dev/console
	echo "[DHCPC] : old/new=$old_mask/$subnet" > /dev/console
	if [ "$old_ip" = "$ip" -a "$old_mask" = "$subnet" ]; then
		no_change="1"
	fi
	[ -n "$broadcast" ] && BROADCAST="broadcast $broadcast"
	[ -n "$subnet" ] && NETMASK="netmask $subnet"
	ifconfig $interface $ip $BROADCAST $NETMASK
	rgdb -i -s /runtime/wan/inf:1/ip "$ip"
	rgdb -i -s /runtime/wan/inf:1/netmask "$subnet"
	rgdb -i -s /runtime/wan/inf:1/interface "$interface"

	echo "router=$router" > /dev/console

	if [ -n "$router" ]; then
		for i in $router ; do
			if [ $subnet = "255.255.255.255" ]; then
				route add $i dev $interface
				echo "Add gw interface route" > /dev/console
			fi
				route add default gw $i dev $interface
				rgdb -i -s /runtime/wan/inf:1/gateway "$i"
				echo "Add default gw $i" > /dev/console
		done
	fi

	echo -n > $RESOLV_CONF
<?
	if ($autodns==0)
	{
		if ($dns1 != "" && $dns1 != "0.0.0.0")
		{
			echo "	echo \"nameserver ".$dns1."\" >> $RESOLV_CONF\n";
			echo "	rgdb -i -s /runtime/wan/inf:1/primarydns \"".$dns1."\"\n";
		}
		if ($dns2 != "" && $dns2 != "0.0.0.0")
		{
			echo "	echo \"nameserver ".$dns2."\" >> $RESOLV_CONF\n";
			echo "	rgdb -i -s /runtime/wan/inf:1/secondarydns \"".$dns2."\"\n";
		}
	}
	else
	{
		echo "	PDNS=\"\"\n";
		echo "	for i in $dns ; do\n";
		echo "		echo \"adding dns $i...\" > /dev/console\n";
		echo "		echo \"nameserver $i\" >> $RESOLV_CONF\n";
		echo "		if [ \"$PDNS\" = \"\" ]; then\n";
		echo "			rgdb -i -s /runtime/wan/inf:1/primarydns \"$i\"\n";
		echo "			PDNS=\"$i\"\n";
		echo "		else\n";
		echo "			rgdb -i -s /runtime/wan/inf:1/secondarydns \"$i\"\n";
		echo "		fi\n";
		echo "	done\n";
	}
		//here add seting netbios options value to /runtime/......
	if(query("/runtime/func/router_netbios")==1)
	{	
		echo "	PWINS=\"\"\n";
		echo "	for i in $wins ; do\n";
		echo "		echo \"adding wins address $i...\" > /dev/console\n";
		echo "		if [ \"$PWINS\" = \"\" ]; then\n";
		echo "			rgdb -i -s /runtime/wan/inf:1/primarywins \"$i\"\n";
		echo "			PWINS=\"$i\"\n";
		echo "		else\n";
		echo "			rgdb -i -s /runtime/wan/inf:1/secondarywins \"$i\"\n";
		echo "		fi\n";
		echo "	done\n";
		echo "	for i in $winstype ; do\n";
		echo "		echo \"adding winstype $i...\" > /dev/console\n";
		echo "			rgdb -i -s /runtime/wan/inf:1/winstype \"$i\"\n";
		echo "	done\n";
		echo "	for i in $scope ; do\n";
		echo "		echo \"adding netbios_scope $i...\" > /dev/console\n";
		echo "			rgdb -i -s /runtime/wan/inf:1/scope \"$i\"\n";
		echo "	done\n";
	}
?>
	new_dns1=`rgdb -i -g /runtime/wan/inf:1/primarydns`
	new_dns2=`rgdb -i -g /runtime/wan/inf:1/secondarydns`
	if [ "$old_dns1" != "$new_dns1" -o "$old_dns2" != "$new_dns2" ]; then
		no_change="0"
	fi
	[ -n "$domain" ] && echo search $domain >> $RESOLV_CONF
	rgdb -i -s /runtime/wan/inf:1/domain "$domain"
	rgdb -i -s /runtime/wan/inf:1/lease "$lease"
	rgdb -i -s /runtime/wan/inf:1/connectstatus connected
	echo "[DHCPC] : no_change = $no_change" > /dev/console
	if [ "$no_change" != "1" ]; then
		[ -f <?=$template_root?>/wanup.sh ] && <?=$template_root?>/wanup.sh > /dev/console &
	else
		echo "[DHCPC] : Same IP, do not restart WAN interface..." > /dev/console
	fi
	;;
dhcpplus)
	echo "config $interface $ip/$subnet/$broadcast" > /dev/console
	[ -n "$broadcast" ] && BROADCAST="broadcast $broadcast"
	[ -n "$subnet" ] && NETMASK="netmask $subnet"
	ifconfig $interface $ip $BROADCAST $NETMASK

	if [ -n "$router" ]; then
		for i in $router ; do
			if [ $subnet = "255.255.255.255" ]; then
				route add $i dev $interface
				echo "Add gw interface route" > /dev/console
			fi
				route add default gw $i dev $interface
				echo "Add default gw $i" > /dev/console
		done
	fi
	;;
classlessstaticroute)
	echo "adding Classes Static Route: $sindex : $sdest $ssubnet $srouter"  > /dev/console
	if [ $sdest != "" -a $ssubnet != "" -a $srouter != "" ]; then

		rgdb -i -s /runtime/wan/inf:1/classlessstaticroute/number                "$snum"
		rgdb -i -s /runtime/wan/inf:1/classlessstaticroute:$sindex/dest          "$sdest"
		rgdb -i -s /runtime/wan/inf:1/classlessstaticroute:$sindex/subnet        "$ssubnet"
		rgdb -i -s /runtime/wan/inf:1/classlessstaticroute:$sindex/router        "$srouter"

		route add -net $sdest netmask $ssubnet gw $srouter
	fi
	;;
staticroute)
	echo "adding Static Route: $sindex : $sdest $ssubnet $srouter"  > /dev/console
	if [ $sdest != "" -a $ssubnet != "" -a $srouter != "" ]; then

		rgdb -i -s /runtime/wan/inf:1/staticroute/number                "$snum"
		rgdb -i -s /runtime/wan/inf:1/staticroute:$sindex/dest          "$sdest"
		rgdb -i -s /runtime/wan/inf:1/staticroute:$sindex/subnet        "$ssubnet"
		rgdb -i -s /runtime/wan/inf:1/staticroute:$sindex/router        "$srouter"

		route add -net $sdest netmask $ssubnet gw $srouter
	fi
	;;
esac
exit 0
