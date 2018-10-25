<?
/* vi: set sw=4 ts=4: ---------------------------------------------------------*/
$MY_NAME		="wiz_wan_5_saving";
$MY_MSG_FILE	=$MY_NAME.".php";

$MY_ACTION		= "5_saving";
$WIZ_PREV		= "3_sel_wan";
$WIZ_NEXT		= "6_done";
/* --------------------------------------------------------------------------- */
if ($ACTION_POST!="")
{
	require("/www/model/__auth_check.php");
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
	return true;
}
function go_prev()
{
	self.location.href="<?=$POST_ACTION?>?TARGET_PAGE=<?=$WIZ_PREV?>";
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
<!-- ________________________________ Main Content Start ______________________________ -->
		<? require($LOCALE_PATH."/dsc/dsc_".$MY_NAME.".php"); ?>
		<br>
		<center>
		<script>
		prev("");
		document.write("<input type='submit' name='next' value=\"<?=$m_connect?>\" onClick='return check()'>&nbsp;");
		exit();
		</script>
		</center>
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
