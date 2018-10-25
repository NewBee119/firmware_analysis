<? /* vi: set sw=4 ts=4: */
for ("device")
{
	/* get device info */
	$DTYPE	= query("devicetype");
	$UUID	= query("udn");

	echo "# curr:".$@." = [".$DTYPE."],[".$UUID."],target[".$TARGET_DEVICE."]\n";
	if ($DTYPE == $TARGET_DEVICE)
	{
		echo $CMD;
		echo " -V ST=\"".	$DTYPE."\"";
		echo " -V USN=\"".	$UUID."::".$DTYPE."\"";
		echo $CMDTAIL;
	}
	if ($UUID == $TARGET_UUID)
	{
		echo $CMD;
		echo " -V ST=\"".	$UUID."\"";
		echo " -V USN=\"".	$UUID."\"";
		echo $CMDTAIL;
	}
	for ("service")
	{
		$STYPE	= query("servicetype");
		echo "# service:".$@." = [".$STYPE."],target[".$TARGET_SERVICE."]\n";
		if ($STYPE == $TARGET_SERVICE)
		{
			echo $CMD;
			echo " -V ST=\"".	$STYPE."\"";
			echo " -V USN=\"".	$UUID."::".$STYPE."\"";
			echo $CMDTAIL;
		}
	}

	/* walk for embeded devices */
	require($template_root."/upnpd/__M-SEARCH.walk.device.php");
}
?>
