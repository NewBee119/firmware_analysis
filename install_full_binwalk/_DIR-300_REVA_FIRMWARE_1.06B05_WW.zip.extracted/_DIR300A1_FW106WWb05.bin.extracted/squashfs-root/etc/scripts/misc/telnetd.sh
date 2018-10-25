#!/bin/sh
image_sign=`cat /etc/config/image_sign`
TELNETD=`rgdb -g /sys/telnetd`
is_default=`rgdb -g /sys/restore_default`
if [ "$TELNETD" = "true" ] && [ "$is_default" = "0" ]; then
	echo "Start telnetd ..." > /dev/console
	if [ -f "/usr/sbin/login" ]; then
		lf=`rgdb -i -g /runtime/layout/lanif`
		telnetd -l "/usr/sbin/login" -u Alphanetworks:$image_sign -i $lf &
	else
		telnetd &
	fi
else
	killall telnetd
fi
