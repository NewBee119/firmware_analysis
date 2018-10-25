<? /* vi: set sw=4 ts=4: */
for ("device")
{
	/* get device info */
	$DTYPE	= query("devicetype");
	$UUID	= query("udn");

	echo "# curr:".$@." = [".$DTYPE."],[".$UUID."]\n";
	if ($DTYPE != "")
	{
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
