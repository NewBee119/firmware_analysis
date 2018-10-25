#!/bin/sh
starspeed=`rgdb -g /wan/rg/inf:1/pppoe/starspeed/enable`
wanmode=`rgdb -g /wan/rg/inf:1/mode`
wan2mode=`rgdb -g /wan/rg/inf:2/mode`
langcode=`cat /www/locale/alt/langcode`
if [ "$starspeed" != "1" -o "$wanmode" != "3" ];then
	exit;
fi
if [ "$wan2mode" != "" ];then
	exit;
fi
if [ -f /www/locale/alt/langcode -a "$langcode" = "zhcn" ];then
	echo "Start starspeed (For China)!";
else
	exit;
fi
echo [$0]...
#ifname="eth0.2"
ifname=`rgdb -i -g /runtime/layout/wanif`
pap_secret="/var/etc/ppp/pap-secrets"
chap_secret="/var/etc/ppp/chap-secrets"
pppoe_user=`rgdb -g /wan/rg/inf:1/pppoe/user`
pppoe_pass=`rgdb -g /wan/rg/inf:1/pppoe/password`
peer_ips="192.168.0.1 192.168.10.1 192.168.1.1 192.167.0.1 10.0.0.138 10.0.0.2"
pap_type=`rgdb -g /wan/rg/inf:1/pppoe/starspeed/type`
next_state=`rgdb -i -g /runtime/wan/rg/inf:1/pppoe/starspeed/nextstate`
is_change_account=`rgdb -i -g /runtime/wan/rg/inf:1/pppoe/starspeed/change`
if [ "$next_state" = "" ];then
	next_state="name"
fi
echo "Next state : "$next_state
curr_state=$next_state
if [ "$pap_type" != "" -a "$is_change_account" != "true" ];then
	curr_state=$pap_type
	rgdb -i -s /runtime/wan/rg/inf:1/pppoe/starspeed/change true
fi
echo "Current state : "$curr_state
case $curr_state in
	no )
		echo "\"$pppoe_user\" * \"$pppoe_pass\" *" > $pap_secret
		rgdb -i -s /runtime/wan/rg/inf:1/pppoe/starspeed/nextstate name
		rgdb -i -s /runtime/wan/rg/inf:1/pppoe/starspeed/currstate no
		rm -f $chap_secret
		ln -s $pap_secret $chap_secret
		cp /etc/ppp/options.session1 /etc/ppp/options.session1.tmp
		sed -e "/^user/c\user \"$pppoe_user\"" /etc/ppp/options.session1.tmp > /etc/ppp/options.session1
		rm -f /etc/ppp/options.session1.tmp
		;;
	name )
		username=`hubei $pppoe_user`
		echo "\"$username\" * \"$pppoe_pass\" *" > $pap_secret
		rgdb -i -s /runtime/wan/rg/inf:1/pppoe/starspeed/nextstate mac
		rgdb -i -s /runtime/wan/rg/inf:1/pppoe/starspeed/currstate name
		rm -f $chap_secret
		ln -s $pap_secret $chap_secret
		cp /etc/ppp/options.session1 /etc/ppp/options.session1.tmp
		sed -e "/^user/c\user \"$username\"" /etc/ppp/options.session1.tmp > /etc/ppp/options.session1
		rm -f /etc/ppp/options.session1.tmp
		;;
	mac | dname )
		for peer_ip in $peer_ips
		do
			if [ "$curr_state" = "dname" ];then
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
				rgdb -i -s /runtime/wan/rg/inf:1/pppoe/starspeed/nextstate dname
				rgdb -i -s /runtime/wan/rg/inf:1/pppoe/starspeed/currstate mac
				pap_pass=`pap_crack $pppoe_user $pppoe_pass $peer_mac`
				break
			fi
		done
		echo $pap_pass
		if [ "$pap_pass" = "" -o "$curr_state" = "dname" ];then
			rgdb -i -s /runtime/wan/rg/inf:1/pppoe/starspeed/nextstate henan
			rgdb -i -s /runtime/wan/rg/inf:1/pppoe/starspeed/currstate dname
			pap_pass=`pap_crack $pppoe_user $pppoe_pass`
		fi
		echo "pap pass":$pap_pass
		echo "\"$pppoe_user\" * \"$pap_pass\" *" > $pap_secret
		rm -f $chap_secret
		ln -s $pap_secret $chap_secret
		cp /etc/ppp/options.session1 /etc/ppp/options.session1.tmp
		sed -e "/^user/c\user \"$pppoe_user\"" /etc/ppp/options.session1.tmp > /etc/ppp/options.session1
		rm -f /etc/ppp/options.session1.tmp
		;;
	henan)
		username=`henan $pppoe_user`
		echo "\"$username\" * \"$pppoe_pass\" *" > $pap_secret
		rgdb -i -s /runtime/wan/rg/inf:1/pppoe/starspeed/nextstate no
		rgdb -i -s /runtime/wan/rg/inf:1/pppoe/starspeed/currstate henan
		rm -f $chap_secret
		ln -s $pap_secret $chap_secret
		cp /etc/ppp/options.session1 /etc/ppp/options.session1.tmp
		sed -e "/^user/c\user \"$username\"" /etc/ppp/options.session1.tmp > /etc/ppp/options.session1
		rm -f /etc/ppp/options.session1.tmp
		;;         
esac
