#!/bin/sh
echo [$0] ... > /dev/console
# Check if WAN down is in progress
while [ -f /var/run/wan_down_running ]; do
	echo "[$0] wan down is running, wait 1 sec !!!" > /dev/console
	sleep 1
done
# Clear, it's our turn.
echo "running" > /var/run/wan_down_running
rgdb -A /etc/templates/wan_down.php > /var/run/wan_down.sh
sh /var/run/wan_down.sh > /dev/console
rm -f /var/run/wan_down_running
exit 0
