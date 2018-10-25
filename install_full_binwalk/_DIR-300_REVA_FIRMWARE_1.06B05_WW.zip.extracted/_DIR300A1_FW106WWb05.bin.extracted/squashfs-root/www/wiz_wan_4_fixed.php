<?
/* vi: set sw=4 ts=4: ---------------------------------------------------------*/
$MY_NAME		="wiz_wan_4_fixed";
$MY_MSG_FILE	=$MY_NAME.".php";

$MY_ACTION		= "4_fixed";
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
anchor("/wan/rg/inf:1/static");
$cfg_static_ip      = query("ip");
$cfg_static_mask    = query("netmask");
$cfg_static_gw      = query("gateway");
anchor("/dnsrelay/server");
$cfg_dns1   = query("primarydns");
$cfg_dns2   = query("secondarydns");
$cfg_lan_ip	= query("/lan/ethernet/ip");
$cfg_lan_mask	= query("/lan/ethernet/netmask");
/* --------------------------------------------------------------------------- */
?>

<script>
/* page init functoin */
function init()
{
	var f=get_obj("frm");
	// init here ...
}
/* parameter checking */
function check()
{
	var f=get_obj("frm");
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
		<table width="536" align="center">
		<tr>
			<td class="r_tb" width="235"><strong><?=$m_ipaddr?>&nbsp;:</strong></td>
			<td class="l_tb" width="468">
				<input type=text id=ipaddr name=ipaddr size=16 maxlength=15 value="<?=$cfg_static_ip?>">
			</td>
		</tr>
		<tr>
			<td class="r_tb"><strong><?=$m_netmask?>&nbsp;:</strong></td>
			<td class="l_tb">
				<input type=text id=netmask name=netmask size=16 maxlength=15 value="<?=$cfg_static_mask?>">
			</td>
		</tr>
		<tr>
			<td class="r_tb"><strong><?=$m_gateway_addr?>&nbsp;:</strong></td>
			<td class="l_tb">
				<input type=text id=gateway name=gateway size=16 maxlength=15 value="<?=$cfg_static_gw?>">
			</td>
		</tr>
		<tr>
			<td class="r_tb"><strong><?=$m_primary_dns?>&nbsp;:</strong></td>
			<td class="l_tb">
				<input type=text id=dns1 name=dns1 size=16 maxlength=15 value="<?=$cfg_dns1?>">
			</td>
		</tr>
		<tr>
			<td class="r_tb"><strong><?=$m_secondary_dns?>&nbsp;:</strong></td>
			<td class="l_tb">
				<input type=text id=dns2 name=dns2 size=16 maxlength=15 value="<?=$cfg_dns2?>">&nbsp;<?=$m_optional?>
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
