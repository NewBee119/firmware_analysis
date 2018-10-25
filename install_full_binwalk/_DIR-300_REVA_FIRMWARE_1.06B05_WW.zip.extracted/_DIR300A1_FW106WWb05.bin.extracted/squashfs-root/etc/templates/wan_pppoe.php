# pppoe >>>>
<? /* vi: set sw=4 ts=4: */
$rg_script=1;
$pppoe_session1="session1";
$timeset=query("/runtime/timeset");

if ($generate_start == 1)
{
	anchor("/runtime/layout");
	$wanif=query("wanif");
	$orig_wanmac=query("wanmac");
	$curr_wanmac=query("/runtime/wan/inf:1/mac");
	$clonemac=query("/wan/rg/inf:1/pppoe/clonemac");
	require($template_root."/clone_wanmac.php");

	/* Generate authentication info */
	echo "[ -f /etc/ppp/chap-secrets ] && rm -f /etc/ppp/chap-secrets\n";
	echo "echo -n > /etc/ppp/pap-secrets\n";
	echo "ln -s /etc/ppp/pap-secrets /etc/ppp/chap-secrets\n";

	/* Prepare ip-up ip-down */
	echo "cp ".$template_root."/ppp/ip-up /etc/ppp/.\n";
	echo "cp ".$template_root."/ppp/ip-down /etc/ppp/.\n";
	echo "cp ".$template_root."/ppp/ppp-status /etc/ppp/.\n";

	set("/runtime/wan/inf:1/connecttype", "3");
	anchor("/runtime/wan/inf:1");
	set("ip", "");
	set("netmask", "");
	set("gateway", "");
	set("primarydns", "");
	set("secondarydns", "");
	set("mtu", "");

	$ppp_linkname	= $pppoe_session1;

	/* Query PPP parameters */
	anchor("/wan/rg/inf:1/pppoe");
	$ppp_type			= "pppoe";
	$starspeed_en		= query("starspeed/enable");
	$starspeed_type		= query("starspeed/type");
	if ($starspeed_type == "") { $starspeed_type = 0; }
	if (query("mode")==1)	{ $ppp_staticip = query("staticip"); }
	else					{ $ppp_staticip = ""; }
	$ppp_username		= get("s", "user");
	$ppp_password		= get("s", "password");
	$ppp_idle			= query("idletimeout");
	$ppp_persist		= query("autoReconnect");
	$ppp_demand			= query("onDemand");
	$ppp_schedule		= query("schedule/id");
	$ppp_usepeerdns		= query("autodns");
	if(query("/runtime/func/router_netbios")==1) { $ppp_usepeerwins = 1; }
	$ppp_mtu			= query("mtu");
	$ppp_mru			= query("mtu");
	$pppoe_type			= 0;
	$ppp_defaultroute	= 1;
	if ($ppp_demand!=1) { $ppp_idle=0; }

	set("/runtime/wan/inf:1/pppoetype", $pppoe_type); /* 0: pppoe, 1, unumberred ip, 2: unumberred ip + private */

	/* PPPoE parameters */
	$pppoe_acname  = query("acname");
	$pppoe_service = query("acservice");
	$pppoe_if      = query("/runtime/layout/wanif");

	/* Starspeed (Mainland,China) */
	if ($starspeed_en == 1 && $starspeed_type != 0)
	{
		$ppp_username   = get("s", "/runtime/wan/rg/inf:1/pppoe/starspeed/username");
		$ppp_password	= get("s", "/runtime/wan/rg/inf:1/pppoe/starspeed/password");
		$user_format	= query("/runtime/wan/rg/inf:1/pppoe/starspeed/userformat");
	}

	echo "echo \"\\\"".$ppp_username."\\\" * \\\"".$ppp_password."\\\" *\" >> /etc/ppp/pap-secrets\n";
	require($template_root."/ppp/ppp_setup.php");

	if ($ppp_persist == 1)
	{
		/* Always start WAN if persist (auto-reconnect) is selected. */
		echo "sh /var/run/ppp-".$ppp_linkname.".sh start > /dev/console\n";
	}
	else if ($ppp_schedule != "" && $ppp_schedule != 0)
	{
		/* WAN start with schedule setting. Start WAN when time is not sync yet. */
		set("/runtime/wan/inf:1/scheduled", "1");
		$sch_sock_path = "/var/run/schedule_usock";
		$UNIQUEID = $ppp_schedule;
		require("/etc/templates/rg/__schedule.php");

		$sch_cmd = "usockc ".$sch_sock_path." \"act=add";
		if ($timeset != 1)
		{
			$sch_cmd = $sch_cmd." et=1";
		}
		$sch_cmd = $sch_cmd." start=".$START." end=".$END." days=".$DAYS." cmd=[sh /var/run/ppp-".$ppp_linkname.".sh]\"\n";
		echo $sch_cmd." > /dev/console\n";
	}

	if ($ppp_demand == 1)
	{
		set("/runtime/wan/inf:1/connecttype", "3");
		anchor("/runtime/wan/inf:1");
		set("ip", "10.112.112.112");
		set("netmask", "255.255.255.255");
		set("gateway", "10.112.112.113");
		set("primarydns", "10.112.112.114");
	}
	
	/*----------8021x--------------------------------------------*/
	if (query("/runtime/func/w8021x")=="1" && query("/w8021x/pppoeenable")!="on" )
	{
		if (query("/wan/rg/inf:2/mode") == "1" || query("/wan/rg/inf:2/mode") == "2" )
  	{
  		echo "echo 802.1x authentication is disabled ! > /dev/console\n";
  		echo "echo \"Stop 802.1x Authentication on ".$wanif."...\" > /dev/console\n";
  		//tommy 05.11
  		set("/runtime/w8021x/auth", "");
  		///
  		echo "killall xsupplicant\n"; 
  	}
  }
	else
	{
		if(query("/runtime/func/w8021x")=="1" && query("/w8021x/pppoeenable")=="on")
		{
			if (query("/wan/rg/inf:2/mode") == "1" || query("/wan/rg/inf:2/mode") == "2")
			{
    		anchor("/w8021x");
    		$type = query("pppoetype");
    		$auth = query("pppoeauth");
    		$username = query("pppoeuser");
    		$password = query("pppoepassword");
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

	}	
  	/*--------------------------------------------------------------------------------------*/
}
else
{
	$ppp_schedule  = query("/wan/rg/inf:1/pppoe/schedule/id");
	if($ppp_schedule != "" && $ppp_schedule != 0)
	{
		set("/runtime/wan/inf:1/scheduled", "0");
		$sch_sock_path="/var/run/schedule_usock";
		$UNIQUEID=$ppp_schedule;
		require("/etc/templates/rg/__schedule.php");
		$sch_cmd ="usockc ".$sch_sock_path." \"act=del cmd=[sh /var/run/ppp-".$pppoe_session1.".sh]\"\n";
		echo $sch_cmd." > /dev/console\n";
	}
	echo "sh /var/run/ppp-".$pppoe_session1.".sh stop > /dev/console\n";
	/*-------------------------------802.1x--------------------------------------------------*/
	if (query("/runtime/func/w8021x")=="1" && query("/w8021x/pppoeenable")!="on" )
	{
  		if (query("/wan/rg/inf:2/mode") == "1" || query("/wan/rg/inf:2/mode") == "2" )
  	{
  		echo "echo 802.1x authentication is disabled ! > /dev/console\n";
  	}
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
?># pppoe <<<
