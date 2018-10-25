Server {
	Interface <?=$LAN_IF?>
	Port 1900
<?
	$SRV_NAME=query("/runtime/upnpdev/server");
	if($SRV_NAME!="") { echo "	ServerName \"".$SRV_NAME."\"\n"; }
?>	Address 239.255.255.250	# UPnP broadcase
	UDPServer On
	Virtual {
		AnyHost
		Control {
			Alias /
			Location /www/upnp
			External {
				/usr/sbin/upnpdev { * }
			}
		}
	}
}

Server {
	Interface <?=$LAN_IF?>
	Port <?=$UPNP_PORT?>
<?
	if($SRV_NAME!="") { echo "	ServerName \"".$SRV_NAME."\"\n"; }
?>	Virtual {
		AnyHost
		Control {
			Alias /
			Location /htdocs/upnp
			External {
				/usr/sbin/upnpdev { upnp }
			}
		}
	}
}
