#!/bin/sh
echo [$0] ... > /dev/console

shell="/var/run/restart_ap.sh"
echo "#!/bin/sh"					>  $shell
echo "/etc/templates/wlan.sh stop"	>> $shell
echo "submit COMMIT"				>> $shell
echo "/etc/templates/wlan.sh start"	>> $shell
echo "rm -f $shell"					>> $shell
xmldbc -t "WPS:1:sh $shell > /dev/console"
