#!/bin/sh
echo [$0] ... > /dev/console
TROOT="/etc/templates"

lanif=`xmldbc -i -g /runtime/layout/lanif`

start="lpd -I $lanif -Z 1 -U 1"

stop="killall lpd; killall rawlpd"

if [ "`xmldbc -i -g /runtime/router/enable`" = "0" ]; then
	hostname=`xmldbc -g /sys/hostname`
	i=0
	while [ "$i" -lt "10" ]; do
		ipaddr=`xmldbc -i -g /runtime/wan/inf:1/ip`
		[ "$ipaddr" != "" ] && break
		echo "Waiting for WAN ip to be set ..." > /dev/console
		sleep 1
		i=`expr $i + 1`
	done

	hostname $hostname
	echo "127.0.0.1 localhost" > /etc/hosts
	[ "$ipaddr" != "" ] && echo "$ipaddr $hostname" >> /etc/hosts
fi

case "$1" in
start|restart)
	eval $stop
	eval $start
	;;
stop)
	eval $stop
	;;
delay_restart)
	sleep 2
	eval $start
	;;
*)
	echo "usage: lpd.sh {start|stop|restart|delay_restart}"
	;;
esac
