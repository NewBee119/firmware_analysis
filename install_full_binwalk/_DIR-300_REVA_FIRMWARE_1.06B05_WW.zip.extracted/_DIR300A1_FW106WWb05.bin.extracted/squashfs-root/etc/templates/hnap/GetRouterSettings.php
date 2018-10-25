HTTP/1.1 200 OK
Content-Type: text/xml; charset=utf-8

<?
echo "\<\?xml version='1.0' encoding='utf-8'\?\>";
anchor("/security/firewall");
$remoteMng = query("httpallow");
if( $remoteMng == "1" ) { $remoteMngStr = "true"; } else { $remoteMngStr = "false"; }
$remotePort = query("httpremoteport");
$remoteSSL = "false";
$remoteName = get("x","/ddns/hostname");

if( query("/qos/mode") == "1" )
{ $wireQos = "true"; }
else
{ $wireQos = "false"; }

$mngWlan = query("/hnap/SetRouterSettings/ManageWireless");
if( $mngWlan == "" )
{ $mngWlan = "true"; }

//$pinCode = query("/wireless/wps/pin"); 
//if($pinCode == ""){ $pinCode = query("/runtime/wps/pin"); }
?>
<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
  <soap:Body>
    <GetRouterSettingsResponse xmlns="http://purenetworks.com/HNAP1/">
      <GetRouterSettingsResult>OK</GetRouterSettingsResult>
      <ManageRemote><?=$remoteMngStr?></ManageRemote>
      <ManageWireless><?=$mngWlan?></ManageWireless>
      <RemotePort><?=$remotePort?></RemotePort>
	  <RemoteSSL><?=$remoteSSL?></RemoteSSL>
	  <DomainName><?=$remoteName?></DomainName>
	  <WiredQoS><?=$wireQos?></WiredQoS>
	  <WPSPin><?=$pinCode?></WPSPin>
    </GetRouterSettingsResponse>
  </soap:Body>
</soap:Envelope>
