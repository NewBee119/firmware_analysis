#!/bin/sh
echo [$0] ... > /dev/console
for i in /etc/defnodes/S??* ;do
	[ ! -f "$i" ] && continue
	case "$i" in
	*.sh)
		sh $i
		;;
	*.php)
		echo "PHP [$i] ..." > /dev/console
		rgdb -A $i
		;;
	esac
done
echo [$0] Done !! > /dev/console
