# wan_static >>>
<? /* vi: set sw=4 ts=4: */
$router = query("/runtime/router/enable");
if ($router == 1)	{ $wanif = query("/runtime/layout/wanif"); }
else				{ $wanif = query("/runtime/layout/lanif"); }

if ($generate_start == 1)
{
	echo "echo \"It is the Static mod start!!\" > /dev/console\n";
	anchor("/wan/rg/inf:1/static");
	$ipaddr  = query("ip");
	$netmask = query("netmask");
	$gateway = query("gateway");
	$mtu     = query("mtu");
	if ($mtu=="" || $mtu=="0") { $mtu="1500"; }
	
	anchor("/dnsrelay/server");
	$pri_dns = query("primarydns");
	$sec_dns = query("secondarydns");

	if ($router == 1)
	{
		$orig_wanmac= query("/runtime/layout/wanmac");
		$curr_wanmac= query("/runtime/wan/inf:1/mac");
		$clonemac	= query("/wan/rg/inf:1/static/clonemac");
		require($template_root."/clone_wanmac.php");
	}

	set("/runtime/wan/inf:1/connecttype", "1");
	anchor("/runtime/wan/inf:1");
	set("connectstatus", "connected");
	set("ip", $ipaddr);
	set("netmask", $netmask);
	set("gateway", $gateway);
	set("primarydns", $pri_dns);
	set("secondarydns", $sec_dns);
	set("interface", $wanif);
	set("mtu", $mtu);

	$param="";
	if ($netmask != "" && $netmask != "0.0.0.0")	{ $param=$param." netmask ".$netmask; }
	if ($mtu != "" && $mtu != "0")					{ $param=$param." mtu ".$mtu; }
	echo "ifconfig ".$wanif." ".$ipaddr.$param."\n";
	/* Enable arp filter */
	if ($wanif != "")
	{
		echo "echo 1 > /proc/sys/net/ipv4/conf/".$wanif."/arp_filter\n";
		echo "echo 1 > /proc/sys/net/ipv4/conf/".$wanif."/arp_ignore\n";
		echo "echo 1 > /proc/sys/net/ipv4/conf/".$wanif."/arp_announce\n";
	}
	echo "echo \"Start WAN(".$wanif."),".$ipaddr."/".$netmask." ...\" > /dev/console\n";

	if ($gateway != "" && $gateway != "0.0.0.0")	{ echo "route add default gw ".$gateway." dev ".$wanif."\n"; }
	echo "echo -n > /etc/resolv.conf\n";
	if ($pri_dns != "" && $pri_dns != "0.0.0.0")	{ echo "echo \"nameserver ".$pri_dns."\" >> /etc/resolv.conf\n"; }
	if ($sec_dns != "" && $sec_dns != "0.0.0.0")	{ echo "echo \"nameserver ".$sec_dns."\" >> /etc/resolv.conf\n"; }

	echo $template_root."/wanup.sh > /dev/console\n";
	
	/*--------------8021x------------------*/
	if (query("/runtime/func/w8021x")=="1" && query("/w8021x/stenable")!="on")
	{
		echo "echo 802.1x authentication is disabled ! > /dev/console\n";
		echo "echo \"Stop 802.1x Authentication on ".$wanif."...\" > /dev/console\n";
		//tommy 05.11
		set("/runtime/w8021x/auth", "");
		///
		echo "killall xsupplicant\n"; 
		
	}
	else
	{
		if(query("/runtime/func/w8021x")=="1")
		{
  		anchor("/w8021x");
  		$type = query("sttype");
  		$auth = query("stauth");
  		$username = query("stuser");
  		$password = query("stpassword");
  		$1xConfigFilePath = "/var/1xConfig.conf";
  		$phase1 = "";
  		$phase2 = "";
  
  		if($type=="1") {$phase1="md5";} /* MD5 */
  		if($type=="2") {$phase1="peap"; $phase2="mschapv2";} /* PEAP: and it only support MSCHAPv2 */
  		if($type=="3") {$phase1="ttls";} /* TTLS */
  		
  
  		if($auth=="1") {$phase2="pap";} /* PAP */
  		if($auth=="2") {$phase2="chap";} /* CHAP */
  		if($auth=="3") {$phase2="mschap";} /* MSCHAP */
  		if($auth=="4") {$phase2="mschapv2";} /* MSCHAPv2 */
  				
  		fwrite($1xConfigFilePath, "network_list = all\n");
  		fwrite2($1xConfigFilePath, "destination = multicast\n");
  		fwrite2($1xConfigFilePath, "default_netname = default\n");
  		fwrite2($1xConfigFilePath, "logfile = /var/log/xsupplicant.log\n");  
  		fwrite2($1xConfigFilePath, "default{\n");
  		fwrite2($1xConfigFilePath, "\ttype = wired\n");
  		fwrite2($1xConfigFilePath, "\tallow_types=all\n");
  		fwrite2($1xConfigFilePath, "\tidentity = \"".$username."\"\n");
  		
  		fwrite2($1xConfigFilePath, "\teap-".$phase1."{\n");
  		if($type=="1") /* MD5 */
  		{
  			fwrite2($1xConfigFilePath, "\t\tusername=\"".$username."\"\n");
  			fwrite2($1xConfigFilePath, "\t\tpassword=\"".$password."\"\n");
  		}
  		if($type=="2" || $type=="3") /* PEAP and TTLS */
  		{
  			
  			fwrite2($1xConfigFilePath, "\t\troot_cert=NONE\n"); 
  
  			fwrite2($1xConfigFilePath, "\t\tchunk_size = 1398\n");
  			fwrite2($1xConfigFilePath, "\t\trandom_file = /dev/urandom\n");
  			fwrite2($1xConfigFilePath, "\t\tsession_resume = yes\n");
  			if($type=="2") /* PEAP */
  			{
  				
  				fwrite2($1xConfigFilePath, "\t\tallow_types = all\n");
  				fwrite2($1xConfigFilePath, "\t\teap-".$phase2."{\n");
  			}
  			if($type=="3") /* TTLS */
  			{
  				fwrite2($1xConfigFilePath, "\t\tphase2_type=".$phase2."\n");
  				fwrite2($1xConfigFilePath, "\t\t".$phase2."{\n");
  			}
  			fwrite2($1xConfigFilePath, "\t\t\tusername=\"".$username."\"\n");
  			fwrite2($1xConfigFilePath, "\t\t\tpassword=\"".$password."\"\n");
  			fwrite2($1xConfigFilePath, "\t\t}\n");
  		}
  
  		fwrite2($1xConfigFilePath, "\t}\n");
  		
  		fwrite2($1xConfigFilePath, "}\n");
  
  		echo "ifconfig ".$wanif." promisc\n";
  		echo "ifconfig ".$wanif." down\n";
  		echo "ifconfig ".$wanif." up\n";
  
  		echo "echo \"Start 802.1x Authentication on ".$wanif."...\" > /dev/console\n";
  		echo "xsupplicant -c ".$1xConfigFilePath." -i ".$wanif." \n";
  	}
	}	
	
	if ($gateway != "" && $gateway != "0.0.0.0")	{ echo "route add default gw ".$gateway." dev ".$wanif."\n"; }
	echo "echo -n > /etc/resolv.conf\n";
	if ($pri_dns != "" && $pri_dns != "0.0.0.0")	{ echo "echo \"nameserver ".$pri_dns."\" >> /etc/resolv.conf\n"; }
	if ($sec_dns != "" && $sec_dns != "0.0.0.0")	{ echo "echo \"nameserver ".$sec_dns."\" >> /etc/resolv.conf\n"; }
}
else
{
	echo "echo \"Stop WAN ...\" > /dev/console\n";
	echo "[ -f ".$template_root."/wandown.sh ] && ".$template_root."/wandown.sh > /dev/console\n";
	echo "ifconfig ".$wanif." 0.0.0.0 > /dev/console 2>&1\n";
	echo "route del default gw 0.0.0.0 dev ".$wanif." > /dev/console\n";
	echo "rgdb -i -s /runtime/wan/inf:1/connectstatus disconnected\n";
	echo "rgdb -i -s /runtime/wan/inf:1/ip \"\"\n";
	echo "rgdb -i -s /runtime/wan/inf:1/netmask \"\"\n";
	echo "rgdb -i -s /runtime/wan/inf:1/gateway \"\"\n";
	echo "rgdb -i -s /runtime/wan/inf:1/primarydns \"\"\n";
	echo "rgdb -i -s /runtime/wan/inf:1/secondarydns \"\"\n";
	echo "rgdb -i -s /runtime/wan/inf:1/interface \"\"\n";
	echo "rgdb -i -s /runtime/wan/inf:1/mtu \"\"\n";
	
	if (query("/runtime/func/w8021x")=="1" && query("/w8021x/stenable")!="on")
	{
		echo "echo 802.1x authentication is disabled ! > /dev/console\n";
	}
	else 
	{	
		if(query("/runtime/func/w8021x")=="1")
		{
  		echo "echo \"Stop 802.1x Authentication on ".$wanif."...\" > /dev/console\n";
			//tommy 05.11
			set("/runtime/w8021x/auth", "");
			///
  		echo "killall xsupplicant\n"; 
		}
	}
}
?>
# wan_static <<<
