#!/bin/sh
echo [$0] ... > /dev/console
<? /* vi: set sw=4 ts=4: */
require("/etc/templates/troot.php");

$conf_file = "/var/lld2d.conf";

$lanif = query("/runtime/layout/lanif");
if (query("/wireless/enable")==1) {	$wlanif = query("/runtime/layout/wlanif"); }
else { $wlanif = ""; }
if (query("/wlan/inf:3/enable")==1) { $wlanif_11a = query("/runtime/layout/wlanif_11a"); }
else { $wlanif_11a = ""; }

if ($generate_start==1)
{
	echo "echo Start LLD2 daemon ... > /dev/console\n";
	fwrite($conf_file, "icon = /www/pic/lld2d.ico\n");
	fwrite2($conf_file, "jumbo-icon = /www/pic/lld2d.ico\n");
	echo "lld2d -c ".$conf_file." ".$lanif." ".$wlanif." ".$wlanif_11a." & > /dev/console\n";
}
else
{
	echo "echo Stop LLD2 daemon ... > /dev/console\n";
	echo "killall lld2d\n";
}
?>
