<?
/* vi: set sw=4 ts=4: ---------------------------------------------------------*/
$MY_NAME		="wiz_wan_1_password";
$MY_MSG_FILE	=$MY_NAME.".php";

$MY_ACTION		= "1_password";
$WIZ_PREV		= "0_flowchart";
$WIZ_NEXT		= "2_timezone";
$TMP_PASSWORD	= query($G_WIZ_PREFIX_WAN."/password");

if($TMP_PASSWORD == $G_DEF_PASSWORD)
{
	$TMP_PASSWORD="";
}
/* --------------------------------------------------------------------------- */
if ($ACTION_POST!="")
{
	require("www/model/__admin_check.php");
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
	// do check here ....
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
<body onLoad="init();" <?=$G_BODY_ATTR?>>
<form name="frm" id="frm" method="post" action="<?=$POST_ACTION?>" onSubmit="return check();">
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
			<td class="r_tb" width="92"><?=$m_password?> :</td>
			<td class="l_tb" width="173">
				<input type=password id=password name=password size=20 maxlength=20 value="<?=$TMP_PASSWORD?>">
			</td>
		</tr>
		<tr>
			<td class="r_tb" width="100"><?=$m_verify_password?>:</td>
			<td class="l_tb">
				<input type=password id=password_v name=password_v size=20 maxlength=20 value="<?=$TMP_PASSWORD?>">
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
