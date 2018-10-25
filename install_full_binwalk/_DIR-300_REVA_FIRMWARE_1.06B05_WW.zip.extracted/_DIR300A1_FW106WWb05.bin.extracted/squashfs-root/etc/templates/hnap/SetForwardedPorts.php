HTTP/1.1 200 OK
Content-Type: text/xml; charset=utf-8

<?
echo "\<\?xml version='1.0' encoding='utf-8'\?\>";
$nodebase="/runtime/hnap/SetForwardedPorts/ForwardedPorts/ForwardedPort";
$max_rules=query("/nat/vrtsrv/max_rules");
if($max_rules=="") { $max_rules=32; }
$rlt="OK";
$i=0;
for($nodebase)
{
	$i++;
}

if($i==0 && query($nodebase."/Enabled")!="") //-----Maybe only one node, set it to array
{
	$PrivateIP=query($nodebase."/PrivateIP");
	set($nodebase.":1/Enabled", query($nodebase."/Enabled"));
	set($nodebase.":1/Name", query($nodebase."/Name"));
	if($PrivateIP=="")
	{
		$PrivateIP="0.0.0.0";
	}
	set($nodebase.":1/PrivateIP", $PrivateIP);
	set($nodebase.":1/Protocol", query($nodebase."/Protocol"));
	set($nodebase.":1/Port", query($nodebase."/Port"));
	$i++;
}

fwrite($ShellPath, "#!/bin/sh\n");
fwrite2($ShellPath, "echo [$0] > /dev/console\n");
if($i>$max_rules)
{
	$rlt="TOOMANY";
	fwrite2($ShellPath, "echo \"We got a error in setting, so we do nothing...\" > /dev/console");
}
else
{
	$i=0;
	//-----Clear
	anchor("/nat/vrtsrv/");
	for("entry")
	{
		$i++;
	}
	while($i>0)
	{
//-----work around for purenetworks(with postfix //)
if(query("/nat/vrtsrv/entry:".$i."/publicPort")=="8008")//
{//
		del("entry:".$i);
}//
		$i--;
	}

for("entry")//
{//
	if($i==0 && query("enable")==0) { $i=$@; }//
}//
if($i==0) { $i=1; }
//	for($nodebase)//
//	{//
//		$i++;//
	anchor($nodebase);
	if(query("privateip")!="")//
	{//
		$Enabled="0";
		if(query("Enabled")=="true")
		{
			$Enabled="1";
		}

		$Protocol="0";
		if(query("Protocol")=="tcp" || query("Protocol")=="TCP")
		{
			$Protocol="1";
		}
		else if(query("Protocol")=="udp" || query("Protocol")=="UDP")
		{
			$Protocol="2";
		}
		$PrivateIP=query("privateip");
		if($PrivateIP=="")
		{
			$PrivateIP="0.0.0.0";
		}
		set("/nat/vrtsrv/entry:".$i."/enable", $Enabled);
		set("/nat/vrtsrv/entry:".$i."/description", query("Name"));
		set("/nat/vrtsrv/entry:".$i."/privateip", $PrivateIP);
		set("/nat/vrtsrv/entry:".$i."/protocol", $Protocol);
		set("/nat/vrtsrv/entry:".$i."/privatePort", query("Port"));
		set("/nat/vrtsrv/entry:".$i."/publicPort", query("Port"));
		set("/nat/vrtsrv/entry:".$i."/schedule/enable", "0");
	}
	fwrite2($ShellPath, "/etc/scripts/misc/profile.sh put > /dev/console\n");
	fwrite2($ShellPath, "/etc/templates/rg.sh vrtsrv > /dev/console\n");
	fwrite2($ShellPath, "rgdb -i -s /runtime/hnap/dev_status '' > /dev/console");
	set("/runtime/hnap/dev_status", "ERROR");
	$rlt="REBOOT";
}
?>
<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
  <soap:Body>
    <SetForwardedPortsResponse xmlns="http://purenetworks.com/HNAP1/">
      <SetForwardedPortsResult><?=$rlt?></SetForwardedPortsResult>
    </SetForwardedPortsResponse>
  </soap:Body>
</soap:Envelope>
