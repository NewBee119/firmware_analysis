<?
/* vi: set sw=4 ts=4: ---------------------------------------------------------*/
$MY_NAME		="wiz_wlan_2_wpa";
$MY_MSG_FILE	=$MY_NAME.".php";

$MY_ACTION		="2_wpa";
$WIZ_PREV		="1_ssid";
//$WIZ_NEXT will be assigned again in __wiz_wlan_action.php
$WIZ_NEXT		="3_saving"; 
/* --------------------------------------------------------------------------- */
if ($ACTION_POST!="")
{
	require("/www/model/__admin_check.php");
	require("/www/__wiz_wlan_action.php");
	$ACTION_POST="";
	require("/www/wiz_wlan_".$WIZ_NEXT.".php");
	exit;
}

/* --------------------------------------------------------------------------- */
$NO_NEED_AUTH="0";
require("/www/model/__html_head.php");
/* --------------------------------------------------------------------------- */
// get the variable value from rgdb.
$db_secret=get("h",$G_WIZ_PREFIX_WLAN."/wireless/full_secret");
$db_defkey=get("h","/wireless/wep/defkey");
if( $db_secret == "" )
{ $db_secret = get("h","/wireless/wep/key:".$db_defkey); }
$db_sec_type= query($G_WIZ_PREFIX_WLAN."/wireless/authtype");
if($db_sec_type=="")
{
	$db_sec_type=query("/wireless/authtype");
	$db_wep_type=query("/wireless/encrypttype");
}
else
{
	$db_wep_type=query($G_WIZ_PREFIX_WLAN."/wireless/encrypttype");
}
if($db_sec_type==""){$db_sec_type=0;}
if($db_wep_type==""){$db_wep_type=0;}
/* --------------------------------------------------------------------------- */
?>

<script>
/* page init functoin */
function init()
{
	var f=get_obj("frm");
	var db_sec_type=<?=$db_sec_type?>;
	var db_wep_type=<?=$db_wep_type?>;
}
/* parameter checking */
function check()
{
	var f=get_obj("frm");
	// do check here ....
	if(f.secret.value.length < 8)
	{
		alert("<?=$a_invalid_secret?>");
		f.secret.select();
		return false;
	}
	if(f.secret.value.length == 64)
	{
		if(is_hexdigit(f.secret.value)==false)
		{
			alert("<?=$a_invalid_secret?>");
			f.secret.select();
			return false;
		}
		f.psk_type.value=2;
	}
	else
	{
		if(strchk_unicode(f.secret.value)==true)
		{
			alert("<?=$a_invalid_secret?>");
			f.secret.select();
			return false;
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
<form name="frm" id="frm" method="post" action="<?=$POST_ACTION?>" OnSubmit="return check();">
<input type="hidden" name="ACTION_POST" value="<?=$MY_ACTION?>">
<input type="hidden" name="TARGET_PAGE" value="<?=$MY_ACTION?>">
<input type="hidden" name="psk_type" value="1">
<?require("/www/model/__banner.php");?>
<table <?=$G_MAIN_TABLE_ATTR?>>
<tr valign=top>
	<td width=10%></td>
	<td id="maincontent" width=80%>
		<br>
		<div id="box_header">
		<? require($LOCALE_PATH."/dsc/dsc_".$MY_NAME.".php"); ?>
<!-- ________________________________ Main Content Start ______________________________ -->
			<table>
			<tr>
				<td class=l_tb><?=$m_wpa_dsc?></td>
			</tr>
			<tr>
				<td class=l_tb><?=$m_rule_guide?></td>
			</tr>
			<tr>
				<td class=l_tb><?=$m_rule_1?></td>
			</tr>
			<tr>
				<td class=l_tb><?=$m_rule_2?></td>
			</tr>
			<tr>
				<td>
					<table>
						<tr>
						<td width=40% class=r_tb><?=$m_wpa_key?></td>
						<td ><input type=text name=secret size="20" maxlength="64" value="<?=$db_secret?>"></td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td class=l_tb><?=$m_note?></td>
			</tr>
			</table>
		<br>
		<center><script>prev("");next("");exit_wlan_wiz();</script></center>
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
