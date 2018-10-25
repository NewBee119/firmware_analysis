#!/bin/sh
<? /* vi: set sw=4 ts=4: */
require("/etc/templates/troot.php");
anchor($TARGET_SERVICE);
require($template_root."/upnpd/__SUBSCRIBE.cleanup.php");
for ("subscription")
{
	$seq = query("seq");
	if ($seq=="") { $seq=0; }
	$host = query("host");
	echo "xmldbc -A ".$template_root."/upnpd/".$TARGET_PHP;
	echo " -V HDR_URL=".query("uri");
	echo " -V HDR_HOST=".$host;
	echo " -V HDR_SID=".query("uuid");
	echo " -V HDR_SEQ=".$seq;
	echo " | upnpkits -H ".$host." -p TCP\n";
	$seq++;
	set("seq", $seq);
}
?>
