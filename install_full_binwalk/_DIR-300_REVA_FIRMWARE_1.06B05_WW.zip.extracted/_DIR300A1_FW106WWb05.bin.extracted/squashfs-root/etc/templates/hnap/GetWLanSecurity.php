HTTP/1.1 200 OK
Content-Type: text/xml; charset=utf-8

<?
echo "\<\?xml version='1.0' encoding='utf-8'\?\>";
anchor("/wireless");
$auth=query("authtype");
$encrypt=query("encrypttype");
$key="";
if($encrypt!="0")
{
	$enabled="true";
	if($auth>1)
	{
		$auth="WPA";
		$key=get("x","wpa/radius:1/secret");
	}
	else
	{
		$auth="WEP";
		$id=query("wep/defkey");
		$key=get("x","wep/key:".$id);
	}
}
else
{
	$enabled="false";
	$auth="WEP";
}
?>
<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
  <soap:Body>
    <GetWLanSecurityResponse xmlns="http://purenetworks.com/HNAP1/">
      <GetWLanSecurityResult>OK</GetWLanSecurityResult>
      <Enabled><?=$enabled?></Enabled>
      <Type><?=$auth?></Type>
      <WEPKeyBits><?map("wep/length", "", "64");?></WEPKeyBits>
      <SupportedWEPKeyBits>
        <int>64</int>
        <int>128</int>
      </SupportedWEPKeyBits>
      <Key><?=$key?></Key>
      <RadiusIP1><?query("wpa/radius:1/host");?></RadiusIP1>
      <RadiusPort1><?map("wpa/radius:1/port", "", "0");?></RadiusPort1>
      <RadiusIP2><?query("wpa/radius:2/host");?></RadiusIP2>
      <RadiusPort2><?map("wpa/radius:2/port", "", "0");?></RadiusPort2>
    </GetWLanSecurityResponse>
  </soap:Body>
</soap:Envelope>
