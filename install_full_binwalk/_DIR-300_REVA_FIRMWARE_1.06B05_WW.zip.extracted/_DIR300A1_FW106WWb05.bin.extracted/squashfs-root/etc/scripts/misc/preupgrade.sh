#!/bin/sh
echo [$0] $1 ... > /dev/console
if [ "$1" = "restore" ]; then
	echo RESTORE > /dev/console
	/etc/templates/lld2d.sh restart	> /dev/console
	/etc/templates/neaps.sh	restart	> /dev/console
	if [ "`rgdb -i -g /runtime/func/lpd`" = "1" ]; then
	/etc/templates/lpd.sh start		> /dev/console
	fi
else
	echo PREUPGRADE > /dev/console
	/etc/templates/lld2d.sh stop	> /dev/console
	/etc/templates/neaps.sh stop	> /dev/console
	if [ "`rgdb -i -g /runtime/func/lpd`" = "1" ]; then
	/etc/templates/lpd.sh stop		> /dev/console
	fi
	if [ -f /etc/templates/smbd.sh ]; then
	/etc/templates/smbd.sh smbumount_start > /dev/console
	fi
fi
