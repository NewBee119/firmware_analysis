#!/bin/sh
echo [$0] ... > /dev/console
<?
for ("/routing/route/entry")
{
	if (query("enable")==1)
	{
		$dest = query("destination");
		$mask = query("netmask");
		$gway = query("gateway");
		$intf = query("interface");
		if		($intf == "WAN")	{ $dev = query("/runtime/wan/inf:1/interface"); }
		else if	($intf == "WANPHY")	{ $dev = query("/runtime/wan/inf:2/interface"); }
		else						{ $dev = query("/runtime/layout/lanif"); }

		if ($dev!="")
		{
			if ($generate_start==1) { $action = "add"; }
			else					{ $action = "del"; }
			echo "route ".$action." -net ".$dest." netmask ".$mask." gw ".$gway." dev ".$dev."\n";
		}
	}
}
?>
