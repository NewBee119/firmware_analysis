#!/bin/sh
<? /* vi: set sw=4 ts=4: */
require("/etc/templates/troot.php");

for ("/runtime/upnpdev/root")
{
	/* prepare common values */
	require($template_root."/upnpd/__M-SEARCH.common.php");

	/* get device info */
	$DTYPE	= query("devicetype");
	$UUID	= query("udn");

	echo "# root:".$@." = [".$DTYPE."],[".$UUID."]\n";
	if ($DTYPE != "")
	{
		echo $CMD;
		echo " -V ST=\"".	$UUID."\"";
		echo " -V USN=\"".	$UUID."\"";
		echo $CMDTAIL;

		echo $CMD;
		echo " -V ST=\"".	$DTYPE."\"";
		echo " -V USN=\"".	$UUID."::".$DTYPE."\"";
		echo $CMDTAIL;

		echo $CMD;
		echo " -V ST=\"upnp:rootdevice\"";
		echo " -V USN=\"".$UUID."::upnp:rootdevice\"";
		echo $CMDTAIL;
	}
}
?>
