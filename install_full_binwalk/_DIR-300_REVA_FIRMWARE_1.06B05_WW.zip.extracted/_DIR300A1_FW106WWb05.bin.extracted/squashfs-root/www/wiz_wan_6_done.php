<?
/* vi: set sw=4 ts=4: ---------------------------------------------------------*/
$MY_NAME		="wiz_wan_6_done";
$MY_MSG_FILE	=$MY_NAME.".php";

/* --------------------------------------------------------------------------- */
$NO_NEED_AUTH="0";
require("/www/model/__html_head.php");
?>

<script>
/* page init functoin */
function init()
{
	var str="<?=$G_HOME_PAGE?>.xgi?random_num="+generate_random_str();
	str += exe_str("submit COMMIT; submit WAN; submit TIME");
	self.location.href=str;
}
</script>
<body onload="init();" <?=$G_BODY_ATTR?>>
<?require("/www/model/__banner.php");?>
<table <?=$G_MAIN_TABLE_ATTR?>>
<tr valign=middle align=center>
	<td width=10%></td>
	<td id="maincontent" width=80%>
		<br>
		<div id="box_header">
<!-- ________________________________ Main Content Start ______________________________ -->
		<center>
		<? require($LOCALE_PATH."/dsc/dsc_".$MY_NAME.".php"); ?>
		<br>
<!-- ________________________________  Main Content End _______________________________ -->
		</center>
		</div>
		<br>
	</td>
	<td width=10%></td>
</tr>
</table>
<?require("/www/model/__tailer.php");?>
</body>
</html>
