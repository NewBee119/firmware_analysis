#!/bin/sh
echo [$0] ... > /dev/console
# Check if WAN up is in progress
while [ -f /var/run/wan_up_running ]; do
	echo "[$0] wan up is running, wait 1 sec. !!!" > /dev/console
	sleep 1
done
# Clear, it's our turn.
echo "running" > /var/run/wan_up_running
rgdb -A /etc/templates/wan_up.php > /var/run/wan_up.sh
sh /var/run/wan_up.sh > /dev/console
rm -f /var/run/wan_up_running
exit 0
