<?
/* vi: set sw=4 ts=4: ---------------------------------------------------------*/
$MY_NAME		="wiz_wan_4_l2tp";
$MY_MSG_FILE	=$MY_NAME.".php";

$MY_ACTION		= "4_l2tp";
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
$cfg_l2tp_mode  = query($G_WIZ_PREFIX_WAN."/l2tp/mode");
$cfg_l2tp_ip    = query($G_WIZ_PREFIX_WAN."/l2tp/ipaddr");
$cfg_l2tp_mask  = query($G_WIZ_PREFIX_WAN."/l2tp/netmask");
$cfg_l2tp_gw    = query($G_WIZ_PREFIX_WAN."/l2tp/gateway");
$cfg_l2tp_server= query($G_WIZ_PREFIX_WAN."/l2tp/server");
$cfg_l2tp_user  = get(h,$G_WIZ_PREFIX_WAN."/l2tp/username");

anchor("/wan/rg/inf:1/l2tp");
if ($cfg_l2tp_mode=="")     {$cfg_l2tp_mode = query("mode");}
if ($cfg_l2tp_ip=="")       {$cfg_l2tp_ip   = query("ip");}
if ($cfg_l2tp_mask=="")     {$cfg_l2tp_mask = query("netmask");}
if ($cfg_l2tp_gw=="")       {$cfg_l2tp_gw   = query("gateway");}
if ($cfg_l2tp_server=="")   {$cfg_l2tp_server   = query("serverip");}
if ($cfg_l2tp_user=="")     {$cfg_l2tp_user = get("h","user");}

$cfg_lan_ip	= query("/lan/ethernet/ip");
$cfg_lan_mask	= query("/lan/ethernet/netmask");
/* --------------------------------------------------------------------------- */
?>

<script>

function on_click_ppp_ipmode(form)
{
	var f = get_obj(form);

	f.ipaddr.disabled = f.netmask.disabled = f.gateway.disabled =
	f.ipmode[0].checked ? true : false;
}



/* page init functoin */
function init()
{
	var f=get_obj("frm");
	// init here ...
<?
	if ($cfg_l2tp_mode!="1")	{echo "	f.ipmode[0].checked=true;\n";}
	else						{echo "	f.ipmode[1].checked=true;\n";}
?>
	on_click_ppp_ipmode("frm");
}
/* parameter checking */
function check()
{
	var f = get_obj("frm");

	var net1, net2;

	f.mode.value = f.ipmode[0].checked ? "2" : "1";
	if (f.mode.value == "1")
	{
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
		}
		else
		{
			if (!is_valid_ip(f.gateway.value, 0))
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
		}
	}
/*
	if (!is_valid_ip(f.server.value, 0))
	{
		alert("<?=$a_invalid_ip?>");
		field_focus(f.server, "**");
		return false;
	}
*/
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
		<table width="525" align="center">
		<tr>
			<td class="r_tb" width="220"><strong><?=$m_address_mode?> :</strong></td>
			<td class="l_tb" width="304">
				<input type=radio value=0 id=ipmode name=ipmode onclick=on_click_ppp_ipmode("frm")><?=$m_dynamic_ip?>
				&nbsp;&nbsp;
				<input type=radio value=1 id=ipmode name=ipmode onClick=on_click_ppp_ipmode("frm")><?=$m_static_ip?>
				<input type=hidden id=mode name=mode>
			</td>
		</tr>
		<tr>
			<td class="r_tb"><strong><?=$m_l2tp_ipaddr?> :</strong></td>
			<td class="l_tb">
				<input type=text id=ipaddr name=ipaddr size=32 maxlength=15 value="<?=$cfg_l2tp_ip?>">
			</td>
		</tr>
		<tr>
			<td class="r_tb"><strong><?=$m_l2tp_netmask?> :</strong></td>
			<td class="l_tb">
				<input type=text id=netmask name=netmask size=32 maxlength=15 value="<?=$cfg_l2tp_mask?>">
			</td>
		</tr>
		<tr>
			<td class="r_tb"><strong><?=$m_l2tp_gateway?> :</strong></td>
			<td class="l_tb">
				<input type=text id=gateway name=gateway size=32 maxlength=15 value="<?=$cfg_l2tp_gw?>">
			</td>
		</tr>
		<tr>
			<td class="r_tb"><strong><?=$m_l2tp_server?>&nbsp;:</strong></td>
			<td class="l_tb">
				<input type=text id=server name=server size=32 maxlength=32 value="<?=$cfg_l2tp_server?>">
			</td>
		</tr>
		<tr>
			<td class="r_tb"><strong><?=$m_user_name?> :</strong></td>
			<td class="l_tb">
				<input type=text id=username name=username size=32 maxlength=255 value="<?=$cfg_l2tp_user?>">
			</td>
		</tr>
		<tr>
			<td class="r_tb"><strong><?=$m_password?> :</strong></td>
			<td class="l_tb">
				<input type=password id=password name=password size=32 maxlength=255 value="<?=$G_DEF_PASSWORD?>">
			</td>
		</tr>
		<tr>
			<td class="r_tb"><strong><?=$m_verify_pwd?> :</strong></td>
			<td class="l_tb">
				<input type=password id=password_v name=password_v size=32 maxlength=255 value="<?=$G_DEF_PASSWORD?>">
			</td>
		</tr>
		</table>
		<br>
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
