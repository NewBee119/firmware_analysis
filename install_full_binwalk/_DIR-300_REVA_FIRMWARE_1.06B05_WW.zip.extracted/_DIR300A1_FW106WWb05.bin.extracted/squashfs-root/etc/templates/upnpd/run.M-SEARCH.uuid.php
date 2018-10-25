#!/bin/sh
<? /* vi: set sw=4 ts=4: */
require("/etc/templates/troot.php");
$TARGET_UUID=$PARAM;

for ("/runtime/upnpdev/root")
{
	/* prepare common values */
	require($template_root."/upnpd/__M-SEARCH.common.php");

	/* get device info */
	$DTYPE	= query("devicetype");
	$UUID	= query("udn");

	echo "# root:".$@." = [".$DTYPE."],[".$UUID.",target[".$TARGET_DEVICE."]\n";
	if ($UUID == $TARGET_UUID)
	{
		echo $CMD;
		echo " -V ST=\"".	$UUID."\"";
		echo " -V USN=\"".	$UUID."\"";
		echo $CMDTAIL;
	}
	require($template_root."/upnpd/__M-SEARCH.walk.device.php");
}
?>
