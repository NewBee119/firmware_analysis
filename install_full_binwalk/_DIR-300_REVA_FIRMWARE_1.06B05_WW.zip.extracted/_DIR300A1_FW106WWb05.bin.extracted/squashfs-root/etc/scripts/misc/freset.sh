#!/bin/sh
echo [$0] ... > /dev/console
sleep 5
/etc/templates/wan.sh stop > /dev/console
/etc/scripts/misc/profile.sh reset
echo "[$0] reset config done !" > /dev/console
reboot
