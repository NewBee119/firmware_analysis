HTTP/1.1 200 OK
Content-Type: text/xml; charset=utf-8

<?
echo "\<\?xml version='1.0' encoding='utf-8'\?\>";
anchor("/wireless11a");

$channel=query("/wireless11a/channel");
if(query("enable")=="1" && query("autochannel")=="1")
{
		//update channel value when autochannel setup for HNAP Spec.
		//$channel=query("/runtime/stats/wireless11a/channel");
		$channel="0";
}
?>
<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
  <soap:Body>
    <GetWLanSettings54Response xmlns="http://purenetworks.com/HNAP1/">
      <GetWLanSettings54Result>OK</GetWLanSettings54Result>
      <Enabled><?map("enable", "1", "true", "*", "false");?></Enabled>
      <MacAddress><?query("/runtime/sys/info/lanmac");?></MacAddress>
      <SSID><?query("ssid");?></SSID>
      <SSIDBroadcast><?map("ssidHidden", "1", "false", "*", "true");?></SSIDBroadcast>
      <Channel><?=$channel?></Channel>
    </GetWLanSettings54Response>
  </soap:Body>
</soap:Envelope>
