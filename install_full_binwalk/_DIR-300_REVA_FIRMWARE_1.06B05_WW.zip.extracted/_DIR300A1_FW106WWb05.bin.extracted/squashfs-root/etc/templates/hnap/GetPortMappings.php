HTTP/1.1 200 OK
Content-Type: text/xml; charset=utf-8

<?
echo "\<\?xml version='1.0' encoding='utf-8'\?\>";
?>
<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
  <soap:Body>
    <GetPortMappingsResponse xmlns="http://purenetworks.com/HNAP1/">
      <GetPortMappingsResult>OK</GetPortMappingsResult>
      <PortMappings>
<?
	for("/nat/vrtsrv/entry")
	{
		if(query("enable")=="1")
		{
			$prot="both";
			if(query("protocol")=="1")
			{
				$prot="TCP";
			}
			else if(query("protocol")=="2")
			{
				$prot="UDP";
			}
			$ip=query("privateip");
			if($ip=="0.0.0.0")
			{
				$ip="";
			}
			echo "        <PortMapping>\n";
			echo "          <PortMappingDescription>".get("x","description")."</PortMappingDescription>\n";
			echo "          <InternalClient>".$ip."</InternalClient>\n";
			if($prot=="both")
			{
				echo "          <PortMappingProtocol>TCP</PortMappingProtocol>\n";
			}
			else
			{
				echo "          <PortMappingProtocol>".$prot."</PortMappingProtocol>\n";
			}
			echo "          <ExternalPort>".query("publicPort")."</ExternalPort>\n";
			echo "          <InternalPort>".query("privatePort")."</InternalPort>\n";
			echo "        </PortMapping>\n";
			if($prot=="both")
			{
				echo "        <PortMapping>\n";
				echo "          <Enabled>".$enable."</Enabled>\n";
				echo "          <PortMappingDescription>".get("x","description")."</PortMappingDescription>\n";
				echo "          <InternalClient>".$ip."</InternalClient>\n";
				echo "          <PortMappingProtocol>UDP</PortMappingProtocol>\n";
				echo "          <ExternalPort>".query("publicPort")."</ExternalPort>\n";
				echo "          <InternalPort>".query("privatePort")."</InternalPort>\n";
				echo "        </PortMapping>\n";
			}
		}
	}
?>      </PortMappings>
    </GetPortMappingsResponse>
  </soap:Body>
</soap:Envelope>
