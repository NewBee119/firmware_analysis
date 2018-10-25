#!/bin/sh
<? /* vi: set sw=4 ts=4: */

for ("/runtime/upnpdev/root")
{
	/* prepare common values */
	require($template_root."/upnpd/__NOTIFY.common.php");

	/* get device info */
	$DTYPE	= query("devicetype");
	$UUID	= query("udn");

	echo "# root:".$@." = [".$DTYPE."],[".$UUID."]\n";
	if ($DTYPE != "")
	{
		/* Three discovery message for root device. */
		$i=0;
		while ($i<2)
		{
			echo $CMD;
			echo " -V NT=\"upnp:rootdevice\"";
			echo " -V USN=\"".$UUID."::upnp:rootdevice\"";
			echo $CMDTAIL;
			$i++;
		}

		$i=0;
		while ($i<2)
		{
			echo $CMD;
			echo " -V NT=\"".$UUID."\"";
			echo " -V USN=\"".$UUID."\"";
			echo $CMDTAIL;
			$i++;
		}

		$i=0;
		while ($i<2)
		{
			echo $CMD;
			echo " -V NT=\"".$DTYPE."\"";
			echo " -V USN=\"".$UUID."::".$DTYPE."\"";
			echo $CMDTAIL;
			$i++;
		}

		/* services */
		for ("service")
		{
			$STYPE = query("servicetype");
			echo "# service:".$@." = [".$STYPE."]\n";
			if ($STYPE != "")
			{
				$i=0;
				while ($i<2)
				{
					echo $CMD;
					echo " -V NT=\"".$STYPE."\"";
					echo " -V USN=\"".$UUID."::".$STYPE."\"";
					echo $CMDTAIL;
					$i++;
				}
			}
		}

		/* walk embeded devices */
		require($template_root."/upnpd/__NOTIFY.walk.all.devices.php");
	}
}

?>
