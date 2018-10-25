#!/bin/sh
xmldbc -x /runtime/genuuid	"get:genuuid -r"
xmldbc -x /runtime/genpin	"get:wps -g"
# DDNS status
xmldbc -x /runtime/ddns/status		"get:scut -p status: /var/run/dyndns.info"
xmldbc -x /runtime/ddns/errormsg	"get:scut -p error_msg: /var/run/dyndns.info"
xmldbc -x /runtime/ddns/ipaddr		"get:scut -p ip: /var/run/dyndns.info"
xmldbc -x /runtime/ddns/uptime		"get:scut -p uptime: /var/run/dyndns.info"
xmldbc -x /runtime/ddns/provider	"get:scut -p provider: /var/run/dyndns.info"

#get kernel version for hidden page
xmldbc -x /sys/kernel_version "get:cut -d\\( -f1 proc/version"