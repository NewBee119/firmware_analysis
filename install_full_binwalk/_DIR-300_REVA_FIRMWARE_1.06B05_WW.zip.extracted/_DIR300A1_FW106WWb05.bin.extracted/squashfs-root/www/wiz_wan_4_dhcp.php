<?
/* vi: set sw=4 ts=4: ---------------------------------------------------------*/
$MY_NAME		="wiz_wan_4_dhcp";
$MY_MSG_FILE	=$MY_NAME.".php";

$MY_ACTION		= "4_dhcp";
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
$tmp_clonemac = query($G_WIZ_PREFIX_WAN."/dhcp/clonemac");
if ($tmp_clonemac == "") {$tmp_clonemac = query("/wan/rg/inf:1/dhcp/clonemac");}
$tmp_hostname = get(h,$G_WIZ_PREFIX_WAN."/dhcp/hostname");
if ($tmp_hostname == "") {$tmp_hostname = get(h,"/sys/hostname");}
/* --------------------------------------------------------------------------- */
?>

<script>

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

/* page init functoin */
function init()
{
	var f=get_obj("frm");
	var addr = get_mac("<?=$tmp_clonemac?>");

	f.mac1.value = addr[1];
	f.mac2.value = addr[2];
	f.mac3.value = addr[3];
	f.mac4.value = addr[4];
	f.mac5.value = addr[5];
	f.mac6.value = addr[6];
}
/* parameter checking */
function check()
{
	var f=get_obj("frm");
	// do check here ....
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
		<table align="center">
		<tr>
			<td class="r_tb" width="137"><strong><?=$m_macaddr?> :</strong></td>
			<td class="l_tb" width="473">
				<input type=text id=mac1 name=mac1 size=2 maxlength=2 value=""> -
				<input type=text id=mac2 name=mac2 size=2 maxlength=2 value=""> -
				<input type=text id=mac3 name=mac3 size=2 maxlength=2 value=""> -
				<input type=text id=mac4 name=mac4 size=2 maxlength=2 value=""> -
				<input type=text id=mac5 name=mac5 size=2 maxlength=2 value=""> -
				<input type=text id=mac6 name=mac6 size=2 maxlength=2 value=""> <?=$m_optional?>
				<input type=hidden id=clonemac name=clonemac>
			</td>
		</tr>
		<tr>
			<td class="r_tb">&nbsp;</td>
			<td class="l_tb">
				<input type=button name="clone" value="<?=$m_clone_mac?>" onclick='set_clone_mac("frm")'>
			</td>
		</tr>
		<tr>
			<td class="r_tb"><strong><?=$m_host_name?> :</strong></td>
			<td class="l_tb"><input type=text id=hostname name=hostname size=40 maxlength=39 value="<?=$tmp_hostname?>"></td>
		</tr>
		<tr>
			<td class="r_tb">&nbsp;</td>
			<td class="l_tb"><?=$m_host_name_note?></td>
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
