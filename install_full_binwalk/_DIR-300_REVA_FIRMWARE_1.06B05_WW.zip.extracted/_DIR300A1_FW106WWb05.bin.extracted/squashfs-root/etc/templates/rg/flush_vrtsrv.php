# flush_vrtsrv.php >>>
<? /* vi: set sw=4 ts=4: */ ?>
iptables -t nat -F PRE_VRTSRV
iptables -t nat -F PST_VRTSRV
iptables -t nat -F PST_VRTSRV2
<?
$count		= 0;
$idcount	= 0;
$ftpcount	= 0;

$CMD_MAN = "iptables -t mangle -A PRE_MARK";
$CMD_NAT = "iptables -t nat -A PRE_VRTSRV";

if ($wanip!="")
{
	for("/nat/vrtsrv/entry")
	{
		if (query("enable")==1)
		{
			$protocol	= query("protocol");
			$priip		= query("internal/ip");
			$priport	= query("internal/startport");
			$priport2	= query("internal/endport");
			$pubport	= query("external/startport");
			$pubport2	= query("external/endport");
						
			/* Schedule */
			$TIMESTRING	= "";
			$UNIQUEID	= query("schedule/id");
			if ($UNIQUEID!="") { require("/etc/templates/rg/__schedule.php"); }

			/* Public port */
			$single_port="0";
			if($priport==$priport2 && $pubport==$pubport2)	{$single_port="1";}
			if($priport2=="" && $pubport2=="")				{$single_port="1";}
			if($single_port=="1")
			{ 
				$PUB_PORT = " --dport ".$pubport;
				$DNAT_TARGET = " --to-destination ".$priip.":".$priport;
			}
            else
			{
				$PUB_PORT = " -m mport --ports ".$pubport.":".$pubport2;
				if ($priport == $pubport)
				{
					$DNAT_TARGET = " --to-destination ".$priip.":".$priport."-".$priport2;
				}
				else
				{
					if ($priport > $pubport)	{ $SHIFT = $priport - $pubport; }
					else						{ $SHIFT = 65536 - $pubport + $priport; }
					$DNAT_TARGET = " --to-shift ".$priip.":".$SHIFT;
				}
			}

			$TARGET_MAN = " -j MARK --set-mark 1\n";
			$TARGET_MAN2 = " -j MARK --set-mark 2\n";
			$TARGET_NAT = " -j DNAT".$DNAT_TARGET."\n";
			/* generate iptables command */
			if($protocol==0 || $protocol == 1)
			{
				echo $CMD_MAN." -p tcp".$PUB_PORT." -d ".$wanip.$TARGET_MAN;
				echo $CMD_NAT." -p tcp".$PUB_PORT." -d ".$wanip.$TIMESTRING.$TARGET_NAT;
				$count++;
				if($wan2if!="" && $wan2ip!="")
				{
					echo $CMD_MAN." -p tcp".$PUB_PORT." -d ".$wan2ip.$TARGET_MAN2;
					echo $CMD_NAT." -p tcp".$PUB_PORT." -d ".$wan2ip.$TIMESTRING.$TARGET_NAT;
					$count++;
				}
			}
			if($protocol==0 || $protocol == 2)
			{
				echo $CMD_MAN." -p udp".$PUB_PORT." -d ".$wanip.$TARGET_MAN;
				echo $CMD_NAT." -p udp".$PUB_PORT." -d ".$wanip.$TIMESTRING.$TARGET_NAT;
				$count++;
				if($wan2if!="" && $wan2ip!="")
				{
					echo $CMD_MAN." -p udp".$PUB_PORT." -d ".$wan2ip.$TARGET_MAN2;
					echo $CMD_NAT." -p udp".$PUB_PORT." -d ".$wan2ip.$TIMESTRING.$TARGET_NAT;
					$count++;
				}
			}
			
		}
	}
	echo "iptables -t nat -A PST_VRTSRV -j SNAT --to-source ".$wanip."\n";
	if($wan2if!="" && $wan2ip!="")
	{
		echo "iptables -t nat -A PST_VRTSRV2 -j SNAT --to-source ".$wan2ip."\n";
	}

	set("/runtime/rgfunc/vrtsrv", $count);
}
?>
# flush_vrtsrv.php <<<
