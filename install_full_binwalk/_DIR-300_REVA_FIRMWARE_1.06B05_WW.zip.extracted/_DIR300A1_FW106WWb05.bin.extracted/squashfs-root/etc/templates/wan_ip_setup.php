# wan_ip_setup >>>
<? /* vi: set sw=4 ts=4: */
$dhcpc_ppp_pid = "/var/run/udhcpc-ppp.pid";
$wanif = query("/runtime/layout/wanif");
$wanmode = query("/wan/rg/inf:1/mode");
if		($wanmode == "4")	{ anchor("/wan/rg/inf:1/pptp"); $PPPMODE="PPTP"; }
else if	($wanmode == "5")	{ anchor("/wan/rg/inf:1/l2tp"); $PPPMODE="L2TP"; }
else
{
	echo "echo \"Not in PPTP/L2TP mode ...\" > /dev/console\n";
	exit;
}

/* read configuration */
$dhcp_mode	= query("mode");
$ipaddr		= query("ip");
$subnet		= query("netmask");
$gateway	= query("gateway");
$dns		= query("dns");
$serverip	= query("serverip");
$physical	= query("physical");

if ($generate_start == 1)
{
	if ($PPPMODE == "PPTP")
	{
		set("/runtime/wan/inf:1/connecttype", "4"); 

		$orig_wanmac= query("/runtime/layout/wanmac");
		$curr_wanmac= query("/runtime/wan/inf:1/mac");
		$clonemac   = query("/wan/rg/inf:1/pptp/clonemac");
		require($template_root."/clone_wanmac.php");
	}
	else
	{
		set("/runtime/wan/inf:1/connecttype", "5"); 

		$orig_wanmac= query("/runtime/layout/wanmac");
		$curr_wanmac= query("/runtime/wan/inf:1/mac");
		$clonemac   = query("/wan/rg/inf:1/l2tp/clonemac");
		require($template_root."/clone_wanmac.php");
	}
	set("/runtime/wan/inf:1/ip", "");
	set("/runtime/wan/inf:1/netmask", "");
	set("/runtime/wan/inf:1/gateway", "");
	set("/runtime/wan/inf:1/primarydns", "");
	set("/runtime/wan/inf:1/secondarydns", "");
	set("/runtime/wan/inf:1/mtu", "");

	set("/runtime/wan/phy/ip", "");
	set("/runtime/wan/phy/netmask", "");
	
	/*----------8021x--------------------------------------------*/
	if (query("/runtime/func/w8021x")=="1" && query("/w8021x/pptpenable")!="on" && query("/wan/rg/inf:1/pptp/physical")=="1")
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
		if(query("/runtime/func/w8021x")=="1" && query("/w8021x/pptpenable") =="on" && query("/wan/rg/inf:1/pptp/physical")=="1")
		{
  		anchor("/w8021x");
  		$type = query("pptptype");
  		$auth = query("pptpauth");
  		$username = query("pptpuser");
  		$password = query("pptppassword");
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
  		echo "xsupplicant -c ".$1xConfigFilePath." -i ".$wanif."\n";
  	}

	}	
  /*--------------------------------------------------------------------------------------*/

	if ($dhcp_mode == 2)
	{
		echo "echo \"Setup DHCP for ".$PPPMODE." ...\" > /dev/console\n";
		echo "rgdb -A ".$template_root."/dhcp/dhcpc_ppp.php";
		echo	" -V physical=".$physical." -V server=".$serverip." > /var/run/dhcpc_ppp.sh\n";
		echo "chmod +x /var/run/dhcpc_ppp.sh\n";
		echo "udhcpc -i ".$wanif." -p ".$dhcpc_ppp_pid." -H ".query("/sys/modelname");
		echo " -s /var/run/dhcpc_ppp.sh -D2 -R5 -S300 &\n";
	}
	else
	{
		echo "echo \"Setup Static IP for ".$PPPMODE." ...\" > /dev/console\n";
		echo "ifconfig ".$wanif." ".$ipaddr;
		if ($subnet != "" && $subnet != "0.0.0.0") { echo " netmask ".$subnet; }
		echo "\n";
		if ($dns != "" && $dns != "0.0.0.0")
		{
			echo "echo \"nameserver ".$dns."\" > /etc/resolv.conf\n";
			echo "if [ \"`chnet ".$dns." ".$subnet."`\" != \"`chnet ".$ipaddr." ".$subnet."`\" ]; then\n";
			echo "	route add -host ".$dns." gw ".$gateway." dev ".$wanif."\n";
			echo "fi\n";
		}
		/* Is server alive? */
		echo "route add default gw ".$gateway."\n";
		echo "SERVER=`gethostip -D ".$serverip."`\n";
		echo "for sip in $SERVER ; do\n";
		echo "echo ping $sip... > /dev/console\n";
		echo "result=`ping $sip | grep \"is alive\"`\n";
		echo "if [ \"$result\" != \"\" ]; then\n";
		echo "	echo get response from $sip !! > /dev/console\n";
		echo "	break\n";
		echo "fi\n";
		echo "done\n";
		/* Is it possible that server doesn't want to response ping? */
		echo "if [ \"$result\" = \"\" ]; then\n";
		echo "sip=`gethostip -d ".$serverip."`\n";
		echo "fi\n";
		echo "if [ \"$sip\" != \"\" ]; then\n";
		echo "	if [ \"`chnet $sip ".$subnet."`\" != \"`chnet ".$ipaddr." ".$subnet."`\" ]; then\n";
		echo "		route add -host $sip gw ".$gateway." dev ".$wanif."\n";
		echo "	fi\n";
		echo "	echo \"Using server $sip\" > /dev/console\n";
		echo "	sh ".$template_root."/wan_ppp.sh start $sip > /dev/console\n";
		echo "else\n";
		echo "	echo \"Can not find server (".$serverip.") : $sip\" > /dev/console\n";
		echo "fi\n";
		echo "route del default\n";

		set("/runtime/wan/phy/ip",		$ipaddr);
		set("/runtime/wan/phy/netmask",	$subnet);
		if ($physical==1)
		{
			set("/runtime/wan/inf:2/connecttype", "1");
			anchor("/runtime/wan/inf:2");
			set("connectstatus", "connected");
			set("ip", $ipaddr);
			set("netmask", $subnet);
			set("gateway", $gateway);
			set("primarydns", $dns);
			set("interface", $wanif);

			if ($PPPMODE == "L2TP")
			{ $ppp_persist=query("/wan/rg/inf:1/l2tp/autoreconnect"); }
			else 
			{ $ppp_persist=query("/wan/rg/inf:1/pptp/autoreconnect"); }

			if ($ppp_persist!=1) 
			{ 
				echo "route add default gw ".$gateway."\n"; 
				echo "/etc/templates/rg.sh misc > /dev/console\n";
			}
			echo $template_root."/route.sh restart > /dev/console\n";
		}
		echo "submit DNSR\n";
	}
}
else
{
	echo $template_root."/wan_ppp.sh stop > /dev/console\n";
	if ($dhcp_mode == 2)
	{
		echo "echo \"Stop DHCP of ".$PPPMODE." ...\" > /dev/console\n";
		echo "if [ -f ".$dhcpc_ppp_pid." ]; then\n";
		echo "	PID=`cat ".$dhcpc_ppp_pid."`\n";
		echo "	if [ $PID != 0 ]; then\n";
		echo "		kill -SIGUSR2 $PID > /dev/console 2>&1\n";
		echo "		kill $PID > /dev/console 2>&1\n";
		echo "	fi\n";
		echo "	rm -f ".$dhcpc_ppp_pid."\n";
		echo "fi\n";
	}
	else
	{
		echo "echo \"Stop Static IP of ".$PPPMODE." ...\" > /dev/console\n";
		echo "rgdb -i -s /runtime/wan/inf:2/connectstatus disconnected\n";
		echo "rgdb -i -s /runtime/wan/inf:2/ip \"\"\n";
		echo "rgdb -i -s /runtime/wan/inf:2/netmask \"\"\n";
		echo "rgdb -i -s /runtime/wan/inf:2/gateway \"\"\n";
		echo "rgdb -i -s /runtime/wan/inf:2/primarydns \"\"\n";
		echo "rgdb -i -s /runtime/wan/inf:2/secondarydns \"\"\n";
		echo "rgdb -i -s /runtime/wan/inf:2/interface \"\"\n";
		echo "rgdb -i -s /runtime/wan/inf:2/mtu \"\"\n";
	}
	echo "ifconfig ".$wanif." 0.0.0.0 > /dev/console 2>&1\n";
	
	/*-------------------------------802.1x--------------------------------------------------*/
	if (query("/runtime/func/w8021x")=="1" && query("/w8021x/pptpenable")!="on" && query("/wan/rg/inf:1/pptp/physical")=="1")
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
	/*-------------------------------------------------------------------------------------*/
}
?>#wan_ip_setup <<<
