#!/bin/sh
echo [$0] ... > /dev/console
<?
anchor($TARGET_SERVICE);
require("/etc/templates/upnpd/__SUBSCRIBE.cleanup.php");
for ("subscription")
{
	if ($REMOTE_ADDR=="" || $REMOTE_ADDR==query("remote"))
	{
		$seq = query("seq");
		if ($seq=="") { $seq=0; }

		$host = query("host");
		$uuid = query("uuid");
		$temp_file = "/var/run/WFAWLANConfig-".$uuid."-payload";

		echo "xmldbc -A /etc/templates/upnpd/__NOTIFY.req.event.php";
		echo " -V HDR_URL=".query("uri");
		echo " -V HDR_HOST=".$host;
		echo " -V HDR_SID=".$uuid;
		echo " -V HDR_SEQ=".$seq;
		echo " > ".$temp_file."\n";

		echo "wfanotify";
		echo " -t ".$EVENT_TYPE;
		echo " -m ".$EVENT_MAC;
		echo " -f ".$EVENT_PAYLOAD;
		echo " >> ".$temp_file."\n";

		echo "cat ".$temp_file." | upnpkits -H ".$host." -p TCP\n";
		echo "rm -f ".$temp_file." ".$EVENT_PAYLOAD."\n";
		$seq++;
		set("seq", $seq);
	}
}
?>
