<?
/* vi: set sw=4 ts=4: ------------------------------------------ */
$MY_NAME	= "do_wps_step3_fail";
$MY_MSG_FILE= $MY_NAME.".php";
$MY_ACTION	= "step3_fail";
/* ------------------------------------------------------------- */
$NO_NEED_AUTH="0";
require("/www/model/__html_head.php");
?>
<script>
function init() { return true; }
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
		<center><input type="button" name="continue" value="<?=$m_continue?>"
		onclick="javascript:self.location.href='do_wps.php'"></center>
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
