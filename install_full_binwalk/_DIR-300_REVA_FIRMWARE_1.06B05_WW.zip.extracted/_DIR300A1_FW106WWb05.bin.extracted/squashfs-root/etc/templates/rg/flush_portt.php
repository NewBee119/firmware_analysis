# flush_portt.php >>>
<? /* vi: set sw=4 ts=4: */ ?>
[ -d /var/porttrigger ] || mkdir -p /var/porttrigger
rm -f /var/porttrigger/*
iptables -F FOR_PORTT
iptables -t nat -F PRE_PORTT
trigger -m flush
<?
$limit=" -m limit --limit 30/m --limit-burst 5";
$log  =" -j LOG --log-level info --log-prefix PTR:";

$count=0;
for("/nat/porttrigger/entry")
{
	if (query("enable")==1)
	{
		$TIMESTRING = "";
		$UNIQUEID   = query("schedule/id");
		if ($UNIQUEID!="") { require("/etc/templates/rg/__schedule.php"); }

		$prot	= query("trigger/protocol");
		$begin	= query("trigger/startport");
		$end	= query("trigger/endport");
		if ($end == "" || $end==$begin)	{ $dport=" --dport ".$begin; }
		else							{ $dport=" -m mport --ports ".$begin.":".$end; }
		if ($prot==2 || $prot==0)
		{
			echo "iptables -A FOR_PORTT -p udp".$dport.$limit.$TIMESTRING.$log.$@.":\n";
		}
		if ($prot==1 || $prot==0)
		{
			echo "iptables -A FOR_PORTT -p tcp".$dport.$limit.$TIMESTRING.$log.$@.":\n";
		}

		$prot	= query("external/protocol");
		$port	= query("external/portlist");
		echo "echo \"";
		if		($prot == 0)	{ echo "both"; }
		else if	($prot == 1)	{ echo "tcp"; }
		else if	($prot == 2)	{ echo "udp"; }
		echo ",".$port."\" > /var/porttrigger/".$@."\n";

		$count++;
	}
}
set("/runtime/rgfunc/portt", $count);
?>
# flush_portt.php <<<
