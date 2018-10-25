<? /* vi: set sw=4 ts=4: */
if ($ROUTER_ON==0)
{
	$errorCode=501;
}
else
{
	anchor("/runtime/upnp/DeletePortMapping");
	$NewRemoteHost		= query("NewRemoteHost");
	$NewExternalPort	= query("NewExternalPort");
	$NewProtocol		= query("NewProtocol");

	$target = 0;
	$count = 0;
	for ("/runtime/upnp/wan:".$WID."/entry")
	{
		if ($NewProtocol == "TCP")	{ $proto=1; } else { $proto=2; }
		if ($NewRemoteHost == query("remoteip") &&
			$NewExternalPort == query("port2") &&
			$proto == query("protocol"))
		{
			$target = $@;
		}
	}
	if ($target != 0)
	{
		anchor("/runtime/upnp/wan:".$WID."/entry:".$target);
		if (query("enable")==1)
		{
			if (query("remoteip") != "")    { $sourceip = " -s \"".get("s","remoteip")."\""; }
			else                            { $sourceip = ""; }
			if (query("protocol") == 1)     { $proto = " -p tcp"; }
			else                            { $proto = " -p udp"; }
			$extport = query("port2");
			$intport = query("port1");
			$intclnt = get("s","ip");

			$wanip = query("wanip");
			if ($wanip != "")
			{
				$cmd =	"iptables -t nat -D PRE_UPNP".$sourceip.$proto.
						" -d ".$wanip." --dport ".$extport.
						" -j DNAT --to-destination \"".$intclnt."\":".$intport;

				fwrite2($ShellPath, "echo UPNP:".$cmd." > /dev/console\n");
				fwrite2($ShellPath, $cmd."\n");
			}
		}
		del("/runtime/upnp/wan:".$WID."/entry:".$target);
		
		if(query("/runtime/wan/inf:2/ip")!="")
		{
			$W2ID=2;
			anchor("/runtime/upnp/wan:".$W2ID."/entry:".$target);
			if (query("enable")==1)
			{
				if (query("remoteip") != "")    { $sourceip = " -s \"".get("s","remoteip")."\""; }
				else                            { $sourceip = ""; }
				if (query("protocol") == 1)     { $proto = " -p tcp"; }
				else                            { $proto = " -p udp"; }
				$extport = query("port2");
				$intport = query("port1");
				$intclnt = get("s","ip");
				$wan2ip = query("wanip");
				if($wan2ip!="")
				{
					$cmd2 =	"iptables -t nat -D PRE_UPNP".$sourceip.$proto.
							" -d ".$wan2ip." --dport ".$extport.
							" -j DNAT --to-destination \"".$intclnt."\":".$intport;

					fwrite2($ShellPath, "echo UPNP:".$cmd2." > /dev/console\n");
					fwrite2($ShellPath, $cmd2."\n");
				}
				del("/runtime/upnp/wan:".$W2ID."/entry:".$target);
			}
		}
		
		/* kick upnpd to send notify */
		//fwrite2($shell, "killall -16 upnpd\n");
		$errorCode=200;
	}
	else
	{
		$errorCode=714;
	}
}
?>
