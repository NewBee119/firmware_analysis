<?
/* vi: set sw=4 ts=4: */
$RouterOn = query("/runtime/router/enable");
?>
<e:propertyset xmlns:e="urn:schemas-upnp-org:event-1-0">
	<e:property>
		<PossibleConnectionTypes><?

			if ($RouterOn==1)	{ echo "IP_Routed"; }
			else				{ echo "IP_Bridge"; }

		?></PossibleConnectionTypes>
	</e:property>
	<e:property>
		<ConnectionStatus><? 

			if ($RouterOn==1)	{ map("/runtime/wan/inf:".$WID."/connectstatus","connected","Connected","*","Disconnected"); }
			else				{ echo "Connected"; }

		?></ConnectionStatus>
	</e:property>
	<e:property>
		<ExternalIPAddress><?

			if ($RouterOn==1)	{ query("/runtime/wan/inf:".$WID."/ip"); }
			else				{ query("/lan/ethernet/ip"); }

		?></ExternalIPAddress>
	</e:property>
	<e:property>
		<PortMappingNumberOfEntries><?

			if ($RouterOn==1)
			{
				$count = 0;
				for ("/runtime/upnp/wan:".$WID."/entry") { $count++; }
				echo $count;
			}
			else
			{
				echo "0";
			}

		?></PortMappingNumberOfEntries>
	</e:property>
	<e:property>
		<X_Name><?

			if ($RouterOn==1)
			{
				$wanmode = query("/wan/rg/inf:".$WID."/mode");
				if ($wanmode == 6)
				{
					$pid = query("/wan/rg/inf:".$WID."/profileid");
					echo query("/wan/rg/profile:".$pid."/name");
				}
				else 
				{
					echo "WAN Connection";
				}
			}
			else
			{
				echo "Bridge Mode";
			}

		?></X_Name>
	</e:property>
</e:propertyset>
