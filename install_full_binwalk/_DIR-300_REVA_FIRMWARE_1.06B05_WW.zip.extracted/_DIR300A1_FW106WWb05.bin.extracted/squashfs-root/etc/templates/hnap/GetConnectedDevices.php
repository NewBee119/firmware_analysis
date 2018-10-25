HTTP/1.1 200 OK
Content-Type: text/xml; charset=utf-8

<?
echo "\<\?xml version='1.0' encoding='utf-8'\?\>";
// set wireless client to tmp
$nodebase="/runtime/hnap/GetConnectedDevices";
del("/runtime/hnap/GetConnectedDevices");
$nodebase=$nodebase."entry";
$i=0;
for("/runtime/stats/wireless/client")
{
	$i++;
	set($nodebase.":".$i."/time", query("time"));
	set($nodebase.":".$i."/mac", query("mac"));
	set($nodebase.":".$i."/Wireless", "true");
	set($nodebase.":".$i."/Active", "true");
	$mode=query("mode");
	if($mode=="11b")
	{
		set($nodebase.":".$i."/PortName", "WLAN 802.11b");
	}
	else if($mode=="11g")
	{
		set($nodebase.":".$i."/PortName", "WLAN 802.11g");
	}
}

for("/runtime/dhcpserver/lease")
{
	$mac=query("mac");
	$hostname=query("hostname");
	$found=0;
	$j=0;
	for($nodebase)
	{
		$j++;
		if($mac==query("mac"))
		{
			set($nodebase.":".$j."DeviceName", $hostname);
			$found=1;
		}
	}

	if($found==0)
	{
		$i++;
		set($nodebase.":".$i."/time", "");
		set($nodebase.":".$i."/mac", $mac);
		set($nodebase.":".$i."/DeviceName", $hostname);
		set($nodebase.":".$i."/PortName", "LAN");
		set($nodebase.":".$i."/Wireless", "false");
		set($nodebase.":".$i."/Active", "true");
	}
}
?>
<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
  <soap:Body>
    <GetConnectedDevicesResponse xmlns="http://purenetworks.com/HNAP1/">
      <GetConnectedDevicesResult>OK</GetConnectedDevicesResult>
      <ConnectedClients>
<?
for($nodebase)
{
	echo "        <ConnectedClient>\n";
//	echo "          <ConnectTime>".query("time")."</ConnectTime>\n";
	echo "          <ConnectTime>2005-05-31T17:23:18</ConnectTime>\n";
	echo "          <MacAddress>".query("mac")."</MacAddress>\n";
	echo "          <DeviceName>".query("DeviceName")."</DeviceName>\n";
	echo "          <PortName>".query("PortName")."</PortName>\n";
	echo "          <Wireless>".query("Wireless")."</Wireless>\n";
	echo "          <Active>".query("Active")."</Active>\n";
	echo "        </ConnectedClient>\n";
}
?>      </ConnectedClients>
    </GetConnectedDevicesResponse>
  </soap:Body>
</soap:Envelope>
