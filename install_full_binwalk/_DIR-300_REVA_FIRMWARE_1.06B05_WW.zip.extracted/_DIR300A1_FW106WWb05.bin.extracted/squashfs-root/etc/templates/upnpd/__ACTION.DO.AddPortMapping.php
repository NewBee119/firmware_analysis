<? /* vi: set sw=4 ts=4: */
if ($ROUTER_ON==0)
{
	$errorCode=501;
}
else
{
	anchor("/runtime/upnp/AddPortMapping");
	$NewRemoteHost				= query("NewRemoteHost");
	$NewExternalPort			= query("NewExternalPort");
	$NewProtocol				= query("NewProtocol");
	$NewInternalPort			= query("NewInternalPort");
	$NewInternalClient			= query("NewInternalClient");
	$NewEnabled					= query("NewEnabled");
	$NewPortMappingDescription	= query("NewPortMappingDescription");
	$NewLeaseDuration			= query("NewLeaseDuration");

	if ($NewExternalPort=="" || $NewExternalPort==0)
	{
		$errorCode=716;
	}
	else if ($NewInternalPort!="" && $NewInternalPort==0)
	{
		$errorCode=402;
	}
	else if ($NewProtocol=="" || $NewInternalClient=="" || $NewInternalClient==0)
	{
		$errorCode=402;
	}
	else
	{
		$done=0;
		$errorCode=200;

		if ($NewInternalPort=="")	{ $NewInternalPort=$NewExternalPort; }
		if ($NewProtocol=="TCP")	{ $proto=1; } else { $proto=2; }

		$count=0;
		for ("/runtime/upnp/wan:".$WID."/entry")
		{
			if ($done==0)
			{
				/* if exist, update the description. */
				if ($NewRemoteHost == query("remoteip") && $NewExternalPort == query("port2") &&
					$proto == query("protocol"))
				{
					$errorCode=718;
					$done=1;
				}
				/* XBOX test wish us to report OK, if the rule is existing. */
				if ($proto == query("protocol") &&
					$NewRemoteHost == query("remoteip") && $NewInternalClient == query("ip") &&
					$NewInternalPort == query("port1") && $NewExternalPort == query("port2"))
				{
					if ($NewPortMappingDescription != query("description"))
					{
						set("description", $NewPortMappingDescription);
					}
					$errorCode=200;
					$done=1;
				}
			}
			$count++;
		}

		if ($NewLeaseDuration != "" && $NewLeaseDuration >0)
		{
			$errorCode=725;
			$done=1;
		}
		if ($done==0)
		{
			$count++;
			set("/runtime/upnp/wan:".$WID."/entry:".$count."/enable", $NewEnabled);
			anchor("/runtime/upnp/wan:".$WID."/entry:".$count);
			set("remoteip",		$NewRemoteHost);
			set("ip",			$NewInternalClient);
			set("port1",		$NewInternalPort);
			set("port2",		$NewExternalPort);
			set("description",	$NewPortMappingDescription);

			$NewRemoteHost = get("s","remoteip");
			$NewInternalClient = get("s","ip");

			if ($NewProtocol == "TCP")	{ set("protocol", 1); $proto=" -p tcp"; }
			else                        { set("protocol", 2); $proto=" -p udp"; }

			if ($NewRemoteHost != "")   { $sourceip = " -s \"".$NewRemoteHost."\""; }
			else                        { $sourceip = ""; }

			if ($NewEnabled == "1")
			{
				$wanip = query("/runtime/wan/inf:".$WID."/ip");
				if ($wanip != "")
				{
					set("wanip", $wanip);

					$cmd =	"iptables -t nat -A PRE_UPNP".$sourceip.$proto.
							" -d ".$wanip." --dport ".$NewExternalPort.
							" -j DNAT --to-destination \"".$NewInternalClient."\":".$NewInternalPort;

					fwrite2($ShellPath, "echo UPNP:".$cmd." > /dev/console\n");
					fwrite2($ShellPath, $cmd."\n");
				}
			}
			
			$W2ID=2;
			$wan2ip=query("/runtime/wan/inf:".$W2ID."/ip");
			if($wan2ip!="")
			{
				set("/runtime/upnp/wan:".$W2ID."/entry:".$count."/enable", $NewEnabled);
				anchor("/runtime/upnp/wan:".$W2ID."/entry:".$count);
				set("remoteip",		$NewRemoteHost);
				set("ip",			$NewInternalClient);
				set("port1",		$NewInternalPort);
				set("port2",		$NewExternalPort);
				set("description",	$NewPortMappingDescription);
				
				$NewRemoteHost = get("s","remoteip");
				$NewInternalClient = get("s","ip");

				if ($NewProtocol == "TCP")	{ set("protocol", 1); $proto=" -p tcp"; }
				else                        { set("protocol", 2); $proto=" -p udp"; }

				if ($NewRemoteHost != "")   { $sourceip = " -s \"".$NewRemoteHost."\""; }
				else                        { $sourceip = ""; }
				if ($NewEnabled == "1")
				{
					set("wanip", $wan2ip);

					$cmd2 =	"iptables -t nat -A PRE_UPNP".$sourceip.$proto.
							" -d ".$wan2ip." --dport ".$NewExternalPort.
							" -j DNAT --to-destination \"".$NewInternalClient."\":".$NewInternalPort;

					fwrite2($ShellPath, "echo UPNP:".$cmd2." > /dev/console\n");
					fwrite2($ShellPath, $cmd2."\n");
				}
			}
			$errorCode=200;
			$done=1;
		}
	}
}
?>
