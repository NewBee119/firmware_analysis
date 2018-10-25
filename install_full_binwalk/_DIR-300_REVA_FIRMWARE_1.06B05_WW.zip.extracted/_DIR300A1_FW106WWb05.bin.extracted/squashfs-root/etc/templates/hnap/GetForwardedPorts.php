HTTP/1.1 200 OK
Content-Type: text/xml; charset=utf-8

<?
echo "\<\?xml version='1.0' encoding='utf-8'\?\>";
?>
<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
  <soap:Body>
    <GetForwardedPortsResponse xmlns="http://purenetworks.com/HNAP1/">
      <GetForwardedPortsResult>OK</GetForwardedPortsResult>
      <ForwardedPorts>
<?
	for("/nat/vrtsrv/entry")
	{
//-----work around for purenetworks(with postfix //)
if(query("publicPort")=="8008")//
{//
		$enable="false";
		if(query("enable")=="1")
		{
			$enable="true";
		}
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
		echo "        <ForwardedPort>\n";
		echo "          <Enabled>".$enable."</Enabled>\n";
		echo "          <Name>".query("description")."</Name>\n";
		echo "          <PrivateIP>".$ip."</PrivateIP>\n";
		if($prot=="both")
		{
			echo "          <Protocol>TCP</Protocol>\n";
		}
		else
		{
			echo "          <Protocol>".$prot."</Protocol>\n";
		}
		echo "          <Port>".query("publicPort")."</Port>\n";
		echo "        </ForwardedPort>\n";
		if($prot=="both")
		{
			echo "        <ForwardedPort>\n";
			echo "          <Enabled>".$enable."</Enabled>\n";
			echo "          <Name>".query("description")."</Name>\n";
			echo "          <PrivateIP>".$ip."</PrivateIP>\n";
			echo "          <Protocol>UDP</Protocol>\n";
			echo "          <Port>".query("publicPort")."</Port>\n";
			echo "        </ForwardedPort>\n";
		}
}//
	}
?>      </ForwardedPorts>
    </GetForwardedPortsResponse>
  </soap:Body>
</soap:Envelope>
