HTTP/1.1 200 OK
Content-Type: text/xml; charset=utf-8

<?
echo "\<\?xml version='1.0' encoding='utf-8'\?\>";
?>
<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
  <soap:Body>
    <GetNetworkStatsResponse xmlns="http://purenetworks.com/HNAP1/">
    <GetNetworkStatsResult>OK</GetNetworkStatsResult>
      <Stats>
<?
anchor("/runtime/stats/lan");
echo "        <NetworkStats>\n";
echo "          <PortName>LAN</PortName>\n";
echo "          <PacketsReceived>".query("rx/packets")."</PacketsReceived>\n";
echo "          <PacketsSent>".query("tx/packets")."</PacketsSent>\n";
echo "          <BytesReceived>".query("rx/bytes")."</BytesReceived>\n";
echo "          <BytesSent>".query("tx/bytes")."</BytesSent>\n";
echo "        </NetworkStats>\n";

anchor("/runtime/stats/wireless");
echo "        <NetworkStats>\n";
echo "          <PortName>WLAN 802.11b</PortName>\n";
echo "          <PacketsReceived>".query("rx/packets")."</PacketsReceived>\n";
echo "          <PacketsSent>".query("tx/packets")."</PacketsSent>\n";
echo "          <BytesReceived>".query("rx/bytes")."</BytesReceived>\n";
echo "          <BytesSent>".query("tx/bytes")."</BytesSent>\n";
echo "        </NetworkStats>\n";
?>      </Stats>
    </GetNetworkStatsResponse>
  </soap:Body>
</soap:Envelope>