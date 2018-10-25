#!/bin/sh
echo [$0] ... > /dev/console
nvram=`cat /etc/config/nvram`

# NVRAM, rgcfg.
xmldbc -x /runtime/nvram/flashspeed	 			"get:rgcfg getenv -n $nvram -e flashspeed"
xmldbc -x /runtime/nvram/pin					"get:rgcfg getenv -n $nvram -e pin"
xmldbc -x /runtime/nvram/wanmac					"get:rgcfg getenv -n $nvram -e wanmac"
xmldbc -x /runtime/nvram/lanmac					"get:rgcfg getenv -n $nvram -e lanmac"
xmldbc -x /runtime/nvram/wlanmac				"get:rgcfg getenv -n $nvram -e wlanmac"
xmldbc -x /runtime/nvram/hwrev					"get:rgcfg getenv -n $nvram -e hwrev"
xmldbc -x /runtime/nvram/countrycode			"get:rgcfg getenv -n $nvram -e countrycode"
xmldbc -x /runtime/nvram/wpa/key                "get:rgcfg getenv -n $nvram -e key"
# time
xmldbc -x /runtime/sys/uptime					"get:uptime seconly"
xmldbc -x /runtime/time/date					"get:date +%m/%d/%Y"
xmldbc -x /runtime/time/time					"get:date +%T"
xmldbc -x /runtime/time/rfc1123					"get:date +'%a, %d %b %Y %X %Z'"
# statistics
xmldbc -x /runtime/stats/lan/rx/bytes			"get:scut -p br0: -f 1 /proc/net/dev"
xmldbc -x /runtime/stats/lan/rx/packets			"get:scut -p br0: -f 2 /proc/net/dev"
xmldbc -x /runtime/stats/lan/tx/bytes			"get:scut -p br0: -f 9 /proc/net/dev"
xmldbc -x /runtime/stats/lan/tx/packets			"get:scut -p br0: -f 10 /proc/net/dev"
xmldbc -x /runtime/stats/wireless/rx/bytes		"get:scut -p ath0: -f 1 /proc/net/dev"
xmldbc -x /runtime/stats/wireless/rx/packets	"get:scut -p ath0: -f 2 /proc/net/dev"
xmldbc -x /runtime/stats/wireless/tx/bytes		"get:scut -p ath0: -f 9 /proc/net/dev"
xmldbc -x /runtime/stats/wireless/tx/packets	"get:scut -p ath0: -f 10 /proc/net/dev"
# cable status
xmldbc -x /runtime/switch/port:1/linktype		"get:psts -i 3"
xmldbc -x /runtime/switch/port:2/linktype		"get:psts -i 2"
xmldbc -x /runtime/switch/port:3/linktype		"get:psts -i 1"
xmldbc -x /runtime/switch/port:4/linktype		"get:psts -i 0"
xmldbc -x /runtime/switch/wan_port				"get:psts -i 4"
