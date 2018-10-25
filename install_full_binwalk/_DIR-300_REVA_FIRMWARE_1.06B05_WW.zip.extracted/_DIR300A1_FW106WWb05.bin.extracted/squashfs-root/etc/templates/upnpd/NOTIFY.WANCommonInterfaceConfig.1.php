<? /* vi: set sw=4 ts=4: */
require("/etc/templates/troot.php");
require($template_root."/upnpd/__NOTIFY.req.event.php");
?>
<e:propertyset xmlns:e="urn:schemas-upnp-org:event-1-0">
	<e:property>
		<PhysicalLinkStatus><?
			if (query("/runtime/switch/wan_port")=="0") { echo "Down"; } else { echo "Up"; }
		?></PhysicalLinkStatus>
	</e:property>
</e:propertyset>
