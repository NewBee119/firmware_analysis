HTTP/1.1 200 OK
Content-Type: text/xml; charset=utf-8

<?
echo "\<\?xml version='1.0' encoding='utf-8'\?\>";
// set wireless client to tmp
$nodebase="/runtime/hnap/GetClientStats";
del("/runtime/hnap/GetClientStats");
$nodebase=$nodebase."entry";
$i=0;
for("/runtime/stats/wireless/client")
{
	$i++;
	set($nodebase.":".$i."/Rssi", query("rssi"));
	set($nodebase.":".$i."/mac", query("mac"));
	set($nodebase.":".$i."/Wireless", "true");
	set($nodebase.":".$i."/LinkSpeedIn", query("rate"));
	set($nodebase.":".$i."/LinkSpeedOut", query("rate"));
}

for("/runtime/dhcpserver/lease")
{
	$mac=query("mac");
	$found=0;
	$j=0;
	for($nodebase)
	{
		$j++;
		if($mac==query("mac"))
		{
			$found=1;
		}
	}

	if($found==0)
	{
		$i++;
		set($nodebase.":".$i."/mac", $mac);
		set($nodebase.":".$i."/LinkSpeedIn", "100");
		set($nodebase.":".$i."/LinkSpeedOut", "100");
		set($nodebase.":".$i."/Wireless", "false");
		set($nodebase.":".$i."/Rssi", "0");
	}
}
?>
<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
  <soap:Body>
    <GetClientStatsResponse xmlns="http://purenetworks.com/HNAP1/">
      <GetClientStatsResult>OK</GetClientStatsResult>
      <ClientStats>
<?
for($nodebase)
{
	echo "        <ClientStat>\n";
	echo "          <MacAddress>".query("mac")."</MacAddress>\n";
	echo "          <Wireless>".query("Wireless")."</Wireless>\n";
	echo "          <LinkSpeedIn>".query("LinkSpeedIn")."</LinkSpeedIn>\n";
	echo "          <LinkSpeedOut>".query("LinkSpeedOut")."</LinkSpeedOut>\n";
	echo "          <SignalStrength>".query("Rssi")."</SignalStrength>\n";
	echo "        </ClientStat>\n";
}
?>    
     </ClientStats>
    </GetClientStatsResponse>
  </soap:Body>
</soap:Envelope>
