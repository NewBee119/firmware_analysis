#!/bin/sh
echo [$0] ... > /dev/console
<?/* vi: set sw=4 ts=4: */
$wanif = query("/runtime/wan/inf:1/interface");
$lanif = query("/runtime/layout/lanif");
$lan2if = query("/runtime/layout/lanif2");
$enhance = query("/nat/general/igmpproxy/enhancement");

if ($ACTION == "add")
{
	if (query("/runtime/router/enable")==1)
	{
		if ($IF==$lanif || $IF==$lan2if)
		{
			if ($GROUP!="")
			{
				$found = 0;
				$count = 0;
				for ("/runtime/igmpproxy/group")
				{
					if (query("ipaddr")==$GROUP) { $found=1; }
					$count++;
				}
				if ($found == 0)
				{
					$count++;
					set("/runtime/igmpproxy/group:".$count."/ipaddr", $GROUP);
					echo "iptables -t nat -A PRE_IGMP -d ".$GROUP." -j ACCEPT\n";
				}
			}
		}
	}
	else
	{
		echo "echo Bridge mode selected. Don't add firewall rule > /dev/console\n";
	}
}
else if ($ACTION == "del")
{
	if (query("/runtime/router/enable")==1)
	{
		$found = 0;
		if ($GROUP!="")
		{
			for ("/runtime/igmpproxy/group") {if (query("ipaddr")==$GROUP) {$found=$@;}}
			if ($found > 0)
			{
				del("/runtime/igmpproxy/group:".$found);
				echo "iptables -t nat -D PRE_IGMP -d ".$GROUP." -j ACCEPT\n";
			}
		}
	}
	else
	{
		echo "echo Bridge mode selected. Don't delete firewall rule > /dev/console\n";
	}
}
else if ($ACTION == "add_member")
{
	if($enhance == 1)
	{
		echo "echo \"add ".$GROUP." ".$SRC."\" > /proc/net/br_igmpp_".$IF."\n";
	}
	/* record memberships for show device information */
	for ("/runtime/igmpproxy/group")
	{
		if (query("ipaddr")==$GROUP) 
		{
			$find=0;
			$count=0;
			for ("/runtime/igmpproxy/group:".$@."/member")
			{
				if (query("ipaddr")==$SRC) {$find=1;}
				$count++;
			}
			if($find==0)
			{
				$count++;
				set("/runtime/igmpproxy/group:".$@."/member:".$count."/ipaddr", $SRC);
			}
		}
	}
}
else if ($ACTION == "del_member")
{
	if($enhance == 1)
	{
		echo "echo \"remove ".$GROUP." ".$SRC."\" > /proc/net/br_igmpp_".$IF."\n";
	}
	/* record memberships for show device information */
	for ("/runtime/igmpproxy/group")
	{
		if (query("ipaddr")==$GROUP) 
		{
			$find=$@;
			for ("/runtime/igmpproxy/group:".$find."/member")
			{
				if (query("ipaddr")==$SRC) 
				{
					del("/runtime/igmpproxy/group:".$find."/member:".$@);
				}
			}
		}
	}
}
else if ($ACTION == "flush")
{
	if (query("/runtime/router/enable")==1)
	{
		del("/runtime/igmpproxy");
		echo "iptables -t nat -F PRE_IGMP\n";
		echo "iptables -t nat -A PRE_IGMP -d 224.0.0.1 -j ACCEPT\n"
	}
	else
	{
		echo "echo Bridge mode selected. Don't delete firewall rule > /dev/console\n";
	}
}
?>
