<? /* vi: set sw=4 ts=4: */
$ccode = query("/sys/countrycode");
if ($ccode == "") { $ccode = query("/runtime/nvram/countrycode"); }
if ($ccode == "") { $ccode = "840"; }
?>
echo "Restart WLAN driver ... " > /dev/console
# remove interfaces from bridge
brctl delif br0 eth0.0
brctl delif br0 ath0
# destroy and remove wireless driver.
wlanconfig ath0 destroy
rmmod ath_ahb
# remove module ath_dfs.o for madwifi driver v5.2.0.112
test -f /lib/modules/ath_dfs.o && rmmod ath_dfs
rmmod ath_rate_atheros ath_hal wlan_acl wlan_ccmp wlan_scan_ap wlan_scan_sta wlan_tkip wlan_wep wlan_xauth wlan
# install and create wireless driver
insmod /lib/modules/wlan.o; insmod /lib/modules/wlan_xauth.o; insmod /lib/modules/wlan_wep.o; \
insmod /lib/modules/wlan_tkip.o; insmod /lib/modules/wlan_scan_sta.o; insmod /lib/modules/wlan_scan_ap.o; \
insmod /lib/modules/wlan_ccmp.o; insmod /lib/modules/wlan_acl.o; insmod /lib/modules/ath_hal.o; \
insmod /lib/modules/ath_rate_atheros.o
# insert module ath_dfs.o for madwifi driver v5.2.0.112
test -f /lib/modules/ath_dfs.o && insmod /lib/modules/ath_dfs.o
insmod /lib/modules/ath_ahb.o countrycode=<?=$ccode?>
wlanconfig ath0 create wlandev wifi0 wlanmode ap
# add interfaces to bridge
brctl addif br0 ath0
brctl addif br0 eth0.0
