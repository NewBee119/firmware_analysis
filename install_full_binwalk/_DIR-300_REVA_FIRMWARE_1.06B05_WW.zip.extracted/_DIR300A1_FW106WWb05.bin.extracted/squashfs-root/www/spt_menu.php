<?
/* vi: set sw=4 ts=4: ---------------------------------------------------------*/
$MY_NAME		="spt_menu";
$MY_MSG_FILE	=$MY_NAME.".php";
$CATEGORY		="spt";
$NO_NEED_AUTH="0";
require("/www/model/__html_head.php");
?>
<script>
/* page init functoin */
function init()
{
	var f=get_obj("frm");
	// init here ...
}
</script>
<body onload="init();" <?=$G_BODY_ATTR?>>
<form name="frm" id="frm" method="post" action="<?=$MY_NAME?>.php">
<input type="hidden" name="ACTION_POST" value="SOMETHING">
<?require("/www/model/__banner.php");?>
<?require("/www/model/__menu_top.php");?>
<table <?=$G_MAIN_TABLE_ATTR?> height="100%">
<tr valign=top>
	<td <?=$G_MENU_TABLE_ATTR?>>
	<?require("/www/model/__menu_left.php");?>
	</td>
	<td id="maincontent">
<!-- ________________________________ Main Content Start ______________________________ -->
		<div id="box_header"><?require($LOCALE_PATH."/dsc/dsc_".$MY_NAME.".php");?></div>
<!-- ________________________________  Main Content End _______________________________ -->
	</td>
	<td <?=$G_HELP_TABLE_ATTR?>><?require($LOCALE_PATH."/help/h_".$MY_NAME.".php");?></td>
</tr>
</table>
<?require("/www/model/__tailer.php");?>
</form>
</body>
</html>
