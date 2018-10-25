<? /* vi: set sw=4 ts=4: */

$ipaddr	= query("/runtime/upnpdev/host");
$port	= query("/runtime/upnpdev/port");
$server	= query("/runtime/upnpdev/server");
$maxage	= query("/runtime/upnpdev/maxage");
$vendor	= query("/sys/vendor");
$model	= query("/sys/modelname");
$url	= query("/sys/url");
$ver	= query("/runtime/sys/info/firmwareversion");
$sn		= query("/sys/serialnumber");
if( $sn == "" )	{ $sn = "0000001"; }

$modeldesc	= $vendor." ".$model;

/********************************************************************/
/* root device: Internet Gateway Device */
$dev_root	= "/runtime/upnpdev/root:1";
$udn		= "uuid:".query($dev_root."/uuid");
set($dev_root."/devicetype",	"urn:schemas-upnp-org:device:InternetGatewayDevice:1");
	anchor($dev_root);
		set("friendlyname",		$model);
		set("manufacturer",		$vendor);
		set("manufacturerurl",	$url);
		set("modeldescription",	$modeldesc);
		set("modelname",		$model);
		set("modelnumber",		"1");
		set("modelurl",			$url);
		set("serialnumber",		$sn);
		set("udn",				$udn);

		/* used by upnpkits */
		set("location",			"http:/\/".$ipaddr.":".$port."/xmldoc/InternetGatewayDevice.xml");
		set("maxage",			$maxage);
		set("server",			$server);

$serv_root = $dev_root."/service:1";
set($serv_root."/servicetype",	"urn:schemas-microsoft-com:service:OSInfo:1");
	anchor($serv_root);
		set("serviceid",		"urn:microsoft-com:serviceId:OSInfo1");
		set("controlurl",		"/upnpdev.cgi?service=OSInfo1");
		set("eventsuburl",		"/OSInfo1.upnp");
		set("scpdurl",			"http:/\/".$ipaddr.":".$port."/xmldoc/OSInfo.xml");

$serv_root = $dev_root."/service:2";
set($serv_root."/servicetype", "urn:schemas-upnp-org:service:Layer3Forwarding:1"); anchor($serv_root);
		set("serviceid",		"urn:upnp-org:serviceId:L3Forwarding1");
		set("controlurl",		"/upnpdev.cgi?service=L3Forwarding1");
		set("eventsuburl",		"/L3Forwarding1.upnp");
		set("scpdurl",			"http:/\/".$ipaddr.":".$port."/xmldoc/Layer3Forwarding.xml");

/********************************************************************/
/* WANDevice */
$dev_root = "/runtime/upnpdev/root:1/device:1";
set($dev_root."/devicetype",	"urn:schemas-upnp-org:device:WANDevice:1"); anchor($dev_root);
		set("friendlyname",		"WANDevice");
		set("manufacturer",		$vendor);
		set("manufacturerurl",	$url);
		set("modeldescription",	"WANDevice");
		set("modelname",		$model);
		set("modelnumber",		"1");
		set("modelurl",			$url);
		set("serialnumber",		$sn);
		set("udn",				$udn);

$serv_root = $dev_root."/service:1";
set($serv_root."/servicetype",	"urn:schemas-upnp-org:service:WANCommonInterfaceConfig:1"); anchor($serv_root);
		set("serviceid",		"urn:upnp-org:serviceId:WANCommonIFC1");
		set("controlurl",		"/upnpdev.cgi?service=WANCommonIFC1");
		set("eventsuburl",		"/WANCommonIFC1.upnp");
		set("scpurl",			"http:/\/".$ipaddr.":".$port."/xmldoc/WANCommonInterfaceConfig.xml");

/********************************************************************/
/* WANConnectionDevice */
$dev_root = "/runtime/upnpdev/root:1/device:1/device:1";
set($dev_root."/devicetype",	"urn:schemas-upnp-org:device:WANConnectionDevice:1"); anchor($dev_root);
		set("friendlyname",		"WANConnectionDevice");
		set("manufacturer",		$vendor);
		set("manufacturerurl",	$url);
		set("modeldescription",	"WANConnectionDevice");
		set("modelname",		$model);
		set("modelnumber",		"1");
		set("modelurl",			$url);
		set("serialnumber",		$sn);
		set("udn",				$udn);

$serv_root = $dev_root."/service:1";
set($serv_root."/servicetype",	"urn:schemas-upnp-org:service:WANEthernetLinkConfig:1"); anchor($serv_root);
		set("serviceid",		"urn:upnp-org:serviceId:WANEthLinkC1");
		set("controlurl",		"/upnpdev.cgi?service=WANEthLinkC1");
		set("eventsuburl",		"/WANEthLinkC1.upnp");
		set("scpurl",			"http:/\/".$ipaddr.":".$port."/xmldoc/WANEthernetLinkConfig.xml");

$serv_root = $dev_root."/service:2";
set($serv_root."/servicetype",	"urn:schemas-upnp-org:service:WANIPConnection:1"); anchor($serv_root);
		set("serviceid",		"urn:upnp-org:serviceId:WANIPConn1");
		set("controlurl",		"/upnpdev.cgi?service=WANIPConn1");
		set("eventsuburl",		"/WANIPConn1.upnp");
		set("scpurl",			"http:/\/".$ipaddr.":".$port."/xmldoc/WANIPConnection.xml");

/********************************************************************/
/* root device: WFADevice */
$dev_root	= "/runtime/upnpdev/root:2";
if (query("/runtime/func/wfadev")=="1")
{
	$udn	= "uuid:".query($dev_root."/uuid");
	set($dev_root."/devicetype",	"urn:schemas-wifialliance-org:device:WFADevice:1"); anchor($dev_root);
		set("friendlyname",		$model);
		set("manufacturer",		$vendor);
		set("manufacturerurl",	$url);
		set("modeldescription",	$modeldesc);
		set("modelname",		$model);
		set("modelnumber",		"1");
		set("modelurl",			$url);
		set("serialnumber",		$sn);
		set("udn",				$udn);

		/* used by upnpkits */
		set("location",			"http:/\/".$ipaddr.":".$port."/xmldoc/WFADevice.xml");
		set("maxage",			$maxage);
		set("server",			$server);

	$serv_root = $dev_root."/service:1";
	set($serv_root."/servicetype",	"urn:schemas-wifialliance-org:service:WFAWLANConfig:1"); anchor($serv_root);
		set("serviceid",		"urn:wifialliance-org:serviceId:WFAWLANConfig1");
		set("controlurl",		"/upnpdev.cgi?service=WFAWLANConfig1");
		set("eventsuburl",		"/WFAWLANConfig1.upnp");
		set("scpdurl",			"http:/\/".$ipaddr.":".$port."/xmldoc/WFAWLANConfig.xml");
}
else
{
	del($dev_root);
}
?>
