#!/bin/sh
echo [$0] ... > /dev/console
<?
/* vi: set sw=4 ts=4: */
require("/etc/templates/troot.php");

$ROUTER = query("/runtime/router/enable");
if ($ROUTER==1)
{
	$INTERFACE	= query("/runtime/layout/lanif");
	$IPADDR		= query("/lan/ethernet/ip");
}
else
{
	$INTERFACE	= query("/runtime/wan/inf:1/interface");
	$IPADDR		= query("/runtime/wan/inf:1/ip");
}

set("/runtime/upnpdev/host",		$IPADDR);
set("/runtime/upnpdev/interface",	$INTERFACE);

if ($generate_start==1)
{
	if (query("/upnp/enable")==1)
	{
		echo "echo Starting UPnP ... > /dev/console\n";
		require($template_root."/upnpd/setup.php");

		$VDIR="/var/upnp";
		$DDIR="/htdocs/web/upnp";
		echo "mkdir -p ".$VDIR."\n";
		echo "xmldbc -A ".$DDIR."/InternetGatewayDevice.xml > ".$VDIR."/InternetGatewayDevice.xml\n";
		echo "xmldbc -A ".$DDIR."/Layer3Forwarding.xml > ".$VDIR."/Layer3Forwarding.xml\n";
		echo "xmldbc -A ".$DDIR."/OSInfo.xml > ".$VDIR."/OSInfo.xml\n";
		echo "xmldbc -A ".$DDIR."/WANCommonInterfaceConfig.xml > ".$VDIR."/WANCommonInterfaceConfig.xml\n";
		echo "xmldbc -A ".$DDIR."/WANEthernetLinkConfig.xml > ".$VDIR."/WANEthernetLinkConfig.xml\n";
		echo "xmldbc -A ".$DDIR."/WANIPConnection.xml > ".$VDIR."/WANIPConnection.xml\n";
		if (query("/wireless/wps/enable")=="1" && query("/runtime/func/wfadev")=="1")
		{
			echo "xmldbc -A ".$DDIR."/WFADevice.xml > ".$VDIR."/WFADevice.xml\n";
			echo "xmldbc -A ".$DDIR."/WFAWLANConfig.xml > ".$VDIR."/WFAWLANConfig.xml\n";
		}

		$Nfile = "/var/run/upnp_alive.sh";
		fwrite ($Nfile, "#!/bin/sh\n");
		fwrite2($Nfile, "echo [$0] ... > /dev/console\n");
		fwrite2($Nfile, "xmldbc -k upnp_notify\n");
		fwrite2($Nfile, $template_root."/upnpd/NOTIFY.sh alive\n");
		fwrite2($Nfile, "xmldbc -t \"upnp_notify:1800:".$Nfile."\"\n");

		echo "chmod +x ".$Nfile."\n";
		echo $Nfile."\n";
	}
	else
	{
		echo "echo UPNP function is not enabled !! > /dev/console\n";
	}
}
else
{
	echo "echo Stopping UPNPD ... > /dev/console\n";
	echo "xmldbc -k upnp_notify\n";
	echo $template_root."/upnpd/NOTIFY.sh byebye\n";
	echo "rm -rf /var/upnp\n";
}
?>
