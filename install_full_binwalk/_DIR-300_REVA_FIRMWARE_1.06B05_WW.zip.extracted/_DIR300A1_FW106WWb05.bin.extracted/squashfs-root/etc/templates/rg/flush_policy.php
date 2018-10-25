# flush_policy >>>
TROOT="/etc/templates"
iptables -F FOR_POLICY
[ -f /var/run/delete_policy.sh ] && sh /var/run/delete_policy.sh > /dev/console
rgdb -A $TROOT/rg/delete_policy.php > /var/run/delete_policy.sh
<? /* vi: set sw=4 ts=4: */
$policy_id	= 0;
$webfilter  = "";

if (query("/security/policy/enable")==1)
{
	//echo "iptables -N FOR_POLICY\n";
	for ("/security/policy/entry")
	{
		$policy_id++;
		
		$enable		= query("enable");
		$UNIQUEID	= query("schedule/id");
		
		/* create a new policy rule and filter */
		echo "iptables -N FOR_POLICY_RULE".$policy_id."\n";
		echo "iptables -N FOR_POLICY_FILTER".$policy_id."\n";
		echo "iptables -A FOR_POLICY -j FOR_POLICY_RULE".$policy_id."\n";
		if ($enable==1)
		{
			/* machine match */
			$IPTCMD = "iptables -A FOR_POLICY_RULE".$policy_id;
			$ip_count = 0;
			$mac_count = 0;
			for ("/security/policy/entry:".$policy_id."/machine/entry")
			{
				$type = query("type");
				$value = query("value");

				if($type == 1)
				{
					$MACHINE = " -s ".$value;
					$ip_count++;
				}
				else if($type == 2)
				{
					$MACHINE = " -m mac --mac-source ".$value;
					$mac_count++;
				}
				else
				{
					$MACHINE = "";
				}
				echo $IPTCMD.$MACHINE." -j FOR_POLICY_FILTER".$policy_id."\n";
			}

			/* port filter or web filter target */
			$method = query("method");
			if($method == 1) //log web access only
			{
				$logtgt     = " -j LOG --log-level info --log-prefix";
				$log_prefix = " 'NTC:045:'\n";
				$IPTCMD  = "iptables -A FOR_POLICY_FILTER".$policy_id." -p tcp --dport 80 ";
				if ($UNIQUEID!="") { require("/etc/templates/rg/__schedule.php"); }
				echo $IPTCMD.$TIMESTRING." -m string --http_req ".$logtgt.$log_prefix;																
			}
			else if($method == 2) //block all access
			{
				$logtgt     = " -j LOG --log-level info --log-prefix";
				$log_prefix = " 'DRP:008:'\n";
				$IPTCMD = "iptables -A FOR_POLICY_FILTER".$policy_id." -p all ";
				if ($UNIQUEID!="") { require("/etc/templates/rg/__schedule.php"); }
				echo $IPTCMD.$TIMESTRING.$logtgt.$log_prefix;
				echo $IPTCMD.$TIMESTRING." -j DROP\n";
			}
			else //block some access
			{
				/* Do web filter target */
				$en_webfilter = query("webfilter/enable");
				$en_logging	= query("webfilter/logging");
				if($en_webfilter == 1)
				{
					/* if have web filter enabled, mark it */
					$webfilter = 1;
					//echo "iptables -F FOR_BLOCKING\n";

					$logtgt		= " -j LOG --log-level info --log-prefix";
					$log_prefix = " 'DRP:008:'\n";
					$IPTURLCMD	= "iptables -A FOR_POLICY_FILTER".$policy_id." -p tcp --dport 80 ";
					if (query("/security/urlblocking/action")==1) //1:allow the entries, deny the others
					{
						for ("/security/urlblocking/entry")
						{	
							//$enable = query("enable");   //  This ENABLE node does not been used by the adv_web_filter.php page, so we also don't use it here.
							$target_url=query("url");
							if ($target_url!=""/*  && $enable!=0*/)
							{
								if ($UNIQUEID!="") { require("/etc/templates/rg/__schedule.php"); }
								echo $IPTURLCMD.$TIMESTRING." -m string --url ".$target_url." -j ACCEPT\n";
							}
						}
						if ($en_logging == 1) {echo $IPTURLCMD." -m string --http_req ".$logtgt.$log_prefix;}
						echo $IPTURLCMD." -m string --http_req -j DROP\n";
					}
					else if (query("/security/urlblocking/action")==0)//0:deny the entries, allow the others
					{
						for ("/security/urlblocking/entry")
						{
							//$enable = query("enable");   //  This ENABLE node does not been used by the adv_web_filter.php page, so we also don't use it here.
							$target_url=query("url");
							if ($target_url!="" /*&& $enable!=0*/)
							{
								if ($UNIQUEID!="") { require("/etc/templates/rg/__schedule.php"); }
								if ($en_logging == 1) 
								{
									echo $IPTURLCMD.$TIMESTRING." -m string --url ".$target_url.$logtgt.$log_prefix;
								}
								echo $IPTURLCMD.$TIMESTRING." -m string --url ".$target_url." -j DROP\n";
							}
						}
					}
				} //end if enable webfilter

				/* Do port filter target */
				$en_portfilter = query("portfilter/enable");
				if($en_portfilter == 1)
				{
					$logtgt		= " -j LOG --log-level info --log-prefix";
					$log_prefix = " 'DRP:008:'\n";
					$IPTPORTCMD = "iptables -A FOR_POLICY_FILTER".$policy_id;
					for ("/security/policy/entry:".$policy_id."/portfilter/entry")
					{
						$enable = query("enable");
						if ($enable == 1)
						{
							$dsip = query("startip");
							$deip = query("endip");
							$dsport = query("startport");
							$deport = query("endport");
							$proto = query("protocol");
							
							if		($proto==1)	{ $protocol=" -p all"; }
							else if	($proto==2)	{ $protocol=" -p tcp"; }
							else if	($proto==3)	{ $protocol=" -p udp"; }
							else if	($proto==4)	{ $protocol=" -p icmp"; }

							if ($dsip == $deip) { $dstiprange = " -d ".$deip; }
							else				{ $dstiprange = " -m iprange --dst-range ".$dsip."-".$deip; }
							$dstportrange="";
							if ($proto==2 || $proto==3)
							{
								if($dsport == $deport)	{ $dstportrange = " --dport ".$deport; }
								else					{ $dstportrange = " --dport ".$dsport.":".$deport; }
							}
									
							if ($UNIQUEID!="") { require("/etc/templates/rg/__schedule.php"); }

							echo $IPTPORTCMD.$protocol.$dstiprange.$dstportrange.$TIMESTRING.$logtgt.$log_prefix;
							echo $IPTPORTCMD.$protocol.$dstiprange.$dstportrange.$TIMESTRING." -j DROP\n";
						}
					}
				}

			} //end block some access
		} //end if policy entry enable
	} //for policy entry loop
	if ($policy_id > 0) { echo "logger -p 192.0 \"SYS:040\"\n"; }
} //end if policy function enable
else
{
	echo "logger -p 192.0 \"SYS:039\"\n";
}
set("/runtime/rgfunc/webfilter", $webfilter);
set("/runtime/rgfunc/policy", $policy_id);
set("/runtime/rgfunc/weblog", $policy_id);
?>
# flush_policy <<< 
