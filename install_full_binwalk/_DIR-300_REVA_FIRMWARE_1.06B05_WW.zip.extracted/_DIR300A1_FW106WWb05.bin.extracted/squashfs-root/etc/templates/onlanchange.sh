#!/bin/sh
echo [$0] $1 ... > /dev/console
xmldbc -A /etc/templates/misc/onlanchange.php > /var/run/olc.sh
sh /var/run/olc.sh
