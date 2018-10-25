#!/bin/sh
echo [$0] ... > /dev/console
TROOT="/etc/templates"
rm -rf /var/run/dyndns.html > /dev/console
rm -rf /var/run/dyndns.info > /dev/console
xmldbc -A $TROOT/misc/dyndns_test_run.php -V OUTPUT_FILE="/var/run/dyndns_test.sh"
sh /var/run/dyndns_test.sh > /dev/console
