var result = <?
if (query("/time/syncwith")==2)
{
	if (query("/runtime/time/ntp/state")!=1) { $Status = "WAIT"; }
	else
	{
		$Status = "OK";
		$Date	= get("j", "/runtime/time/date");
		$Time	= get("j", "/runtime/time/time");
		$Server = query("/time/ntpserver/ip");
		$Uptime	= get("D", "/runtime/time/ntp/uptime");
		$Next	= query("/runtime/time/ntp/uptime") + query("/time/ntpserver/interval");
		set("/runtime/time/ntp/nextupdate", $Next);
		$Next	= get("D", "/runtime/time/ntp/nextupdate");
	}
}
else
{
	if (query("/runtime/time/ntp/state")==1)
	{
		$Status = "OK";
		$Date	= get("j", "/runtime/time/date");
		$Time	= get("j", "/runtime/time/time");
		$Server = query("/time/ntpserver/ip");
		$Uptime	= get("D", "/runtime/time/ntp/uptime");
		$Next	= query("/runtime/time/ntp/uptime") + query("/time/ntpserver/interval");
		set("/runtime/time/ntp/nextupdate", $Next);
		$Next	= get("D", "/runtime/time/ntp/nextupdate");
	}
	else
	{
		$Status = "MANUAL";
		$Date	= get("j", "/runtime/time/date");
		$Time	= get("j", "/runtime/time/time");
	}
}
?>new Array("<?=$Status?>","<?=$Date?>","<?=$Time?>","<?=$Server?>","<?=$Uptime?>","<?=$Next?>");
