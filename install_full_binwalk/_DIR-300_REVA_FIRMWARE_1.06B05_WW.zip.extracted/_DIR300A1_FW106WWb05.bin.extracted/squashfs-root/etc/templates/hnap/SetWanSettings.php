HTTP/1.1 200 OK
Content-Type: text/xml; charset=utf-8

<?
echo "\<\?xml version='1.0' encoding='utf-8'\?\>";
$nodebase="/runtime/hnap/SetWanSettings/";
$mac="";
$rlt="OK";
$Type=query($nodebase."Type");
$MacAddress=query($nodebase."MacAddress");
$IPAddress=query($nodebase."IPAddress");
$SubnetMask=query($nodebase."SubnetMask");
$Gateway=query($nodebase."Gateway");
$MTU=query($nodebase."MTU");
$Username=query($nodebase."Username");
$Password=query($nodebase."Password");
$MaxIdleTime=query($nodebase."MaxIdleTime");
$ServiceName=query($nodebase."ServiceName");
$AutoReconnect=query($nodebase."AutoReconnect");
$PriDns = query($nodebase."DNS/Primary");
$SecDns = query($nodebase."DNS/Secondary");
$OpenDnsEnable = query($nodebase."OpenDNS/enable");

//if( $PriDns != "" || $SecDns != "" )
//{
	set("/dnsrelay/server/primaryDns", $PriDns);
	set("/dnsrelay/server/secondaryDns", $SecDns);
	if($OpenDnsEnable == "true")
	{
		set("/advdns/enable", 1);
	}
	else
	{
		set("/advdns/enable", 0);
	}
//}

if($Type == "Static")
{
	$mode=1;
	anchor("/wan/rg/inf:1/static");
	set("clonemac", $MacAddress);
	set("ip", $IPAddress);
	set("netmask", $SubnetMask);
	set("gateway", $Gateway);
	if($MTU == "0")
	{
		//$rlt = "ERROR_AUTO_MTU_NOT_SUPPORTED";
		set("mtu", 1500);
	}
	else
	{
		if($MTU >= 200 && $MTU <= 1500) { set("mtu", $MTU); }
		else	{ $rlt="ERROR"; }
	}
}
else if($Type == "DHCP")
{
	$mode=2;
	anchor("/wan/rg/inf:1/dhcp");
	set("clonemac", $MacAddress);
	if($MTU == "0")
	{
		//$rlt = "ERROR_AUTO_MTU_NOT_SUPPORTED";
		set("mtu", 1500);
	}
	else
	{
		if($MTU >= 200 && $MTU <= 1500) { set("mtu", $MTU); }
		else	{ $rlt="ERROR"; }
	}
}
else if($Type == "StaticPPPoE" || $Type == "DHCPPPPoE")
{
	$mode=3;
	anchor("/wan/rg/inf:1/pppoe");
	if($Type == "StaticPPPoE")
	{
		set("mode", 1);
		set("staticip", $IPAddress);
	}
	else
	{
		set("mode", 2);
	}

	set("gateway", $Gateway);
	set("user", $Username);
	set("password", $Password);
	set("idleTimeout", $MaxIdleTime);
	set("acService", $ServiceName);
	if($MaxIdleTime>0)
	{
		set("ondemand", 1);
	}
	else
	{
		set("ondemand", 0);
	}
	if($AutoReconnect=="true")
	{
		set("autoReconnect", 1);
	}
	else if($AutoReconnect=="false")
	{
		set("autoReconnect", 0);
	}
	set("clonemac", $MacAddress);
	if($MTU == "0")
	{
		//$rlt = "ERROR_AUTO_MTU_NOT_SUPPORTED";
		set("mtu", 1492);
	}
	else
	{
		if($MTU >= 200 && $MTU <= 1492) { set("mtu", $MTU); }
		else	{ $rlt="ERROR"; }
	}
	if( $PriDns != "" || $SecDns != "" )
	{
		set("autodns", 0);
	}
	
}
else if($Type == "StaticPPTP" || $Type == "DynamicPPTP")
{
	$mode=4;
	anchor("/wan/rg/inf:1/pptp");
	if($Type == "StaticPPTP")
	{
		set("mode", 1);
		set("ip", $IPAddress);
		set("netmask", $SubnetMask);
	}
	else
	{
		set("mode", 2);
	}

	set("gateway", $Gateway);
	set("user", $Username);
	set("password", $Password);
	set("idleTimeout", $MaxIdleTime);
	set("serverip", $ServiceName);
	if($MaxIdleTime>0)
	{
		set("ondemand", 1);
	}
	else
	{
		set("ondemand", 0);
	}
	if($AutoReconnect=="true")
	{
		set("autoReconnect", 1);
	}
	else if($AutoReconnect=="false")
	{
		set("autoReconnect", 0);
	}
	set("clonemac", $MacAddress);
	if($MTU == "0")
	{
		//$rlt = "ERROR_AUTO_MTU_NOT_SUPPORTED";
		set("mtu", 1400);
	}
	else
	{
		if($MTU >= 200 && $MTU <= 1400) { set("mtu", $MTU); }
		else	{ $rlt="ERROR"; }
	}
	
}
else if($Type == "StaticL2TP" || $Type == "DynamicL2TP")
{
	$mode=5;
	anchor("/wan/rg/inf:1/l2tp");
	if($Type == "StaticL2TP")
	{
		set("mode", 1);
		set("ip", $IPAddress);
		set("netmask", $SubnetMask);
	}
	else
	{
		set("mode", 2);
	}

	set("gateway", $Gateway);
	set("serverip", $ServiceName);
	set("user", $Username);
	set("password", $Password);
	set("idleTimeout", $MaxIdleTime);
	if($MaxIdleTime>0)
	{
		set("ondemand", 1);
	}
	else
	{
		set("ondemand", 0);
	}
	if($AutoReconnect=="true")
	{
		set("autoReconnect", 1);
	}
	else if($AutoReconnect=="false")
	{
		set("autoReconnect", 0);
	}
	set("clonemac", $MacAddress);
	if($MTU == "0")
	{
		//$rlt = "ERROR_AUTO_MTU_NOT_SUPPORTED";
		set("mtu", 1400);
	}
	else
	{
		if($MTU >= 200 && $MTU <= 1400) { set("mtu", $MTU); }
		else	{ $rlt="ERROR"; }
	}
	
}
else
{
	$rlt = "ERROR_BAD_WANTYPE";
}
set("/wan/rg/inf:1/mode", $mode);

fwrite($ShellPath, "#!/bin/sh\n");
fwrite2($ShellPath, "echo \"[$0]-->Wan Change\" > /dev/console\n");
if($rlt=="OK")
{
	fwrite2($ShellPath, "/etc/scripts/misc/profile.sh put > /dev/console\n");
	fwrite2($ShellPath, "/etc/templates/wan.sh restart > /dev/console \n");
	fwrite2($ShellPath, "rgdb -i -s /runtime/hnap/dev_status '' > /dev/console");
	set("/runtime/hnap/dev_status", "ERROR");
}
else
{
	fwrite2($ShellPath, "echo \"We got a error in setting, so we do nothing...\" > /dev/console\n");
}
?>
<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
<soap:Body>
<SetWanSettingsResponse xmlns="http://purenetworks.com/HNAP1/">
<SetWanSettingsResult><?=$rlt?></SetWanSettingsResult>
</SetWanSettingsResponse>
</soap:Body>
</soap:Envelope>
