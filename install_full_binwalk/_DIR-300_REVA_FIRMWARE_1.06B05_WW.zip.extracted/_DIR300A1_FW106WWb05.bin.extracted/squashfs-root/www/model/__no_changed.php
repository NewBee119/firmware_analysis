<?
/* vi: set sw=4 ts=4: */
$NO_NEED_AUTH="1";
require("/www/model/__html_head.php");
?>
<script>
function init()
{
	var f=get_obj("frm");
	f.bt.focus();
}
function click_bt()
{
	self.location.href="<?=$NEXT_PAGE?>.php";
}
</script>
<body onload="init();" <?=$G_BODY_ATTR?>>
<form name="frm" id="frm">
<?require("/www/model/__banner.php");?>
<table <?=$G_MAIN_TABLE_ATTR?>>
<tr valign=middle align=center>
	<td>
	<br>
	<!-- ________________________________ Main Content Start ______________________________ -->
	<table width=80%>
	<tr>
		<td id="box_header">
			<h1><?=$m_nochg_title?></h1><br><br>
			<center><?=$m_nochg_dsc?><br><br><br>
			<input type=button name='bt' value='<?=$m_continue?>' onclick='click_bt();'>
			</center><br>
		</td>
	</tr>
	</table>
	<!-- ________________________________  Main Content End _______________________________ -->
	<br>
	</td>
</tr>
</table>
<?require("/www/model/__tailer.php");?>
</form>
</body>
</html><? exit; ?>
