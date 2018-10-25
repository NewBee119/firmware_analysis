<?
/* vi: set sw=4 ts=4: ---------------------------------------------------------*/
$MY_NAME		="wiz_wan_4_pppoe";
$MY_MSG_FILE	=$MY_NAME.".php";

$MY_ACTION		= "4_pppoe";
$WIZ_PREV		= "3_sel_wan";
$WIZ_NEXT		= "5_saving";
/* --------------------------------------------------------------------------- */
if ($ACTION_POST!="")
{
	require("/www/model/__admin_check.php");
	require("/www/__wiz_wan_action.php");
	$ACTION_POST="";
	require("/www/wiz_wan_".$WIZ_NEXT.".php");
	exit;
}

/* --------------------------------------------------------------------------- */
$NO_NEED_AUTH="0";
require("/www/model/__html_head.php");
require("/www/comm/__js_ip.php");
/* --------------------------------------------------------------------------- */
// get the variable value from rgdb.
$cfg_poe_mode		= query($G_WIZ_PREFIX_WAN."/pppoe/mode");
$cfg_poe_staticip	= query($G_WIZ_PREFIX_WAN."/pppoe/ipaddr");
$cfg_poe_user		= get(h, $G_WIZ_PREFIX_WAN."/pppoe/username");
$cfg_poe_service	= get(h, $G_WIZ_PREFIX_WAN."/pppoe/svc_name");

anchor("/wan/rg/inf:1/pppoe");
if ($cfg_poe_mode=="")		{$cfg_poe_mode		= query("mode");}
if ($cfg_poe_staticip=="")	{$cfg_poe_staticip	= query("staticip");}
if ($cfg_poe_user=="")		{$cfg_poe_user		= get("h","user");}
if ($cfg_poe_service=="")	{$cfg_poe_service	= get("h","acservice");}

$wan_type=query($G_WIZ_PREFIX_WAN."/wan_type");
if ($wan_type=="r_pppoe")
{
	$cfg_phy_mode	= query($G_WIZ_PREFIX_WAN."/wan/rg/inf:2/mode");
	if ($cfg_phy_mode=="")	{anchor("/wan/rg/inf:2");	$cfg_phy_mode = query("mode");	}
	else					{anchor($G_WIZ_PREFIX_WAN."/wan/rg/inf:2");		}
	if ($cfg_phy_mode=="1")
	{
		$cfg_phy_ip		= query("static/ip");
		$cfg_phy_mask	= query("static/netmask");
		$cfg_phy_gw		= query("static/gateway");
		$cfg_phy_dns1	= query("static/primarydns");
		$cfg_phy_dns2	= query("static/secondarydns");
	}
}

$cfg_lan_ip	= query("/lan/ethernet/ip");
$cfg_lan_mask	= query("/lan/ethernet/netmask");
/* --------------------------------------------------------------------------- */
?>

<script>
function on_click_ppp_ipmode(form)
{
	var f = get_obj(form);

	f.ipaddr.disabled = f.ipmode[0].checked ? true : false;
}

/* page init functoin */
function init()
{
	var f=get_obj("frm");
	// init here ...
<?
	if ($cfg_poe_mode != "1")	{ echo "	f.ipmode[0].checked = true;\n";}
	else						{ echo "	f.ipmode[1].checked = true;\n";}
	if ($wan_type=="r_pppoe")
	{
	 	echo "\tget_obj(\"r_pppoe\").style.display=\"\"\n";
		if($cfg_phy_mode=="1")	{echo "\tf.phy_mode[1].checked=true;\n";}
		else					{echo "\tf.phy_mode[0].checked=true;\n";}
		echo "\tchg_phy_mode()\n";
	}
?>
	var phy_mode="<?=$cfg_phy_mode?>";
	on_click_ppp_ipmode("frm");
}
function chg_phy_mode()
{
	var f=get_obj("frm");
	var dis=true;
	if(f.phy_mode[1].checked=="1") dis=false;
	get_obj("phy_ip").disabled = get_obj("phy_mask").disabled = dis;
	get_obj("phy_gw").disabled = get_obj("phy_dns1").disabled = get_obj("phy_dns2").disabled = dis;
}
/* parameter checking */
function check()
{
	var f = get_obj("frm");
	var wan_type="<?=$wan_type?>";
	var net1, net2;
	f.mode.value = f.ipmode[0].checked ? "2" : "1";
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
	}
	/* russia pppoe */
	if (wan_type=="r_pppoe")
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
			if (!is_valid_gateway(f.phy_ip.value, f.phy_mask.value, f.phy_gw.value, 0))
			{
				alert("<?=$a_invalid_ip?>");
				field_focus(f.phy_gw, "**");
				return false;
			}
			if(f.phy_ip.value == f.phy_gw.value)
			{
				alert("<?=$a_ip_equal_gateway?>");
				field_focus(f.phy_ip, "**");
				return false;		
			}			
			net1 = get_network_id(f.phy_ip.value, f.phy_mask.value);
			net2 = get_network_id(f.phy_gw.value, f.phy_mask.value);
			if (net1[0] != net2[0])
			{
				alert("<?=$a_gw_in_different_subnet?>");
				field_focus(f.phy_gw, "**");
				return false;
			}
			if(!is_valid_ip(f.phy_dns1.value, 0))
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
	}

	return true;
}
function go_prev()
{
	self.location.href="<?=$POST_ACTION?>?TARGET_PAGE=<?=$WIZ_PREV?>";
}
</script>
<body onload="init();" <?=$G_BODY_ATTR?>>
<form name="frm" id="frm" method="post" action="<?=$POST_ACTION?>" onsubmit="return check();">
<input type="hidden" name="ACTION_POST" value="<?=$MY_ACTION?>">
<input type="hidden" name="TARGET_PAGE" value="<?=$MY_ACTION?>">
<?require("/www/model/__banner.php");?>
<table <?=$G_MAIN_TABLE_ATTR?>>
<tr valign=top>
	<td width=10%></td>
	<td id="maincontent" width=80%>
		<br>
		<div id="box_header">
<!-- ________________________________ Main Content Start ______________________________ -->
		<? require($LOCALE_PATH."/dsc/dsc_".$MY_NAME.".php"); ?>
		<table>
		<tr>
			<td class="r_tb" width="200"><strong><?=$m_address_mode?> :</strong></td>
			<td class="l_tb" width="410">
				<input type=radio value=0 id=ipmode name=ipmode onclick=on_click_ppp_ipmode("frm")><?=$m_dynamic_ip?>
				<input type=radio value=1 id=ipmode name=ipmode onclick=on_click_ppp_ipmode("frm")><?=$m_static_ip?>
				<input type=hidden id=mode name=mode>
			</td>
		</tr>
		<tr>
			<td class="r_tb"><strong><?=$m_ipaddr?> :</strong></td>
			<td class="l_tb">
				<input type=text id=ipaddr name=ipaddr size=16 maxlength=15 value="<?=$cfg_poe_staticip?>">
			</td>
		</tr>
		<tr>
			<td class="r_tb"><strong><?=$m_user_name?> :</strong></td>
			<td class="l_tb">
				<input type=text id=username name=username size=30 maxlength=255 value="<?=$cfg_poe_user?>">
			</td>
		</tr>
		<tr>
			<td class="r_tb"><strong><?=$m_password?> :</strong></td>
			<td class="l_tb">
				<input type=text id=password name=password size=30 maxlength=255 value="<?=$G_DEF_PASSWORD?>">
			</td>
		</tr>
		<tr>
			<td class="r_tb"><strong><?=$m_verify_pwd?> :</strong></td>
			<td class="l_tb">
				<input type=text id=password_v name=password_v size=30 maxlength=255 value="<?=$G_DEF_PASSWORD?>">
			</td>
		</tr>
		<tr>
			<td class="r_tb"><strong><?=$m_service_name?> :</strong></td>
			<td class="l_tb">
				<input type=text id=svc_name name=svc_name size=30 maxlength=63 value="<?=$cfg_poe_service?>">&nbsp;<?=$m_optional?>
			</td>
		</tr>
		<tr>
			<td class="r_tb">&nbsp;</td>
			<td class="l_tb"><?=$m_service_name_note?>
			</td>
		</tr>
		</table>
		<br>
		<!-- WAN PHYSICAL SETTING  -->
		<div id=r_pppoe style="display:none">
			<hr>
			<table>
			<tr>
				<td class="bl_tb" width="200"><?=$m_title_r_pppoe?></td>
			</tr>
			<tr><td height=20></td></tr>
			<tr>
				<td></td>
				<td class="l_tb">&nbsp;
					<input type=radio name=phy_mode id="phy_mode" value="2" onclick="chg_phy_mode();"><?=$m_dynamic_ip?>
					<input type=radio name=phy_mode id="phy_mode" value="1" onclick="chg_phy_mode();"><?=$m_static_ip?>
				</td>
			</tr>
			<tr>
				<td class="br_tb"><?=$m_ipaddr?> :</td>
				<td class="l_tb">&nbsp;
					<input type=text id=phy_ip name=phy_ip size=16 maxlength=15 value="<?=$cfg_phy_ip?>">
				<td>
			</tr>
			<tr>
				<td class="br_tb"><?=$m_subnet?> :</td>
				<td class="l_tb">&nbsp;
					<input type=text id=phy_mask name=phy_mask size=16 maxlength=15 value="<?=$cfg_phy_mask?>">
				<td>
			</tr>
			<tr>
				<td class="br_tb"><?=$m_gateway?> :</td>
				<td class="l_tb">&nbsp;
					<input type=text id=phy_gw name=phy_gw size=16 maxlength=15 value="<?=$cfg_phy_gw?>">
				</td>
			</tr>
			<tr>
				<td class="br_tb"><?=$m_primary_dns?> :</td>
				<td class="l_tb">&nbsp;
					<input type=text id=phy_dns1 name=phy_dns1 size=16 maxlength=15 value="<?=$cfg_phy_dns1?>">
				</td>
			</tr>
			<tr>
				<td class="br_tb"><?=$m_secondary_dns?> :</td>
				<td class="l_tb">&nbsp;
					<input type=text id=phy_dns2 name=phy_dns2 size=16 maxlength=15 value="<?=$cfg_phy_dns2?>"><?=$m_optional?>
				</td>
			</tr>
			</table>
			<br><br>
		</div>
		<center><script>prev("");next("");exit();</script></center>
		<br>
<!-- ________________________________  Main Content End _______________________________ -->
		</div>
		<br>
	</td>
	<td width=10%></td>
</tr>
</table>
<?require("/www/model/__tailer.php");?>
</form>
</body>
</html>
