HTTP/1.1 200 OK
Content-Type: text/xml; charset=utf-8
<?
$tzInx = get("x","/time/timezone");
if( $tzInx != "" )
{
	$tzStr = get("x","/hnap/timezone:".$tzInx."/name");
}
$ds = query("/time/daylightsaving");
if($ds == "1")
{ $autoAdj = "true"; }
else
{ $autoAdj = "false"; }
$locale  = query("/hnap/Locale");
?>
<? echo "<"."?";?>xml version="1.0" encoding="utf-8"<? echo "?".">";?>
<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
  <soap:Body>
    <GetDeviceSettings2Response xmlns="http://purenetworks.com/HNAP1/">
      <GetDeviceSettings2Result>OK</GetDeviceSettings2Result>
      <SerialNumber><?
		echo "00000001";
      ?></SerialNumber>
      <TimeZone><?=$tzStr?></TimeZone>
      <AutoAdjustDST><?=$autoAdj?></AutoAdjustDST>
      <Locale><?=$locale?></Locale>
      <SupportedLocales><string></string></SupportedLocales>
      <SSL><?echo "false";?></SSL>
    </GetDeviceSettings2Response>
  </soap:Body>
</soap:Envelope>
