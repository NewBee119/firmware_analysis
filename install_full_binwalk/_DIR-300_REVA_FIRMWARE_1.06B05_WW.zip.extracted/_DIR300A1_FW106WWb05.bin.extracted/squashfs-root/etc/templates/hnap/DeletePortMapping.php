HTTP/1.1 200 OK
Content-Type: text/xml; charset=utf-8

<?
echo "\<\?xml version='1.0' encoding='utf-8'\?\>";
$nodebase="/runtime/hnap/DeletePortMapping";
$rlt="OK";
$i=0;
anchor($nodebase);
$ExternalPort=query("ExternalPort");
for("/nat/vrtsrv/entry")
{
	$prot=query("protocol");
	if($prot=="1")
	{
		$prot="TCP";
	}
	else if($prot=="2")
	{
		$prot="UDP";
	}
	if(query("publicPort")==$ExternalPort && $prot==query($nodebase."/PortMappingProtocol"))
	{
		$i=$@;
	}
}
fwrite($ShellPath, "#!/bin/sh\n");
fwrite2($ShellPath, "echo [$0] > /dev/console\n");
if($i==0)
{
	$rlt="ERROR";
	fwrite2($ShellPath, "echo \"We got a error in setting, so we do nothing...\" > /dev/console\n");
}
else
{
	del("/nat/vrtsrv/entry:".$i);

	fwrite2($ShellPath, "/etc/scripts/misc/profile.sh put > /dev/console\n");
	fwrite2($ShellPath, "/etc/templates/rg.sh vrtsrv > /dev/console\n");
	fwrite2($ShellPath, "rgdb -i -s /runtime/hnap/dev_status '' > /dev/console");
	set("/runtime/hnap/dev_status", "ERROR");
	$rlt="REBOOT";
}
?>
<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
  <soap:Body>
    <DeletePortMappingResponse xmlns="http://purenetworks.com/HNAP1/">
      <DeletePortMappingResult><?=$rlt?></DeletePortMappingResult>
    </DeletePortMappingResponse>
  </soap:Body>
</soap:Envelope>
