<?
/* vi: set sw=4 ts=4: ---------------------------------------------------------*/
$MY_NAME		="wiz_wlan_4_done";
$MY_MSG_FILE	=$MY_NAME.".php";
$WLAN_MAIN_PAGE	="bsc_wlan_main";
/* --------------------------------------------------------------------------- */
$NO_NEED_AUTH="0";
require("/www/model/__html_head.php");
/* --------------------------------------------------------------------------- */
?>
<script>
/* page init functoin */
function init()
{
	var str='<?=$WLAN_MAIN_PAGE?>.xgi?random_num='+generate_random_str();
	str+=exe_str('submit COMMIT; submit WLAN');
	self.location.href=str;
}
</script>
<body onload="init();" <?=$G_BODY_ATTR?>>
<form name="frm" id="frm">
<?require("/www/model/__banner.php");?>
<table <?=$G_MAIN_TABLE_ATTR?>>
<tr valign=top>
	<td width=10%></td>
	<td id="maincontent" width=80%>
		<br>
		<div id="box_header">
		<? require($LOCALE_PATH."/dsc/dsc_".$MY_NAME.".php"); ?>
<!-- ________________________________ Main Content Start ______________________________ -->
		<br><br><br>
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
