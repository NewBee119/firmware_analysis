#!/bin/sh
[ -z "$1" ] && echo "Error: should be called from udhcpc" > /dev/console && exit 1
echo [$0] $1 ... > /dev/console
case "$1" in
bound)
	[ -n "$broadcast" ] && BROADCAST="broadcast $broadcast"
	[ -n "$subnet" ] && NETMASK="netmask $subnet"
	ifconfig $interface $ip $BROADCAST $NETMASK
<?
if ($physical==1)
{
	echo "\tlast_wan2_st=`xmldbc -i -g /runtime/wan/inf:2/connectstatus`\n";

	echo "\txmldbc -i -s /runtime/wan/inf:2/ip \"$ip\"\n";
	echo "\txmldbc -i -s /runtime/wan/inf:2/netmask \"$subnet\"\n";
	echo "\txmldbc -i -s /runtime/wan/inf:2/interface \"$interface\"\n";
	echo "\txmldbc -i -s /runtime/wan/inf:2/domain \"$domain\"\n";
	echo "\txmldbc -i -s /runtime/wan/inf:2/lease \"$lease\"\n";
	echo "\txmldbc -i -s /runtime/wan/inf:2/connectstatus connected\n";
	if ( query("/wan/rg/inf:1/mode") == "4" ) /* PPTP MODE */
	{ $ppp_persist=query("/wan/rg/inf:1/pptp/autoreconnect"); }
	else /* L2TP MODE */
	{ $ppp_persist=query("/wan/rg/inf:1/l2tp/autoreconnect"); }

	if ($ppp_persist!=1)
	{
		echo "\tif [ -n \$router ]; then\n";
		echo "\t	for i in \$router ; do\n";
		echo "\t		route del default\n";
		echo "\t		route add default gw \$i\n";
		echo "\t		xmldbc -i -s /runtime/wan/inf:2/gateway \$i\n";
		echo "\t	done\n";
		echo "\tfi\n";
	}
}
?>
	echo -n > /etc/resolv.conf
	PDNS="";
	for i in $dns ; do
		echo adding dns $i ... > /dev/console
		echo nameserver $i >> /etc/resolv.conf
		if [ "`chnet $i $subnet`" != "`chnet $ip $subnet`" ]; then
			route add -host $i gw $router dev $interface
		fi
<?
if ($physical==1)
{
		echo "\t\tif [ \"$PDNS\" = \"\" ]; then\n";
		echo "\t\t	xmldbc -i -s /runtime/wan/inf:2/primarydns \"$i\"\n";
		echo "\t\t	PDNS=\"$i\"\n";
		echo "\t\telse\n";
		echo "\t\t	xmldbc -i -s /runtime/wan/inf:2/secondarydns \"$i\"\n";
		echo "\t\tfi\n";
}
?>
	done
	submit DNSR

	xmldbc -i -s /runtime/wan/phy/ip		$ip
	xmldbc -i -s /runtime/wan/phy/netmask	$subnet

	# Is server alive?
	SERVER=`gethostip -D <?=$server?>`

	for sip in $SERVER ; do
			echo ping $sip... > /dev/console
			result=`ping $sip | grep "is alive"`
			if [ "$result" != "" ]; then
				echo get response from $sip !! > /dev/console	 
				break
			fi	
	done 
#Is it possible that server doesn't want to response ping?
	if [ "$result" = "" ]; then
		sip=`gethostip -d <?=$server?>`
	fi	
	if [ "$sip" != "" ]; then
		if [ "`chnet $sip $subnet`" != "`chnet $ip $subnet`" ]; then
			route add -host $sip gw $router dev $interface
		fi
		echo "Using server $sip" > /dev/console
		sh /etc/templates/wan_ppp.sh start $sip DHCP > /dev/console
	else
		echo "Can not find server (<?=$server?>) : $sip" > /dev/console
	fi

<?
if ($physical==1)
{ 
	echo "\tif [ \"\$last_wan2_st\" != \"connected\" ]; then\n"; 
	echo "\t\t[ -f /etc/templates/dhcpd.sh ] && /etc/templates/dhcpd.sh > /dev/console\n";
	echo "\tfi\n";

	if ( query("/wan/rg/inf:1/mode") == "4" ) /* PPTP MODE */
	{ $ppp_persist=query("/wan/rg/inf:1/pptp/autoreconnect"); }
	else /* L2TP MODE */
	{ $ppp_persist=query("/wan/rg/inf:1/l2tp/autoreconnect"); }

	if ($ppp_persist!=1) {  echo "\t/etc/templates/rg.sh misc > /dev/console\n"; } 
 	echo "\t/etc/templates/route.sh restart > /dev/console\n";
}
?>
	;;
deconfig)
	/etc/templates/wan_ppp.sh stop > /dev/console
	ifconfig $interface 0.0.0.0 > /dev/console
	xmldbc -i -s /runtime/wan/phy/ip ""
	xmldbc -i -s /runtime/wan/phy/netmask ""

	for i in `rgdb -i -g /runtime/wan/inf:2/classlessstaticroute/number`; do
		rgdb -d /runtime/wan/inf:2/classlessstaticroute:1
	done	

	for i in `rgdb -i -g /runtime/wan/inf:2/staticroute/number`; do
		rgdb -d /runtime/wan/inf:2/staticroute:1
	done	
<?
if ($physical==1)
{
	echo "\txmldbc -i -s /runtime/wan/inf:2/connectstatus disconnected\n";
	echo "\txmldbc -i -s /runtime/wan/inf:2/ip \"\"\n";
	echo "\txmldbc -i -s /runtime/wan/inf:2/netmask \"\"\n";
	echo "\txmldbc -i -s /runtime/wan/inf:2/gateway \"\"\n";
	echo "\txmldbc -i -s /runtime/wan/inf:2/primarydns \"\"\n";
	echo "\txmldbc -i -s /runtime/wan/inf:2/secondarydns \"\"\n";
	echo "\txmldbc -i -s /runtime/wan/inf:2/domain \"\"\n";
	echo "\txmldbc -i -s /runtime/wan/inf:2/interface \"\"\n";
	echo "\txmldbc -i -s /runtime/wan/inf:2/mtu \"\"\n";

	echo "\t[ -f /etc/templates/dhcpd.sh ] && /etc/templates/dhcpd.sh > /dev/console\n";
}
?>


	;;
classlessstaticroute)
	echo "adding Classes Static Route: $sindex : $sdest $ssubnet $srouter"  > /dev/console
	if [ $sdest != "" -a $ssubnet != "" -a $srouter != "" ]; then

		rgdb -i -s /runtime/wan/inf:2/classlessstaticroute/number                "$snum"
		rgdb -i -s /runtime/wan/inf:2/classlessstaticroute:$sindex/dest          "$sdest"
		rgdb -i -s /runtime/wan/inf:2/classlessstaticroute:$sindex/subnet        "$ssubnet"
		rgdb -i -s /runtime/wan/inf:2/classlessstaticroute:$sindex/router        "$srouter"

		route add -net $sdest netmask $ssubnet gw $srouter
	fi
	;;

staticroute)
	echo "adding Classes Static Route: $sindex : $sdest $ssubnet $srouter"  > /dev/console
	if [ $sdest != "" -a $ssubnet != "" -a $srouter != "" ]; then

		rgdb -i -s /runtime/wan/inf:2/staticroute/number                "$snum"
		rgdb -i -s /runtime/wan/inf:2/staticroute:$sindex/dest          "$sdest"
		rgdb -i -s /runtime/wan/inf:2/staticroute:$sindex/subnet        "$ssubnet"
		rgdb -i -s /runtime/wan/inf:2/staticroute:$sindex/router        "$srouter"

		route add -net $sdest netmask $ssubnet gw $srouter
	fi
	;;
esac
exit 0
