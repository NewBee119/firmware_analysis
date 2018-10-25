#!/bin/sh
echo "[$0] ..." > /dev/console
TROOT=`rgdb -i -g /runtime/template_root`
[ "$TROOT" = "" ] && TROOT="/etc/templates"
rm -f /var/run/timezone_set.sh > /dev/console
if [ -f /var/run/timezone_set.sh ]; then
	/var/run/timezone_set.sh > /dev/console &
else
	rgdb -A $TROOT/misc/timezone_setup.php > /var/run/timezone_set.sh
	chmod +x /var/run/timezone_set.sh
	/var/run/timezone_set.sh
fi
