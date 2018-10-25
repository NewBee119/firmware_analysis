<?
/* vi: set sw=4 ts=4: ---------------------------------------------------------*/
$MY_NAME		="bsc_wan";
$MY_MSG_FILE	=$MY_NAME.".php";
$CATEGORY		="bsc";
$SUB_CATEGORY	="bsc_internet";
/* --------------------------------------------------------------------------- */

if ($ACTION_POST!="")
{
	require("/www/model/__admin_check.php");
	$SUBMIT_STR="";
	$dirty=0;
	$rb_dirty=0;
  
  echo "<!--\n";
	echo "ACTION_POST=".$ACTION_POST."\n";
	echo "enable_ap_mode=".$enable_ap_mode."\n";

	$bridge_setting = query("/bridge");
	if ($enable_ap_mode!=1) {$enable_ap_mode=0;}
	if ($bridge_setting!=1) {$bridge_setting=0;}
	if ($bridge_setting!=$enable_ap_mode) {$rb_dirty++; set("/bridge", $enable_ap_mode);}
if($enable_ap_mode!=1)
{
	if ($pppoe_phy!="1" && query("/wan/rg/inf:2/mode")!="")
	{
		$dirty++;
		//del("/wan/rg/inf:2");
		set("/wan/rg/inf:1/pppoe/mppe/enable", "0");
		$pppoe_mppe=0;
	}
	if ($pptp_phy!="1" && query("/wan/rg/inf:1/pptp/physical")==1)
	{
		$dirty++;
		set("/wan/rg/inf:1/pptp/physical","0");
		set("/wan/rg/inf:1/pptp/mppe/enable", "0");
		$pptp_mppe=0;
	}
}
	/* disable netsniper */
	if(query("/runtime/func/netsniper")=="1")
	{
		if(query("/wan/rg/inf:1/netsniper_enable") != 0) {$dirty++; set("/wan/rg/inf:1/netsniper_enable", 0);}
	}

	if ($ACTION_POST == "STATIC")
	{
		echo "ipaddr=".$ipaddr."\n";
		echo "netmask=".$netmask."\n";
		echo "gateway=".$gateway."\n";
		echo "clonemac=".$clonemac."\n";
		echo "dns1=".$dns1."\n";
		echo "dns2=".$dns2."\n";
		echo "mtu=".$mtu."\n";
		

		$entry="/wan/rg/inf:1/static/";
		if (query("/wan/rg/inf:1/mode")	!="1")	{$dirty++; set("/wan/rg/inf:1/mode", "1");}
		if (query($entry."ip")		!=$ipaddr)	{$dirty++; set($entry."ip", $ipaddr);}
		if (query($entry."netmask")	!=$netmask)	{$dirty++; set($entry."netmask", $netmask);}
		if (query($entry."gateway")	!=$gateway)	{$dirty++; set($entry."gateway", $gateway);}
		if (query($entry."clonemac")!=$clonemac){$dirty++; set($entry."clonemac", $clonemac);}
		if (query($entry."mtu")		!=$mtu)		{$dirty++; set($entry."mtu", $mtu);}
		$entry="/dnsrelay/server/";
		if (query($entry."primarydns")	!=$dns1){$dirty++; set($entry."primarydns", $dns1);}
		if (query($entry."secondarydns")!=$dns2){$dirty++; set($entry."secondarydns", $dns2);}
		$entry="/w8021x/";
		if (query($entry."sttype")!=$st_eap_type){$dirty++; set($entry."sttype", $st_eap_type);}
		if (query($entry."stauth")!=$st_authentication){$dirty++; set($entry."stauth", $st_authentication);}
		if (query($entry."stenable")!=$enable_st8021x){$dirty++; set($entry."stenable", $enable_st8021x);}
		if (query($entry."stuser")!=$st_username){$dirty++;	set($entry."stuser", $st_username);}
		if (query($entry."stpassword")!=$st_password){$dirty++; set($entry."stpassword", $st_password);}
	}
	else if ($ACTION_POST == "DHCP")
	{
		echo "hostname=".$hostname."\n";
		echo "clonemac=".$clonemac."\n";
		echo "dns1=".$dns1."\n";
		echo "dns2=".$dns2."\n";
		echo "mtu=".$mtu."\n";

		$entry="/wan/rg/inf:1/dhcp/";
		if (query("/wan/rg/inf:1/mode") != "2")	{$dirty++; set("/wan/rg/inf:1/mode", "2");}
		if (query("/sys/hostname")!= $hostname)	{$dirty++; set("/sys/hostname", $hostname);}
		if (query($entry."clonemac")!= $clonemac)	{$dirty++; set($entry."clonemac", $clonemac);}
		if (query($entry."mtu")		!= $mtu)		{$dirty++; set($entry."mtu", $mtu);}
		$entry="/dnsrelay/server/";
		if (query($entry."primarydns")	!=$dns1){$dirty++; set($entry."primarydns", $dns1);}
		if (query($entry."secondarydns")!=$dns2){$dirty++; set($entry."secondarydns", $dns2);}
		$entry="/w8021x/";
		if (query($entry."dhtype")!=$dh_eap_type){$dirty++; set($entry."dhtype", $dh_eap_type);}
		if (query($entry."dhauth")!=$dh_authentication){$dirty++; set($entry."dhauth", $dh_authentication);}
		if (query($entry."dhenable")!=$enable_dh8021x){$dirty++; set($entry."dhenable", $enable_dh8021x);}
    if (query($entry."dhuser")!=$dh_username){$dirty++; set($entry."dhuser", $dh_username);}
    if (query($entry."dhpassword")!=$dh_password){$dirty++; set($entry."dhpassword", $dh_password);}
    
	}
	else if ($ACTION_POST == "PPPOE")
	{
		echo "mode=".$mode."\n";
		echo "username=".$username."\n";
		echo "pppoe_mppe=".$pppoe_mppe."\n";
		echo "password=".$password."\n";
		echo "svc_name=".$svc_name."\n";
		echo "ipaddr=".$ipaddr."\n";
		echo "clonemac=".$clonemac."\n";
		echo "autodns=".$autodns."\n";
		echo "dns1=".$dns1."\n";
		echo "dns2=".$dns2."\n";
		echo "idletime=".$idletime."\n";
		echo "mtu=".$mtu."\n";
		echo "ppp_auto=".$ppp_auto."\n";
		echo "ppp_ondemand=".$ppp_ondemand."\n";
		echo "wan_sch=".$wan_sch."\n";
		echo "pppoe_phy=".$pppoe_phy."\n";
		echo "phy_mode=".$phy_mode."\n";
		echo "phy_ip=".$phy_ip."\n";
		echo "phy_mask=".$phy_mask."\n";
		echo "phy_gw=".$phy_gw."\n";
		echo "phy_dns1=".$phy_dns1."\n";
		echo "phy_dns2=".$phy_dns2."\n";

		if ($pppoe_mppe != "1") {$pppoe_mppe = "0";}
		$entry="/wan/rg/inf:1/pppoe/";
		if (query("/wan/rg/inf:1/mode") != "3")		{$dirty++; set("/wan/rg/inf:1/mode", "3");}
		if (query($entry."mode") != $mode)			{$dirty++; set($entry."mode", $mode);}
		if (query($entry."user") != $username)		{$dirty++; set($entry."user", $username);}
		if (query($entry."mppe/enable") != $pppoe_mppe) {$dirty++; set($entry."mppe/enable", $pppoe_mppe);}
		if ($G_DEF_PASSWORD != $password &&
			query($entry."password") != $password)	{$dirty++; set($entry."password", $password);}
		if (query($entry."acservice") != $svc_name)	{$dirty++; set($entry."acservice", $svc_name);}
		if ($mode == "1")
		{
			if (query($entry."staticip")!=$ipaddr)	{$dirty++; set($entry."staticip", $ipaddr);}
			//if (query($entry."autodns") != "0")		{$dirty++; set($entry."autodns", "0");}
			if (query($entry."autodns") != $autodns)	{$dirty++; set($entry."autodns", $autodns);}
			if ($autodns != "1") //manually
			{
				$d_en="/dnsrelay/server/";
				if (query($d_en."primarydns")!=$dns1)	{$dirty++; set($d_en."primarydns", $dns1);}
				if (query($d_en."secondarydns")!=$dns2)	{$dirty++; set($d_en."secondarydns", $dns2);}
			}
		}
		else
		{
			//if (query($entry."autodns") != "1")		{$dirty++; set($entry."autodns", "1");}
			if (query($entry."autodns") != $autodns)	{$dirty++; set($entry."autodns", $autodns);}
			if ($autodns != "1") //manually
			{
				$d_en="/dnsrelay/server/";
				if (query($d_en."primarydns")!=$dns1)	{$dirty++; set($d_en."primarydns", $dns1);}
				if (query($d_en."secondarydns")!=$dns2)	{$dirty++; set($d_en."secondarydns", $dns2);}
			}
		}
		if($ppp_ondemand=="1")
		{
			$idletime = $idletime * 60;
			if (query($entry."idletimeout")!=$idletime)	{$dirty++; set($entry."idletimeout", $idletime);}
		}
		if (query($entry."mtu")!=$mtu)					{$dirty++; set($entry."mtu", $mtu);}
		if (query($entry."autoreconnect")!=$ppp_auto)	{$dirty++; set($entry."autoreconnect", $ppp_auto);}
		if (query($entry."ondemand") != $ppp_ondemand)	{$dirty++; set($entry."ondemand", $ppp_ondemand);}
		if (query($entry."schedule/id") != $wan_sch)	{$dirty++; set($entry."schedule/id", $wan_sch);}
		if (query($entry."clonemac") != $clonemac)		{$dirty++; set($entry."clonemac", $clonemac);}
		/* Russia PPPoE */
		if ($pppoe_phy == "1")
		{
			if ($phy_mode =="1" || $phy_mode =="2")
			{
				if (query("/wan/rg/inf:2/mode")!=$phy_mode)	{$dirty++; set("/wan/rg/inf:2/mode",$phy_mode);}
				if($phy_mode == "1")
				{
					$entry="/wan/rg/inf:2/static/";
					if (query($entry."ip")!=$phy_ip)			{$dirty++; set($entry."ip",				$phy_ip);}
					if (query($entry."netmask")!=$phy_mask)		{$dirty++; set($entry."netmask",		$phy_mask);}
					if (query($entry."gateway")!=$phy_gw)		{$dirty++; set($entry."gateway",		$phy_gw);}
					if (query($entry."primarydns")!=$phy_dns1)	{$dirty++; set($entry."primarydns",		$phy_dns1);}
					if (query($entry."secondarydns")!=$phy_dns2){$dirty++; set($entry."secondarydns",	$phy_dns2);}
				}
			}
		}
		else
		{
			del("/wan/rg/inf:2");
		}

		$entry="/w8021x/";
		if (query($entry."pppoetype")!=$pppoe_eap_type){$dirty++; set($entry."pppoetype", $pppoe_eap_type);}
		if (query($entry."pppoeauth")!=$pppoe_authentication){$dirty++; set($entry."pppoeauth", $pppoe_authentication);}
		if (query($entry."pppoeenable")!=$enable_pppoe8021x){$dirty++; set($entry."pppoeenable", $enable_pppoe8021x);}
    if (query($entry."pppoeuser")!=$pppoe_username){$dirty++; set($entry."pppoeuser", $pppoe_username);}
    if (query($entry."pppoepassword")!=$pppoe_password){$dirty++; set($entry."pppoepassword", $pppoe_password);}
    
		/* netsniper */
		if($LANGCODE == "zhcn")
		{
			if(query("/runtime/func/netsniper")=="1")
			{
				echo "netsniper_enable=".$netsniper_enable."\n";
				if(query("/wan/rg/inf:1/netsniper_enable") != $netsniper_enable) {$dirty++; set("/wan/rg/inf:1/netsniper_enable", $netsniper_enable);}
			}
		}
		/* starspeed*/
		if(query("/wan/rg/inf:1/pppoe/starspeed/enable")=="1")
		{
			echo "starspeed=".$starspeed."\n";
			if(query("/wan/rg/inf:1/pppoe/starspeed/type") != $starspeed)
			{
				$dirty++;
				set("/wan/rg/inf:1/pppoe/starspeed/type", $starspeed);
			}
		}
	}
	else if ($ACTION_POST == "PPTP" || $ACTION_POST == "L2TP")
	{
		echo "mode=".$mode."\n";
		echo "ipaddr=".$ipaddr."\n";
		echo "netmask=".$netmask."\n";
		echo "gateway=".$gateway."\n";
		echo "dns=".$dns."\n";
		echo "clonemac=".$clonemac."\n";
		echo "server=".$server."\n";
		echo "username=".$username."\n";
		echo "pptp_mppe=".$pptp_mppe."\n";
		echo "password=".$password."\n";
		echo "idletime=".$idletime."\n";
		echo "mtu=".$mtu."\n";
		echo "ppp_auto=".$ppp_auto."\n";
		echo "ppp_ondemand=".$ppp_ondemand."\n";
		echo "pptp_phy=".$pptp_phy."\n";

		if ($pptp_mppe != "1") {$pptp_mppe = "0";}

		if ($ACTION_POST == "PPTP")
		{
			if (query("/wan/rg/inf:1/mode")!="4")	{$dirty++; set("/wan/rg/inf:1/mode", "4");}
			$entry="/wan/rg/inf:1/pptp/";
			if (query($entry."physical")!=$pptp_phy)		{$dirty++; set($entry."physical", $pptp_phy);}
		}
		else
		{
			if (query("/wan/rg/inf:1/mode")!="5")	{$dirty++; set("/wan/rg/inf:1/mode", "5");}
			$entry="/wan/rg/inf:1/l2tp/";
		}
		if (query($entry."mode")!=$mode)			{$dirty++; set($entry."mode", $mode);}
		if ($mode == "1")
		{
			if (query($entry."ip")!=$ipaddr)		{$dirty++; set($entry."ip", $ipaddr);}
			if (query($entry."netmask")!=$netmask)	{$dirty++; set($entry."netmask", $netmask);}
			if (query($entry."gateway")!=$gateway)	{$dirty++; set($entry."gateway", $gateway);}
			if (query($entry."dns")!=$dns)			{$dirty++; set($entry."dns", $dns);}
		}
		if (query($entry."serverip")!=$server)		{$dirty++; set($entry."serverip", $server);}
		if (query($entry."user")!=$username)		{$dirty++; set($entry."user", $username);}
		if (query($entry."mppe/enable") !=$pptp_mppe) {$dirty++; set($entry."mppe/enable", $pptp_mppe);}
		if ($password != $G_DEF_PASSWORD)			{$dirty++; set($entry."password", $password);}
		if($ppp_ondemand=="1")
		{
			$idletime = $idletime * 60;
			if (query($entry."idletimeout")!=$idletime)	{$dirty++; set($entry."idletimeout", $idletime);}
		}
		if (query($entry."mtu")!=$mtu)				{$dirty++; set($entry."mtu", $mtu);}
		if (query($entry."autoreconnect")!=$ppp_auto){$dirty++;set($entry."autoreconnect", $ppp_auto);}
		if (query($entry."ondemand")!=$ppp_ondemand){$dirty++; set($entry."ondemand", $ppp_ondemand);}
		if (query($entry."schedule/id") != $wan_sch){$dirty++; set($entry."schedule/id", $wan_sch);}
		if (query($entry."clonemac") != $clonemac)		{$dirty++; set($entry."clonemac", $clonemac);}
		
		$entry="/w8021x/";
        if (query($entry."pptptype")!=$pptp_eap_type){$dirty++; set($entry."pptptype", $pptp_eap_type);}
        if (query($entry."pptpauth")!=$pptp_authentication){$dirty++; set($entry."pptpauth", $pptp_authentication);}
        if (query($entry."pptpenable")!=$enable_pptp8021x){$dirty++; set($entry."pptpenable", $enable_pptp8021x);}
    if (query($entry."pptpuser")!=$pptp_username){$dirty++; set($entry."pptpuser", $pptp_username);}
    if (query($entry."pptppassword")!=$pptp_password){$dirty++; set($entry."pptppassword", $pptp_password);}

	}else if ($ACTION_POST == "DHCPPLUS")/*It is used for HE_NAN china dhcpplus */
	{
		echo "hostname=".$hostname."\n";
		echo "clonemac=".$clonemac."\n";
		
		$entry="/wan/rg/inf:1/dhcpplus/";
		
		
		if (query("/wan/rg/inf:1/mode") != "8")	{$dirty++; set("/wan/rg/inf:1/mode", "8");}
		if (query("/sys/hostname")!= $hostname)	{$dirty++; set("/sys/hostname", $hostname);}
		if (query($entry."clonemac")!= $clonemac)	{$dirty++; set($entry."clonemac", $clonemac);}
		if (query($entry."mtu")		!= $mtu)		{$dirty++; set($entry."mtu", $mtu);}
		if (query($entry."dhcpplususer")!=$dhcpp_username){$dirty++; set($entry."dhcpplususer", $dhcpp_username);}
    if (query($entry."dhcppluspassword")!=$dhcpp_passwd){$dirty++; set($entry."dhcppluspassword", $dhcpp_passwd);}
    
	}

	$NEXT_PAGE=$MY_NAME;
	if ($rb_dirty > 0)		{$SUBMIT_STR=";submit SYSTEM";$XGISET_STR="set/runtime/stats/resetCounter=1"; $NEXT_PAGE="bsc_chg_rg_mode";}
	else if ($dirty > 0)	{$SUBMIT_STR=";submit WAN";}
	echo "SUBMIT_STR=".$SUBMIT_STR."\n";
	echo "-->\n";

	if($SUBMIT_STR!="")	{require($G_SAVING_URL);}
	else				{require($G_NO_CHANGED_URL);}
}

/* --------------------------------------------------------------------------- */
$NO_NEED_AUTH="0";
require("/www/model/__html_head.php");
require("/www/comm/__js_ip.php");
/* --------------------------------------------------------------------------- */
// get the variable value from rgdb.
$cfg_lan_ip		= query("/lan/ethernet/ip");
$cfg_lan_mask	= query("/lan/ethernet/netmask");
anchor("/dnsrelay/server");
$cfg_dns1	= query("primarydns");
$cfg_dns2	= query("secondarydns");

anchor("/wan/rg/inf:1");
$cfg_mode = query("mode");
if($cfg_mode=="3")	/* pppoe */
{
	anchor("/wan/rg/inf:2");
	$wan2_mode=query("mode");
	/* russia pppoe */
	if ($wan2_mode=="1")		/* static IP */
	{
		anchor("/wan/rg/inf:2/static");
		$r_mode			="r_pppoe";
		$cfg_phy_mode	="1";
		$cfg_phy_ip		=query("ip");
		$cfg_phy_mask	=query("netmask");
		$cfg_phy_gw		=query("gateway");
		$cfg_phy_dns1	=query("primarydns");
		$cfg_phy_dns2	=query("secondarydns");
	}
	else if ($wan2_mode=="2")	/* dynamic IP*/
	{
		$r_mode			="r_pppoe";
		$cfg_phy_mode	="2";
	}
}
else if ($cfg_mode=="4")	/* pptp */
{
	/* russia pptp */
	if (query("/wan/rg/inf:1/pptp/physical")=="1")	
	{$r_mode="r_pptp";}
}

anchor("/wan/rg/inf:1/static");
$cfg_static_ip		= query("ip");
$cfg_static_mask	= query("netmask");
$cfg_static_gw		= query("gateway");
$cfg_static_mac		= query("clonemac");
$cfg_static_mtu		= query("mtu");


/*-----------------w8021x-------------------*/
anchor("/w8021x");
$cfg_stenable	= query("stenable");
$cfg_dhenable	= query("dhenable");
$cfg_pppoeenable = query("pppoeenable");
$cfg_pptpenable = query("pptpenable");
$cfg_sttype = query("sttype");
$cfg_stauth = query("stauth");
$cfg_dhtype = query("dhtype");
$cfg_dhauth = query("dhauth");
$cfg_pppoetype = query("pppoetype");
$cfg_pppoeauth = query("pppoeauth");
$cfg_pptptype = query("pptptype");
$cfg_pptpauth = query("pptpauth");
$cfg_static_usr		= get("h","stuser");
$cfg_dh_usr	= get("h","dhuser");
$cfg_pppoe_usr		= get("h","pppoeuser");
$cfg_pptp_usr	= get("h","pptpuser");
$cfg_st_password = query("stpassword");
$cfg_dh_password = query("dhpassword");
$cfg_pppoe_password = query("pppoepassword");
$cfg_pptp_password = query("pptppassword");

/*-----------------------------------------*/


anchor("/wan/rg/inf:1/dhcp");
$cfg_dhcp_hostname	= get("h","/sys/hostname");
$cfg_dhcp_mac		= query("clonemac");
$cfg_dhcp_mtu		= query("mtu");

anchor("/wan/rg/inf:1/pppoe");
$cfg_poe_mode		= query("mode");
$cfg_poe_staticip	= query("staticip");
$cfg_poe_user		= get("h","user");
$cfg_poe_service	= get("h","acservice");
$cfg_poe_auto_conn	= query("autoreconnect");
$cfg_poe_on_demand	= query("ondemand");
$cfg_poe_sch		= query("schedule/id");
$cfg_poe_idle_time	= query("idletimeout");
$cfg_poe_mtu		= query("mtu");
$cfg_poe_mac		= query("clonemac");
$cfg_poe_idle_time	= $cfg_poe_idle_time/60;
$cfg_poe_auto_dns	= query("autodns");

if(query("mppe/enable") == "1")
{ $cfg_poe_mppe = "checked"; }
else
{ $cfg_poe_mppe = ""; }
/* netsniper */
if($LANGCODE == "zhcn")
{
	if(query("/runtime/func/netsniper")=="1")
	{
		if(query("/wan/rg/inf:1/netsniper_enable")==1){ $cfg_netsniper = "checked"; }
		else { $cfg_netsniper = ""; }
	}	
}

anchor("/wan/rg/inf:1/pptp");
$cfg_pptp_mode		= query("mode");
$cfg_pptp_ip		= query("ip");
$cfg_pptp_mask		= query("netmask");
$cfg_pptp_gw		= query("gateway");
$cfg_pptp_dns		= query("dns");
$cfg_pptp_mac		= query("clonemac");
$cfg_pptp_server	= query("serverip");
$cfg_pptp_user		= get("h","user");
$cfg_pptp_auto_conn	= query("autoreconnect");
$cfg_pptp_on_demand	= query("ondemand");
$cfg_pptp_sch		= query("schedule/id");
$cfg_pptp_idle_time	= query("idletimeout");
$cfg_pptp_mtu		= query("mtu");
$cfg_pptp_idle_time	= $cfg_pptp_idle_time/60;
if(query("mppe/enable") == "1")
{ $cfg_pptp_mppe = "checked"; }
else
{ $cfg_pptp_mppe = ""; }

anchor("/wan/rg/inf:1/l2tp");
$cfg_l2tp_mode		= query("mode");
$cfg_l2tp_ip		= query("ip");
$cfg_l2tp_mask		= query("netmask");
$cfg_l2tp_gw		= query("gateway");
$cfg_l2tp_dns		= query("dns");
$cfg_l2tp_mac		= query("clonemac");
$cfg_l2tp_server	= query("serverip");
$cfg_l2tp_user		= get("h","user");
$cfg_l2tp_auto_conn	= query("autoreconnect");
$cfg_l2tp_on_demand	= query("ondemand");
$cfg_l2tp_sch		= query("schedule/id");
$cfg_l2tp_idle_time	= query("idletimeout");
$cfg_l2tp_mtu		= query("mtu");
$cfg_l2tp_idle_time	= $cfg_l2tp_idle_time/60;

if($LANGCODE == "zhcn")
{
	if(query("/runtime/func/dhcpplus")=="1")
	{
		anchor("/wan/rg/inf:1/dhcpplus/");
		//$cfg_dhcp_hostname	= get("h","/sys/hostname");
		$cfg_dhcpplus_mac		= query("clonemac");
		$cfg_dhcpplus_mtu		= query("mtu");
		$cfg_dhcpplus_username=query("dhcpplususer");
		$cfg_dhcpplus_password=query("dhcppluspassword");
	}	
}
if($LANGCODE == "zhcn")
{
	if(query("/wan/rg/inf:1/pppoe/starspeed/enable")=="1")
	{
		$cfg_starspeed = query("/wan/rg/inf:1/pppoe/starspeed/type");
	}
}

/* --------------------------------------------------------------------------- */
?>
<script>

/*	802.1x -----------------------------------------------------------*/
function st_w8021x_eap_sel()
{
	var type_selected = get_obj("st_eap_type");
	var type1 = type_selected.options[ type_selected.selectedIndex ].value;
	var auth_selected = get_obj("st_authentication");
	
	//get_obj("final_form").f_eap_type.value = type1;	
	get_obj("st_authentication").disabled = true;
	get_obj("st_password").disabled = false;
	get_obj("st_password_v").disabled = false;

	
	switch( type1 )
	{
		
		case "1": /* MD5 */

			auth_selected.selectedIndex = "0";
			//get_obj("final_form").f_auth.value = auth_selected.selectedIndex;
			break;
		case "2": /* PEAP */
			auth_selected.selectedIndex = "4";
			//get_obj("final_form").f_auth.value = auth_selected.selectedIndex;
			break;
		case "3": /* TTLS */
			get_obj("st_authentication").disabled = false;
			break;

		default: /* if database value = "" */
			type_selected.selectedIndex = "0";
			auth_selected.selectedIndex = "0";
			break;
	}
		
}

function dh_w8021x_eap_sel()
{
	//alert("dhsel!!");
	var type_selected = get_obj("dh_eap_type");
	var type1 = type_selected.options[ type_selected.selectedIndex ].value;
	var auth_selected = get_obj("dh_authentication");
	
	//get_obj("final_form").f_eap_type.value = type1;
	get_obj("dh_authentication").disabled = true;
	get_obj("dh_password").disabled = false;
	get_obj("dh_password_v").disabled = false;

	
	switch( type1 )
	{
		
		case "1": /* MD5 */

			auth_selected.selectedIndex = "0";
			//get_obj("final_form").f_auth.value = auth_selected.selectedIndex;
			break;
		case "2": /* PEAP */
			auth_selected.selectedIndex = "4";
			//get_obj("final_form").f_auth.value = auth_selected.selectedIndex;
			break;
		case "3": /* TTLS */
			get_obj("dh_authentication").disabled = false;
			break;

		default: /* if database value = "" */
			type_selected.selectedIndex = "0";
			auth_selected.selectedIndex = "0";
			break;
	}
		
}

function pppoe_w8021x_eap_sel()
{
	var type_selected = get_obj("pppoe_eap_type");
	var type1 = type_selected.options[ type_selected.selectedIndex ].value;
	var auth_selected = get_obj("pppoe_authentication");
	
	//get_obj("final_form").f_eap_type.value = type1;	
	get_obj("pppoe_authentication").disabled = true;
	get_obj("pppoe_password").disabled = false;
	get_obj("pppoe_password_v").disabled = false;

	
	switch( type1 )
	{
		
		case "1": /* MD5 */

			auth_selected.selectedIndex = "0";
			//get_obj("final_form").f_auth.value = auth_selected.selectedIndex;
			break;
		case "2": /* PEAP */
			auth_selected.selectedIndex = "4";
			//get_obj("final_form").f_auth.value = auth_selected.selectedIndex;
			break;
		case "3": /* TTLS */
			get_obj("pppoe_authentication").disabled = false;
			break;

		default: /* if database value = "" */
			type_selected.selectedIndex = "0";
			auth_selected.selectedIndex = "0";
			break;
	}
		
}

function pptp_w8021x_eap_sel()
{
	var type_selected = get_obj("pptp_eap_type");
	var type1 = type_selected.options[ type_selected.selectedIndex ].value;
	var auth_selected = get_obj("pptp_authentication");
	
	//get_obj("final_form").f_eap_type.value = type1;	
	get_obj("pptp_authentication").disabled = true;
	get_obj("pptp_password").disabled = false;
	get_obj("pptp_password_v").disabled = false;

	
	switch( type1 )
	{
		
		case "1": /* MD5 */

			auth_selected.selectedIndex = "0";
			//get_obj("final_form").f_auth.value = auth_selected.selectedIndex;
			break;
		case "2": /* PEAP */
			auth_selected.selectedIndex = "4";
			//get_obj("final_form").f_auth.value = auth_selected.selectedIndex;
			break;
		case "3": /* TTLS */
			get_obj("pptp_authentication").disabled = false;
			break;

		default: /* if database value = "" */
			type_selected.selectedIndex = "0";
			auth_selected.selectedIndex = "0";
			break;
	}
		
}


function on_change_w8021x()
{
	f1 = get_obj("enable_st8021x");
	f2 = get_obj("enable_dh8021x");
	f3 = get_obj("enable_pppoe8021x");
	f4 = get_obj("enable_pptp8021x");
	get_obj("st_show_8021x").style.display = "none";
	get_obj("dh_show_8021x").style.display = "none";
	get_obj("pppoe_show_8021x").style.display = "none";
	get_obj("pptp_show_8021x").style.display = "none";
	
	
	if(f1!=null && f1.checked == true)
	{
		var type_selected = get_obj("st_eap_type");
		var type1 = "<?=$cfg_sttype?>";
		var auth_selected = get_obj("st_authentication");
		get_obj("st_show_8021x").style.display = "";
		type_selected.selectedIndex = parseInt(type1)-1;		
		auth_selected.selectedIndex = "<?=$cfg_stauth?>";
		st_w8021x_eap_sel();
	}
	
	if(f2!=null && f2.checked == true)
	{

		var type_selected = get_obj("dh_eap_type");
		var type1 = "<?=$cfg_dhtype?>";
		var auth_selected = get_obj("dh_authentication");
		get_obj("dh_show_8021x").style.display = "";
		type_selected.selectedIndex = parseInt(type1)-1;		
		auth_selected.selectedIndex = "<?=$cfg_dhauth?>";
		dh_w8021x_eap_sel();
	}
	
	if(f3!=null && f3.checked == true && get_obj("wan_type").value == "r_pppoe")
	{

		var type_selected = get_obj("pppoe_eap_type");
		var type1 = "<?=$cfg_pppoetype?>";
		var auth_selected = get_obj("pppoe_authentication");
		get_obj("pppoe_show_8021x").style.display = "";
		type_selected.selectedIndex = parseInt(type1)-1;		
		auth_selected.selectedIndex = "<?=$cfg_pppoeauth?>";
		pppoe_w8021x_eap_sel();
	}
	
	if(f4!=null && f4.checked == true && get_obj("wan_type").value == "r_pptp")
	{

		var type_selected = get_obj("pptp_eap_type");
		var type1 = "<?=$cfg_pptptype?>";
		var auth_selected = get_obj("pptp_authentication");
		get_obj("pptp_show_8021x").style.display = "";
		type_selected.selectedIndex = parseInt(type1)-1;		
		auth_selected.selectedIndex = "<?=$cfg_pptpauth?>";
		pptp_w8021x_eap_sel();
	}
}

function init_st8021x()
{
	if("<?=$cfg_stenable?>")
	{
		get_obj("enable_st8021x").checked = true;
	
		type2=get_obj("st_eap_type").selectedIndex ;
	
		if( type2 == "" )
		{
			type2 = "1";
		}	
		else
		{
			type2 = "<?=$cfg_sttype?>";
		}
		type3=get_obj("st_authentication").selectedIndex;
	
		if( type3 == "" )
		{
			type3 = "1";
		}
				else
		{
			type3 = "<?=$cfg_stauth?>";
		}
	
		get_obj("st_username").value = "<?=$cfg_static_usr?>";
		get_obj("st_password").value = "<?=$cfg_st_password?>";
		get_obj("st_password_v").value = "<?=$cfg_st_password?>";
	
		//st_w8021x_eap_sel();
	}
	else
	{
		if(get_obj("enable_st8021x")!=null)
		{
			get_obj("enable_st8021x").checked = false;
		}
		get_obj("st_username").value = "<?=$cfg_static_usr?>";
		get_obj("st_password").value = "<?=$cfg_st_password?>";
		get_obj("st_password_v").value = "<?=$cfg_st_password?>";
	}
	//st_w8021x_eap_sel();
	on_change_w8021x();
	
}

function init_dh8021x()
{
	if("<?=$cfg_dhenable?>")
	{
		get_obj("enable_dh8021x").checked = true;
		
		
		type2 = get_obj("dh_eap_type").selectedIndex;
		
		if( type2 == "" )
		{
			type2 = "1";
		}
		else
		{
			
			type2 = "<?=$cfg_dhtype?>";
		}
			
		type3=get_obj("dh_authentication").selectedIndex;
	
		if( type3 == "" )
		{
			type3 = "1";
		}
		else
		{
			type3 = "<?=$cfg_dhauth?>";
		}
	
		get_obj("dh_username").value = "<?=$cfg_dh_usr?>";
		get_obj("dh_password").value = "<?=$cfg_dh_password?>";
		get_obj("dh_password_v").value = "<?=$cfg_dh_password?>";
	
		//dh_w8021x_eap_sel();
		
	}
	else
	{
		if(get_obj("enable_dh8021x")!=null)
		{
			get_obj("enable_dh8021x").checked = false;
		}
		get_obj("dh_username").value = "<?=$cfg_dh_usr?>";
		get_obj("dh_password").value = "<?=$cfg_dh_password?>";
		get_obj("dh_password_v").value = "<?=$cfg_dh_password?>";
		
	}
	//dh_w8021x_eap_sel();
	on_change_w8021x();
	
}

function init_pppoe8021x()
{
	if("<?=$cfg_pppoeenable?>")
	{
		get_obj("enable_pppoe8021x").checked = true;
		
		
		type2 = get_obj("pppoe_eap_type").selectedIndex;
		
		if( type2 == "" )
		{
			type2 = "1";
		}
		else
		{
			
			type2 = "<?=$cfg_pppoetype?>";
		}
			
		type3=get_obj("pppoe_authentication").selectedIndex;
	
		if( type3 == "" )
		{
			type3 = "1";
		}
		else
		{
			type3 = "<?=$cfg_pppoeauth?>";
		}
	
		get_obj("pppoe_username").value = "<?=$cfg_pppoe_usr?>";
		get_obj("pppoe_password").value = "<?=$cfg_pppoe_password?>";
		get_obj("pppoe_password_v").value = "<?=$cfg_pppoe_password?>";
	
		//dh_w8021x_eap_sel();
		
	}
	else
	{
		if(get_obj("enable_pppoe8021x")!=null)
		{
			get_obj("enable_pppoe8021x").checked = false;
		}
		get_obj("pppoe_username").value = "<?=$cfg_pppoe_usr?>";
		get_obj("pppoe_password").value = "<?=$cfg_pppoe_password?>";
		get_obj("pppoe_password_v").value = "<?=$cfg_pppoe_password?>";
		
	}
	
	on_change_w8021x();
	
}

function init_pptp8021x()
{
	if("<?=$cfg_pptpenable?>")
	{
		get_obj("enable_pptp8021x").checked = true;
		
		
		type2 = get_obj("pptp_eap_type").selectedIndex;
		
		if( type2 == "" )
		{
			type2 = "1";
		}
		else
		{
			
			type2 = "<?=$cfg_pptptype?>";
		}
			
		type3=get_obj("pptp_authentication").selectedIndex;
	
		if( type3 == "" )
		{
			type3 = "1";
		}
		else
		{
			type3 = "<?=$cfg_pptpauth?>";
		}
	
		get_obj("pptp_username").value = "<?=$cfg_pptp_usr?>";
		get_obj("pptp_password").value = "<?=$cfg_pptp_password?>";
		get_obj("pptp_password_v").value = "<?=$cfg_pptp_password?>";
	
		//dh_w8021x_eap_sel();
		
	}
	else
	{
		if(get_obj("enable_pptp8021x")!=null)
		{
			get_obj("enable_pptp8021x").checked = false;
		}
		get_obj("pptp_username").value = "<?=$cfg_pptp_usr?>";
		get_obj("pptp_password").value = "<?=$cfg_pptp_password?>";
		get_obj("pptp_password_v").value = "<?=$cfg_pptp_password?>";
		
	}
	//dh_w8021x_eap_sel();
	on_change_w8021x();
	
}

function check_st_w8021x()
{

	if (is_blank(get_obj("st_username").value))
	{
		alert("<?=$a_empty_username?>");
		field_focus(get_obj("st_username"), "**");
		return false;
	}
	
	if (is_blank(get_obj("st_password").value))
	{
		alert("<?=$a_empty_password?>");
		field_focus(get_obj("st_password"), "**");
		return false;
	}
	
	if (get_obj("st_password").value != get_obj("st_password_v").value)
	{
		alert("<?=$a_diff_password?>");
		field_focus(get_obj("st_password"), "**");
		return false;
	}
	
}

function check_dh_w8021x()
{

	if (is_blank(get_obj("dh_username").value))
	{
		alert("<?=$a_empty_username?>");
		field_focus(get_obj("dh_username"), "**");
		return false;
	}
	
	if (is_blank(get_obj("dh_password").value))
	{
		alert("<?=$a_empty_password?>");
		field_focus(get_obj("dh_password"), "**");
		return false;
	}
	
	if (get_obj("dh_password").value != get_obj("dh_password_v").value)
	{
		alert("<?=$a_diff_password?>");
		field_focus(get_obj("dh_password"), "**");
		return false;
	}
}

function check_pppoe_w8021x()
{

	if (is_blank(get_obj("pppoe_username").value))
	{
		alert("<?=$a_empty_username?>");
		field_focus(get_obj("pppoe_username"), "**");
		return false;
	}
	
	if (is_blank(get_obj("pppoe_password").value))
	{
		alert("<?=$a_empty_password?>");
		field_focus(get_obj("pppoe_password"), "**");
		return false;
	}
	
	if (get_obj("pppoe_password").value != get_obj("pppoe_password_v").value)
	{
		alert("<?=$a_diff_password?>");
		field_focus(get_obj("pppoe_password"), "**");
		return false;
	}
}


function check_pptp_w8021x()
{

	if (is_blank(get_obj("pptp_username").value))
	{
		alert("<?=$a_empty_username?>");
		field_focus(get_obj("pptp_username"), "**");
		return false;
	}
	
	if (is_blank(get_obj("pptp_password").value))
	{
		alert("<?=$a_empty_password?>");
		field_focus(get_obj("pptp_password"), "**");
		return false;
	}
	
	if (get_obj("pptp_password").value != get_obj("pptp_password_v").value)
	{
		alert("<?=$a_diff_password?>");
		field_focus(get_obj("pptp_password"), "**");
		return false;
	}
}
/*---------------------------------------------------------------------------*/

function on_change_wan_type()
{
	var frm = get_obj("frm");

	get_obj("show_static").style.display	= "none";
	get_obj("show_dhcp").style.display		= "none";
	get_obj("show_pppoe").style.display		= "none";
	get_obj("show_pptp").style.display		= "none";
	get_obj("show_l2tp").style.display		= "none";
	get_obj("org_pptp").style.display		= "none";
	get_obj("org_pppoe").style.display		= "none";
	get_obj("russia_pptp").style.display	= "none";
	get_obj("russia_pppoe").style.display	= "none";
	get_obj("show_physical").style.display	= "none";
	get_obj("show_pppoe_mppe").style.display= "none";
	get_obj("show_pptp_mppe").style.display = "none";
	get_obj("pppoe_show_8021x_check").style.display = "none";
	get_obj("pppoe_show_8021x").style.display = "none";
	get_obj("pptp_show_8021x_check").style.display = "none";
	get_obj("pptp_show_8021x").style.display = "none";

	<? if($LANGCODE == "zhcn")
	{
		if(query("/runtime/func/dhcpplus")=="1")
		{
			echo "get_obj(\"show_dhcpplus\").style.display = \"none\";\n";
		}
	}
	?>
	
	<? if($LANGCODE == "zhcn")
	{
		if(query("/runtime/func/netsniper")=="1")
		{
			echo "get_obj(\"show_pppoe_netsniper\").style.display = \"\";\n";
		}
	}
	?>

	<? if($LANGCODE == "zhcn")
	{
		if(query("/wan/rg/inf:1/pppoe/starspeed/enable")=="1")
		{
			echo "get_obj(\"pppoe_show_starspeed\").style.display = \"\";\n";
		}
	}
	?>

	switch (frm.wan_type.value)
	{
	case "1":		get_obj("show_static").style.display	= ""; break;
	case "2":		get_obj("show_dhcp").style.display		= ""; break;
	case "3":       get_obj("show_pppoe").style.display		= get_obj("org_pppoe").style.display		= ""; break;
	case "4":		get_obj("show_pptp").style.display		= get_obj("org_pptp").style.display	= ""; break;
	case "5":		get_obj("show_l2tp").style.display		= ""; break;
	case "r_pptp":	get_obj("show_pptp").style.display		= "";
					get_obj("russia_pptp").style.display	= "";
					get_obj("show_pptp_mppe").style.display = "";
				
			<?
			
			if (query("/w8021x/pptpenable") == "on")
			{
				echo "get_obj(\"enable_pptp8021x\").checked = true;\n";				
			}
			
			if(query("/runtime/func/w8021x")=="1")
			{
				
				echo "get_obj(\"pptp_show_8021x_check\").style.display = \"\";\n";
				echo "if(get_obj(\"enable_pptp8021x\").checked == true)";
				echo "get_obj(\"pptp_show_8021x\").style.display = \"\";\n";				
			}
			
			?>
					
		break;
	case "r_pppoe":
		get_obj("show_pppoe").style.display		= get_obj("russia_pppoe").style.display = "";
		get_obj("show_physical").style.display	= "";
		get_obj("show_pppoe_mppe").style.display= "";


			<? 
			
			if (query("/w8021x/pppoeenable") == "on")
			{
				echo "get_obj(\"enable_pppoe8021x\").checked = true;\n";				
			
		  }
			
			if(query("/runtime/func/w8021x")=="1")
			{
				
				echo "get_obj(\"pppoe_show_8021x_check\").style.display = \"\";\n";
				echo "if(get_obj(\"enable_pppoe8021x\").checked == true)";
				echo "get_obj(\"pppoe_show_8021x\").style.display = \"\";\n";				
				
			}
		
			?>

		<? if($LANGCODE == "zhcn")
		{
			if(query("/wan/rg/inf:1/pppoe/starspeed/enable")=="1")
			{
				echo "get_obj(\"pppoe_show_starspeed\").style.display = \"none\";\n";
			}
		}
		?>

		<? if($LANGCODE == "zhcn")
		{
			if(query("/runtime/func/netsniper")=="1")
			{
				echo "get_obj(\"show_pppoe_netsniper\").style.display = \"none\";\n";
			}
		}
		?>
		break;

	<? if($LANGCODE == "zhcn")
	{
		if(query("/runtime/func/dhcpplus")=="1")
		{
			echo "case \"8\":	get_obj(\"show_dhcpplus\").style.display = \"\"; break;";
		}
	}
	?>
		
	}
}

function set_clone_mac(form)
{
	var f = get_obj(form);
	var addr = get_mac("<?=$macaddr?>");

	f.mac1.value = addr[1];
	f.mac2.value = addr[2];
	f.mac3.value = addr[3];
	f.mac4.value = addr[4];
	f.mac5.value = addr[5];
	f.mac6.value = addr[6];
}

function check_mac(m1,m2,m3,m4,m5,m6, result)
{
	result.value = "";

	if (is_blank(m1.value) && is_blank(m2.value) && is_blank(m3.value) &&
		is_blank(m4.value) && is_blank(m5.value) && is_blank(m6.value))
	{
		return true;
	}

	if (!is_valid_mac(m1.value)) return false;
	if (!is_valid_mac(m2.value)) return false;
	if (!is_valid_mac(m3.value)) return false;
	if (!is_valid_mac(m4.value)) return false;
	if (!is_valid_mac(m5.value)) return false;
	if (!is_valid_mac(m6.value)) return false;

	if (m1.value.length == 1) m1.value = "0"+m1.value;
	if (m2.value.length == 1) m2.value = "0"+m2.value;
	if (m3.value.length == 1) m3.value = "0"+m3.value;
	if (m4.value.length == 1) m4.value = "0"+m4.value;
	if (m5.value.length == 1) m5.value = "0"+m5.value;
	if (m6.value.length == 1) m6.value = "0"+m6.value;

	result.value = m1.value+":"+m2.value+":"+m3.value+":"+m4.value+":"+m5.value+":"+m6.value;
	return true;
}

function on_click_ppp_ipmode(form)
{
	var f = get_obj(form);

	if (form == "frm_pppoe")
	{
		/*
		f.ipaddr.disabled = f.dns1.disabled = f.dns2.disabled =
		f.ipmode[0].checked ? true : false;
		*/
		f.ipaddr.disabled = f.ipmode[0].checked ? true : false;
		on_click_dns_mode(form);
	}
	else if (form == "frm_pptp" || form == "frm_l2tp")
	{
		f.ipaddr.disabled = f.netmask.disabled = f.gateway.disabled =
		f.dns.disabled = f.ipmode[0].checked ? true : false;
	}
}
function on_click_dns_mode(form)
{
	var f = get_obj(form);

	if(form == "frm_pppoe")
	{
		f.dns1.disabled = f.dns2.disabled = f.dnsmode[0].checked? true : false;
	}
}

/*  static ip -----------------------------------------------------------*/
function init_static()
{
	var f = get_obj("frm_static");
	var addr = get_mac("<?=$cfg_static_mac?>");
	f.mac1.value = addr[1];
	f.mac2.value = addr[2];
	f.mac3.value = addr[3];
	f.mac4.value = addr[4];
	f.mac5.value = addr[5];
	f.mac6.value = addr[6];
	///////////////////
	init_st8021x();
	return true;
	///////////////////
	
	
}

function check_static()
{
	var f = get_obj("frm_static");
	var net1, net2;

	if (!is_valid_ip(f.ipaddr.value, 0))
	{
		alert("<?=$a_invalid_ip?>");
		field_focus(f.ipaddr, "**");
		return false;
	}
	if (!is_valid_mask(f.netmask.value))
	{
		alert("<?=$a_invalid_netmask?>");
		field_focus(f.netmask, "**");
		return false;
	}
	if (!is_valid_ip2(f.ipaddr.value, f.netmask.value))
	{
		alert("<?=$a_invalid_ip?>");
		field_focus(f.ipaddr, "**");
		return false;
	}
	//check if it is at the same subnet with LAN
	var lannet, wannet;
	lannet = get_network_id("<?=$cfg_lan_ip?>", "<?=$cfg_lan_mask?>");
	wannet = get_network_id(f.ipaddr.value, f.netmask.value);
	if (lannet[0] == wannet[0])
	{
		alert("<?=$a_invalid_ip?>");
		field_focus(f.ipaddr, "**");
		return false;
	}

	if (!is_valid_gateway(f.ipaddr.value, f.netmask.value, f.gateway.value, 0))
	{
		alert("<?=$a_invalid_ip?>");
		field_focus(f.gateway, "**");
		return false;
	}

	//  We need to compare the gateway addr with ipaddr to ensure the two are different.	
	if(f.ipaddr.value == f.gateway.value)
	{
		alert("<?=$a_ip_equal_gateway?>");
		field_focus(f.ipaddr, "**");
		return false;		
	}

	net1 = get_network_id(f.ipaddr.value, f.netmask.value);
	net2 = get_network_id(f.gateway.value, f.netmask.value);
	if (net1[0] != net2[0])
	{
		alert("<?=$a_gw_in_different_subnet?>");
		field_focus(f.gateway, "**");
		return false;
	}
	if (check_mac(f.mac1, f.mac2, f.mac3, f.mac4, f.mac5, f.mac6, f.clonemac)==false)
	{
		alert("<?=$a_invalid_mac?>");
		field_focus(f.mac1, "**");
		return false;
	}
	if (!is_valid_ip(f.dns1.value, 0))
	{
		alert("<?=$a_invalid_ip?>");
		field_focus(f.dns1, "**");
		return false;
	}
	if (!is_valid_ip(f.dns2.value, 1))
	{
		alert("<?=$a_invalid_ip?>");
		field_focus(f.dns2, "**");
		return false;
	}
	if (!is_digit(f.mtu.value) || !is_in_range(f.mtu.value, 200, 1500))
	{
		alert("<?=$a_invalid_mtu?>");
		field_focus(f.mtu, "**");
		return false;
	}
	/////////////////////////////
	
	if(get_obj("enable_st8021x")!=null && get_obj("enable_st8021x").checked)
	{
		if ( check_st_w8021x() == false)	return false;
		//on_change_w8021x();
	}
	/////////////////////////////
	
	f.submit();
	return true;
}



/*  DHCP  -----------------------------------------------------------*/
function init_dhcp()
{
	var f = get_obj("frm_dhcp");
	var addr = get_mac("<?=$cfg_dhcp_mac?>");

	f.mac1.value = addr[1];
	f.mac2.value = addr[2];
	f.mac3.value = addr[3];
	f.mac4.value = addr[4];
	f.mac5.value = addr[5];
	f.mac6.value = addr[6];
	//////////////////
	init_dh8021x();
	return true;
	///////////////////
	
}

function check_dhcp()
{
	var f = get_obj("frm_dhcp");

	if (is_blank(f.hostname.value) || !strchk_hostname(f.hostname.value))
	{
		alert("<?=$a_invalid_hostname?>");
		field_focus(f.hostname, "**");
		return false;
	}
	if (check_mac(f.mac1, f.mac2, f.mac3, f.mac4, f.mac5, f.mac6, f.clonemac)==false)
	{
		alert("<?=$a_invalid_mac?>");
		field_focus(f.mac1, "**");
		return false;
	}
	if (!is_valid_ip(f.dns1.value, 1))
	{
		alert("<?=$a_invalid_ip?>");
		field_focus(f.dns1, "**");
		return false;
	}
	if (!is_valid_ip(f.dns2.value, 1))
	{
		alert("<?=$a_invalid_ip?>");
		field_focus(f.dns2, "**");
		return false;
	}
	if (!is_digit(f.mtu.value) || !is_in_range(f.mtu.value, 200, 1500))
	{
		alert("<?=$a_invalid_mtu?>");
		field_focus(f.mtu, "**");
		return false;
	}
	
	/*---------------------------*/
	if(get_obj("enable_dh8021x")!=null && get_obj("enable_dh8021x").checked)
	{
		if ( check_dh_w8021x() == false)	return false;
	}
	
	/*------------------------------*/
	f.submit();
	return true;
}


/*  PPPOE  -----------------------------------------------------------*/
function init_pppoe()
{
	var f = get_obj("frm_pppoe");
	var addr = get_mac("<?=$cfg_poe_mac?>");

	f.mac1.value = addr[1];
	f.mac2.value = addr[2];
	f.mac3.value = addr[3];
	f.mac4.value = addr[4];
	f.mac5.value = addr[5];
	f.mac6.value = addr[6];
	//tommy
	//alert("init pppoe!");
<?
	echo "\/\/ auto=".$cfg_poe_auto_conn.", ondemand=".$cfg_poe_on_demand."\n";
	if ($cfg_poe_mode != "1")	{ echo "	f.ipmode[0].checked = true;\n";}
	else						{ echo "	f.ipmode[1].checked = true;\n";}
	if ($cfg_poe_auto_conn == "1")
	{	if ($cfg_poe_on_demand == "1")	{echo "	f.ppp_conn_mode[2].checked=true;\n";}
		else							{echo "	f.ppp_conn_mode[0].checked=true;\n";}
	}	
	else							
	{
		if ($cfg_poe_sch != "")		{echo " f.ppp_conn_mode[0].checked=true;\n";}
		else						{echo " f.ppp_conn_mode[1].checked=true;\n";}
	}
	if ($cfg_poe_auto_dns == "1") { echo "	f.dnsmode[0].checked = true;\n"; }
	else						  {	echo "	f.dnsmode[1].checked = true;\n"; }
?>
	<? if($LANGCODE == "zhcn")
	{
		if(query("/wan/rg/inf:1/pppoe/starspeed/enable")=="1")
		{
			echo "select_index(f.starspeed, \"".$cfg_starspeed."\");\n";
		}
	}
	?>

	on_click_ppp_ipmode("frm_pppoe");
	chg_ppp_conn_mode(f);
	if("<?=$cfg_phy_mode?>"=="1")	f.phy_mode[1].checked=true;
	else							f.phy_mode[0].checked=true;
	chg_phy_mode();
	//////////////////
	init_pppoe8021x();
	return true;
	///////////////////
}

function check_pppoe()
{
	var f = get_obj("frm_pppoe");
	var net1, net2;
	f.mode.value = f.ipmode[0].checked ? "2" : "1";
	f.autodns.value = f.dnsmode[0].checked ? "1" : "0";
	if (is_blank(f.username.value))
	{
		alert("<?=$a_invalid_username?>");
		field_focus(f.username, "**");
		return false;
	}
	if (f.password.value != f.password_v.value)
	{
		alert("<?=$a_password_mismatch?>");
		field_focus(f.password, "**");
		return false;
	}
	if (f.mode.value == "1")
	{
		if (!is_valid_ip(f.ipaddr.value, 0))
		{
			alert("<?=$a_invalid_ip?>");
			field_focus(f.ipaddr, "**");
			return false;
		}
		/*
		if (!is_valid_ip(f.dns1.value, 0))
		{
			alert("<?=$a_invalid_ip?>");
			field_focus(f.dns1, "**");
			return false;
		}
		if (!is_valid_ip(f.dns2.value, 1))
		{
			alert("<?=$a_invalid_ip?>");
			field_focus(f.dns2, "**");
			return false;
		}
		*/
	}
	if (f.autodns.value != "1")
	{
		if (!is_valid_ip(f.dns1.value, 0))
		{
			alert("<?=$a_invalid_ip?>");
			field_focus(f.dns1, "**");
			return false;
		}
		if (!is_valid_ip(f.dns2.value, 1))
		{
			alert("<?=$a_invalid_ip?>");
			field_focus(f.dns2, "**");
			return false;
		}
	}
	
	if (check_mac(f.mac1, f.mac2, f.mac3, f.mac4, f.mac5, f.mac6, f.clonemac)==false)
	{
		alert("<?=$a_invalid_mac?>");
		field_focus(f.mac1, "**");
		return false;
	}
	if (!is_digit(f.mtu.value) || !is_in_range(f.mtu.value, 200, 1492))
	{
		alert("<?=$a_invalid_mtu?>");
		field_focus(f.mtu, "**");
		return false;
	}
	if (f.ppp_conn_mode[0].checked)
	{
		if( f.wan_sch.value != 0)
		{
			f.ppp_auto.value = 0;
		}
		else
		{
			f.ppp_auto.value = 1;
		}
		f.ppp_ondemand.value = "0";
	}
	else if (f.ppp_conn_mode[1].checked)
	{
		f.ppp_auto.value = "0";
		f.ppp_ondemand.value = "0";
	}
	else
	{
		if (!is_digit(f.idletime.value))
		{
			alert("<?=$a_invalid_idletime?>");
			field_focus(f.idletime, "**");
			return false;
		}
		f.ppp_auto.value = "1";
		f.ppp_ondemand.value = "1";
	}
	
	/* russia pppoe */
	if (get_obj("wan_type").value=="r_pppoe")
	{
		if (f.phy_mode[1].checked=="1")
		{
			if(!is_valid_ip(f.phy_ip.value, 0))
			{
				alert("<?=$a_invalid_ip?>");
				field_focus(f.phy_ip, "**");
				return false;
			}
			if (!is_valid_mask(f.phy_mask.value, 0))
			{
				alert("<?=$a_invalid_netmask?>");
				field_focus(f.phy_mask, "**");
				return false;
			}
			if (!is_valid_ip2(f.phy_ip.value, f.phy_mask.value))
			{
				alert("<?=$a_invalid_ip?>");
				field_focus(f.phy_ip, "**");
				return false;
			}
			//check if it is at the same subnet with LAN
			var lannet, wannet;
			lannet = get_network_id("<?=$cfg_lan_ip?>", "<?=$cfg_lan_mask?>");
			wannet = get_network_id(f.phy_ip.value, f.phy_mask.value);
			if (lannet[0] == wannet[0])
			{
				alert("<?=$a_invalid_ip?>");
				field_focus(f.phy_ip, "**");
				return false;
			}
			if (!is_valid_gateway(f.phy_ip.value, f.phy_mask.value, f.phy_gw.value, 1))
			{
				alert("<?=$a_invalid_ip?>");
				field_focus(f.phy_gw, "**");
				return false;
			}

			//  We need to compare the gateway addr with ipaddr to ensure the two are different.	
			if(f.phy_gw.value == f.phy_ip.value)
			{
				alert("<?=$a_ip_equal_gateway?>");
				field_focus(f.phy_ip, "**");
				return false;		
			}
						
			net1 = get_network_id(f.phy_ip.value, f.phy_mask.value);
			net2 = get_network_id(f.phy_gw.value, f.phy_mask.value);
			if (f.phy_gw.value != "" && net1[0] != net2[0])
			{
				alert("<?=$a_gw_in_different_subnet?>");
				field_focus(f.phy_gw, "**");
				return false;
			}
			if(!is_valid_ip(f.phy_dns1.value, 1))
			{
				alert("<?=$a_invalid_ip?>");
				field_focus(f.phy_dns1, "**");
				return false;
			}
			if(!is_valid_ip(f.phy_dns2.value, 1))
			{
				alert("<?=$a_invalid_ip?>");
				field_focus(f.phy_dns2, "**");
				return false;
			}
		}
		get_obj("pppoe_phy").disabled = false;
	}
	else
	{
		get_obj("pppoe_phy").disabled=true;
	}
	get_obj("pppoe_mppe").value=get_obj("pppoe_mppe").checked ? 1 : 0;

	/* netsniper */
	<?
	if($LANGCODE == "zhcn")
	{
		if(query("/runtime/func/netsniper")=="1")
		{
			echo "get_obj(\"netsniper_enable\").value=get_obj(\"netsniper_enable\").checked ? 1 : 0;\n";
		}
	}
	?>
	
	/*---------------------------*/
	if(get_obj("enable_pppoe8021x")!=null && get_obj("enable_pppoe8021x").checked)
	{
		if ( check_pppoe_w8021x() == false)	return false;
	}
	
	/*------------------------------*/
	
	f.submit();
	return true;
}

/*  PPTP/L2TP  -----------------------------------------------------------*/

function init_pptp()
{
	var f = get_obj("frm_pptp");
	var addr = get_mac("<?=$cfg_pptp_mac?>");
	f.mac1.value = addr[1];
	f.mac2.value = addr[2];
	f.mac3.value = addr[3];
	f.mac4.value = addr[4];
	f.mac5.value = addr[5];
	f.mac6.value = addr[6];

<?
	if ($cfg_pptp_mode != "1")	{echo "	f.ipmode[0].checked=true;\n";}
	else						{echo "	f.ipmode[1].checked=true;\n";}
	if ($cfg_pptp_auto_conn == "1")
	{	if ($cfg_pptp_on_demand == "1")	{echo "	f.ppp_conn_mode[2].checked=true;\n";}
		else							{echo "	f.ppp_conn_mode[0].checked=true;\n";}
	}	
	else							
	{
		if ($cfg_pptp_sch != "")		{echo " f.ppp_conn_mode[0].checked=true;\n";}
		else							{echo "	f.ppp_conn_mode[1].checked=true;\n";}
	}	
?>
	on_click_ppp_ipmode("frm_pptp");
	chg_ppp_conn_mode(f);
	
	//////////////////
	init_pptp8021x();
	return true;
	///////////////////
}

function init_l2tp()
{
	var f = get_obj("frm_l2tp");
	var addr = get_mac("<?=$cfg_l2tp_mac?>");
	f.mac1.value = addr[1];
	f.mac2.value = addr[2];
	f.mac3.value = addr[3];
	f.mac4.value = addr[4];
	f.mac5.value = addr[5];
	f.mac6.value = addr[6];

<?
	if ($cfg_l2tp_mode != "1")	{echo "	f.ipmode[0].checked=true;\n";}
	else						{echo "	f.ipmode[1].checked=true;\n";}
	if ($cfg_l2tp_auto_conn == "1")
	{	
		if ($cfg_l2tp_on_demand == "1")	{echo "	f.ppp_conn_mode[2].checked=true;\n";}
		else							{echo "	f.ppp_conn_mode[0].checked=true;\n";}
	}	
	else							
	{	
		if ($cfg_l2tp_sch != "")	{echo " f.ppp_conn_mode[0].checked=true;\n";}
		else						{echo "	f.ppp_conn_mode[1].checked=true;\n";}
	}	
?>
	on_click_ppp_ipmode("frm_l2tp");
	chg_ppp_conn_mode(f);
}

function check_pptp_l2tp(form)
{
	var f = get_obj(form);
	var net1, net2;

	f.mode.value = f.ipmode[0].checked ? "2" : "1";
	if (f.mode.value == "1")
	{
		if (check_mac(f.mac1, f.mac2, f.mac3, f.mac4, f.mac5, f.mac6, f.clonemac)==false)
		{
			alert("<?=$a_invalid_mac?>");
			field_focus(f.mac1, "**");
			return false;
		}
		if (!is_valid_ip(f.ipaddr.value, 0))
		{
			alert("<?=$a_invalid_ip?>");
			field_focus(f.ipaddr, "**");
			return false;
		}
		if (!is_valid_mask(f.netmask.value))
		{
			alert("<?=$a_invalid_netmask?>");
			field_focus(f.netmask, "**");
			return false;
		}
		if (!is_valid_ip2(f.ipaddr.value, f.netmask.value))
		{
			alert("<?=$a_invalid_ip?>");
			field_focus(f.ipaddr, "**");
			return false;
		}
		//check if it is at the same subnet with LAN
		var lannet, wannet;
		lannet = get_network_id("<?=$cfg_lan_ip?>", "<?=$cfg_lan_mask?>");
		wannet = get_network_id(f.ipaddr.value, f.netmask.value);
		if (lannet[0] == wannet[0])
		{
			alert("<?=$a_invalid_ip?>");
			field_focus(f.ipaddr, "**");
			return false;
		}
		if (is_blank(f.gateway.value))
		{
			if (!is_valid_ip(f.server.value, 0))
			{
				alert("<?=$a_invalid_ip?>");
				field_focus(f.server, "**");
				return false;
			}
			net1 = get_network_id(f.ipaddr.value, f.netmask.value);
			net2 = get_network_id(f.server.value, f.netmask.value);
			if (net1[0] != net2[0])
			{
				alert("<?=$a_srv_in_different_subnet?>");
				field_focus(f.server, "**");
				return false;
			}
			f.dns.value = "";
		}
		else
		{
			if (!is_valid_ip(f.gateway.value, 0))
			{
				alert("<?=$a_invalid_ip?>");
				field_focus(f.gateway, "**");
				return false;
			}
			if (!is_valid_ip2(f.gateway.value, f.netmask.value))
			{
				alert("<?=$a_invalid_ip?>");
				field_focus(f.gateway, "**");
				return false;
			}
			net1 = get_network_id(f.ipaddr.value, f.netmask.value);
			net2 = get_network_id(f.gateway.value, f.netmask.value);
			if (net1[0] != net2[0])
			{
				alert("<?=$a_gw_in_different_subnet?>");
				field_focus(f.gateway, "**");
				return false;
			}
			if(f.ipaddr.value == f.gateway.value)
			{
				alert("<?=$a_ip_equal_gateway?>");
				field_focus(f.ipaddr, "**");
				return false;		
			}
			if (is_blank(f.dns.value))
			{
				if (!is_valid_ip(f.server.value, 0))
				{
					alert("<?=$a_invalid_ip?>");
					field_focus(f.server, "**");
					return false;
				}
			}
			else
			{
				if (!is_valid_ip(f.dns.value, 0))
				{
					alert("<?=$a_invalid_ip?>");
					field_focus(f.dns, "**");
					return false;
				}
				if (strchk_hostname(f.server.value)==false)
				{
					alert("<?=$a_invalid_hostname?>");
					field_focus(f.server, "**");
					return false;
				}
			}
		}
	}
	else
	{
		if (check_mac(f.mac1, f.mac2, f.mac3, f.mac4, f.mac5, f.mac6, f.clonemac)==false)
		{
			alert("<?=$a_invalid_mac?>");
			field_focus(f.mac1, "**");
			return false;
		}
		if (is_blank(f.server.value))
		{
			alert("<?=$a_server_empty?>");
			field_focus(f.server, "**");
			return false;
		}
		if (strchk_hostname(f.server.value)==false)
		{
			alert("<?=$a_invalid_hostname?>");
			field_focus(f.server, "**");
			return false;
		}
	}
	if (is_blank(f.username.value))
	{
		alert("<?=$a_account_empty?>");
		field_focus(f.username, "**");
		return false;
	}
	if (f.password.value != f.password_v.value)
	{
		alert("<?=$a_password_mismatch?>");
		field_focus(f.password, "**");
		return false;
	}
	if (!is_digit(f.mtu.value) || !is_in_range(f.mtu.value, 200, 1400))
	{
		alert("<?=$a_invalid_mtu?>");
		field_focus(f.mtu, "**");
		return false;
	}
	if (f.ppp_conn_mode[0].checked)
	{
		if( f.wan_sch.value != 0)
		{
			f.ppp_auto.value = 0;
		}
		else
		{
			f.ppp_auto.value = 1;
		}
		f.ppp_ondemand.value = "0";
	}
	else if (f.ppp_conn_mode[1].checked)
	{
		f.ppp_auto.value = "0";
		f.ppp_ondemand.value = "0";
	}
	else
	{
		if (!is_digit(f.idletime.value))
		{
			alert("<?=$a_invalid_idletime?>");
			field_focus(f.idletime, "**");
			return false;
		}
		f.ppp_auto.value = "1";
		f.ppp_ondemand.value = "1";
	}
	if(get_obj("wan_type").value=="r_pptp")
	{
		get_obj("pptp_phy").disabled = false;
	}
	else
	{
		get_obj("pptp_phy").disabled = true;
	}
	get_obj("pptp_mppe").value=get_obj("pptp_mppe").checked ? 1 : 0;
	
	/*---------------------------*/
	if(get_obj("enable_pptp8021x")!=null && get_obj("enable_pptp8021x").checked)
	{
		if ( check_pptp_w8021x() == false)	return false;
	}
	
	/*------------------------------*/

	f.submit();
	return true;
}


function init_dhcpplus()
{
	
	var f = get_obj("frm_dhcpplus");
	var addr = get_mac("<?=$cfg_dhcpplus_mac?>");
	f.mac1.value = addr[1];
	f.mac2.value = addr[2];
	f.mac3.value = addr[3];
	f.mac4.value = addr[4];
	f.mac5.value = addr[5];
	f.mac6.value = addr[6];
	
	return true;
}

function check_dhcpplus()
{
	var f = get_obj("frm_dhcpplus");
	
	
	
	if (is_blank(f.hostname.value) || !strchk_hostname(f.hostname.value))
	{
		
		alert("<?=$a_invalid_hostname?>");
		field_focus(f.hostname, "**");
		return false;
	}
	
	if (is_blank(f.dhcpp_username.value))
	{
		alert("<?=$a_account_empty?>");
		field_focus(f.dhcpp_username, "**");
		return false;
	}
	
	if (f.dhcpp_passwd.value != f.dhcpp_passwd_v.value)
	{
		alert("<?=$a_password_mismatch?>");
		field_focus(f.password, "**");
		return false;
	}
	
	if (check_mac(f.mac1, f.mac2, f.mac3, f.mac4, f.mac5, f.mac6, f.clonemac)==false)
	{
		alert("<?=$a_invalid_mac?>");
		field_focus(f.mac1, "**");
		return false;
	}
	
	if (!is_digit(f.mtu.value) || !is_in_range(f.mtu.value, 200, 1500))
	{
		alert("<?=$a_invalid_mtu?>");
		field_focus(f.mtu, "**");
		return false;
	}
	
	f.submit();
	return true;
}
/*  -----------------------------------------------------------*/

/* page init functoin */
function init()
{
	// init here ...
	f=get_obj("frm");
	f.enable_ap_mode.checked=<?map("/bridge","1","true","*","false");?>;

	if(init_static())	init_st8021x();
	if(init_dhcp())	  init_dh8021x();
	if(init_pppoe())  init_pppoe8021x();
	if(init_pptp())   init_pptp8021x();
	init_l2tp();

	
		<? if($LANGCODE == "zhcn")
	{
		if(query("/runtime/func/dhcpplus")=="1")
		{
			echo "init_dhcpplus();";
		}
	}
	?>
	
	on_change_wan_type();
	chg_ap_mode();
}
/* parameter checking */
function check()
{
	var f=get_obj("frm");
	// do check here ....
	if(f.enable_ap_mode.checked)
	{
		f.enable_ap_mode.value=1;
		f.submit();
		return true;
	}
	else
	{
		f.enable_ap_mode.value=0;
		switch (f.wan_type.value)
		{
		case "1":	return check_static();
		case "2":	return check_dhcp();
		case "r_pppoe":
		case "3":	return check_pppoe();
		case "r_pptp":
		case "4":	return check_pptp_l2tp("frm_pptp");
		case "5":	return check_pptp_l2tp("frm_l2tp");
		
	<? if($LANGCODE == "zhcn")
	{
		if(query("/runtime/func/dhcpplus")=="1")
		{
			echo 	"case \"8\":	return check_dhcpplus();";
		}
	}
	?>
	
		}
	}
	return false;
}
/* cancel function */
function do_cancel()
{
	self.location.href="<?=$MY_NAME?>.php?random_str="+generate_random_str();
}
function chg_phy_mode()
{
	var f=get_obj("frm_pppoe");
	var dis=true;
	var opt = "<?=$m_optional?>";
	if(f.phy_mode[1].checked=="1") dis=false;
	get_obj("phy_ip").disabled = get_obj("phy_mask").disabled = dis;
	get_obj("phy_gw").disabled = get_obj("phy_dns1").disabled = get_obj("phy_dns2").disabled = dis;
}
function chg_ppp_conn_mode(f)
{
	var dis=true;
	if(f.ppp_conn_mode[2].checked) dis=false;
	f.idletime.disabled=dis;
	if(f.ppp_conn_mode[0].checked)	
	{
		f.wan_sch.disabled = f.wan_sch_btn.disabled = false;
	}
	else
	{
		f.wan_sch.disabled = f.wan_sch_btn.disabled = true;
	}
}
function chg_ap_mode()
{
	var f=get_obj("frm");
	var now_form, i, init_str;
	var dis=false;
	if(f.enable_ap_mode.checked)	dis=true;

	f.wan_type.disabled=dis;
	switch (f.wan_type.value)
	{
	case "1":
		now_form=get_obj("frm_static");
		init_str="init_static()";
		break;
	case "2":
		now_form=get_obj("frm_dhcp");
		init_str="init_dhcp()";
		break;
	case "3":
	case "r_pppoe":
		now_form=get_obj("frm_pppoe");
		init_str="init_pppoe()";
		break;
	case "4":
	case "r_pptp":
		now_form=get_obj("frm_pptp");
		init_str="init_pptp()";
		break;
	case "5":
		now_form=get_obj("frm_l2tp");
		init_str="init_l2tp()";
		break;

	<? if($LANGCODE == "zhcn")
	{
		if(query("/runtime/func/dhcpplus")=="1")
		{
				echo 	"case \"8\": now_form=get_obj(\"frm_dhcpplus\");  init_str=\"init_dhcpplus()\";  break;";		
		}
	}
	?>
		
	}
	fields_disabled(now_form, dis);
	eval(init_str);
}
</script>
<body onload="init();" <?=$G_BODY_ATTR?>>
<!--<form name="final_form" id="final_form" method="post" action="<?=$MY_NAME?>.php">
	<input type="hidden" name="f_eap_type"		value="">
	<input type="hidden" name="f_auth"				value="">
</form>-->
<?require("/www/model/__banner.php");?>
<?require("/www/model/__menu_top.php");?>
<table <?=$G_MAIN_TABLE_ATTR?> height="100%">
<tr valign=top>
	<td <?=$G_MENU_TABLE_ATTR?>>
	<?require("/www/model/__menu_left.php");?>
	</td>
	<td id="maincontent">
		<div id="box_header">
		<? require($LOCALE_PATH."/dsc/dsc_".$MY_NAME.".php"); ?>
		<script>apply('check()'); echo("&nbsp;"); cancel('');</script>
		</div>
<!-- ________________________________ Main Content Start ______________________________ -->
		<form name="frm" id="frm" method="post" action="<?=$MY_NAME?>.php" onsubmit="return check();">
		<div class="box">
			<h2><?=$m_title_ap_mode?></h2>
			<p><?=$m_desc_ap_mode?></p>
			<input type="checkbox" name="enable_ap_mode" onclick="chg_ap_mode();"><?=$m_enable_ap_mode?>
			<input type="hidden" name="ACTION_POST" value="AP_MODE">
		</div>
		<div class="box">
			<h2><?=$m_title_wan_type?></h2>
			<p><?=$m_desc_wan_type?></p>
			<table cellSpacing=1 cellPadding=1 width=525 border=0>
			<tbody>
			<tr>
				<td class="r_tb" width=150><?=$m_wan_type?> :</td>
				<td class="l_tb">&nbsp;
					<select name="wan_type" id="wan_type" onChange="on_change_wan_type()">
						<option value="1"<? if ($cfg_mode==1) {echo " selected";} ?>><?=$m_static_ip?></option>
						<option value="2"<? if ($cfg_mode==2) {echo " selected";} ?>><?=$m_dhcp?></option>
						<option value="3"<? if ($cfg_mode==3) {echo " selected";} ?>><?=$m_pppoe?></option>
						<option value="4"<? if ($cfg_mode==4) {echo " selected";} ?>><?=$m_pptp?></option>
						<option value="5"<? if ($cfg_mode==5) {echo " selected";} ?>><?=$m_l2tp?></option>
						<option value="r_pptp"<? if($r_mode=="r_pptp")  {echo " selected";}?>><?=$m_russia_pptp?></option>
						<option value="r_pppoe"<? if($r_mode=="r_pppoe") {echo " selected";}?>><?=$m_russia_pppoe?></option>

	<? if($LANGCODE == "zhcn")
	{
		if(query("/runtime/func/dhcpplus")=="1")
		{
			echo "<option value=\"8\"";
			if ($cfg_mode==8) {echo " selected";}
			echo ">";
			echo $m_dhcpplus;
			echo"</option>";
		}
	}
	?>
						
					</select>
				</td>
			</tr>
			</tbody>
			</table>
		</div>
		</form>

	<!-- STATIC IP  -->
		<div class="box" id="show_static" style="display:none">
		<h2><?=$m_title_static?></h2>
		<form name="frm_static" id="frm_static" method="post" action="<?=$MY_NAME?>.php" onsubmit="return check();">
		<input type="hidden" name="ACTION_POST" value="STATIC">
		<p><?=$m_desc_static?></p>
		<table cellSpacing=1 cellPadding=1 width=525 border=0>
		<tr>
			<td class="r_tb" width=150><?=$m_ipaddr?> :</td>
			<td class="t_tb">&nbsp;
				<input type=text id=ipaddr name=ipaddr size=16 maxlength=15 value="<?=$cfg_static_ip?>">
				<?=$m_comment_isp?>
			</td>
		</tr>
		<tr>
			<td class="r_tb"><?=$m_subnet?> :</td>
			<td class="l_tb">&nbsp;
				<input type=text id=netmask name=netmask size=16 maxlength=15 value="<?=$cfg_static_mask?>">
			</td>
		</tr>
		<tr>
			<td class="r_tb"><?=$m_isp_gateway?> :</td>
			<td class="l_tb">&nbsp;
				<input type=text id=gateway name=gateway size=16 maxlength=15 value="<?=$cfg_static_gw?>">
			</td>
		</tr>
		<tr>
			<td class="r_tb"><?=$m_macaddr?> :</td>
			<td class="l_tb">&nbsp;
				<input type=text id=mac1 name=mac1 size=2 maxlength=2 value=""> -
				<input type=text id=mac2 name=mac2 size=2 maxlength=2 value=""> -
				<input type=text id=mac3 name=mac3 size=2 maxlength=2 value=""> -
				<input type=text id=mac4 name=mac4 size=2 maxlength=2 value=""> -
				<input type=text id=mac5 name=mac5 size=2 maxlength=2 value=""> -
				<input type=text id=mac6 name=mac6 size=2 maxlength=2 value=""> <?=$m_optional?>
				<input type="button" value="<?=$m_clone_mac?>" name="clone" onclick='set_clone_mac("frm_static")'>
				<input type=hidden id=clonemac name=clonemac>
			</td>
		</tr>
		<tr>
			<td class="r_tb"><?=$m_primary_dns?> :</td>
			<td class="l_tb">&nbsp;
				<input type=text id=dns1 name=dns1 size=16 maxlength=15 value="<?=$cfg_dns1?>">
			</td>
		</tr>
		<tr>
			<td class="r_tb"><?=$m_secondary_dns?> :</td>
			<td class="l_tb">&nbsp;
				<input type=text id=dns2 name=dns2 size=16 maxlength=15 value="<?=$cfg_dns2?>">&nbsp;<?=$m_optional?>
			</td>
		</tr>
		<tr>
			<td class="r_tb"><?=$m_mtu?> :</td>
			<td class="l_tb">&nbsp;
				<input type=text id=mtu name=mtu maxlength=4 size=5 value="<?=$cfg_static_mtu?>">
			</td>
		</tr>

	<!--********************8021x*****************-->
		
		<? if(query("/runtime/func/w8021x")=="1")
		{
			echo "<tr>\n";
				echo "<td class=\"r_tb\">".$m_8021x_setting." :</td>\n";
				echo "<td class=\"l_tb\">&nbsp;\n";
					echo "<input type=\"checkbox\" id=\"enable_st8021x\" name=\"enable_st8021x\" onclick=\"on_change_w8021x()\">\n";
				echo "</td>\n";
			echo "</tr>\n";
		}
		?>

		<tr>
			<td colspan=2>
				<div class="" id="st_show_8021x" style="display:none">
		<table>
		
		<tr>
			<td colspan="2">
				<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$m_8021x_setting_description?></p>
			</td>	
		</tr>
		<tr>
			<td class="r_tb"><?=$m_8021x_eap_type?>	:</td>
			<td class="l_td">
				<select id="st_eap_type" name="st_eap_type" onchange="st_w8021x_eap_sel()">
					<option value="1"><?=$m_8021x_md5?></option>
					<option value="2"><?=$m_8021x_peap?></option>
					<option value="3"><?=$m_8021x_ttls?></option>
				</select>
		</tr>
		<tr>
			<td class="r_tb"><?=$m_8021x_authentication?> : </td>
			<td class="l_tb">
				<select id="st_authentication" name="st_authentication">
					<option value="0"> </option>
					<option value="1"><?=$m_8021x_pap?></option>
					<option value="2"><?=$m_8021x_chap?></option>
					<option value="3"><?=$m_8021x_mschap?></option>
					<option value="4"><?=$m_8021x_mschapv2?></option>
			</select>
		</td>
		</tr>
		<tr>
			<td class="r_tb" width=150><?=$m_8021x_identity?> :</td>
				<td class="l_tb">
			  	<input type=text id="st_username" name="st_username" size=30 maxlength=255 value="<?=$cfg_static_usr?>">
				</td>
		</tr>
		<tr>
			<td class="r_tb" width=150><?=$m_8021x_pass?> :</td>
				<td class="l_tb">
			  	<input type=password id="st_password" name="st_password" size=30 maxlength=255 value="<?=$cfg_st_password?>">
				</td>
		</tr>
		<tr>
			<td class="r_tb" width=150><?=$m_8021x_pass2?> :</td>
			<td class="l_tb">
			  	<input type=password id="st_password_v" name="st_password_v" size=30 maxlength=255 value="<?=$cfg_st_password_v?>">
			</td>
		</tr>
		
	</table>
		</div>
			</td>
		</tr>
		
		
<!--*********************************************-->
		</table>
		</form>
		</div>

	<!-- DHCP  -->
		<div class="box" id="show_dhcp" style="display:none">
		<h2><?=$m_title_dhcp?></h2>
		<form name="frm_dhcp" id="frm_dhcp" method="post" action="<?=$MY_NAME?>.php" onsubmit="return check();">
		<input type="hidden" name="ACTION_POST" value="DHCP">
		<p><?=$m_desc_dhcp?></p>
		<table cellSpacing=1 cellPadding=1 width=525 border=0>
		<tr>
			<td class="r_tb" width=150><?=$m_host_name?> :</td>
			<td class="l_tb">&nbsp;
				<input type=text id=hostname name=hostname size=40 maxlength=39 value="<?=$cfg_dhcp_hostname?>">
			</td>
		</tr>
		<tr>
			<td class="r_tb"><?=$m_macaddr?> :</td>
			<td class="l_tb">&nbsp;
				<input type=text id=mac1 name=mac1 size=2 maxlength=2 value=""> -
				<input type=text id=mac2 name=mac2 size=2 maxlength=2 value=""> -
				<input type=text id=mac3 name=mac3 size=2 maxlength=2 value=""> -
				<input type=text id=mac4 name=mac4 size=2 maxlength=2 value=""> -
				<input type=text id=mac5 name=mac5 size=2 maxlength=2 value=""> -
				<input type=text id=mac6 name=mac6 size=2 maxlength=2 value=""> <?=$m_optional?>
				<input type="button" value="<?=$m_clone_mac?>" name="clone" onclick='set_clone_mac("frm_dhcp")'>
             		
			</td>
			<input type=hidden id=clonemac name=clonemac>
		</tr>
		<tr>
			<td class="r_tb"><?=$m_primary_dns?> :</td>
			<td class="l_tb">&nbsp;
				<input type=text id=dns1 name=dns1 size=16 maxlength=15 value="<?=$cfg_dns1?>">
			</td>
		</tr>
		<tr>
			<td class="r_tb"><?=$m_secondary_dns?> :</td>
			<td class="l_tb">&nbsp;
				<input type=text id=dns2 name=dns2 size=16 maxlength=15 value="<?=$cfg_dns2?>">&nbsp;<?=$m_optional?>
			</td>
		</tr>
		<tr>
			<td class="r_tb"><?=$m_mtu?> :</td>
			<td class="l_tb">&nbsp;
				<input type=text id=mtu name=mtu maxlength=4 size=5 value="<?=$cfg_dhcp_mtu?>">
			</td>
		</tr>
	<!--********************8021x*****************-->
		
		<? if(query("/runtime/func/w8021x")=="1")
		{
			echo "<tr>\n";
				echo "<td class=\"r_tb\">".$m_8021x_setting." :</td>\n";
				echo "<td class=\"l_tb\">&nbsp;\n";
					echo "<input type=\"checkbox\" id=\"enable_dh8021x\" name=\"enable_dh8021x\" onclick=\"on_change_w8021x()\">\n";
				echo "</td>\n";
			echo "</tr>\n";
		}
		?>

		<tr>
			<td colspan=2>
				<div class="" id="dh_show_8021x" style="display:none">
					<table>
		
		<tr>
			<td colspan="2">
				<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$m_8021x_setting_description?></p>
			</td>	
		</tr>
		<tr>
			<td class="r_tb"><?=$m_8021x_eap_type?>	:</td>
			<td class="l_td">
				<select id="dh_eap_type" name="dh_eap_type" onchange="dh_w8021x_eap_sel()">
					<option value="1"><?=$m_8021x_md5?></option>
					<option value="2"><?=$m_8021x_peap?></option>
					<option value="3"><?=$m_8021x_ttls?></option>
				</select>
		</tr>
		<tr>
			<td class="r_tb"><?=$m_8021x_authentication?> : </td>
			<td class="l_tb">
				<select id="dh_authentication" name="dh_authentication">
					<option value="0"> </option>
					<option value="1"><?=$m_8021x_pap?></option>
					<option value="2"><?=$m_8021x_chap?></option>
					<option value="3"><?=$m_8021x_mschap?></option>
					<option value="4"><?=$m_8021x_mschapv2?></option>
			</select>
		</td>
		</tr>
		<tr>
			<td class="r_tb" width=150><?=$m_8021x_identity?> :</td>
				<td class="l_tb">
			  	<input type=text id=dh_username name=dh_username size=30 maxlength=255 value="<?=$cfg_dhcp_usr?>">
				</td>
		</tr>
		<tr>
			<td class="r_tb" width=150><?=$m_8021x_pass?> :</td>
				<td class="l_tb">
			  	<input type=password id=dh_password name=dh_password size=30 maxlength=255 value="<?=$cfg_dhcp_password?>">
				</td>
		</tr>
		<tr>
			<td class="r_tb" width=150><?=$m_8021x_pass2?> :</td>
			<td class="l_tb">
			  	<input type=password id=dh_password_v name=dh_password_v size=30 maxlength=255 value="<?=$cfg_dhcp_password_v?>">
			</td>
		</tr>
		
	</table>
		</div>
			</td>
		</tr>
		
		
<!--*********************************************-->
		</table>
		</form>
		</div>
		
	<!-- DHCPPLUS -->
		<div class="box" id="show_dhcpplus" style="display:none">
		<h2><?=$m_title_dhcp?></h2>
		<form name="frm_dhcpplus" id="frm_dhcpplus" method="post" action="<?=$MY_NAME?>.php" onsubmit="return check();">
		<input type="hidden" name="ACTION_POST" value="DHCPPLUS">
		<p><?=$m_desc_dhcp?></p>
		<table cellSpacing=1 cellPadding=1 width=525 border=0>
		<tr>
			<td class="r_tb" width=150><?=$m_host_name?> :</td>
			<td class="l_tb">&nbsp;
				<input type=text id=hostname name=hostname size=40 maxlength=39 value="<?=$cfg_dhcp_hostname?>">
			</td>
		</tr>
		<tr>
			<td class="r_tb"><?=$m_macaddr?> :</td>
			<td class="l_tb">&nbsp;
				<input type=text id=mac1 name=mac1 size=2 maxlength=2 value=""> -
				<input type=text id=mac2 name=mac2 size=2 maxlength=2 value=""> -
				<input type=text id=mac3 name=mac3 size=2 maxlength=2 value=""> -
				<input type=text id=mac4 name=mac4 size=2 maxlength=2 value=""> -
				<input type=text id=mac5 name=mac5 size=2 maxlength=2 value=""> -
				<input type=text id=mac6 name=mac6 size=2 maxlength=2 value=""> <?=$m_optional?>
				<input type="button" value="<?=$m_clone_mac?>" name="clone" onclick='set_clone_mac("frm_dhcpplus")'>
             		
			</td>
			<input type=hidden id=clonemac name=clonemac>
		</tr>

		<tr>
				<td class="r_tb" width=150><?=$m_dhcpp_username?> :</td>
				<td class="l_tb">&nbsp;
					 	<input type=text id=dhcpp_username name=dhcpp_username size=30 maxlength=255 value="<?=$cfg_dhcpplus_username?>">
				</td>
		</tr>
		
		<tr>
				<td class="r_tb" width=150><?=$m_dhcpp_pass?> :</td>
				<td class="l_tb">&nbsp;
					 	<input type=password id=dhcpp_passwd name=dhcpp_passwd size=30 maxlength=255 value="<?=$cfg_dhcpplus_password?>">
				</td>
		</tr>
			
		<tr>
				<td class="r_tb" width=150><?=$m_dhcpp_pass2?> :</td>
						<td class="l_tb">&nbsp;
						  	<input type=password id=dhcpp_passwd_v name=dhcpp_passwd_v size=30 maxlength=255 value="<?=$cfg_dhcpplus_password?>">
				</td>
		</tr>
		
		<tr>
			<td class="r_tb"><?=$m_mtu?> :</td>
			<td class="l_tb">&nbsp;
				<input type=text id=mtu name=mtu maxlength=4 size=5 value="<?=$cfg_dhcpplus_mtu?>">
			</td>
		</tr>
		

		
		</table>
		</form>
		</div>
		<!-- DHCPPLUS END   ******************************-->

	<!-- PPPoE  -->
		<form name="frm_pppoe" id="frm_pppoe" method="post" action="<?=$MY_NAME?>.php" onsubmit="return check();">
		<div class="box" id="show_pppoe" style="display:none">
			<div id="org_pppoe" style="display:none"><h2><?=$m_title_pppoe?></h2></div>
			<div id="russia_pppoe" style="display:none"><h2><?=$m_title_russia_pppoe?></h2></div>
		<input type="hidden" name="ACTION_POST" value="PPPOE">
		<p><?=$m_desc_pppoe?></p>
		<table cellSpacing=1 cellPadding=1 width=525 border=0>
		<tr>
			<td class="r_tb" width=150></td>
		    <td class="l_tb">&nbsp;
				<input type=radio value=0 id=ipmode name=ipmode onclick=on_click_ppp_ipmode("frm_pppoe")><?=$m_dynamic_pppoe?>
				<input type=radio value=1 id=ipmode name=ipmode onclick=on_click_ppp_ipmode("frm_pppoe")><?=$m_static_pppoe?>
				<input type=hidden id=mode name=mode>
			</td>
		</tr>
		<tr>
			<td class="r_tb" width=150><?=$m_user_name?> :</td>
			<td class="l_tb">
				<table><tr>
				<td class="l_tb">
					&nbsp;<input type=text id=username name=username size=30 maxlength=255 value="<?=$cfg_poe_user?>">
				</td>
				<td class="l_tb" id=show_pppoe_mppe style="display:none" >
					&nbsp;<?=$m_mppe?> :<input type=checkbox id=pppoe_mppe name=pppoe_mppe <?=$cfg_poe_mppe?>>
				</td>
				</tr></table>
			</td>
		</tr>	
		
		<tr>
			<td class="r_tb" width=150><?=$m_password?> :</td>
			<td class="l_tb">&nbsp;
				<input type=text id=password name=password size=30 maxlength=255 value="<?=$G_DEF_PASSWORD?>">
			</td>
        </tr>
		<tr>
			<td class="r_tb" width=150><?=$m_retype_pwd?> :</td>
			<td class="l_tb">&nbsp;
				<input type=text id=password_v name=password_v size=30 maxlength=255 value="<?=$G_DEF_PASSWORD?>">
			</td>
		</tr>
		<tr>
			<td class="r_tb" width=150><?=$m_pppoe_svc_name?> :</td>
			<td class="l_tb">&nbsp;
				<input type=text id=svc_name name=svc_name size=30 maxlength=63 value="<?=$cfg_poe_service?>">&nbsp;<?=$m_optional?>
			</td>
		</tr>
		<tr>
			<td class="r_tb" width=150><?=$m_ipaddr?> :</td>
			<td class="l_tb">&nbsp;
				<input type=text id=ipaddr name=ipaddr size=16 maxlength=15 value="<?=$cfg_poe_staticip?>">
			</td>
		</tr>
		<tr>
			<td class="r_tb"><?=$m_macaddr?> :</td>
			<td class="l_tb">&nbsp;
				<input type=text id=mac1 name=mac1 size=2 maxlength=2 value=""> -
				<input type=text id=mac2 name=mac2 size=2 maxlength=2 value=""> -
				<input type=text id=mac3 name=mac3 size=2 maxlength=2 value=""> -
				<input type=text id=mac4 name=mac4 size=2 maxlength=2 value=""> -
				<input type=text id=mac5 name=mac5 size=2 maxlength=2 value=""> -
				<input type=text id=mac6 name=mac6 size=2 maxlength=2 value="">&nbsp;<?=$m_optional?>
				<input type="button" value="<?=$m_clone_mac?>" name="clone" onclick='set_clone_mac("frm_pppoe")'>
			</td>
			<input type=hidden id=clonemac name=clonemac>
		</tr>
		<tr>
			<td class="r_tb"> </td>
			<td class="l_tb">&nbsp;
				<input type=radio name=dnsmode id=dnsmode value=1 onclick=on_click_dns_mode("frm_pppoe")><?=$m_auto_dns?>
				<input type=radio name=dnsmode id=dnsmode value=0 onclick=on_click_dns_mode("frm_pppoe")><?=$m_manual_dns?>
				<input type=hidden id=autodns name=autodns>
			</td>
		</tr>
		<tr>
			<td class="r_tb"><?=$m_primary_dns?> :</td>
			<td class="l_tb">&nbsp;
				<input type=text id=dns1 name=dns1 size=16 maxlength=15 value="<?=$cfg_dns1?>"></td>
		</tr>
		<tr>
			<td class="r_tb"><?=$m_secondary_dns?> :</td>
			<td class="l_tb">&nbsp;
				<input type=text id=dns2 name=dns2 size=16 maxlength=15 value="<?=$cfg_dns2?>">&nbsp;<?=$m_optional?></td>
		</tr>
		<tr>
			<td class="r_tb" width=150><?=$m_ppp_idle_time?> :</td>
			<td class="l_tb">&nbsp;
				<input type=text id=idletime name=idletime size=5 maxlength=4 value="<?=$cfg_poe_idle_time?>">&nbsp;<?=$m_minutes?>
			</td>
		</tr>
		<tr>
			<td class="r_tb"><?=$m_mtu?> :</td>
			<td class="l_tb">&nbsp;
				<input type=text id=mtu name=mtu maxlength=4 size=5 value="<?=$cfg_poe_mtu?>"></td>
		</tr>
		<tr>
			<td class="r_tb" width=150><?=$m_ppp_connect_mode?> :</td>
			<td class="l_tb">&nbsp;
				<input type=radio name=ppp_conn_mode id=ppp_conn_mode value=0 onclick="chg_ppp_conn_mode(this.form);">
<?						$PREFIX		= "\t\t\t\t\t";
						$OBJID		= "wan_sch";
						$OBJNAME	= "wan_sch";
						$UNIQUEID	= $cfg_poe_sch;
						require("/www/__schedule_combobox.php"); ?><br>&nbsp;	
				<input type=radio name=ppp_conn_mode id=ppp_conn_mode value=1 onclick="chg_ppp_conn_mode(this.form);"><?=$m_manual?>
				<input type=radio name=ppp_conn_mode id=ppp_conn_mode value=2 onclick="chg_ppp_conn_mode(this.form);"><?=$m_on_demand?>
				<input type=hidden name=ppp_auto id=ppp_auto>
				<input type=hidden name=ppp_ondemand id=ppp_ondemand>
			</td>
		</tr>

	<!--********************8021x*****************-->
		
<!--tommy 05.13-->
		<tr>
			<td colspan="2">
				<div class="" id="pppoe_show_8021x_check" style="display:none">
					<table>
			<tr>
				
			<td class="r_tb" width=147><?=$m_8021x_setting?> :</td>
			<td class="l_td">&nbsp;
		<input type="checkbox" id="enable_pppoe8021x" name="enable_pppoe8021x" onclick="on_change_w8021x()">
			</td>
		</tr>
		
	</table>
</div>
</td>
</tr>	
<!------------------------------------->

	


		<tr>
			<td colspan=2>
				<div class="" id="pppoe_show_8021x" style="display:none">
					<table>
	
		<tr>
			<td colspan="2">
				<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$m_8021x_setting_description?></p>
			</td>	
		</tr>
		<tr>
			<td class="r_tb"><?=$m_8021x_eap_type?>	:</td>
			<td class="l_td">
				<select id="pppoe_eap_type" name="pppoe_eap_type" onchange="pppoe_w8021x_eap_sel()">
					<option value="1"><?=$m_8021x_md5?></option>
					<option value="2"><?=$m_8021x_peap?></option>
					<option value="3"><?=$m_8021x_ttls?></option>
				</select>
		</tr>
		<tr>
			<td class="r_tb"><?=$m_8021x_authentication?> : </td>
			<td class="l_tb">
				<select id="pppoe_authentication" name="pppoe_authentication">
					<option value="0"> </option>
					<option value="1"><?=$m_8021x_pap?></option>
					<option value="2"><?=$m_8021x_chap?></option>
					<option value="3"><?=$m_8021x_mschap?></option>
					<option value="4"><?=$m_8021x_mschapv2?></option>
			</select>
		</td>
		</tr>
		<tr>
			<td class="r_tb" width=150><?=$m_8021x_identity?> :</td>
				<td class="l_tb">
			  	<input type=text id="pppoe_username" name="pppoe_username" size=30 maxlength=255 value="<?=$cfg_pppoe_usr?>">
				</td>
		</tr>
		<tr>
			<td class="r_tb" width=150><?=$m_8021x_pass?> :</td>
				<td class="l_tb">
			  	<input type=password id="pppoe_password" name="pppoe_password" size=30 maxlength=255 value="<?=$cfg_pppoe_password?>">
				</td>
		</tr>
		<tr>
			<td class="r_tb" width=150><?=$m_8021x_pass2?> :</td>
			<td class="l_tb">
			  	<input type=password id="pppoe_password_v" name="pppoe_password_v" size=30 maxlength=255 value="<?=$cfg_pppoe_password_v?>">
			</td>
		</tr>
		</table>
		</div>
			</td>
		</tr>
 

		<!--**********************************************************-->

		<!-- netsniper  -->
		<? if($LANGCODE == "zhcn")
		{
			if(query("/runtime/func/netsniper")=="1")
			{
				echo "<tr id=show_pppoe_netsniper style=\"\">\n";
				echo "<td class=r_tb width=150><input type=\"checkbox\" name=\"netsniper_enable\" id=\"netsniper_enable\" ".$cfg_netsniper.">:</td>\n";
          		echo "<td class=l_tb>&nbsp;".$m_netsniper."</td>\n";
          		echo "</tr>\n";
			}
		}
		?>
	
		<!-- starspeed -->
		<? if($LANGCODE == "zhcn")
		{
			if(query("/wan/rg/inf:1/pppoe/starspeed/enable")=="1")	
			{
				echo "<tr>\n";
				echo "	<td colspan=2>\n";
				echo "		<div id=\"pppoe_show_starspeed\" style=\"display:none\">\n";
				echo "		<table>\n";
				echo "		<tr><td class=r_tb width=148>&nbsp;</td>\n";
				echo "		<td class=l_tb>&nbsp;</td></tr>\n";
				echo "		<tr><td colspan=\"2\">".$m_desc_starspeed.":</td></tr>\n";
				echo "		<tr>\n";
				echo "		<td class=r_tb width=148>".$m_starspeed." :</td>\n";
				echo "		<td class=l_tb>&nbsp;\n";
				echo "		<select id=\"starspeed\" name=\"starspeed\">\n";
				echo "		<option value=\"0\">".$m_normal."</option>\n";
				echo "		<option value=\"1\">".$m_xian1."</option>\n";
				echo "		<option value=\"2\">".$m_xian2."</option>\n";
				echo "		<option value=\"3\">".$m_hubei."</option>\n";
				echo "		<option value=\"4\">".$m_henan."</option>\n";
				echo "		<option value=\"5\">".$m_nanchang1."</option>\n";
				echo "		<option value=\"6\">".$m_nanchang2."</option>\n";
				echo "		</select>\n";
				echo "		</td>\n";
				echo "		</tr>\n";
				echo "		</table>\n";
				echo "		</div>\n";
				echo "	</td>\n";
				echo "</tr>\n";
			}
		}
		?>
		</table>
		</div>
		<!-- WAN PHYSICAL SETTING  -->
		<input type=hidden name="pppoe_phy" id="pppoe_phy" value="1" disabled="true">
		<div class="box" id="show_physical" style="display:none">
		<h2><?=$m_title_physical?></h2>
		<table cellSpacing=1 cellPadding=1 width=525 border=0>
		<tr>
			<td></td>
			<td class="l_tb">&nbsp;
				<input type=radio name=phy_mode id="phy_mode" value="2" onclick="chg_phy_mode();"><?=$m_dynamic_ip?>
				<input type=radio name=phy_mode id="phy_mode" value="1" onclick="chg_phy_mode();"><?=$m_static_ip?>
			</td>
		</tr>
		<tr>
			<td class="r_tb"><?=$m_ipaddr?> :</td>
			<td class="l_tb">&nbsp;
				<input type=text id=phy_ip name=phy_ip size=16 maxlength=15 value="<?=$cfg_phy_ip?>">
			<td>
		</tr>
		<tr>
			<td class="r_tb"><?=$m_subnet?> :</td>
			<td class="l_tb">&nbsp;
				<input type=text id=phy_mask name=phy_mask size=16 maxlength=15 value="<?=$cfg_phy_mask?>">
			<td>
		</tr>
		<tr>
			<td class="r_tb"><?=$m_gateway?> :</td>
			<td class="l_tb">&nbsp;
				<input type=text id=phy_gw name=phy_gw size=16 maxlength=15 value="<?=$cfg_phy_gw?>"><?=$m_optional?>
			</td>
		</tr>
		<tr>
			<td class="r_tb"><?=$m_primary_dns?> :</td>
			<td class="l_tb">&nbsp;
				<input type=text id=phy_dns1 name=phy_dns1 size=16 maxlength=15 value="<?=$cfg_phy_dns1?>"><?=$m_optional?>
			</td>
		</tr>
		<tr>
			<td class="r_tb"><?=$m_secondary_dns?> :</td>
			<td class="l_tb">&nbsp;
				<input type=text id=phy_dns2 name=phy_dns2 size=16 maxlength=15 value="<?=$cfg_phy_dns2?>"><?=$m_optional?>
			</td>
		</tr>
		</table>
		</div>
		</form>

	<!-- PPTP  -->
		<div class="box" id="show_pptp" style="display:none">
			<div id="org_pptp" style="display:none"><h2><?=$m_title_pptp?></h2></div>
			<div id="russia_pptp" style="display:none"><h2><?=$m_title_russia_pptp?></h2></div>
		<form name="frm_pptp" id="frm_pptp" method="post" action="<?=$MY_NAME?>.php" onsubmit="return check();">
		<input type="hidden" name="ACTION_POST" value="PPTP">
		<input type=hidden name="pptp_phy" id="pptp_phy" value="1" disabled="true">
		<p><?=$m_desc_pptp?></p>
		<table cellSpacing=1 cellPadding=1 width=525 border=0>
		<tr>
			<td class="r_tb" width=150></td>
		    <td class="l_tb">&nbsp;
				<input type=radio value=0 id=ipmode name=ipmode onclick=on_click_ppp_ipmode("frm_pptp")><?=$m_dynamic_ip?>
				<input type=radio value=1 id=ipmode name=ipmode onClick=on_click_ppp_ipmode("frm_pptp")><?=$m_static_ip?>
				<input type=hidden id=mode name=mode>
			</td>
		</tr>
		<tr>
			<td class="r_tb" width=150><?=$m_ipaddr?> :</td>
			<td class="t_tb">&nbsp;
				<input type=text id=ipaddr name=ipaddr size=16 maxlength=15 value="<?=$cfg_pptp_ip?>">
				<?=$m_comment_isp?>
			</td>
		</tr>
		<tr>
			<td class="r_tb"><?=$m_subnet?> :</td>
			<td class="l_tb">&nbsp;
				<input type=text id=netmask name=netmask size=16 maxlength=15 value="<?=$cfg_pptp_mask?>">
			</td>
		</tr>
		<tr>
			<td class="r_tb"><?=$m_gateway?> :</td>
			<td class="l_tb">&nbsp;
				<input type=text id=gateway name=gateway size=16 maxlength=15 value="<?=$cfg_pptp_gw?>">
			</td>
		</tr>
		<tr>
			<td class="r_tb"><?=$m_dns?> :</td>
			<td class="l_tb">&nbsp;
				<input type=text id=dns name=dns size=16 maxlength=15 value="<?=$cfg_pptp_dns?>">
			</td>
		</tr>
		<tr>
			<td class="r_tb"><?=$m_macaddr?> :</td>
			<td class="l_tb">&nbsp;
				<input type=text id=mac1 name=mac1 size=2 maxlength=2 value=""> -
				<input type=text id=mac2 name=mac2 size=2 maxlength=2 value=""> -
				<input type=text id=mac3 name=mac3 size=2 maxlength=2 value=""> -
				<input type=text id=mac4 name=mac4 size=2 maxlength=2 value=""> -
				<input type=text id=mac5 name=mac5 size=2 maxlength=2 value=""> -
				<input type=text id=mac6 name=mac6 size=2 maxlength=2 value=""> <?=$m_optional?>
				<input type="button" value="<?=$m_clone_mac?>" name="clone" onclick='set_clone_mac("frm_pptp")'>
			</td>
			<input type=hidden id=clonemac name=clonemac>
		</tr>
		<tr>
			<td class="r_tb" width=150><?=$m_server_ip?> :</td>
			<td class="l_tb">&nbsp;
				<input type=text id=server name=server size=32 maxlength=32 value="<?=$cfg_pptp_server?>">
			</td>
		</tr>
		<tr>
			<td class="r_tb" width=150><?=$m_pptp_account?> :</td>
			<td class="l_tb">
				<table><tr>
				<td class="l_tb">
					&nbsp;<input type=text id=username name=username size=32 maxlength=255 value="<?=$cfg_pptp_user?>">
				</td>	
				<td class="l_tb" id=show_pptp_mppe style="display:none" >
					&nbsp;<?=$m_mppe?> :<input type=checkbox id=pptp_mppe name=pptp_mppe <?=$cfg_pptp_mppe?>>
				</td>	
				</tr></table>
			</td>
		</tr>
		<tr>
			<td class="r_tb" width=150><?=$m_pptp_password?> :</td>
			<td class="l_tb">&nbsp;
				<input type=password id=password name=password size=32 maxlength=255 value="<?=$G_DEF_PASSWORD?>">
			</td>
		</tr>
		<tr>
			<td class="r_tb" width=150><?=$m_pptp_retype_pwd?> :</td>
			<td class="l_tb">&nbsp;
				<input type=password id=password_v name=password_v size=32 maxlength=255 value="<?=$G_DEF_PASSWORD?>">
			</td>
		</tr>
		<tr>
			<td class="r_tb" width=150><?=$m_ppp_idle_time?> :</td>
			<td class="l_tb">&nbsp;
				<input type=text id=idletime name=idletime size=5 maxlength=4 value="<?=$cfg_pptp_idle_time?>">&nbsp;<?=$m_minutes?>
			</td>
		</tr>
		<tr>
			<td class="r_tb"><?=$m_mtu?> :</td>
			<td class="l_tb">&nbsp;
				<input type=text id=mtu name=mtu maxlength=4 size=5 value="<?=$cfg_pptp_mtu?>"></td>
		</tr>
		<tr>
			<td class="r_tb" width=150><?=$m_ppp_connect_mode?> :</td>
			<td class="l_tb">&nbsp;
				<input type=radio name=ppp_conn_mode id=ppp_conn_mode value=0 onclick="chg_ppp_conn_mode(this.form);">
<?						$PREFIX		= "\t\t\t\t\t";
						$OBJID		= "wan_sch";
						$OBJNAME	= "wan_sch";
						$UNIQUEID	= $cfg_pptp_sch;
						require("/www/__schedule_combobox.php"); ?><br>&nbsp;	
				<input type=radio name=ppp_conn_mode id=ppp_conn_mode value=1 onclick="chg_ppp_conn_mode(this.form);"><?=$m_manual?>
				<input type=radio name=ppp_conn_mode id=ppp_conn_mode value=2 onclick="chg_ppp_conn_mode(this.form);"><?=$m_on_demand?>
				<input type=hidden name=ppp_auto id=ppp_auto>
				<input type=hidden name=ppp_ondemand id=ppp_ondemand>
			</td>
		</tr>
	
		<!--********************8021x*****************-->
		
		<tr>
			<td colspan="2">
				<div class="" id="pptp_show_8021x_check" style="display:none">
					<table>
			
			<td class="r_tb" width=147><?=$m_8021x_setting?> :</td>
			<td class="l_td">&nbsp;
			<input type="checkbox" id="enable_pptp8021x" name="enable_pptp8021x" onclick="on_change_w8021x()">
			</td>
		
	</table>
</div>
</td>
</tr>	

		<tr>
			<td colspan=2>
				<div class="" id="pptp_show_8021x" style="display:none">
					<table>
		
		<tr>
			<td colspan="2">
				<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$m_8021x_setting_description?></p>
			</td>	
		</tr>
		<tr>
			<td class="r_tb"><?=$m_8021x_eap_type?>	:</td>
			<td class="l_td">
				<select id="pptp_eap_type" name="pptp_eap_type" onchange="pptp_w8021x_eap_sel()">
					<option value="1"><?=$m_8021x_md5?></option>
					<option value="2"><?=$m_8021x_peap?></option>
					<option value="3"><?=$m_8021x_ttls?></option>
				</select>
		</tr>
		<tr>
			<td class="r_tb"><?=$m_8021x_authentication?> : </td>
			<td class="l_tb">
				<select id="pptp_authentication" name="pptp_authentication">
					<option value="0"> </option>
					<option value="1"><?=$m_8021x_pap?></option>
					<option value="2"><?=$m_8021x_chap?></option>
					<option value="3"><?=$m_8021x_mschap?></option>
					<option value="4"><?=$m_8021x_mschapv2?></option>
			</select>
		</td>
		</tr>
		<tr>
			<td class="r_tb" width=150><?=$m_8021x_identity?> :</td>
				<td class="l_tb">
			  	<input type=text id="pptp_username" name="pptp_username" size=30 maxlength=255 value="<?=$cfg_pptp_usr?>">
				</td>
		</tr>
		<tr>
			<td class="r_tb" width=150><?=$m_8021x_pass?> :</td>
				<td class="l_tb">
			  	<input type=password id="pptp_password" name="pptp_password" size=30 maxlength=255 value="<?=$cfg_pptp_password?>">
				</td>
		</tr>
		<tr>
			<td class="r_tb" width=150><?=$m_8021x_pass2?> :</td>
			<td class="l_tb">
			  	<input type=password id="pptp_password_v" name="pptp_password_v" size=30 maxlength=255 value="<?=$cfg_pptp_password_v?>">
			</td>
		</tr>
		</table>
		</div>
			</td>
		</tr>
 

		<!--**********************************************************-->
		</table>
		</form>
		</div>

	<!-- L2TP  -->
		<div class="box" id="show_l2tp" style="display:none">
		<h2>L2TP</h2>
		<form name="frm_l2tp" id="frm_l2tp" method="post" action="<?=$MY_NAME?>.php" onsubmit="return check();">
		<input type="hidden" name="ACTION_POST" value="L2TP">
		<p><?=$m_desc_l2tp?></p>
		<table cellSpacing=1 cellPadding=1 width=525 border=0>
		<tr>
			<td class="r_tb" width=150></td>
		    <td class="l_tb">&nbsp;
				<input type=radio value=0 id=ipmode name=ipmode onclick=on_click_ppp_ipmode("frm_l2tp")><?=$m_dynamic_ip?>
				<input type=radio value=1 id=ipmode name=ipmode onclick=on_click_ppp_ipmode("frm_l2tp")><?=$m_static_ip?>
				<input type=hidden id=mode name=mode>
			</td>
		</tr>
		<tr>
			<td class="r_tb" width=150><?=$m_ipaddr?> :</td>
			<td class="t_tb">&nbsp;
				<input type=text id=ipaddr name=ipaddr size=16 maxlength=15 value="<?=$cfg_l2tp_ip?>">
				<?=$m_comment_isp?>
			</td>
		</tr>
		<tr>
			<td class="r_tb"><?=$m_subnet?> :</td>
			<td class="l_tb">&nbsp;
				<input type=text id=netmask name=netmask size=16 maxlength=15 value="<?=$cfg_l2tp_mask?>">
			</td>
		</tr>
		<tr>
			<td class="r_tb"><?=$m_gateway?> :</td>
			<td class="l_tb">&nbsp;
				<input type=text id=gateway name=gateway size=16 maxlength=15 value="<?=$cfg_l2tp_gw?>">
			</td>
		</tr>
		<tr>
			<td class="r_tb"><?=$m_dns?> :</td>
			<td class="l_tb">&nbsp;
				<input type=text id=dns name=dns size=16 maxlength=15 value="<?=$cfg_l2tp_dns?>">
			</td>
		</tr>
		<tr>
			<td class="r_tb"><?=$m_macaddr?> :</td>
			<td class="l_tb">&nbsp;
				<input type=text id=mac1 name=mac1 size=2 maxlength=2 value=""> -
				<input type=text id=mac2 name=mac2 size=2 maxlength=2 value=""> -
				<input type=text id=mac3 name=mac3 size=2 maxlength=2 value=""> -
				<input type=text id=mac4 name=mac4 size=2 maxlength=2 value=""> -
				<input type=text id=mac5 name=mac5 size=2 maxlength=2 value=""> -
				<input type=text id=mac6 name=mac6 size=2 maxlength=2 value=""> <?=$m_optional?>
				<input type="button" value="<?=$m_clone_mac?>" name="clone" onclick='set_clone_mac("frm_l2tp")'>
			</td>
			<input type=hidden id=clonemac name=clonemac>

		</tr>
		<tr>
			<td class="r_tb" width=150><?=$m_server_ip?> :</td>
			<td class="l_tb">&nbsp;
				<input type=text id=server name=server size=32 maxlength=32 value="<?=$cfg_l2tp_server?>">
			</td>
		</tr>
		<tr>
			<td class="r_tb" width=150><?=$m_l2tp_account?> :</td>
			<td class="l_tb">&nbsp;
				<input type=text id=username name=username size=32 maxlength=255 value="<?=$cfg_l2tp_user?>">
			</td>
		</tr>
		<tr>
			<td class="r_tb" width=150><?=$m_l2tp_password?> :</td>
			<td class="l_tb">&nbsp;
				<input type=password id=password name=password size=32 maxlength=255 value="<?=$G_DEF_PASSWORD?>">
			</td>
		</tr>
		<tr>
			<td class="r_tb" width=150><?=$m_l2tp_retype_pwd?> :</td>
			<td class="l_tb">&nbsp;
				<input type=password id=password_v name=password_v size=32 maxlength=255 value="<?=$G_DEF_PASSWORD?>">
			</td>
		</tr>
		<tr>
			<td class="r_tb" width=150><?=$m_ppp_idle_time?> :</td>
			<td class="l_tb">&nbsp;
				<input type=text id=idletime name=idletime size=5 maxlength=4 value="<?=$cfg_l2tp_idle_time?>">&nbsp;<?=$m_minutes?>
			</td>
		</tr>
		<tr>
			<td class="r_tb"><?=$m_mtu?> :</td>
			<td class="l_tb">&nbsp;
				<input type=text id=mtu name=mtu maxlength=4 size=5 value="<?=$cfg_l2tp_mtu?>"></td>
		</tr>
		<tr>
			<td class="r_tb" width=150><?=$m_ppp_connect_mode?> :</td>
			<td class="l_tb">&nbsp;
				<input type=radio name=ppp_conn_mode id=ppp_conn_mode value=0 onclick="chg_ppp_conn_mode(this.form);">
<?						$PREFIX		= "\t\t\t\t\t";
						$OBJID		= "wan_sch";
						$OBJNAME	= "wan_sch";
						$UNIQUEID	= $cfg_l2tp_sch;
						require("/www/__schedule_combobox.php"); ?><br>&nbsp;	
				<input type=radio name=ppp_conn_mode id=ppp_conn_mode value=1 onclick="chg_ppp_conn_mode(this.form);"><?=$m_manual?>
				<input type=radio name=ppp_conn_mode id=ppp_conn_mode value=2 onclick="chg_ppp_conn_mode(this.form);"><?=$m_on_demand?>
				<input type=hidden name=ppp_auto id=ppp_auto>
				<input type=hidden name=ppp_ondemand id=ppp_ondemand>
			</td>
		</tr>
		</table>
		</form>
		</div>

		<div id="box_bottom">
		<script>apply('check()'); echo("&nbsp;"); cancel('');</script>
		</div>
<!-- ________________________________  Main Content End _______________________________ -->
	</td>
	<td <?=$G_HELP_TABLE_ATTR?>><?require($LOCALE_PATH."/help/h_".$MY_NAME.".php");?></td>
</tr>
</table>
<?require("/www/model/__tailer.php");?>
</body>
</html>
