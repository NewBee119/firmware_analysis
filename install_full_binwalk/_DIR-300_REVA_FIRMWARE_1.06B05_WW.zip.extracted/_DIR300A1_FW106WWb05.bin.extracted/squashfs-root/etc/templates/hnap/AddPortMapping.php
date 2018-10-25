HTTP/1.1 200 OK
Content-Type: text/xml; charset=utf-8

<?
echo "\<\?xml version='1.0' encoding='utf-8'\?\>";
$nodebase	= "/runtime/hnap/AddPortMapping";
$max_rules	= query("/nat/vrtsrv/max_rules");
if ($max_rules=="") { $max_rules=32; }

fwrite($ShellPath, "#!/bin/sh\n");
fwrite2($ShellPath, "echo [$0] > /dev/console\n");

$rlt="OK";
$exist=0;
$i=0;
$j=0;
for("/nat/vrtsrv/entry")
{
	$prot=query("protocol");
	if	($prot=="1") { $prot="TCP"; }
	else if	($prot=="2") { $prot="UDP"; }

	if (	query("privateip")==query($nodebase."/InternalClient") &&
		query("publicPort")==query($nodebase."/ExternalPort") &&
		$prot==query($nodebase."/PortMappingProtocol")	)
	{ $exist=1; }

	if ($i==0 && query("description")=="") { $i=$@; }
	$j++;
}
if($i==0) { $i=$j+1; }
if($i>$max_rules || $exist==1)
{
	fwrite2($ShellPath, "echo \"max_rules=".$max_rules.", i=".$i."exist=".$exist."\" > /dev/console\n");
	$rlt="ERROR";
}
else
{
	anchor($nodebase);
	$PortMappingProtocol="0";
	$Protocol=query("PortMappingProtocol");
	if($Protocol=="tcp" || $Protocol=="TCP")
	{
		$PortMappingProtocol="1";
	}
	else if($Protocol=="udp" || $Protocol=="UDP")
	{
		$PortMappingProtocol="2";
	}
	$PrivateIP=query("InternalClient");
	if($PrivateIP=="")
	{
		$PrivateIP="0.0.0.0";
	}

	set("/nat/vrtsrv/entry:".$i."/enable", "1");
	set("/nat/vrtsrv/entry:".$i."/description", query("PortMappingDescription"));
	set("/nat/vrtsrv/entry:".$i."/privateip", $PrivateIP);
	set("/nat/vrtsrv/entry:".$i."/protocol", $PortMappingProtocol);
	set("/nat/vrtsrv/entry:".$i."/privatePort", query("InternalPort"));
	set("/nat/vrtsrv/entry:".$i."/publicPort", query("ExternalPort"));
	set("/nat/vrtsrv/entry:".$i."/schedule/enable", "0");

	fwrite2($ShellPath, "/etc/scripts/misc/profile.sh put > /dev/console\n");
	fwrite2($ShellPath, "/etc/templates/rg.sh vrtsrv > /dev/console\n");
	fwrite2($ShellPath, "rgdb -i -s /runtime/hnap/dev_status '' > /dev/console");
	set("/runtime/hnap/dev_status", "ERROR");
	$rlt="REBOOT";
}
?>
<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
  <soap:Body>
    <AddPortMappingResponse xmlns="http://purenetworks.com/HNAP1/">
      <AddPortMappingResult><?=$rlt?></AddPortMappingResult>
    </AddPortMappingResponse>
  </soap:Body>
</soap:Envelope>
