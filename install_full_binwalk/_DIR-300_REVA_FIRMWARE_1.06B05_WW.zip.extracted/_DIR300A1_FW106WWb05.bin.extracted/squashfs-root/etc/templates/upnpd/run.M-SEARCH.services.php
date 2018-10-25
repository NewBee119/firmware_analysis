#!/bin/sh
<? /* vi: set sw=4 ts=4: */
require("/etc/templates/troot.php");
$TARGET_SERVICE=$PARAM;

for ("/runtime/upnpdev/root")
{
	/* prepare common values */
	require($template_root."/upnpd/__M-SEARCH.common.php");

	/* get device info */
	$DTYPE	= query("devicetype");
	$UUID	= query("udn");

	echo "# root:".$@." = [".$DTYPE."],[".$UUID.",target[".$TARGET_DEVICE."]\n";
	for ("service")
	{
		$STYPE = query("servicetype");
		echo "# service:".$@." = [".$STYPE."],target[".$TARGET_SERVICE."]\n";
		if ($STYPE == $TARGET_SERVICE)
		{
			echo $CMD;
			echo " -V ST=\"".   $STYPE."\"";
			echo " -V USN=\"".  $UUID."::".$STYPE."\"";
			echo $CMDTAIL;
		}
	}

	/* walk for embeded devices */
	require($template_root."/upnpd/__M-SEARCH.walk.device.php");
}
?>
