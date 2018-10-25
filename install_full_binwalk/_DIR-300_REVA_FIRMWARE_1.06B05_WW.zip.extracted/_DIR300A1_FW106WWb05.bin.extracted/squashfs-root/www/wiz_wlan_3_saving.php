<?
/* vi: set sw=4 ts=4: ---------------------------------------------------------*/
$MY_NAME		="wiz_wlan_3_saving";
$MY_MSG_FILE	=$MY_NAME.".php";

$MY_ACTION		="3_saving";
anchor($G_WIZ_PREFIX_WLAN."/wireless/");
if(query("wiz_type")==1 && query("authtype")==7)		{ $WIZ_PREV = "2_wpa"; }
else if( query("wiz_type")==1 && query("authtype")==0)	{ $WIZ_PREV = "2_wep"; }
else													{ $WIZ_PREV = "1_ssid"; }
$WIZ_NEXT		="4_done";
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
anchor($G_WIZ_PREFIX_WLAN."/wireless/");
$ssid		=get("h","ssid");
$auth		=query("authtype");
$wepmode	=query("wepmode");
$wep_length	=query("wep_length");
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
	return true;
}
function go_prev()
{
	self.location.href="<?=$POST_ACTION?>?TARGET_PAGE=<?=$WIZ_PREV?>";
}
function do_save()
{
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
		<? require($LOCALE_PATH."/dsc/dsc_".$MY_NAME.".php"); ?>
<!-- ________________________________ Main Content Start ______________________________ -->
		<br>
		<table width=95%>
			<tr>
				<?
				$td_h="22";
				$td_attr="class=br_tb height=".$td_h;
				?>
				<td class=br_tb width=40% height="<?=$td_h?>"><?=$m_wlan_ssid?> :</td>
				<td width="5"></td>
				<td><?=$ssid?></td>
			</tr>
<?
if($auth=="0" && $wepmode!="0")
{
	if($wep_length == "64")	{ $m_bits=$m_64bits; }
	else					{ $m_bits=$m_128bits; }
	echo "<tr><td ".$td_attr.">".$m_wep_length." :</td><td></td><td>".$m_bits."</td></tr>\n";
	echo "<tr><td ".$td_attr.">".$m_def_wep_index." :</td><td></td><td>1</td></tr>\n";
	echo "<tr><td ".$td_attr.">".$m_auth." :</td><td></td><td>".$m_open."</td></tr>\n";
	echo "<tr><td ".$td_attr.">".$m_key." :</td><td></td><td>".query("full_secret")."</td></tr>\n";
}
else if($auth=="7" || $auth=="5")
{
	echo "<tr><td ".$td_attr.">".$m_secu." :</td><td></td><td>".$m_psk."</td></tr>";
	echo "<tr><td ".$td_attr.">".$m_encry." :</td><td></td><td>".$m_cipher."</td></tr>";
	echo "<tr><td ".$td_attr.">".$m_key." :</td><td></td><td>".get("h","full_secret")."</td></tr>\n";
}

?>
		</table>
		<table>
			<tr>
			<td><?=$m_note?></td>
			</tr>
		</table>
		<br>
		<center><script>prev("");wiz_save();exit_wlan_wiz();</script></center>
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
