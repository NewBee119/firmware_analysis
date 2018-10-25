<?
/* vi: set sw=4 ts=4: ---------------------------------------------------------*/
$MY_NAME		="st_device";
$MY_MSG_FILE	=$MY_NAME.".php";
$CATEGORY		="st";
/* --------------------------------------------------------------------------- */
$NO_NEED_AUTH="0";
require("/www/model/__html_head.php");
/* --------------------------------------------------------------------------- */
if(query("/lan/dhcp/server/enable")==1) {$dhcp_en=$m_enabled;}
else                                    {$dhcp_en=$m_disabled;}

anchor("/runtime/wan/inf:1");
if (query("connectstatus") == "connected")
{
	$wan_status = $m_connected;
	$wanipaddr	= query("ip");
	$wansubnet	= query("netmask");
	$wangateway	= query("gateway");
	$wandns		= query("primarydns")."&nbsp;".query("secondarydns");
}
else
{
	$wan_status = $m_disconnected;
	$wanipaddr	= $m_null_ip;
	$wansubnet	= $m_null_ip;
	$wangateway	= $m_null_ip;
	$wandns		= $m_null_ip;
}
$router=query("/runtime/router/enable");
if($router == "1")
{
	$wanmode = query("/wan/rg/inf:1/mode");
}
else
{
	$wanmode = query("/runtime/wan/inf:1/connecttype");
}
$autodns = query("/wan/rg/inf:1/pppoe/autodns");
if ($wanmode == 1)
{
	$msg_wanmode	= $m_static_ip;
	$msg_wanstatus	= "";
	$msg_connect	= "";
	$msg_disconnect	= "";
}
else if ($wanmode == 2)
{
	$msg_wanmode	= $m_dhcp_client;
	$msg_wanstatus	= $wan_status;
	$msg_connect	= $m_dhcp_renew;
	$msg_disconnect	= $m_dhcp_release;
}
else if ($wanmode == 3)
{
	if (query("/wan/rg/inf:2/mode") != "")
	{
		if (query("/wan/rg/inf:2/mode") == "1" || query("/wan/rg/inf:2/mode") == "2")
		{
			$msg_wanmode    = $m_russia_pppoe;
		}
		else
		{
			$msg_wanmode    = $m_pppoe;
		}
	}
	else
	{
		$msg_wanmode	= $m_pppoe;
	}
	$msg_wanstatus	= $wan_status;
	$msg_connect	= $m_connect;
	$msg_disconnect	= $m_disconnect;
	$wangateway		= $wanipaddr;
	if ($wandns == $m_null_ip && $autodns == "0")
	{
		anchor("/dnsrelay/server");
		$wandns = query("primarydns")."&nbsp;".query("secondarydns");
	}
}
else if ($wanmode == 4)
{
	if (query("/wan/rg/inf:1/pptp/physical")=="1")
	{
		$msg_wanmode    = $m_russia_pptp;	
	}
	else
	{
		$msg_wanmode	= $m_pptp;
	}
	$msg_wanstatus	= $wan_status;
	$msg_connect	= $m_connect;
	$msg_disconnect	= $m_disconnect;
	$wangateway		= $wanipaddr;
	if ($wandns == $m_null_ip || $wandns == "" || $wandns == "&nbsp;")
	{
		anchor("/runtime/wan/inf:2");
		$wandns = query("primarydns")."&nbsp;".query("secondarydns");
	}
	if ($wandns == $m_null_ip || $wandns == "" || $wandns == "&nbsp;")
	{
		anchor("/dnsrelay/server");
		$wandns = query("primarydns")."&nbsp;".query("secondarydns");
	}
}
else if ($wanmode == 5)
{
	if (query("/wan/rg/inf:1/l2tp/physical")=="1")
	{
		$msg_wanmode    = $m_russia_l2tp;
	}
	else
	{
		$msg_wanmode	= $m_l2tp;
	}
	$msg_wanstatus	= $wan_status;
	$msg_connect	= $m_connect;
	$msg_disconnect	= $m_disconnect;
	$wangateway		= $wanipaddr;
	if ($wandns == $m_null_ip || $wandns == "" || $wandns == "&nbsp;")
	{
		anchor("/runtime/wan/inf:2");
		$wandns = query("primarydns")."&nbsp;".query("secondarydns");
	}
	if ($wandns == $m_null_ip || $wandns == "" || $wandns == "&nbsp;")
	{
		anchor("/dnsrelay/server");
		$wandns = query("primarydns")."&nbsp;".query("secondarydns");
	}
}
else if ($wanmode == 8)
{
	$msg_wanmode	= $m_dhcp_client;
	$msg_wanstatus	= $wan_status;
}
/* wireless part */
anchor("/wireless");
$wlan_en	= query("enable");
if($wlan_en == 1)
{
	$wep_len	= query("wep/length");
	$ssid		= get(h,"ssid");
	$str_sec	= "";
	$auth		= query("authtype");
	$sec		= query("encrypttype");

	if (query("autochannel")!=1 || $wlan_en==0)	{$channel = query("channel");}
	else {$channel = query("/runtime/stats/wireless/channel");}

	if		($sec==1)   {$str_sec = $str_sec."&nbsp;".$wep_len."&nbsp;".$m_bits; }
	else if	($sec==2)   {$str_sec = $str_sec."&nbsp;".$m_tkip; }
	else if	($sec==3)   {$str_sec = $str_sec."&nbsp;".$m_aes; }
	else if	($sec==4)   {$str_sec = $str_sec."&nbsp;".$m_cipher_auto; }
	else				{$str_sec = $str_sec."&nbsp;".$m_disabled; }
}

?>

<script>

function do_connect()
{
	get_obj("bt_connect").disabled = true;
	send_request("conninfo.xgi?r="+generate_random_str()+"&set/runtime/wan/inf:1/connect=1");
	//self.location.href="<?=$MY_NAME?>.xgi?set/runtime/wan/inf:1/connect=1";
}

function do_disconnect()
{
	get_obj("bt_disconnect").disabled = true;
	send_request("conninfo.xgi?r="+generate_random_str()+"&set/runtime/wan/inf:1/disconnect=1");
	//send_request("conninfo.xgi?r="+generate_random_str()+"&set/runtime/wan/inf:1/disconnect=1&set/connect/pppconnbuttonstatus=0");
	//self.location.href="<?=$MY_NAME?>.xgi?set/runtime/wan/inf:1/disconnect=1";
}

var AjaxReq = null;

function send_request(url)
{
	if (AjaxReq == null) AjaxReq = createRequest();
	AjaxReq.open("GET", url, true);
	AjaxReq.onreadystatechange = update_page;
	AjaxReq.send(null);
}

function update_state()
{
	send_request("/conninfo.php?r="+generate_random_str());
}

/* var count = 0; */
var period = 1000;

function update_page()
{
/*
	count++;
	get_obj("connstate").value = count + " r="+AjaxReq.readyState+",t="+AjaxReq.responseText;
 */
	if (AjaxReq != null && AjaxReq.readyState == 4)
	{
		if (AjaxReq.responseText.substring(0,3)=="var")
		{
			eval(AjaxReq.responseText);
/*
			get_obj("connstate").value = count + " r:"+result[0]+","+result[1];
 */
			switch (result[0])
			{
			case "OK":
				if (result[1] == "connected")
				{
					<?
					if ($wanmode == "3" || $wanmode == "4" || $wanmode == "5")
					{	echo "result[4] = result[2];\n";	}
					?>
					get_obj("connstate").value		= "<?=$m_connected?>";
					get_obj("wanipaddr").innerHTML	= "&nbsp;"+result[2];
					get_obj("wansubnet").innerHTML	= "&nbsp;"+result[3];
					get_obj("wangateway").innerHTML	= "&nbsp;"+result[4];
					get_obj("wandns").innerHTML		= "&nbsp;"+result[5];

					get_obj("bt_connect").disabled = true;
					get_obj("bt_disconnect").disabled = false;
				}
				else if(result[1] == "disconnected")
				{
					get_obj("connstate").value		= "<?=$m_disconnected?>";
					get_obj("wanipaddr").innerHTML	= "&nbsp;<?=$m_null_ip?>";
					get_obj("wansubnet").innerHTML	= "&nbsp;<?=$m_null_ip?>";
					get_obj("wangateway").innerHTML	= "&nbsp;<?=$m_null_ip?>";
					get_obj("wandns").innerHTML		= "&nbsp;<?=$m_null_ip?>";
					
					get_obj("bt_connect").disabled = false;
					get_obj("bt_disconnect").disabled = true;
				}
				else if(result[1] == "connecting" || result[1] == "disconnecting")
				{
					if(result[1] == "connecting")
						connstate = "<?=$m_connecting?>";
					else
						connstate = "<?=$m_disconnecting?>";
					get_obj("connstate").value		= connstate;
					get_obj("wanipaddr").innerHTML	= "&nbsp;<?=$m_null_ip?>";
					get_obj("wansubnet").innerHTML	= "&nbsp;<?=$m_null_ip?>";
					get_obj("wangateway").innerHTML	= "&nbsp;<?=$m_null_ip?>";
					get_obj("wandns").innerHTML		= "&nbsp;<?=$m_null_ip?>";
					
					get_obj("bt_connect").disabled = true;
					get_obj("bt_disconnect").disabled = true;

				}
				setTimeout("update_state()", period);
				break;
				
			case "WAIT":
				setTimeout("update_state()", period);
				break;
			}
			delete result;
		}
	}
}

/* page init functoin */
function init()
{
	<?
	if ($router!="1")
	{
		echo "get_obj('show_lan').style.display = 'none';\n";
	}
	anchor("/runtime/wan/inf:1");
	$connstatus = query("connectstatus");
	if($connstatus == "connected")
	{
		$bt_con    = "true";
		$bt_discon = "false";
	}
	else if($connstatus == "disconnected")
	{
		$bt_con    = "false";
		$bt_discon = "true";
	}
	else if($connstatus == "connecting" || $connstatus == "disconnecting")
	{
		$bt_con    = "true";
		$bt_discon = "true";
		if($connstatus == "connecting")
		{
			$msg_wanstatus = $m_connecting;
		}
		else
		{
			 $msg_wanstatus = $m_disconnecting;
		}
	}
	else // "on demand" or empty
	{
		$bt_con    = "false";
		$bt_discon = "true";
	}
	if ($AUTH_GROUP=="0" && $msg_connect!="" && $router=="1")
	{
		if($bt_con!="" && $bt_discon!="")
		{
		echo "get_obj(\"bt_connect\").disabled = ".$bt_con.";\n";
		echo "get_obj(\"bt_disconnect\").disabled = ".$bt_discon.";\n";
		}
	}
	if (query("connectstatus")!="connected")
	{
		echo "setTimeout(\"update_state()\", period);\n";
	}
	?>
}
/* parameter checking */
function check()
{
}
/* cancel function */
function do_cancel()
{
	self.location.href="<?=$MY_NAME?>.php?random_str="+generate_random_str();
}

</script>
<body onload="init();" <?=$G_BODY_ATTR?>>
<form name="frm" id="frm" method="post" action="<?=$MY_NAME?>.php">
<input type="hidden" name="ACTION_POST" value="SOMETHING">
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
		<p align="center"><strong>
			<?=$m_fw_ver?>&nbsp;:&nbsp;<?query("/runtime/sys/info/firmwareversion");?>&nbsp;,
			&nbsp;<?query("/runtime/sys/info/firmwarebuildate");?>
		</strong></p>
		</div>
<!-- ________________________________ Main Content Start ______________________________ -->
		<div class="box" id="show_lan">
			<h2><?=$m_lan?></h2>
			<table cellpadding="1" cellspacing="1" border="0" width="525">
			<tr>
				<td class="r_tb" width="200"><?=$m_macaddr?>&nbsp;:</td>
				<td class="l_tb">&nbsp;<?query("/runtime/sys/info/lanmac");?></td>
			</tr>
			<tr>
				<td class="r_tb"><?=$m_ipaddr?>&nbsp;:</td>
				<td class="l_tb">&nbsp;<?query("/lan/ethernet/ip");?></td>
			</tr>
			<tr>
				<td class="r_tb"><?=$m_netmask?>&nbsp;:</td>
				<td class="l_tb">&nbsp;<?query("/lan/ethernet/netmask");?></td>
			</tr>
			<tr>
				<td class="r_tb"><?=$m_dhcp_server?>&nbsp;:</td>
				<td class="l_tb">&nbsp;<?=$dhcp_en?></td>
			</tr>
			</table>
		</div>
		<div class="box">
			<h2><?if($router!="1"){echo $m_wired;}else{echo $m_wan;}?></h2>
			<table cellpadding="1" cellspacing="1" border="0" width="525">
			<tr>
				<td class="r_tb" width="198"><?=$m_macaddr?>&nbsp;:</td>
				<td class="l_tb" width=320>&nbsp;<?query("/runtime/wan/inf:1/mac");?></td>
			</tr>
			<tr>
				<td class="r_tb"><?=$m_connection?>&nbsp;:</td>
				<td class="l_tb">&nbsp;<?=$msg_wanmode?>&nbsp;
					<input class="l_tb" type="text" readonly id="connstate" value="<?=$msg_wanstatus?>" style="border-width:0;">
<?
if ($AUTH_GROUP=="0" && $msg_connect!="" && $router=="1")
{
	echo "\t\t\t\t\t<br>\n";
	echo "\t\t\t\t\t<input type=button onclick=do_connect(); name=bt_connect id=bt_connect value=\"".$msg_connect."\">&nbsp;\n";
	echo "\t\t\t\t\t<input type=button onclick=do_disconnect(); name=bt_disconnect id=bt_disconnect value=\"".$msg_disconnect."\">\n";
}
?>
				</td>
			</tr>
			<tr>
				<td class="r_tb"><?=$m_ipaddr?>&nbsp;:</td>
				<td class="l_tb"><div id="wanipaddr">&nbsp;<?=$wanipaddr?></div></td>
			</tr>
			<tr>
				<td class="r_tb"><?=$m_netmask?>&nbsp;:</td>
				<td class="l_tb"><div id="wansubnet">&nbsp;<?=$wansubnet?></div></td>
			</tr>
			<tr>
				<td class="r_tb"><?=$m_default_gw?>&nbsp;:</td>
				<td class="l_tb"><div id="wangateway">&nbsp;<?=$wangateway?></div></td>
			</tr>
			<tr>
				<td class="r_tb"><?=$m_dns?>&nbsp;:</td>
				<td class="l_tb"><div id="wandns">&nbsp;<?=$wandns?></div></td>
			</tr>
			</table>
		</div>
		<div class="box">
			<h2>
			<?
			if( query("/runtime/func/ieee80211n") == "1" )
			{	echo $m_wlan_11n;	}
			else
			{	echo $m_wlan;	}
			?>
			</h2>
			<table cellpadding="1" cellspacing="1" border="0" width="525">
			<tr>
				<td class="r_tb" width="200"><?=$m_ssid?>&nbsp;:</td>
				<td class="l_tb">&nbsp;<?=$ssid?></td>
			</tr>
			<tr>
				<td class="r_tb"><?=$m_channel?>&nbsp;:</td>
				<td class="l_tb">&nbsp;<?=$channel?></td>
			</tr>
			<tr>
				<td class="r_tb"><?=$m_privacy?>&nbsp;:</td>
				<td class="l_tb"><?=$str_sec?></td>
			</tr>
			</table>
		</div>
<!-- ________________________________  Main Content End _______________________________ -->
	</td>
	<td <?=$G_HELP_TABLE_ATTR?>><?require($LOCALE_PATH."/help/h_".$MY_NAME.".php");?></td>
</tr>
</table>
<?require("/www/model/__tailer.php");?>
</form>
</body>
</html>
