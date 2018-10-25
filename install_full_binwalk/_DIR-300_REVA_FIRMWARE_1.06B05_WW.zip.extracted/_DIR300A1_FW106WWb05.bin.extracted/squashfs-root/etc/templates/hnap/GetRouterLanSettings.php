HTTP/1.1 200 OK
Content-Type: text/xml; charset=utf-8

<?
echo "\<\?xml version='1.0' encoding='utf-8'\?\>";
anchor("/lan");
$dhcp_enbled="false";
if(query("dhcp/server/enable")==1)
{
	$dhcp_enbled="true";
}
?>
<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
  <soap:Body>
    <GetRouterLanSettingsResponse xmlns="http://purenetworks.com/HNAP1/">
      <GetRouterLanSettingsResult>OK</GetRouterLanSettingsResult>
      <RouterIPAddress><?query("ethernet/ip");?></RouterIPAddress>
      <RouterSubnetMask><?query("ethernet/netmask");?></RouterSubnetMask>
      <DHCPServerEnabled><?=$dhcp_enbled?></DHCPServerEnabled>
    </GetRouterLanSettingsResponse>
  </soap:Body>
</soap:Envelope>
