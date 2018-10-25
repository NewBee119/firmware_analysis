# flush_firewall.php >>>
iptables -F FOR_FIREWALL
<? /* vi: set sw=4 ts=4: */

$count=0;
for("/security/firewall/entry")
{
	if (query("enable") == 1 && $wanif!="")
	{
		$proto = query("protocol");
		$iprange = 0;
		if		($proto==1)	{ $protocol="all"; }
		else if	($proto==2)	{ $protocol="tcp"; }
		else if	($proto==3)	{ $protocol="udp"; }
		else if	($proto==4)	{ $protocol="icmp"; }

		/* Reset command first */
		$sipstring	= "";
		$dipstring	= "";
		$portstring	= "";
		$timestring	= "";
		$sifstr		= "";
		$difstr		= "";

		/* gen source ipstring */
		$siptype= query("src/iptype");
		$sbip	= query("src/startip");
		$seip	= query("src/endip");
		if ($siptype==2 && $sbip!="")				{ $sipstring=" -s ".$sbip; }
		if ($siptype==3 && $sbip!="" && $seip!="")	{ $sipstring=" -m iprange --src-range ".$sbip."-".$seip; $iprange=1;}

		/* gen dest ipstring */
		$diptype= query("dst/iptype");
		$dbip	= query("dst/startip");
		$deip	= query("dst/endip");
		if ($diptype==2 && $dbip!="")				{ $dipstring=" -d ".$dbip; }
		if ($diptype==3 && $dbip!="" && $deip!="")
		{
			if ($iprange==0)	{$dipstring=" -m iprange --dst-range ".$dbip."-".$deip;}
			else				{$dipstring=" --dst-range ".$dbip."-".$deip;}
		}

		/* gen dest portstring */
		if ($proto==2 || $proto==3)
		{
			$porttype=query("dst/porttype");
			$bport	=query("dst/startport");
			$eport	=query("dst/endport");
			if ($porttype==2 && $bport!="")					{ $portstring=" --dport ".$bport; }
			if ($porttype==3 && $bport!="" && $eport!="")	{ $portstring=" --dport ".$bport.":".$eport; }
		}

		/* gen timestring */
		$TIMESTRING	= "";
		$UNIQUEID	= query("schedule/id");
		if ($UNIQUEID!="") { require("/etc/templates/rg/__schedule.php"); }
		$timestring = $TIMESTRING;
		
		/* gen interface */
		$sif = query("src/inf");
		if		($sif==1)	{ $sifstr=" -i ".$lanif; }
		else if	($sif==2)	
		{
			$sifstr=" -i ".$wanif;
			if ($wan2if!="" && $wan2ip!="")
			{
				$sifstr2=" -i ".$wan2if;
			}
		}
		$dif = query("dst/inf");
		if		($dif==1)	{ $difstr=" -o ".$lanif; }
		else if	($dif==2)	
		{
			$difstr=" -o ".$wanif;
			if ($wan2if!="" && $wan2ip!="")
			{
				$difstr2=" -o ".$wan2if;
			}
		}
		$act = query("action");
		if ($act==1)		{ $cmd_tail=" -j ACCEPT\n"; }
		else				{ $cmd_tail=" -j DROP\n";   }	

		/* gen cmd*/
		$cmd_head="iptables -A FOR_FIREWALL".$sifstr.$difstr." -p ";
		if($sifstr2!="" || $difstr2!="")
		{
			if($sifstr2=="")
			{
				$cmd_head2="iptables -A FOR_FIREWALL".$sifstr.$difstr2." -p ";
			}
			else if($difstr2=="")
			{
				$cmd_head2="iptables -A FOR_FIREWALL".$sifstr2.$difstr." -p ";
			}
		}
		if (query("/security/log/droppacketinfo")==1 && $act!=1)
		{ 
			$logstring=" -j LOG --log-level info --log-prefix DRP:006:\n";
			echo $cmd_head.$protocol.$sipstring.$dipstring.$portstring.$timestring.$logstring;
			if($cmd_head2!="")
			{
				echo $cmd_head2.$protocol.$sipstring.$dipstring.$portstring.$timestring.$logstring;
			}
		}
		if ($act == 2)
		{
			$tcp_reject = " -j REJECT --reject-with tcp-reset\n";
			$udp_reject = " -j REJECT --reject-with icmp-host-unreachable\n";
			if($proto == 1)
			{
				    echo $cmd_head." tcp ".$sipstring.$dipstring.$portstring.$timestring.$tcp_reject;
					    echo $cmd_head." ! tcp ".$sipstring.$dipstring.$portstring.$timestring.$udp_reject;
			}
			else if ($proto == 2){echo $cmd_head.$protocol.$sipstring.$dipstring.$portstring.$timestring.$tcp_reject;}
			else {echo $cmd_head.$protocol.$sipstring.$dipstring.$portstring.$timestring.$udp_reject;}
		}
		else
		{ echo $cmd_head.$protocol.$sipstring.$dipstring.$portstring.$timestring.$cmd_tail;}
		$count++;
		if($cmd_head2!="")
		{
			echo $cmd_head2.$protocol.$sipstring.$dipstring.$portstring.$timestring.$cmd_tail;
			$count++;
		}
	}
}

set("/runtime/rgfunc/firewall", $count);
?>
# flush_firewall.php <<<
