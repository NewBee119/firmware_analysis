#!/bin/sh
starspeed=`rgdb -g /wan/rg/inf:1/pppoe/starspeed/enable`
pap_type=`rgdb -g /wan/rg/inf:1/pppoe/starspeed/type`
wanmode=`rgdb -g /wan/rg/inf:1/mode`
wan2mode=`rgdb -g /wan/rg/inf:2/mode`
langcode=`cat /www/locale/alt/langcode`
if [ "$starspeed" != "1" -o "$wanmode" != "3" ];then
	exit;
fi
if [ "$wan2mode" != "" ];then
	exit;
fi
if [ -f /www/locale/alt/langcode -a "$langcode" = "zhcn" -a "$pap_type" != "" ];then
	echo "Start starspeed (For China)!";
else
	exit;
fi
echo [$0]...
ifname=`rgdb -i -g /runtime/layout/wanif`
pppoe_user=`rgdb -g /wan/rg/inf:1/pppoe/user`
pppoe_pass=`rgdb -g /wan/rg/inf:1/pppoe/password`
peer_ips="192.168.0.1 192.168.10.1 192.168.1.1 192.167.0.1 10.0.0.138 10.0.0.2"
rgdb -i -s /runtime/wan/rg/inf:1/pppoe/starspeed/username ""
rgdb -i -s /runtime/wan/rg/inf:1/pppoe/starspeed/password ""
rgdb -i -s /runtime/wan/rg/inf:1/pppoe/starspeed/userformat ""
curr_state=$pap_type
echo "Current state : "$curr_state
case $curr_state in
	0 )
		rgdb -i -s /runtime/wan/rg/inf:1/pppoe/starspeed/username $pppoe_user
		rgdb -i -s /runtime/wan/rg/inf:1/pppoe/starspeed/password $pppoe_pass
		;;
	1 | 2 )
		for peer_ip in $peer_ips
		do
			if [ "$curr_state" = "2" ];then
				break
			fi
			ip=`echo $peer_ip | grep "192.168.1.*"`
			if [ "$ip" != "" ];then
				client_ip="192.168.1.5"
			fi
			ip=`echo $peer_ip | grep "192.168.0.*"`
			if [ "$ip" != "" ];then
				client_ip="192.168.0.222"
			fi
			ip=`echo $peer_ip | grep "192.168.10.*"`
			if [ "$ip" != "" ];then
				client_ip="192.168.10.222"
			fi
			ip=`echo $peer_ip | grep "192.167.0.*"`
			if [ "$ip" != "" ];then
				client_ip="192.167.0.222"
			fi
			ip=`echo $peer_ip | grep "10.0.0.*"`
			if [ "$ip" != "" ];then
				client_ip="10.0.0.212"
			fi
			echo "client IP : "$client_ip
			if [ $client_ip != "" ];then
				ifconfig $ifname $client_ip
			else
				break
			fi
			peer_mac=`arpping -t $peer_ip -i $ifname`

			echo $peer_ip":"$peer_mac
			if [ "$peer_mac" != "no" -a "$peer_mac" != "" ];then
				pap_pass=`pap_crack $pppoe_user $pppoe_pass $peer_mac`
				break
			fi
		done
		echo $pap_pass
		if [ "$pap_pass" = "" -o "$curr_state" = "2" ];then
			pap_pass=`pap_crack $pppoe_user $pppoe_pass`
		fi
		echo "pap pass":$pap_pass
		rgdb -i -s /runtime/wan/rg/inf:1/pppoe/starspeed/username $pppoe_user
		rgdb -i -s /runtime/wan/rg/inf:1/pppoe/starspeed/password $pap_pass
		;;
	3 )
		username=`hubei $pppoe_user`
		rgdb -i -s /runtime/wan/rg/inf:1/pppoe/starspeed/username $username
		rgdb -i -s /runtime/wan/rg/inf:1/pppoe/starspeed/password $pppoe_pass
		;;
	4 )
		username=`henan $pppoe_user`
		rgdb -i -s /runtime/wan/rg/inf:1/pppoe/starspeed/username $username
		rgdb -i -s /runtime/wan/rg/inf:1/pppoe/starspeed/password $pppoe_pass
		;;
	5 )
		username=`nanchang_campus $pppoe_user 18`
		rgdb -i -s /runtime/wan/rg/inf:1/pppoe/starspeed/username $username
		rgdb -i -s /runtime/wan/rg/inf:1/pppoe/starspeed/userformat 1
		rgdb -i -s /runtime/wan/rg/inf:1/pppoe/starspeed/password $pppoe_pass
		;;
	6 )
		username=`nanchang_campus $pppoe_user 0`
		rgdb -i -s /runtime/wan/rg/inf:1/pppoe/starspeed/username $username
		rgdb -i -s /runtime/wan/rg/inf:1/pppoe/starspeed/userformat 1
		rgdb -i -s /runtime/wan/rg/inf:1/pppoe/starspeed/password $pppoe_pass
		;;
esac
