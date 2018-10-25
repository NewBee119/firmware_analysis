#!/bin/sh
[ "$1" != "" ] && sleep "$1"
[ -f /etc/templates/wan.sh ] && /etc/templates/wan.sh stop > /dev/console
reboot
