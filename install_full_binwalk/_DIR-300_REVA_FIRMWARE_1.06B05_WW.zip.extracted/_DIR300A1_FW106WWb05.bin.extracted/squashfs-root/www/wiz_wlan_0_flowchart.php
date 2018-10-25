<?
/* vi: set sw=4 ts=4: ---------------------------------------------------------*/
$MY_NAME		="wiz_wlan_0_flowchart";
$MY_MSG_FILE	=$MY_NAME.".php";
$MY_ACTION		="0_flowchart";
$WIZ_NEXT		="1_ssid";
/* --------------------------------------------------------------------------- */

if($ACTION_POST!="")
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

?>
<script>
/* page init functoin */
function init()
{
}
/* parameter checking */
function check()
{
}
</script>
<body onload="init();" <?=$G_BODY_ATTR?>>
<form name="frm" id="frm" method="post" action="<?=$POST_ACTION?>">
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
		<center><script>next("");exit_wlan_wiz();</script></center>
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
