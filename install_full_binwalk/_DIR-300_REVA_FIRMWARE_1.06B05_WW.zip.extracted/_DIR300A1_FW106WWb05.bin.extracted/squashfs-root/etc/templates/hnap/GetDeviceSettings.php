HTTP/1.1 200 OK
Content-Type: text/xml; charset=utf-8

<? echo "<"."?";?>xml version="1.0" encoding="utf-8"<? echo "?".">";?>
<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
  <soap:Body>
    <GetDeviceSettingsResponse xmlns="http://purenetworks.com/HNAP1/">
      <GetDeviceSettingsResult>OK</GetDeviceSettingsResult>
      <Type><?
	if (query("/runtime/router/enable")=="1") { echo "GatewayWithWiFi"; } else { echo "WiFiAccessPoint"; }
      ?></Type>
      <DeviceName><?get("x","/sys/devicename");?></DeviceName>
      <VendorName><?query("/sys/vendor");?></VendorName>
      <ModelDescription><?query("/sys/modeldescription");?></ModelDescription>
      <ModelName><?query("/sys/modelname");?> <?query("/runtime/sys/info/hardwareversion");?></ModelName>
      <FirmwareVersion><?query("/runtime/sys/info/firmwareversion");?>, <?query("/runtime/sys/info/firmwarebuildate");?></FirmwareVersion>
      <PresentationURL>/</PresentationURL>
      <CAPTCHA><?if(query("/sys/captcha")=="1") { echo "true"; } else { echo "false"; }?></CAPTCHA>
      <SOAPActions>
        <string>http://purenetworks.com/HNAP1/GetDeviceSettings</string>
        <string>http://purenetworks.com/HNAP1/SetDeviceSettings</string>
        <string>http://purenetworks.com/HNAP1/GetRouterLanSettings</string>
        <string>http://purenetworks.com/HNAP1/SetRouterLanSettings</string>
        <string>http://purenetworks.com/HNAP1/GetMACFilters</string>
        <string>http://purenetworks.com/HNAP1/SetMACFilters</string>
        <string>http://purenetworks.com/HNAP1/GetMACFilters2</string>
        <string>http://purenetworks.com/HNAP1/SetMACFilters2</string>
        <string>http://purenetworks.com/HNAP1/GetWanSettings</string>
        <string>http://purenetworks.com/HNAP1/SetWanSettings</string>
        <string>http://purenetworks.com/HNAP1/GetWLanSettings24</string>
        <string>http://purenetworks.com/HNAP1/SetWLanSettings24</string>
        <string>http://purenetworks.com/HNAP1/GetWLanSecurity</string>
        <string>http://purenetworks.com/HNAP1/SetWLanSecurity</string>
        <string>http://purenetworks.com/HNAP1/GetForwardedPorts</string>
        <string>http://purenetworks.com/HNAP1/SetForwardedPorts</string>
        <string>http://purenetworks.com/HNAP1/GetPortMappings</string>
        <string>http://purenetworks.com/HNAP1/AddPortMapping</string>
        <string>http://purenetworks.com/HNAP1/DeletePortMapping</string>
        <string>http://purenetworks.com/HNAP1/Reboot</string>
        <string>http://purenetworks.com/HNAP1/GetConnectedDevices</string>
        <string>http://purenetworks.com/HNAP1/RenewWanConnection</string>
        <string>http://purenetworks.com/HNAP1/GetNetworkStats</string>
        <string>http://purenetworks.com/HNAP1/IsDeviceReady</string>
		<string>http://purenetworks.com/HNAP1/SetAccessPointMode</string>


		<string>http://purenetworks.com/HNAP1/GetDeviceSettings2</string>
		<string>http://purenetworks.com/HNAP1/SetDeviceSettings2</string>
		<string>http://purenetworks.com/HNAP1/GetWLanRadios</string>
		<string>http://purenetworks.com/HNAP1/GetWLanRadioSettings</string>
		<string>http://purenetworks.com/HNAP1/SetWLanRadioSettings</string>
		<string>http://purenetworks.com/HNAP1/GetWLanRadioSecurity</string>
		<string>http://purenetworks.com/HNAP1/SetWLanRadioSecurity</string>
		<string>http://purenetworks.com/HNAP1/GetWanStatus</string>
		<string>http://purenetworks.com/HNAP1/GetClientStats</string>
		<string>http://purenetworks.com/HNAP1/GetRouterSettings</string>
		<string>http://purenetworks.com/HNAP1/SetRouterSettings</string>
      </SOAPActions>
      <SubDeviceURLs>
      </SubDeviceURLs>
      <Tasks>
      </Tasks>
    </GetDeviceSettingsResponse>
  </soap:Body>
</soap:Envelope>
