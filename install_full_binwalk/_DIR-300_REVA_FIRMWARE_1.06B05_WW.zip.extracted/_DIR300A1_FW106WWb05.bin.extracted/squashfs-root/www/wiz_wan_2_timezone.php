<?
/* vi: set sw=4 ts=4: ---------------------------------------------------------*/
$MY_NAME		="wiz_wan_2_timezone";
$MY_MSG_FILE	=$MY_NAME.".php";

$MY_ACTION		= "2_timezone";
$WIZ_PREV		= "1_password";
$WIZ_NEXT		= "3_sel_wan";

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
/* --------------------------------------------------------------------------- */
// get the variable value from rgdb.
$timezone = query($G_WIZ_PREFIX_WAN."/timezone");
if ($timezone == "") { $timezone = query("/time/timezone"); }
$ntpserver = query($G_WIZ_PREFIX_WAN."/ntpserver");
if ($ntpserver == "") { $ntpserver = query("/time/ntpserver/ip"); }
/* --------------------------------------------------------------------------- */
?>

<script>
/* page init functoin */
function init()
{
	select_index(get_obj("ntp_server"), "<?=$ntpserver?>");
}
/* parameter checking */
function check()
{
	var f = get_obj("frm");

	if (is_blank(f.ntp_server.value) || strchk_hostname(f.ntp_server.value)==false)
	{
		alert("<?=$a_invalid_ntp_server?>");
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
			<td class="r_tb"><?=$m_time_zone?>&nbsp;:</td>
			<td class="l_tb">&nbsp;
				<select size=1 name=tzone id=tzone>
<?
				for("/tmp/tz/zone")
				{
					echo "<option value=".$@;
					if($timezone==$@){echo " selected";}
					echo ">".query("name")."</option>\n";
				}
?>
				</select>
			</td>
		</tr>
		<tr>
			<td class="r_tb"><?=$m_ntp_server?>&nbsp;:</td>
			<td class="l_tb">&nbsp;
			<select name="ntp_server" id="ntp_server">
				<option value=""><?=$m_select_ntps?></option>
				<option value="ntp1.dlink.com">ntp1.dlink.com</option>
				<option value="ntp.dlink.com.tw">ntp.dlink.com.tw</option>
			</select>
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
