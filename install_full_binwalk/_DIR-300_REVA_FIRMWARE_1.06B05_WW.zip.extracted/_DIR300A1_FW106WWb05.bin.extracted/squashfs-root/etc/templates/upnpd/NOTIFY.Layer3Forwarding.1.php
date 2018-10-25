<? /* vi: set sw=4 ts=4: */
require("/etc/templates/troot.php");
require($template_root."/upnpd/__NOTIFY.req.event.php");
$udn=query("/runtime/upnpdev/root:1/udn");
?>
<e:propertyset xmlns:e="urn:schemas-upnp-org:event-1-0">
	<e:property>
		<DefaultConnectionService><?=$udn?>:WANConnectionDevice:1,urn:upnp-org:serviceId:WANIPConn1</DefaultConnectionService>
	</e:property>
</e:propertyset>
